<?php
declare(strict_types=1);

namespace libforms\elements;

use jojoe77777\FormAPI\CustomForm as FormAPICustomForm;

class Input extends Element
{
    public function __construct(
        string $text,
        protected string $placeholder = "",
        protected ?string $default = null,
        $callback = null
    ) {
        parent::__construct($text, $callback);
    }

    public function addToForm(FormAPICustomForm $form, string $label): void
    {
        $form->addInput($this->text, $this->placeholder, $this->default, null, $label);
    }
}
