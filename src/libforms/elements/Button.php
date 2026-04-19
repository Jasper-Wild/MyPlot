<?php
declare(strict_types=1);

namespace libforms\elements;

use pocketmine\player\Player;

class Button
{
    public const IMAGE_TYPE_PATH = 0;
    public const IMAGE_TYPE_URL = 1;

    public function __construct(
        protected string $text,
        protected $callback = null,
        protected int $imageType = -1,
        protected string $imagePath = ""
    ) {
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getImageType(): int
    {
        return $this->imageType;
    }

    public function getImagePath(): string
    {
        return $this->imagePath;
    }

    public function handle(Player $player): void
    {
        if ($this->callback !== null) {
            ($this->callback)($player);
        }
    }
}
