<?php
declare(strict_types=1);

namespace MyPlot\subcommand;

use MyPlot\forms\interfaces\MyPlotForm;
use MyPlot\forms\subforms\TimeForm;
use MyPlot\MyPlotPermissions;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\SetTimePacket;
use pocketmine\player\Player;

class TimeSubCommand extends SubCommand
{
    public function canUse(CommandSender $sender): bool
    {
        return ($sender instanceof Player);
    }

    public function execute(CommandSender $sender, array $args): bool
    {
        if (!$sender instanceof Player) {
            return true;
        }
        if (!$sender->hasPermission(MyPlotPermissions::RANK_LEGEND)) {
            $this->plugin->sendError($sender, 'You need the MyPlot legend permission to change your plot time.');
        } else {
            if (count($args) !== 1) {
                return false;
            }
            if ($args[0] === 'day') {
                $value = 0;
            } elseif ($args[0] === 'night') {
                $value = 14000;
            } else {
                $value = $this->getInteger($args[0], 0);
            }
            $this->setTime($sender, $value);
        }
        return true;
    }

    private function getInteger(mixed $value, int $min = 30000000, int $max = -30000000): int
    {
        $i = (int)$value;
        if ($i < $min) {
            $i = $min;
        } elseif ($i > $max) {
            $i = $max;
        }
        return $i;
    }

    private function setTime(Player $player, int $time): void
    {
        if (in_array($player->getName(), $this->getPlugin()->stopTime, true)) {
            $index = array_search($player->getName(), $this->getPlugin()->stopTime, true);
            unset($this->getPlugin()->stopTime[$index]);
        }

        $player->getNetworkSession()->sendDataPacket(SetTimePacket::create($time));

        $this->getPlugin()->stopTime[] = $player->getName();
    }

    public function getForm(?Player $player = null): ?MyPlotForm
    {
        return $player !== null ? new TimeForm($player) : null;
    }
}
