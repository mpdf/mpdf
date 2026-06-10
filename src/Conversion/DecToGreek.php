<?php

namespace Mpdf\Conversion;

class DecToGreek
{
	private static $alphabet = [
		'α', 'β', 'γ', 'δ', 'ε', 'ζ', 'η', 'θ', 'ι', 'κ', 'λ', 'μ',
		'ν', 'ξ', 'ο', 'π', 'ρ', 'σ', 'τ', 'υ', 'φ', 'χ', 'ψ', 'ω',
	];

	public function convert($valor)
	{
		$valor = (int) $valor;

		if ($valor < 1) {
			return (string) $valor;
		}

		$greek = '';

		while ($valor > 0) {
			$valor--;
			$greek = self::$alphabet[$valor % 24] . $greek;
			$valor = (int) ($valor / 24);
		}

		return $greek;
	}

}
