<?php

namespace Mpdf\Image;

class ImageTypeGuesser
{

	public function guess($data)
	{
		if (substr($data, 6, 4) == 'JFIF' || substr($data, 6, 4) == 'Exif' || substr($data, 0, 2) == chr(255) . chr(216)) { // 0xFF 0xD8	// mpDF 5.7.2
			return 'jpeg';
		} elseif (substr($data, 0, 6) == "GIF87a" || substr($data, 0, 6) == "GIF89a") {
			return 'gif';
		} elseif (substr($data, 0, 8) == chr(137) . 'PNG' . chr(13) . chr(10) . chr(26) . chr(10)) {
			return 'png';
		} elseif (substr($data, 0, 4) == chr(215) . chr(205) . chr(198) . chr(154)) {
			return 'wmf';
		} elseif (preg_match('/<svg.*<\/svg>/is', $data)) {
			return 'svg';
		} elseif (substr($data, 0, 2) == "BM") {
			return 'bmp';
		}

		return null;
	}

}
