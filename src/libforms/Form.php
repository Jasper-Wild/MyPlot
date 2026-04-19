<?php
declare(strict_types=1);

namespace libforms;

use pocketmine\player\Player;

abstract class Form
{
    protected ?Player $player;

    public function __construct(?Player $player = null)
    {
        $this->player = $player;
    }

    public function setPlayer(?Player $player): void
    {
        $this->player = $player;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    abstract public function sendForm(): void;
}
