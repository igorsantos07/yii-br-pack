Yii Brazilian Validators
=========================

Yii 1.1 Extension that provides validators and fields for Brazilian localization.

* Validators:
    - CPF: Cadastro de Pessoa Física (like a Security Social Number in USA) 
    - CNPJ: Cadastro Nacional de Pessoa Jurídica
    - landlines: beginning with 2 and 3
    - cellphones: 9 digits or 8 digits beginning with 7, 8 or 9
* Fields:
    - CPF and CNPJ
    - Polymorphic credit card field - given the name it turns into a PAN, CVV or Expiry field

Installation
------------

The preferred way to install this extension is through [Composer](http://getcomposer.org/download/).

[![Latest Stable Version](https://poser.pugx.org/igorsantos07/yii-br-pack/v/stable.svg)](https://packagist.org/packages/igorsantos07/yii-br-pack)
[![Total Downloads](https://poser.pugx.org/igorsantos07/yii-br-pack/downloads.svg)](https://packagist.org/packages/igorsantos07/yii-br-pack)

Either run this:

```
php composer.phar require --prefer-dist igorsantos07/yii-br-pack:1.*
```

or add this to the "require" section of your `composer.json` file.

```
"igorsantos07/yii-br-pack": "1.*"
```

Usage
-----

Add the rules as the following example:

```php
class PersonForm extends CModel {

  public $cpf;
  public $cnpj;
  public $cellphone;
  public $landline;
  public $phone;
  public $areaCode;

  // For maximum readability, you should create an alias for the validator folder :)
  // Here we are assuming you have at least an alias for your vendor folder.
  public function rules() {
    // Using short array notation but the class is PHP <5.4 compatible ;)
    return [
      // CPF validator
      ['cpf', 'vendor.igorsantos07.yii-br-pack.CpfValidator'],
      // CNPJ validator
      ['cnpj', 'vendor.igorsantos07.yii-br-pack.CnpjValidator'],
      // Cellphone-only validator, checking area code inside the field
      ['cellphone', 'vendor.igorsantos07.yii-br-pack.PhoneValidator', 'type' => PhoneValidator::TYPE_CELLPHONE],
      // Cellphone-only validator, not validating area code
      [
        'cellphone',
        'vendor.igorsantos07.yii-br-pack.PhoneValidator',
        'type'     => PhoneValidator::TYPE_CELLPHONE,
        'areaCode' => false
      ],
      // Landline-only validator
      ['landline', 'vendor.igorsantos07.yii-br-pack.PhoneValidator', 'type' => PhoneValidator::TYPE_LANDLINE],
      // Any phone validator - cellphone or landline
      ['phone', 'vendor.igorsantos07.yii-br-pack.PhoneValidator'],
      // Cellphone validator with external area code check
      [
        'cellphone',
        'vendor.igorsantos07.yii-br-pack.PhoneValidator',
        'type'              => PhoneValidator::TYPE_CELLPHONE,
        'areaCodeAttribute' => 'areaCode'
      ],
    ];
  }
}
```
