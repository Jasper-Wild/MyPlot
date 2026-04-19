<?php
declare(strict_types=1);

namespace libforms;

use pocketmine\player\Player;

final class FormManager
{
    public static function createSimpleForm(?Player $player = null): SimpleForm
    {
        return new SimpleForm($player);
    }
}
