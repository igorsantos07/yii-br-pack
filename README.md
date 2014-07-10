Yii Brazilian Package
=====================

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

> models/PersonForm.php
```php
class PersonForm extends CModel {

  public $name;

  public $cpf;
  public $cnpj;
  public $cellphone;
  public $landline;
  public $phone;
  public $areaCode;

  public $card_number;
  public $card_cvv;
  public $card_expiry;

  public function rules() {
    // Using short array notation but the class is PHP <5.4 compatible ;)
    return [
      // CPF validator
      ['cpf', 'BrPack\Validator\Cpf'],
      // CNPJ validator
      ['cnpj', 'BrPack\Validator\Cnpj'],
      // Cellphone-only validator, checking area code inside the field
      ['cellphone', 'BrPack\Validator\Phone', 'type' => PhoneValidator::TYPE_CELLPHONE],
      // Cellphone-only validator, not validating area code
      [
        'cellphone',
        'BrPack.PhoneValidator',
        'type'     => BrPack\Validator\Phone::TYPE_CELLPHONE,
        'areaCode' => false
      ],
      // Landline-only validator
      ['landline', 'BrPack\Validator\Phone', 'type' => BrPack\Validator\Phone::TYPE_LANDLINE],
      // Any phone validator - cellphone or landline
      ['phone', 'BrPack\Validator\Phone'],
      // Cellphone validator with external area code check
      [
        'cellphone',
        'BrPack\Validator\Phone',
        'type'              => BrPack\Validator\Phone::TYPE_CELLPHONE,
        'areaCodeAttribute' => 'areaCode'
      ],
    ];
  }
}
```

> views/person/new.php
```php
<?php $form = $this->beginWidget('CActiveForm, ['id' => 'my-person-form']) ?>

    <?=$form->label($model, 'name')?>
    <?=$form->textField($model, 'name')?>
    <?=$form->error($model, 'name')?>
    <br/>

    <?=$form->label($model, 'cpf')?>
    <?php $this->widget('BrPack\Field\Cpf', ['model' => $model, 'attribute' => 'cpf']) ?>
    <?=$form->error($model, 'cpf')?>
    <br/>

    <?=$form->label($model, 'cellphone')?>
    <?php $this->widget('BrPack\Field\Phone', ['model' => $model, 'attribute' => 'cellphone', 'type' => 'mobile']) ?>
    <?=$form->error($model, 'cpf')?>
    <br/>

    <?=$form->label($model, 'card_number')?>
    <?php $this->widget('BrPack\Field\Card', ['model' => $model, 'attribute' => 'card_number']) ?>
    <?php $this->widget('BrPack\Field\Card', ['model' => $model, 'attribute' => 'card_expiry']) ?>
    <?php $this->widget('BrPack\Field\Card', ['model' => $model, 'attribute' => 'card_cvv']) ?>
    <?=$form->error($model, 'card_number')?>
    <?=$form->error($model, 'card_expiry')?>
    <?=$form->error($model, 'card_cvv')?>
    <br/>

    <?=CHtml::submitButton()?>
<?php $this->endWidget() ?>
```
