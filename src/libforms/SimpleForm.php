<?php
declare(strict_types=1);

namespace libforms;

use jojoe77777\FormAPI\SimpleForm as FormAPISimpleForm;
use libforms\elements\Button;

class SimpleForm extends Form
{
    protected string $title = "";
    protected string $content = "";
    /** @var Button[] */
    protected array $buttons = [];

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function addButton(Button $button): void
    {
        $this->buttons[] = $button;
    }

    public function sendForm(): void
    {
        if ($this->player === null) {
            return;
        }
        if (!class_exists(FormAPISimpleForm::class)) {
            $this->player->sendMessage("§cFormAPI is required to use MyPlot forms.");
            return;
        }

        $buttons = $this->buttons;
        $form = new FormAPISimpleForm(static function ($player, $data) use ($buttons): void {
            if ($data === null) {
                return;
            }

            $button = $buttons[(int) $data] ?? null;
            if ($button instanceof Button) {
                $button->handle($player);
            }
        });
        $form->setTitle($this->title);
        $form->setContent($this->content);

        foreach ($this->buttons as $index => $button) {
            $form->addButton($button->getText(), $button->getImageType(), $button->getImagePath(), (string) $index);
        }

        $this->player->sendForm($form);
    }
}
