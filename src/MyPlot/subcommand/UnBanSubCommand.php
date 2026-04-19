<?php
declare(strict_types=1);

namespace MyPlot\subcommand;

use MyPlot\forms\interfaces\MyPlotForm;
use MyPlot\forms\subforms\UnBanPlayerForm;
use MyPlot\Plot;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use function array_map;
use function array_search;
use function strtolower;

class UnBanSubCommand extends SubCommand
{
    public function canUse(CommandSender $sender): bool
    {
        return ($sender instanceof Player) and $sender->hasPermission("myplot.command.unbanplayer");
    }

    public function execute(CommandSender $sender, array $args): bool
    {
        if (count($args) === 0) {
            return false;
        }
        $dplayerName = $args[0];
        $plot = $this->plugin->getPlotByPosition($sender->getPosition());
        if ($plot === null) {
            $sender->sendMessage(TextFormat::RED . $this->translateString("notinplot"));
            return true;
        }
        if ($plot->owner !== $sender->getName() and !$sender->hasPermission("myplot.admin.unbanplayer")) {
            $sender->sendMessage(TextFormat::RED . $this->translateString("notowner"));
            return true;
        }

        $dplayer = $this->plugin->matchOnlinePlayer($dplayerName);
        if ($dplayer instanceof Player) {
            $dplayerName = $dplayer->getName();
        } else {
            $bannedKey = array_search(strtolower($dplayerName), array_map('strtolower', $plot->banned), true);
            if ($bannedKey !== false) {
                $dplayerName = $plot->banned[$bannedKey];
            }
        }

        if ($this->plugin->removePlotDenied($plot, $dplayerName)) {
            $sender->sendMessage($this->translateString("unbanplayer.success1", [$dplayerName]));
            if ($dplayer instanceof Player) {
                $dplayer->sendMessage($this->translateString("unbanplayer.success2", [$plot->X, $plot->Z, $sender->getName()]));
            }
        } else {
            $sender->sendMessage(TextFormat::RED . $this->translateString("error"));
        }
        return true;
    }

    public function getForm(?Player $player = null): ?MyPlotForm
    {
        if ($player !== null and ($plot = $this->plugin->getPlotByPosition($player->getPosition())) instanceof Plot) {
            return new UnBanPlayerForm($plot);
        }
        return null;
    }
}
