<?php
namespace BrPack;

class Formatter extends \CLocalizedFormatter {

	/**
	 * Formats a string as a phone number with area code.
	 * @param $number
	 * @throws \CException In case the given value does not have 10~11 digits
	 * @return string
	 */
	public function formatPhone($number) {
		if (!preg_match('/^\d{10,11}$/', $number))
			throw new \CException('Phone formatter expects a string with 10~11 digits (area code + 8~9 digits).');

		return sprintf('(%d) %d-%d', substr($number, 0, 2), substr($number, 2, -4), substr($number, -4));
	}

	/**
	 * Formats a CPF number with dots and dashes.
	 * @param $number
	 * @throws \CException In case the given value does not have 11 digits
	 * @return string
	 */
	public function formatCpf($number) {
		if (!preg_match('/^\d{11}$/', $number))
			throw new \CException('CPF formatter expects a string with exact 11 digits.');

		return vsprintf('%d%d%d.%d%d%d.%d%d%d-%d%d', str_split($number));
	}

	/**
	 * Alias for money formatting, using the `numberFormatter` standard component.
	 * @see \CNumberFormatter::formatCurrency()
	 * @param string|int|float $value    the number to be formatted
	 * @param string           $currency 3-letter ISO 4217 code
	 * @return string
	 */
	public function formatMoney($value, $currency = 'BRL') {
		return \Yii::app()->numberFormatter->formatCurrency($value, $currency);
	}

}