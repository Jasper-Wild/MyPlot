<?php
declare(strict_types=1);

namespace libforms\elements;

class ImageButton extends Button
{
    public function __construct(string $text, int $imageType, string $imagePath, $callback = null)
    {
        parent::__construct($text, $callback, $imageType, $imagePath);
    }
}
