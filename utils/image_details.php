<?php

$orig_srcpath = '../tests/tiger.png';                        // as specified in your file (could be full URL)
$file = 'http://127.0.0.1/MPDF1.com/common/mpdf/tests/tiger.png';    // Full URL
$fileIsLocal = true;                                    // is the file in the same domain?

//======================================================================

$ppUx = 0;
$type = '';
$data = '';

echo 'File: ' . $orig_srcpath . '<br />';
echo 'Full File URL: ' . $file . '<br />';

if ($orig_srcpath && $fileIsLocal && $check = @fopen($orig_srcpath, "rb")) {
	fclose($check);
	$file = $orig_srcpath;
	$data = file_get_contents($file);
	$type = _imageTypeFromString($data);
	echo 'File accessed using fopen on $orig_srcpath' . '<br />';
}

if (!$data && $check = @fopen($file, "rb")) {
	fclose($check);
	$data = file_get_contents($file);
	$type = _imageTypeFromString($data);
	echo 'File accessed using fopen on $file' . '<br />';
}

if ((!$data || !$type) && !ini_get('allow_url_fopen')) {    // only worth trying if remote file and !ini_get('allow_url_fopen')
	file_get_contents_by_socket($file, $data);    // needs full url?? even on local (never needed for local)
	if ($data) {
		$type = _imageTypeFromString($data);
	}
	echo 'File accessed using socket ' . '<br />';
}

if ((!$data || !$type) && !ini_get('allow_url_fopen') && function_exists("curl_init")) {
	file_get_contents_by_curl($file, $data);        // needs full url?? even on local (never needed for local)
	if ($data) {
		$type = _imageTypeFromString($data);
	}
	echo 'File accessed using cURL ' . '<br />';
}

if (!$data) {
	echo 'Could not access image file' . '<br />';
	exit;
}

echo 'Image type determined: ' . strtoupper($type) . '<br />';

// JPEG
if ($type == 'jpeg' || $type == 'jpg') {
	$hdr = _jpgHeaderFromString($data);
	if (!$hdr) {
		echo 'Error parsing JPG header' . '<br />';
		exit;
	}
	$a = _jpgDataFromHeader($hdr);
	$channels = intval($a[4]);
	echo 'Width: ' . $a[0] . '<br />';
	echo 'Height: ' . $a[1] . '<br />';
	echo 'Colorspace: ' . $a[2] . '<br />';
	echo 'BPC (bits per channel): ' . $a[3] . '<br />';
	echo 'Channels: ' . $channels . '<br />';

	$j = strpos($data, 'JFIF');
	if ($j) {
		//Read resolution
		$unitSp = ord(substr($data, ($j + 7), 1));
		if ($unitSp > 0) {
			$ppUx = _twobytes2int(substr($data, ($j + 8), 2));    // horizontal pixels per meter, usually set to zero
			if ($unitSp == 2) {    // = dots per cm (if == 1 set as dpi)
				$ppUx = round($ppUx / 10 * 25.4);
			}
		}
		echo 'Resolution ppUx: ' . $ppUx . '<br />';
	} else {
		echo 'JFIF not found in header' . '<br />';
	}


	// mPDF 6 ICC profile
	$offset = 0;
	$icc = array();
	while (($pos = strpos($data, "ICC_PROFILE\0", $offset)) !== false) {
		// get ICC sequence length
		$length = _twobytes2int(substr($data, ($pos - 2), 2)) - 16;
		$sn = max(1, ord($data[($pos + 12)]));
		$nom = max(1, ord($data[($pos + 13)]));
		$icc[($sn - 1)] = substr($data, ($pos + 14), $length);
		$offset = ($pos + 14 + $length);
	}
	// order and compact ICC segments
	if (count($icc) > 0) {
		echo 'ICC profile present' . '<br />';
		ksort($icc);
		$icc = implode('', $icc);
		if (substr($icc, 36, 4) != 'acsp') {
			// invalid ICC profile
			echo 'ICC profile INVALID (no acsp flag)' . '<br />';
		}
		$input = substr($icc, 16, 4);
		$output = substr($icc, 20, 4);
		echo 'ICC profile Input: ' . $input . '<br />';
		echo 'ICC profile Output: ' . $output . '<br />';
		// Ignore Color profiles for conversion to other colorspaces e.g. CMYK/Lab
		if ($input != 'RGB ' || $output != 'XYZ ') {
			echo 'ICC profile ignored by mPDF' . '<br />';
		}
	}
} // PNG
else if ($type == 'png') {
	//Check signature
	if (substr($data, 0, 8) != chr(137) . 'PNG' . chr(13) . chr(10) . chr(26) . chr(10)) {
		echo 'Error parsing PNG identifier<br />';
		exit;
	}
	//Read header chunk
	if (substr($data, 12, 4) != 'IHDR') {
		echo 'Incorrect PNG file (no IHDR block found)<br />';
		exit;
	}

	$w = _fourbytes2int(substr($data, 16, 4));
	$h = _fourbytes2int(substr($data, 20, 4));
	$bpc = ord(substr($data, 24, 1));
	$errpng = false;
	$pngalpha = false;
	$channels = 0;

	echo 'Width: ' . $w . '<br />';
	echo 'Height: ' . $h . '<br />';
	echo 'BPC (bits per channel): ' . $bpc . '<br />';

	$ct = ord(substr($data, 25, 1));
	if ($ct == 0) {
		$colspace = 'DeviceGray';
		$channels = 1;
	} elseif ($ct == 2) {
		$colspace = 'DeviceRGB';
		$channels = 3;
	} elseif ($ct == 3) {
		$colspace = 'Indexed';
		$channels = 1;
	} elseif ($ct == 4) {
		$colspace = 'DeviceGray';
		$channels = 1;
		$errpng = 'alpha channel';
		$pngalpha = true;
	} else {
		$colspace = 'DeviceRGB';
		$channels = 3;
		$errpng = 'alpha channel';
		$pngalpha = true;
	}

	echo 'Colorspace: ' . $colspace . '<br />';
	echo 'Channels: ' . $channels . '<br />';
	if ($pngalpha) {
		echo 'Alpha channel detected' . '<br />';
	}

	if ($ct < 4 && strpos($data, 'tRNS') !== false) {
		echo 'Transparency detected' . '<br />';
		$errpng = 'transparency';
		$pngalpha = true;
	}

	if ($ct == 3 && strpos($data, 'iCCP') !== false) {
		echo 'Indexed plus ICC' . '<br />';
		$errpng = 'indexed plus ICC';
	}

	if (ord(substr($data, 26, 1)) != 0) {
		echo 'compression method not set to zero<br />';
		$errpng = 'compression method';
	}    // only 0 should be specified
	if (ord(substr($data, 27, 1)) != 0) {
		echo 'filter method not set to zero<br />';
		$errpng = 'filter method';
	}        // only 0 should be specified
	if (ord(substr($data, 28, 1)) != 0) {
		echo 'interlaced file not set to zero<br />';
		$errpng = 'interlaced file';
	}

	$j = strpos($data, 'pHYs');
	if ($j) {
		//Read resolution
		$unitSp = ord(substr($data, ($j + 12), 1));
		if ($unitSp == 1) {
			$ppUx = _fourbytes2int(substr($data, ($j + 4), 4));    // horizontal pixels per meter, usually set to zero
			$ppUx = round($ppUx / 1000 * 25.4);
		}
		echo 'Resolution ppUx: ' . $ppUx . '<br />';
	}

	// mPDF 6 Gamma correction
	$gamma_correction = 0;
	$gAMA = 0;
	$j = strpos($data, 'gAMA');
	if ($j && strpos($data, 'sRGB') === false) {    // sRGB colorspace - overrides gAMA
		$gAMA = _fourbytes2int(substr($data, ($j + 4), 4));    // Gamma value times 100000
		$gAMA /= 100000;
	}

	if ($gAMA) {
		$gamma_correction = 1 / $gAMA;
	}

	// Don't need to apply gamma correction if == default i.e. 2.2
	if ($gamma_correction > 2.15 && $gamma_correction < 2.25) {
		$gamma_correction = 0;
	}

	if ($gamma_correction) {
		echo 'Gamma correction detected' . '<br />';
	}

	// NOT supported at present
	if (strpos($data, 'sRGB') !== false) {
		echo 'sRGB colorspace - NOT supported at present' . '<br />';
	}
	if (strpos($data, 'cHRM') !== false) {
		echo 'Chromaticity and Whitepoint - NOT supported at present' . '<br />';
	}

	if (($errpng || $pngalpha || $gamma_correction)) {
		if (function_exists('gd_info')) {
			$gd = gd_info();
		} else {
			$gd = array();
		}
		if (!isset($gd['PNG Support'])) {
			echo 'GD library required for PNG image (' . $errpng . ')' . '<br />';
		}
		$im = imagecreatefromstring($data);

		if (!$im) {
			echo 'Error creating GD image from PNG file (' . $errpng . ')' . '<br />';
		}
		$w = imagesx($im);
		$h = imagesy($im);
		if ($im) {
			// Alpha channel set (including using tRNS for Paletted images)
			if ($pngalpha) {
				echo 'Alpha channel will be used by mPDF (including when tRNS present in Paletted images)<br />';
				if ($colspace == 'Indexed') {
					echo '...Generating Alpha channel values from tRNS (Indexed)<br />';
				} else if ($ct === 0 || $ct == 2) {
					echo '...Generating Alpha channel values from tRNS (non-Indexed)<br />';
				} else {
					echo '...Extracting Alpha channel<br />';
				}
			} else {    // No alpha/transparency set (but cannot read directly because e.g. bit-depth != 8, interlaced etc)
				echo 'No alpha/transparency set (but cannot read directly because e.g. bit-depth != 8, interlaced etc)<br />';

				// ICC profile
				$icc = false;
				$p = strpos($data, 'iCCP');
				if ($p && $colspace == "Indexed") {
					$p += 4;
					$n = _fourbytes2int(substr($data, ($p - 8), 4));
					$nullsep = strpos(substr($data, $p, 80), chr(0));
					$icc = substr($data, ($p + $nullsep + 2), ($n - ($nullsep + 2)));
					// Test if file is gzcompressed
					if (ord(substr($str, 0, 1)) == 0x1f && ord(substr($str, 1, 1)) == 0x8b) {
						$icc = @gzuncompress($icc);    // Ignored if fails
					}
					if ($icc) {
						echo 'ICC profile present' . '<br />';
						if (substr($icc, 36, 4) != 'acsp') {
							echo 'ICC profile INVALID (no acsp flag)' . '<br />';
							$icc = false;
						} // invalid ICC profile
						else {
							$input = substr($icc, 16, 4);
							$output = substr($icc, 20, 4);
							echo 'ICC profile Input: ' . $input . '<br />';
							echo 'ICC profile Output: ' . $output . '<br />';
							// Ignore Color profiles for conversion to other colorspaces e.g. CMYK/Lab
							if ($input != 'RGB ' || $output != 'XYZ ') {
								$icc = false;
								echo 'ICC profile ignored by mPDF' . '<br />';
							}
						}
					}
					// Convert to RGB colorspace so can use ICC Profile
					if ($icc) {
						echo 'ICC profile and Indexed colorspace both present - need to Convert to RGB colorspace so can use ICC Profile<br />';
					}
				}
			}
		}
		echo 'PNG Image parsed on second pass' . '<br />';

		// SECOND PASS
		imagealphablending($im, false);
		imagesavealpha($im, false);
		imageinterlace($im, false);
		ob_start();
		$check = @imagepng($im);
		if (!$check) {
			echo 'Error creating temporary image object whilst using GD library to parse PNG image' . '<br />';
		}
		$data = ob_get_contents();
		ob_end_clean();
		if (!$data) {
			echo 'Error parsing temporary file image object created with GD library to parse PNG image' . '<br />';
		}
		imagedestroy($im);


		//Check signature
		if (substr($data, 0, 8) != chr(137) . 'PNG' . chr(13) . chr(10) . chr(26) . chr(10)) {
			echo 'Error parsing PNG identifier<br />';
			exit;
		}
		//Read header chunk
		if (substr($data, 12, 4) != 'IHDR') {
			echo 'Incorrect PNG file (no IHDR block found)<br />';
			exit;
		}

		$w = _fourbytes2int(substr($data, 16, 4));
		$h = _fourbytes2int(substr($data, 20, 4));
		$bpc = ord(substr($data, 24, 1));
		$errpng = false;
		$pngalpha = false;
		$channels = 0;

		echo 'Width: ' . $w . '<br />';
		echo 'Height: ' . $h . '<br />';
		echo 'BPC (bits per channel): ' . $bpc . '<br />';

		$ct = ord(substr($data, 25, 1));
		if ($ct == 0) {
			$colspace = 'DeviceGray';
			$channels = 1;
		} elseif ($ct == 2) {
			$colspace = 'DeviceRGB';
			$channels = 3;
		} elseif ($ct == 3) {
			$colspace = 'Indexed';
			$channels = 1;
		} elseif ($ct == 4) {
			$colspace = 'DeviceGray';
			$channels = 1;
			$errpng = 'alpha channel';
			$pngalpha = true;
		} else {
			$colspace = 'DeviceRGB';
			$channels = 3;
			$errpng = 'alpha channel';
			$pngalpha = true;
		}

		echo 'Colorspace: ' . $colspace . '<br />';
		echo 'Channels: ' . $channels . '<br />';
		if ($pngalpha) {
			echo 'Alpha channel detected' . '<br />';
		}

		if ($ct < 4 && strpos($data, 'tRNS') !== false) {
			echo 'Transparency detected' . '<br />';
			$errpng = 'transparency';
			$pngalpha = true;
		}

		if ($ct == 3 && strpos($data, 'iCCP') !== false) {
			echo 'Indexed plus ICC' . '<br />';
			$errpng = 'indexed plus ICC';
		}

		if (ord(substr($data, 26, 1)) != 0) {
			echo 'compression method not set to zero<br />';
			$errpng = 'compression method';
		}    // only 0 should be specified
		if (ord(substr($data, 27, 1)) != 0) {
			echo 'filter method not set to zero<br />';
			$errpng = 'filter method';
		}        // only 0 should be specified
		if (ord(substr($data, 28, 1)) != 0) {
			echo 'interlaced file not set to zero<br />';
			$errpng = 'interlaced file';
		}

		$j = strpos($data, 'pHYs');
		if ($j) {
			//Read resolution
			$unitSp = ord(substr($data, ($j + 12), 1));
			if ($unitSp == 1) {
				$ppUx = _fourbytes2int(substr($data, ($j + 4), 4));    // horizontal pixels per meter, usually set to zero
				$ppUx = round($ppUx / 1000 * 25.4);
			}
			echo 'Resolution ppUx: ' . $ppUx . '<br />';
		}

		//Gamma correction
		$gamma_correction = 0;
		$gAMA = 0;
		$j = strpos($data, 'gAMA');
		if ($j && strpos($data, 'sRGB') === false) {    // sRGB colorspace - overrides gAMA
			$gAMA = _fourbytes2int(substr($data, ($j + 4), 4));    // Gamma value times 100000
			$gAMA /= 100000;
		}

		if ($gAMA) {
			$gamma_correction = 1 / $gAMA;
		}

		// Don't need to apply gamma correction if == default i.e. 2.2
		if ($gamma_correction > 2.15 && $gamma_correction < 2.25) {
			$gamma_correction = 0;
		}

		if ($gamma_correction) {
			echo 'Gamma correction detected' . '<br />';
		}

		// NOT supported at present
		if (strpos($data, 'sRGB') !== false) {
			echo 'sRGB colorspace - NOT supported at present' . '<br />';
		}
		if (strpos($data, 'cHRM') !== false) {
			echo 'Chromaticity and Whitepoint - NOT supported at present' . '<br />';
		}
	} else {    // PNG image with no need to convert alpha channels, bpc <> 8 etc.
		//Scan chunks looking for palette, transparency and image data
		$pal = '';
		$trns = '';
		$pngdata = '';
		$icc = false;
		$p = 33;
		do {
			$n = _fourbytes2int(substr($data, $p, 4));
			$p += 4;
			$type = substr($data, $p, 4);
			$p += 4;
			if ($type == 'PLTE') {
				//Read palette
				$pal = substr($data, $p, $n);
				$p += $n;
				$p += 4;
			} else if ($type == 'tRNS') {
				//Read transparency info
				$t = substr($data, $p, $n);
				$p += $n;
				if ($ct == 0) {
					$trns = array(ord(substr($t, 1, 1)));
				} else if ($ct == 2) {
					$trns = array(ord(substr($t, 1, 1)), ord(substr($t, 3, 1)), ord(substr($t, 5, 1)));
				} else {
					$pos = strpos($t, chr(0));
					if (is_int($pos)) {
						$trns = array($pos);
					}
				}
				$p += 4;
			} else if ($type == 'IDAT') {
				$pngdata .= substr($data, $p, $n);
				$p += $n;
				$p += 4;
			} else if ($type == 'iCCP') {
				$nullsep = strpos(substr($data, $p, 80), chr(0));
				$icc = substr($data, ($p + $nullsep + 2), ($n - ($nullsep + 2)));
				// Test if file is gzcompressed
				if (ord(substr($str, 0, 1)) == 0x1f && ord(substr($str, 1, 1)) == 0x8b) {
					$icc = @gzuncompress($icc);    // Ignored if fails
				}
				if ($icc) {
					echo 'ICC profile present' . '<br />';
					if (substr($icc, 36, 4) != 'acsp') {
						echo 'ICC profile INVALID (no acsp flag)' . '<br />';
						$icc = false;
					} // invalid ICC profile
					else {
						$input = substr($icc, 16, 4);
						$output = substr($icc, 20, 4);
						echo 'ICC profile Input: ' . $input . '<br />';
						echo 'ICC profile Output: ' . $output . '<br />';
						// Ignore Color profiles for conversion to other colorspaces e.g. CMYK/Lab
						if ($input != 'RGB ' || $output != 'XYZ ') {
							$icc = false;
							echo 'ICC profile ignored by mPDF' . '<br />';
						}
					}
				}
				$p += $n;
				$p += 4;
			} else if ($type == 'IEND') {
				break;
			} else if (preg_match('/[a-zA-Z]{4}/', $type)) {
				$p += $n + 4;
			} else {
				echo 'Error parsing PNG image data<br />';
			}
		} while ($n);
		if (!$pngdata) {
			echo 'Error parsing PNG image data - no IDAT data found<br />';
		}
		if ($colspace == 'Indexed' && empty($pal)) {
			echo 'Error parsing PNG image data - missing colour palette<br />';
		}
		echo 'PNG Image parsed directly' . '<br />';
	}
} // GIF
else if ($type == 'gif') {
} // BMP (Windows Bitmap)
else if ($type == 'bmp') {
} // WMF
else if ($type == 'wmf') {
} // UNKNOWN TYPE - try GD imagecreatefromstring
else {
	if (function_exists('gd_info')) {
		$gd = gd_info();
	} else {
		$gd = array();
	}
	if (isset($gd['PNG Support']) && $gd['PNG Support']) {
		$im = @imagecreatefromstring($data);
		if ($im) {
			echo 'Image type not recognised, but image created from file using GD imagecreate' . '<br />';
		} else {
			echo 'Error parsing image file - image type not recognised, and not supported by GD imagecreate' . '<br />';
		}
	}
}


exit;

//==============================================================

function _fourbytes2int($s)
{
	//Read a 4-byte integer from string
	return (ord($s[0]) << 24) + (ord($s[1]) << 16) + (ord($s[2]) << 8) + ord($s[3]);
}

function _twobytes2int($s)
{
	// equivalent to _get_ushort
	//Read a 2-byte integer from string
	return (ord(substr($s, 0, 1)) << 8) + ord(substr($s, 1, 1));
}

function _jpgHeaderFromString(&$data)
{
	$p = 4;
	$p += _twobytes2int(substr($data, $p, 2));    // Length of initial marker block
	$marker = substr($data, $p, 2);
	while ($marker != chr(255) . chr(192) && $marker != chr(255) . chr(194) && $p < strlen($data)) {
		// Start of frame marker (FFC0) or (FFC2) mPDF 4.4.004
		$p += (_twobytes2int(substr($data, $p + 2, 2))) + 2;    // Length of marker block
		$marker = substr($data, $p, 2);
	}
	if ($marker != chr(255) . chr(192) && $marker != chr(255) . chr(194)) {
		return false;
	}
	return substr($data, $p + 2, 10);
}

function _jpgDataFromHeader($hdr)
{
	$bpc = ord(substr($hdr, 2, 1));
	if (!$bpc) {
		$bpc = 8;
	}
	$h = _twobytes2int(substr($hdr, 3, 2));
	$w = _twobytes2int(substr($hdr, 5, 2));
	$channels = ord(substr($hdr, 7, 1));
	if ($channels == 3) {
		$colspace = 'DeviceRGB';
	} elseif ($channels == 4) {
		$colspace = 'DeviceCMYK';
	} else {
		$colspace = 'DeviceGray';
	}
	return array($w, $h, $colspace, $bpc, $channels);
}

function file_get_contents_by_curl($url, &$data)
{
	$timeout = 5;
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_NOBODY, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
}


function file_get_contents_by_socket($url, &$data)
{
	// mPDF 5.7.3
	$timeout = 1;
	$p = parse_url($url);
	$file = $p['path'];
	if ($p['scheme'] == 'https') {
		$prefix = 'ssl://';
		$port = ($p['port'] ? $p['port'] : 443);
	} else {
		$prefix = '';
		$port = ($p['port'] ? $p['port'] : 80);
	}
	if ($p['query']) {
		$file .= '?' . $p['query'];
	}
	if (!($fh = @fsockopen($prefix . $p['host'], $port, $errno, $errstr, $timeout))) {
		return false;
	}

	$getstring =
		"GET " . $file . " HTTP/1.0 \r\n" .
		"Host: " . $p['host'] . " \r\n" .
		"Connection: close\r\n\r\n";
	fwrite($fh, $getstring);
	// Get rid of HTTP header
	$s = fgets($fh, 1024);
	if (!$s) {
		return false;
	}
	$httpheader .= $s;
	while (!feof($fh)) {
		$s = fgets($fh, 1024);
		if ($s == "\r\n") {
			break;
		}
	}
	$data = '';
	while (!feof($fh)) {
		$data .= fgets($fh, 1024);
	}
	fclose($fh);
}

//==============================================================

function _imageTypeFromString(&$data)
{
	$type = '';
	if (substr($data, 6, 4) == 'JFIF' || substr($data, 6, 4) == 'Exif' || substr($data, 0, 2) == chr(255) . chr(216)) { // 0xFF 0xD8
		$type = 'jpeg';
	} else if (substr($data, 0, 6) == "GIF87a" || substr($data, 0, 6) == "GIF89a") {
		$type = 'gif';
	} else if (substr($data, 0, 8) == chr(137) . 'PNG' . chr(13) . chr(10) . chr(26) . chr(10)) {
		$type = 'png';
	} /*-- IMAGES-WMF --*/
	else if (substr($data, 0, 4) == chr(215) . chr(205) . chr(198) . chr(154)) {
		$type = 'wmf';
	} /*-- END IMAGES-WMF --*/
	else if (preg_match('/<svg.*<\/svg>/is', $data)) {
		$type = 'svg';
	} // BMP images
	else if (substr($data, 0, 2) == "BM") {
		$type = 'bmp';
	}
	return $type;
}
