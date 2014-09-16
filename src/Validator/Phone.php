<?php
namespace BrPack\Validator;

/**
 * PhoneValidator checks if the attribute value is a valid brazilian phone number.
 *
 * @package BrPack.Validator
 * @author Igor Santos <igorsantos07@gmail.com>
 */
class Phone extends \CValidator {

	const TYPE_LANDLINE = 'landline';

	const TYPE_CELLPHONE = 'cellphone';

	const TYPE_BOTH = 'both';

	public $allowEmpty;

	public $ninthDigitMessage = 'Falta o nono dígito no {attribute}.';

	public $lengthMessage = '{attribute} de tamanho inválido.';

	public $areaMessage = 'Código de área inválido no {attribute}.';

	/**
	 * If it should validate only cellphones, landlines, or both are okay.
	 * Should use one of PhoneValidator::TYPE_* constants.
	 * @var string
	 */
	public $type = self::TYPE_BOTH;

	/**
	 * If true will also validate with the area code in the attribute. Defaults to false.
	 * @see $areaCodeField
	 * @var bool
	 */
	public $areaCode = false;

	/**
	 * If set, will use the given attribute name as area code to validate cellphones.
	 * @var string
	 */
	public $areaCodeAttribute;

	/**
	 * Landline numbers begin with this list of digits.
	 * @var array
	 */
	public $landlineBegin = array(2, 3, 4);

	/**
	 * Cellphone numbers begin with this list of digits.
	 * @var array
	 */
	public $cellphoneBegin = array(7, 8, 9);

	/**
	 * Cellphone numbers that require 9 digits begin with this list of digits.
	 * @see $ninthDigitAreaCodes
	 * @var array
	 */
	public $cellphoneNinthDigitBegin = array(8, 9);

	/**
	 * List of area codes that require 9 digits on the cellphone. Should be updated from time to time.
	 * @link http://portal.embratel.com.br/embratel/9-digito/
	 * @see  $cellphoneNinthDigitBegin
	 */
	public $ninthDigitAreaCodes = array(
		11, 12, 13, 14, 15, 16, 17, 18, 19,
		21, 22, 24, 27, 28,
	);

	/**
	 * Temp area for found errors in sub-validation methods.
	 * @var array
	 */
	protected $errors = array();

	public function __construct() {
		if ($this->message === null) {
			$this->message = \Yii::t('yii', '{attribute} is invalid.');
		}
	}

	/**
	 * @inheritdoc
	 */
	protected function validateAttribute($object, $attribute) {
		if (!$object->$attribute && $this->allowEmpty)
			return;

		if (!in_array($this->type, array(self::TYPE_BOTH, self::TYPE_CELLPHONE, self::TYPE_LANDLINE)))
			throw new \CException('Validator expects "type" property to be one of PhoneValidator::TYPE_* constants');

		$number = preg_replace('/[^0-9_]/', '', $object->$attribute);

		if ($this->areaCode) {
			$area   = substr($number, 0, 2);
			$number = substr($number, 2);
		}
		elseif ($this->areaCodeAttribute) {
			$area = $object->{$this->areaCodeAttribute};
		}
		else {
			$area = null;
		}

		if ($area && (strlen($area) != 2 || $area < 11))
			$this->addError($object, $attribute, $this->areaMessage);

		if (!$this->{'validate'.ucfirst($this->type)}($area, $number)) {
			$total = sizeof($this->errors);
			for ($i = 0; $i < $total; $i++)
				$this->addError($object, $attribute, \Yii::t('yii', array_shift($this->errors)));
		}
	}

	protected function validateLandline($area, $number) {
		if (strlen($number) != 8) {
			$this->errors[] = $this->lengthMessage;
			return false;
		}

		if (!in_array($number[0], $this->landlineBegin)) {
			$this->errors[] = $this->message;
			return false;
		}

		return true;
	}

	protected function validateCellphone($area, $number) {
		$length     = strlen($number);
		$ninthDigit = $area && in_array($area, $this->ninthDigitAreaCodes);
		$dontHaveNinthDigit = array_diff($this->cellphoneBegin, $this->cellphoneNinthDigitBegin);

		if ($ninthDigit && $length == 8 && in_array($number[0], $this->cellphoneNinthDigitBegin)) {
			$this->errors[] = $this->ninthDigitMessage;
			return false;
		}
		elseif (($ninthDigit && $length != 9 && in_array($number[0], $this->cellphoneNinthDigitBegin)) ||
			   (!$ninthDigit && (
			       ($area && $length != 8 && in_array($number[0], $this->cellphoneBegin)) ||
			       (!$area && ($length != 8 && $length != 9))
			   ))
		) {
			$this->errors[] = $this->lengthMessage;
			return false;
		}
		elseif ($ninthDigit && (
			       ($length == 9 && (!in_array($number[1], $this->cellphoneBegin) || $number[0] != 9)) ||
		           ($length == 8 && (!in_array($number[0], $dontHaveNinthDigit)))) ||
			   (!$ninthDigit && (
				   ($area && !in_array($number[0], $this->cellphoneBegin)) ||
				   (!$area && (
					   ($length == 9 && (!in_array($number[1], $this->cellphoneNinthDigitBegin) || $number[0] != 9)) ||
					   ($length == 8 && (!in_array($number[0], $this->cellphoneBegin))))
				   )
			   ))
		) {
			$this->errors[] = $this->message;
			return false;
		}

		return true;
	}

	protected function validateBoth($area, $number) {
		if ($this->validateLandline($area, $number)) {
			return true;
		}
		else {
			$landline_errors = $this->errors;
			$this->errors = array();
			$valid_cellphone = $this->validateCellphone($area, $number);
			if (!$valid_cellphone) {
				foreach($landline_errors as $error) {
					if (!in_array($error, $this->errors))
						$this->errors[] = $error;
				}
			}
		}
	}
}
