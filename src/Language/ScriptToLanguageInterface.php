<?php

namespace MpdfAnalize\Language;

interface ScriptToLanguageInterface
{

	public function getLanguageByScript($script);

	public function getLanguageDelimiters($language);

}
