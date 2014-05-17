<?php
require_once 'TestCase.php';

class CnpjValidatorTest extends TestCase {

	public function testEmpty() {
		$this->guy->cnpj = '';
		$this->assertValid();
	}

	public function testLength() {
		$this->guy->cnpj = '789542284';
		$this->assertInvalid();
	}

	/** @depends testLength */
	public function testErrorMessage() {
		$this->testLength();
		$this->assertModelErrorIs('cnpj', 'Cnpj is invalid.');
	}

	/** @depends testLength */
	public function testCustomErrorMessage() {
		BrazilianPerson::$customRules = array('cnpj' => array('cnpj', 'CnpjValidator', 'message' => 'Isso aqui ta errado!'));
		$this->testLength();
		$this->assertModelErrorIs('cnpj', 'Isso aqui ta errado!');
	}

	public function testRepeated() {
		$this->guy->cnpj = '22222222222222';
		$this->assertInvalid();
		$this->guy->cnpj = '22.222.222/2222-22';
		$this->assertInvalid();
	}

	public function testInvalid() {
		$this->guy->cnpj = '32458657000189';
		$this->assertInvalid();
		$this->guy->cnpj = '32.458.657/0001-89';
		$this->assertInvalid();
	}

	public function testValid() {
		$this->guy->cnpj = '62346464000101';
		$this->assertValid();
		$this->guy->cnpj = '62.346.464/0001-01';
		$this->assertValid();
	}
}