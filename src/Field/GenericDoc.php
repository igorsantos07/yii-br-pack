<?php
namespace BrPack\Field;

class GenericDoc extends \CMaskedTextField {

	/**
	 * If it should show a placeholder. Defaults to true.
	 * @var boolean
	 */
	public $showPlaceholder = true;

	public function run() {
		if ($this->showPlaceholder)
			$this->htmlOptions['placeholder'] = strtr($this->mask, '9a*', '___');

		parent::run();
	}

}
