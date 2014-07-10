<?php
namespace BrPack\Validator;

/**
 * CnpjValidator checks if the attribute value is a valid CNPJ.
 *
 * @author Igor Santos <igorsantos07@gmail.com>
 * @author Leandro Gehlen <leandrogehlen@gmail.com>
 */
class Cnpj extends \CValidator {

	public $allowEmpty;

	public function __construct() {
		if ($this->message === null) {
			$this->message = \Yii::t('yii', '{attribute} is invalid.');
		}
	}

	/**
	 * @inheritdoc
	 */
	protected function validateAttribute($object, $attribute) {
		if (!$object->$attribute && $this->allowEmpty) return;

		$valid = true;
		$cnpj  = str_pad(preg_replace('/[^0-9_]/', '', $object->$attribute), 14, '0', STR_PAD_LEFT);

		for ($x = 0; $x <= 0; $x++) {
			if ($cnpj == str_repeat($x, 14)) {
				$valid = false;
			}
		}

		if ($valid) {
			if (strlen($cnpj) != 14) {
				$valid = false;
			}
			else {
				for ($t = 12; $t < 14; $t++) {
					$d = 0;
					$c = 0;
					for ($m = $t - 7; $m >= 2; $m--, $c++) {
						$d += $cnpj{$c} * $m;
					}
					for ($m = 9; $m >= 2; $m--, $c++) {
						$d += $cnpj{$c} * $m;
					}
					$d = ((10 * $d) % 11) % 10;
					if ($cnpj{$c} != $d) {
						$valid = false;
						break;
					}
				}
			}
		}


		if (!$valid) {
			$this->addError($object, $attribute, $this->message);
		}
	}
}
