<?php
class CardField extends CMaskedTextField {

	/**
	 * One of "pan", "expiry" or "cvv". Can be guessed from the attribute name.
	 * @var string
	 */
	public $type;

	/**
	 * If it should show a placeholder. Defaults to true.
	 * @var
	 */
	public $showPlaceholder = true;

	public static $guesses = [
		'cvv'    => ['cvv'],
		'pan'    => ['pan', 'number', 'numero'],
		'expiry' => ['date', 'expiry', 'data', 'validade']
	];

	public function run() {
		$this->guessType();

		switch ($this->type) {
			case 'cvv':
				$this->mask        = '999';
				$this->placeholder = ' ';
			break;

			case 'expiry':
				$this->mask        = '99/99';
				$this->placeholder = '_';
			break;

			case 'pan':
				$this->mask        = '9999 9999 9999 9999';
				$this->placeholder = ' ';
			break;

			default:
				throw new CException(Yii::t('yii', 'Could not guess CardField type nor CardField.type is defined.'));
		}

		if ($this->showPlaceholder)
			$this->htmlOptions['placeholder'] = str_replace(9, $this->placeholder, $this->mask);

		parent::run();
	}

	protected function guessType() {
		if (!$this->type && $this->attribute) {
			foreach (static::$guesses as $type => $guesses) {
				foreach ($guesses as $guess) {
					if (stripos($this->attribute, $guess)) {
						$this->type = $type;
						break 2;
					}
				}
			}
		}
	}

}
