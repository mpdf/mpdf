<?php

namespace Mpdf\Language;

class LanguageToFontTestImplimentation extends LanguageToFont
{
	public function getLanguageOptions($llcc, $adobeCJK)
	{
		if ($llcc === 'fake') {
			return [false, 'fake-font'];
		}

		return parent::getLanguageOptions($llcc, $adobeCJK);
	}

	protected function fontByScript($script, $adobeCJK)
	{
		if ($script === 'fake') {
			return 'fake-font-script';
		}

		return parent::fontByScript($script, $adobeCJK);
	}
}
