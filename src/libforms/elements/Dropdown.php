<?php
declare(strict_types=1);

namespace libforms\elements;

use jojoe77777\FormAPI\CustomForm as FormAPICustomForm;

class Dropdown extends Element
{
    public function __construct(
        string $text,
        protected array $options,
        protected int $default = -1,
        $callback = null
    ) {
        parent::__construct($text, $callback);
    }

    public function addToForm(FormAPICustomForm $form, string $label): void
    {
        $default = $this->default >= 0 ? $this->default : null;
        $form->addDropdown($this->text, $this->options, $default, null, $label);
    }

    public function getDefault(): int
    {
        return $this->default;
    }
}
