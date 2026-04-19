<?php
declare(strict_types=1);

namespace libforms\elements;

use jojoe77777\FormAPI\CustomForm as FormAPICustomForm;
use pocketmine\player\Player;

abstract class Element
{
    public function __construct(
        protected string $text,
        protected $callback = null
    ) {
    }

    public function getText(): string
    {
        return $this->text;
    }

    abstract public function addToForm(FormAPICustomForm $form, string $label): void;

    public function handle(Player $player, mixed $value): void
    {
        if ($this->callback !== null) {
            ($this->callback)($player, $value);
        }
    }

    public function hasCallback(): bool
    {
        return $this->callback !== null;
    }
}
