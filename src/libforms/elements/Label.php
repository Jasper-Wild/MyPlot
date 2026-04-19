<?php
declare(strict_types=1);

namespace libforms\elements;

use jojoe77777\FormAPI\CustomForm as FormAPICustomForm;

class Label extends Element
{
    public function __construct(string $text)
    {
        parent::__construct($text, null);
    }

    public function addToForm(FormAPICustomForm $form, string $label): void
    {
        $form->addLabel($this->text, $label);
    }
}
