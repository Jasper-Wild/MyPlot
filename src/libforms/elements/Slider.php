<?php
declare(strict_types=1);

namespace libforms\elements;

use jojoe77777\FormAPI\CustomForm as FormAPICustomForm;

class Slider extends Element
{
    protected int $step = -1;
    protected int $default = -1;

    public function __construct(string $text, protected int $min, protected int $max, $callback = null)
    {
        parent::__construct($text, $callback);
    }

    public function setStep(int $step): void
    {
        $this->step = $step;
    }

    public function setDefault(int $default): void
    {
        $this->default = $default;
    }

    public function addToForm(FormAPICustomForm $form, string $label): void
    {
        $form->addSlider($this->text, $this->min, $this->max, $this->step, $this->default, null, $label);
    }
}
