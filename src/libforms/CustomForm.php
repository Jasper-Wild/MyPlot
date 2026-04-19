<?php
declare(strict_types=1);

namespace libforms;

use jojoe77777\FormAPI\CustomForm as FormAPICustomForm;
use libforms\elements\Element;

class CustomForm extends Form
{
    protected string $title = "";
    /** @var Element[] */
    protected array $elements = [];

    public function __construct(?\pocketmine\player\Player $player = null, protected $onSubmit = null)
    {
        parent::__construct($player);
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function addElement(Element $element): void
    {
        $this->elements[] = $element;
    }

    public function sendForm(): void
    {
        if ($this->player === null) {
            return;
        }
        if (!class_exists(FormAPICustomForm::class)) {
            $this->player->sendMessage("§cFormAPI is required to use MyPlot forms.");
            return;
        }

        $elements = $this->elements;
        $onSubmit = $this->onSubmit;
        $form = new FormAPICustomForm(static function ($player, ?array $data) use ($elements, $onSubmit): void {
            if ($data === null) {
                return;
            }

            $ordered = [];
            foreach ($elements as $index => $element) {
                $value = $data["__element_" . $index] ?? null;
                $ordered[$index] = $value;
                if ($element->hasCallback()) {
                    $element->handle($player, $value);
                }
            }

            if ($onSubmit !== null) {
                $onSubmit($player, $ordered);
            }
        });
        $form->setTitle($this->title);

        foreach ($this->elements as $index => $element) {
            $element->addToForm($form, "__element_" . $index);
        }

        $this->player->sendForm($form);
    }
}
