<?php

namespace Mpdf\Pdf;

use Mpdf\Pdf\Protection\UniqidGenerator;

class Protection
{

	/**
	 * @var string
	 */
	private $lastRc4Key;

	/**
	 * @var string
	 */
	private $lastRc4KeyC;

	/**
	 * @var bool
	 */
	private $useRC128Encryption;

	/**
	 * @var string
	 */
	private $encryptionKey;

	/**
	 * @var string
	 */
	private $padding;

	/**
	 * @var string
	 */
	private $uniqid;

	/**
	 * @var string
	 */
	private $oValue;

	/**
	 * @var string
	 */
	private $uValue;

	/**
	 * @var string
	 */
	private $pValue;

	/**
	 * @var int[] Array of permission => byte representation
	 */
	private $options;

	/**
	 * @var \Mpdf\Pdf\Protection\UniqidGenerator
	 */
	private $uniqidGenerator;

	public function __construct(UniqidGenerator $uniqidGenerator)
	{
		if (!function_exists('random_int') || !function_exists('random_bytes')) {
			throw new \Mpdf\MpdfException(
				'Unable to set PDF file protection, CSPRNG Functions are not available. '
				. 'Use paragonie/random_compat polyfill or upgrade to PHP 7.'
			);
		}

		$this->uniqidGenerator = $uniqidGenerator;

		$this->lastRc4Key = '';

		$this->padding = "\x28\xBF\x4E\x5E\x4E\x75\x8A\x41\x64\x00\x4E\x56\xFF\xFA\x01\x08" .
			"\x2E\x2E\x00\xB6\xD0\x68\x3E\x80\x2F\x0C\xA9\xFE\x64\x53\x69\x7A";

		$this->useRC128Encryption = false;

		$this->options = [
			'print' => 4, // bit 3
			'modify' => 8, // bit 4
			'copy' => 16, // bit 5
			'annot-forms' => 32, // bit 6
			'fill-forms' => 256, // bit 9
			'extract' => 512, // bit 10
			'assemble' => 1024, // bit 11
			'print-highres' => 2048 // bit 12
		];
	}

	/**
	 * @param array $permissions
	 * @param string $user_pass
	 * @param string $owner_pass
	 * @param int $length
	 *
	 * @return bool
	 */
	public function setProtection($permissions = [], $user_pass = '', $owner_pass = null, $length = 40)
	{
		if (is_string($permissions) && strlen($permissions) > 0) {
			$permissions = [$permissions];
		} elseif (!is_array($permissions)) {
			return false;
		}

		$protection = $this->getProtectionBitsFromOptions($permissions);

		if ($length === 128) {
			$this->useRC128Encryption = true;
		} elseif ($length !== 40) {
			throw new \Mpdf\MpdfException('PDF protection only allows lenghts of 40 or 128');
		}

		if ($owner_pass === null) {
			$owner_pass = bin2hex(random_bytes(23));
		}

		$this->generateEncryptionKey($user_pass, $owner_pass, $protection);

		return true;
	}

	/**
	 * Compute key depending on object number where the encrypted data is stored
	 *
	 * @param int $n
	 *
	 * @return string
	 */
	public function objectKey($n)
	{
		/*
		 * Get the encryption length as defined by step 2 of Alogrithm 3.1 which is
		 * located on page 73 of the PDF 1.4 manual.
		 *
		 * Algo: Length / 8 + 5
		 * Where Length refers to the encryption key, in bits (refer to table 3.13 on page 72 of the PDF 1.4 manual)
		 *
		 */
		if ($this->useRC128Encryption) {
			$len = 128 / 8 + 5;
		} else {
			$len = 40 / 8 + 5;
		}

		$objectkey = $this->encryptionKey.pack('VXxx', $n);
		$objectkey = substr($this->md5toBinary($objectkey), 0, $len);

		/* We only need the first 16 bytes */
		return substr($objectkey, 0, 16);
	}

	/**
	 * RC4 is the standard encryption algorithm used in PDF format
	 *
	 * @param string $key
	 * @param string $text
	 *
	 * @return string
	 */
	public function rc4($key, $text)
	{
		if ($this->lastRc4Key != $key) {
			$k = str_repeat($key, 256 / strlen($key) + 1);
			$rc4 = range(0, 255);
			$j = 0;
			for ($i = 0; $i < 256; $i++) {
				$t = $rc4[$i];
				$j = ($j + $t + ord($k[$i])) % 256;
				$rc4[$i] = $rc4[$j];
				$rc4[$j] = $t;
			}
			$this->lastRc4Key = $key;
			$this->lastRc4KeyC = $rc4;
		} else {
			$rc4 = $this->lastRc4KeyC;
		}

		$len = strlen($text);
		$a = 0;
		$b = 0;
		$out = '';
		for ($i = 0; $i < $len; $i++) {
			$a = ($a + 1) % 256;
			$t = $rc4[$a];
			$b = ($b + $t) % 256;
			$rc4[$a] = $rc4[$b];
			$rc4[$b] = $t;
			$k = $rc4[($rc4[$a] + $rc4[$b]) % 256];
			$out .= chr(ord($text[$i]) ^ $k);
		}

		return $out;
	}

	/**
	 * @return mixed
	 */
	public function getUseRC128Encryption()
	{
		return $this->useRC128Encryption;
	}

	/**
	 * @return mixed
	 */
	public function getUniqid()
	{
		return $this->uniqid;
	}

	/**
	 * @return mixed
	 */
	public function getOValue()
	{
		return $this->oValue;
	}

	/**
	 * @return mixed
	 */
	public function getUValue()
	{
		return $this->uValue;
	}

	/**
	 * @return mixed
	 */
	public function getPValue()
	{
		return $this->pValue;
	}

	private function getProtectionBitsFromOptions($permissions)
	{
		// bit 31 = 1073741824
		// bit 32 = 2147483648
		// bits 13-31 = 2147479552
		// bits 13-32 = 4294963200 + 192 = 4294963392

		$protection = 4294963392; // bits 7, 8, 13-32

		foreach ($permissions as $permission) {

			if (!isset($this->options[$permission])) {
				throw new \Mpdf\MpdfException(sprintf('Invalid permission type "%s"', $permission));
			}
			if ($this->options[$permission] > 32) {
				$this->useRC128Encryption = true;
			}

			$protection += $this->options[$permission];
		}

		return $protection;
	}

	/**
	 * Calculates the O value in the PDF Encryption Dictionary
	 * See Algorithm 3.3 on page 79 of the PDF 1.4 Manual for details
	 *
	 * @param string $user_pass
	 * @param string $owner_pass
	 *
	 * @internal Step 1 and Step 5 are done prior to calling this method
	 *
	 * @return string
	 */
	private function oValue($user_pass, $owner_pass)
	{
		/* Step 2 */
		$tmp = $this->md5toBinary($owner_pass);

		/* Step 3 */
		if ($this->useRC128Encryption) {
			for ($i = 0; $i < 50; $i++) {
				$tmp = $this->md5toBinary($tmp);
			}
		}

		if ($this->useRC128Encryption) {
			$keybytelen = (128 / 8);
		} else {
			$keybytelen = (40 / 8);
		}
		$owner_rc4_key = substr($tmp, 0, $keybytelen); /* Step 4 */
		$enc = $this->rc4($owner_rc4_key, $user_pass); /* Step 6 */

		/* Step 7 */
		if ($this->useRC128Encryption) {
			$len = strlen($owner_rc4_key);
			for ($i = 1; $i <= 19; $i++) {
				$key = '';
				for ($j = 0; $j < $len; $j++) {
					$key .= chr(ord($owner_rc4_key[$j]) ^ $i);
				}
				$enc = $this->rc4($key, $enc);
			}
		}

		return $enc;
	}

	/**
	 * Calculates the U value in the PDF Encryption Dictionary
	 * See Algorithm 3.4 / 3.5 on page 79 / 80 of the PDF 1.4 Manual for details
	 *
	 * @return string
	 */
	private function uValue()
	{
		if ($this->useRC128Encryption) {
			$tmp = $this->md5toBinary($this->padding . $this->hexToString($this->uniqid)); /* Step 2/3 */
			$enc = $this->rc4($this->encryptionKey, $tmp); /* Step 4 */
			$len = strlen($tmp);

			/* Step 5 */
			for ($i = 1; $i <= 19; ++$i) {
				$key = '';
				for ($j = 0; $j < $len; ++$j) {
					$key .= chr(ord($this->encryptionKey[$j]) ^ $i);
				}
				$enc = $this->rc4($key, $enc);
			}
			$enc .= str_repeat("\x00", 16); /* Step 6 */

			return substr($enc, 0, 32);
		} else {
			return $this->rc4($this->encryptionKey, $this->padding);
		}
	}

	/**
	 * Calculates the encryption key used to encrypt and decrypt the document
	 * See Algorithm 3.2 on page 78 of the PDF 1.4 Manual for details
	 *
	 * @param string $user_pass
	 * @param string $owner_pass
	 * @param integer $protection
	 */
	private function generateEncryptionKey($user_pass, $owner_pass, $protection)
	{
		/* Step 1 - Pad passwords */
		$user_pass = substr($user_pass . $this->padding, 0, 32);
		$owner_pass = substr($owner_pass . $this->padding, 0, 32);

		$this->oValue = $this->oValue($user_pass, $owner_pass);

		$this->uniqid = $this->uniqidGenerator->generate();

		/* Step 2-5 */
		$perms = $this->getEncPermissionsString($protection);
		$tmp = $this->md5toBinary($user_pass . $this->oValue . $perms . $this->hexToString($this->uniqid));

		// Compute encyption key
		if ($this->useRC128Encryption) {
			$keybytelen = (128 / 8);
		} else {
			$keybytelen = (40 / 8);
		}

		/* Step 6 */
		if ($this->useRC128Encryption) {
			for ($i = 0; $i < 50; ++$i) {
				$tmp = $this->md5toBinary(substr($tmp, 0, $keybytelen));
			}
		}

		$this->encryptionKey = substr($tmp, 0, $keybytelen); /* Step 7 */

		$this->uValue = $this->uValue();
		$this->pValue = $protection;
	}

	/**
	 * Convert encryption P value to a string of bytes, low-order byte first.
	 *
	 * @param string $protection 32bit encryption permission value (P value)
	 * @return string
	 */
	public static function getEncPermissionsString($protection)
	{
		$binprot = sprintf('%032b', $protection);
		$str = chr(bindec(substr($binprot, 24, 8)));
		$str .= chr(bindec(substr($binprot, 16, 8)));
		$str .= chr(bindec(substr($binprot, 8, 8)));
		$str .= chr(bindec(substr($binprot, 0, 8)));
		return $str;
	}

	private function md5toBinary($string)
	{
		return pack('H*', md5($string));
	}

	private function hexToString($hs)
	{
		$s = '';
		$len = strlen($hs);
		if (($len % 2) != 0) {
			$hs .= '0';
			++$len;
		}
		for ($i = 0; $i < $len; $i += 2) {
			$s .= chr(hexdec($hs{$i} . $hs{($i + 1)}));
		}

		return $s;
	}

}
