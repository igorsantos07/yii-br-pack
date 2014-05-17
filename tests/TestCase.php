<?php
define('VENDOR', __DIR__.'/../vendor');
require_once(VENDOR.'/autoload.php');
require_once(VENDOR.'/yiisoft/yii/framework/yiit.php');
require_once('BrazilianPerson.php');
error_reporting(E_ERROR); //Skipping weird Yii's autoload errors with PHPUnit stuff

/**
 * This is the base class for all brvalidator unit tests.
 */
class TestCase extends \PHPUnit_Framework_TestCase {

	/** @var BrazilianPerson */
	public $guy;

	protected function concatErrors() {
		$messages = '';
		foreach($this->guy->errors as $field => $errors) {
			$messages .= "> $field: '{$this->guy->$field}' => ";
			foreach($errors as $i => $error) {
				$messages .= "[$i] $error";
			}
			$messages .= "\n";
		}

		return trim($messages);
	}

	public function assertModelErrorIs($field, $message) {
		$this->assertEquals(array($field => array($message)), $this->guy->errors, $this->concatErrors());
	}

	public function assertValidation($shouldBeValid = true, $msg) {
		$valid = $this->guy->validate();
		$this->assertEquals((bool)$shouldBeValid, $valid, $msg.$this->concatErrors());
	}

	public function assertValid() {
		$this->assertValidation(true);
	}

	public function assertInvalid() {
		$this->assertValidation(false, "It looks valid: ".implode($this->guy->attributes));
	}

	/**
	 * Populates Yii::$app with a new application
	 */
	protected function mockApplication() {
		static $config = array(
			'id'       => 'testapp',
			'basePath' => __DIR__,
		);
		Yii::createConsoleApplication($config);
		Yii::setPathOfAlias('vendor', VENDOR);
	}

	/**
	 * Sets up before test
	 */
	protected function setUp() {
		parent::setUp();
		$this->mockApplication();

		$this->guy = new BrazilianPerson;
	}

	/**
	 * Clean up after test.
	 * The application created with [[mockApplication]] will be destroyed.
	 */
	protected function tearDown() {
		parent::tearDown();
		Yii::setApplication(null);
	}
}
