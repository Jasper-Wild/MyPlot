<?php
declare(strict_types=1);

namespace libforms\elements;

use jojoe77777\FormAPI\CustomForm as FormAPICustomForm;

class Toggle extends Element
{
    public function __construct(string $text, protected ?bool $default = null, $callback = null)
    {
        parent::__construct($text, $callback);
    }

    public function addToForm(FormAPICustomForm $form, string $label): void
    {
        $form->addToggle($this->text, $this->default, null, $label);
    }
}
