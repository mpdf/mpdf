<?php

namespace Mpdf\Utils;

class Strings
{
  public static function incrementString(string $str = '')
	{
		return PHP_VERSION_ID === 80500 ? str_increment($str) : ++$str;
	}
}
