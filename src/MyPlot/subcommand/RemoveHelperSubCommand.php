<?php
declare(strict_types=1);

namespace MyPlot\subcommand;

use MyPlot\forms\interfaces\MyPlotForm;
use MyPlot\forms\subforms\RemoveHelperForm;
use MyPlot\Plot;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use function array_map;
use function array_search;
use function strtolower;

class RemoveHelperSubCommand extends SubCommand
{
    public function canUse(CommandSender $sender): bool
    {
        return ($sender instanceof Player) and $sender->hasPermission("myplot.command.removehelper");
    }

    public function execute(CommandSender $sender, array $args): bool
    {
        if (count($args) === 0) {
            return false;
        }
        $helperName = $args[0];
        $plot = $this->plugin->getPlotByPosition($sender->getPosition());
        if ($plot === null) {
            $sender->sendMessage(TextFormat::RED . $this->translateString("notinplot"));
            return true;
        }
        if ($plot->owner !== $sender->getName() and !$sender->hasPermission("myplot.admin.removehelper")) {
            $sender->sendMessage(TextFormat::RED . $this->translateString("notowner"));
            return true;
        }

        $helper = $this->plugin->matchOnlinePlayer($helperName);
        if ($helper instanceof Player) {
            $helperName = $helper->getName();
        } else {
            $helperKey = array_search(strtolower($helperName), array_map('strtolower', $plot->helpers), true);
            if ($helperKey !== false) {
                $helperName = $plot->helpers[$helperKey];
            }
        }

        if ($this->plugin->removePlotHelper($plot, $helperName)) {
            $sender->sendMessage($this->translateString("removehelper.success", [$helperName]));
        } else {
            $sender->sendMessage(TextFormat::RED . $this->translateString("error"));
        }
        return true;
    }

    public function getForm(?Player $player = null): ?MyPlotForm
    {
        if ($player !== null and ($plot = $this->plugin->getPlotByPosition($player->getPosition())) instanceof Plot) {
            return new RemoveHelperForm($plot);
        }
        return null;
    }
}
