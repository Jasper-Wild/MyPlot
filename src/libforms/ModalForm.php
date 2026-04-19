<?php
declare(strict_types=1);

namespace libforms;

use libforms\elements\Button;
use pocketmine\form\FormValidationException;

class ModalForm extends Form
{
    protected string $title = "";
    protected string $content = "";
    protected ?Button $button1 = null;
    protected ?Button $button2 = null;

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setButton1(Button $button): void
    {
        $this->button1 = $button;
    }

    public function setButton2(Button $button): void
    {
        $this->button2 = $button;
    }

    public function sendForm(): void
    {
        if ($this->player === null || $this->button1 === null || $this->button2 === null) {
            return;
        }
        if (!class_exists(\jojoe77777\FormAPI\ModalForm::class)) {
            $this->player->sendMessage("§cFormAPI is required to use MyPlot forms.");
            return;
        }

        $button1 = $this->button1;
        $button2 = $this->button2;
        $form = new class(static function ($player, $data) use ($button1, $button2): void {
            if ($data === null) {
                return;
            }

            if ($data) {
                $button1->handle($player);
            } else {
                $button2->handle($player);
            }
        }) extends \jojoe77777\FormAPI\ModalForm {
            public function processData(&$data): void
            {
                if ($data !== null && !is_bool($data)) {
                    throw new FormValidationException("Expected a boolean response, got " . gettype($data));
                }
            }
        };
        $form->setTitle($this->title);
        $form->setContent($this->content);
        $form->setButton1($this->button1->getText());
        $form->setButton2($this->button2->getText());

        $this->player->sendForm($form);
    }
}
