<?php
require_once 'TestCase.php';

class CpfValidatorTest extends TestCase {

	public function testEmpty() {
		$this->guy->cpf = '';
		$this->assertValid();
	}

	public function testLength() {
		$this->guy->cpf = '7895422';
		$this->assertInvalid();
	}

	/** @depends testLength */
	public function testErrorMessage() {
		$this->testLength();
		$this->assertModelErrorIs('cpf', 'Cpf is invalid.');
	}

	/** @depends testLength */
	public function testCustomErrorMessage() {
		BrazilianPerson::$customRules = array('cpf' => array('cpf', 'CpfValidator', 'message' => 'Isso aqui ta errado!'));
		$this->testLength();
		$this->assertModelErrorIs('cpf', 'Isso aqui ta errado!');
	}

	public function testRepeated() {
		$this->guy->cpf = '11111111111';
		$this->assertInvalid();
		$this->guy->cpf = '111.111.111-11';
		$this->assertInvalid();
	}

	public function testInvalid() {
		$this->guy->cpf = '22245181108';
		$this->assertInvalid();
		$this->guy->cpf = '222.451.811-08';
		$this->assertInvalid();
	}

	public function testValid() {
		$this->guy->cpf = '22245181107';
		$this->assertValid();
		$this->guy->cpf = '222.451.811-07';
		$this->assertValid();
	}
}