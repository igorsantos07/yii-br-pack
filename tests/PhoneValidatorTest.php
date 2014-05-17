<?php
require_once 'TestCase.php';

/**
 * @todo Missing validation for external area codes
 */
class PhoneValidatorTest extends TestCase {

	public function testEmpty() {
		$this->guy->cellphone = '';
		$this->assertValid();
	}

	public function testLength() {
		$this->guy->cellphone = '984-8888';
		$this->assertInvalid();
		$this->assertModelErrorIs('cellphone', 'Cellphone de tamanho inválido.');
		$this->guy->cellphone = '9848888';
		$this->assertInvalid();
		$this->guy->cellphone = '';

		$this->assertModelErrorIs('cellphone', 'Cellphone de tamanho inválido.');
		$this->guy->landline = '281-7456';
		$this->assertInvalid();
		$this->assertModelErrorIs('landline', 'Landline de tamanho inválido.');
		$this->guy->landline = '2817456';
		$this->assertInvalid();
		$this->assertModelErrorIs('landline', 'Landline de tamanho inválido.');
		$this->guy->landline = '22281-7456';
		$this->assertInvalid();
		$this->assertEquals(array('landline' => array('Landline is invalid.','Landline de tamanho inválido.')), $this->guy->errors);
	}

	public function testNinthDigit() {
		BrazilianPerson::$customRules['cellphone'] = array_merge(BrazilianPerson::$defaultRules['cellphone'], array('areaCode' => true));
		$this->guy->cellphone = '(21) 99999-9999';
		$this->assertValid();

		BrazilianPerson::$customRules['cellphone'] = array_merge(BrazilianPerson::$defaultRules['cellphone'], array('areaCode' => true));
		$this->guy->cellphone = '(11) 7999-9999';
		$this->assertValid();

		BrazilianPerson::$customRules['cellphone'] = array_merge(BrazilianPerson::$defaultRules['cellphone'], array('areaCode' => true));
		$this->guy->cellphone = '(11) 9999-9999';
		$this->assertInvalid();
		$this->assertEquals(array('cellphone' => array('Falta o nono dígito no Cellphone.', 'Cellphone is invalid.')), $this->guy->errors);
	}

	public function testInvalid() {
		BrazilianPerson::$customRules['landline'] = array_merge(BrazilianPerson::$defaultRules['landline'], array('type' => PhoneValidator::TYPE_LANDLINE));
		$this->guy->landline = '6789-9999';
		$this->assertInvalid();
		$this->assertModelErrorIs('landline', 'Landline is invalid.');
		$this->guy->landline = '';

		BrazilianPerson::$customRules['cellphone'] = array_merge(BrazilianPerson::$defaultRules['cellphone'], array('type' => PhoneValidator::TYPE_CELLPHONE));
		$this->guy->cellphone = '6789-9999';
		$this->assertInvalid();
		$this->assertEquals(array('cellphone' => array('Cellphone de tamanho inválido.')), $this->guy->errors);

		//here it should warn for any of the two validators
		$this->guy->cellphone = '6789-9999';
		$this->assertInvalid();
		$this->assertEquals(array('cellphone' => array('Cellphone de tamanho inválido.')), $this->guy->errors);

		$this->guy->cellphone = '97789-9999';
		$this->assertInvalid();
		$this->assertEquals(array('cellphone' => array('Cellphone de tamanho inválido.')), $this->guy->errors);
	}

	public function testValid() {
		$this->guy->landline = '2281-2222';
		$this->assertValid();
		$this->guy->cellphone = '9999-9999';
		$this->assertValid();
		$this->guy->cellphone = '7999-9999';
		$this->assertValid();
		$this->guy->cellphone = '99999-9999';
		$this->assertValid();
	}
}
