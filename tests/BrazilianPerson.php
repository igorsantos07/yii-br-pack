<?php
/**
 * Class BrazilianPerson
 *
 * If you need to change a validation rule on-the-fly, set the
 * {@link $customRules} property. It's value is merged onto the
 * {@link $defaultRules} once and then trashed.
 */
class BrazilianPerson extends CModel {

	public $cpf;

	public $cnpj;

	public $cellphone;

	public $landline;

	public static $defaultRules = array(
		'cpf'       => array('cpf', 'CpfValidator', 'allowEmpty' => true),
		'cnpj'      => array('cnpj', 'CnpjValidator', 'allowEmpty' => true),
		'cellphone' => array('cellphone', 'PhoneValidator', 'allowEmpty' => true),
		'landline'  => array('landline', 'PhoneValidator', 'allowEmpty' => true),
	);

	public static $customRules = array();

	public function attributeNames() {
		return array('cpf', 'cnpj', 'cellphone', 'landline');
	}

	public function rules() {
		$rules             = array_merge(self::$defaultRules, self::$customRules);
		self::$customRules = array();
		return $rules;
	}
}