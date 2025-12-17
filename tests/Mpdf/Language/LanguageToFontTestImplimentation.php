<?php

namespace Mpdf\Language;

class LanguageToFontTestImplimentation extends LanguageToFont
{
	public function getLanguageOptions($llcc, $adobeCJK)
	{
		if ($llcc === 'fake') {
			return [false, 'fake-font'];
		}

		if ($llcc === 'zh' && !$adobeCJK) {
			return [false, 'sun-exta'];
		}

		return parent::getLanguageOptions($llcc, $adobeCJK);
	}

	protected function fontByScript($script, $adobeCJK)
	{
		if ($script === 'fake') {
			return 'fake-font-script';
		}

		if ($script === 'latn') {
			return 'dejavusanscondensed';
		}

		if ($script === 'kali') {
			return 'freemono';
		}

		return parent::fontByScript($script, $adobeCJK);
	}
}
