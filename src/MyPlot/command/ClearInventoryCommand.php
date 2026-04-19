<?php
declare(strict_types=1);

namespace MyPlot\command;

use MyPlot\MyPlotPermissions;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class ClearInventoryCommand extends BaseCommand
{
    public function __construct()
    {
        parent::__construct('clearinventory');

        $this->setAliases(['ci']);
        $this->setPermission(MyPlotPermissions::RANK_LEGEND);
        $this->setPermissionMessage('You need the MyPlot legend permission to use this command.');
        $this->setDescription('Command used for clearing your inventory for Legend players');
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if ($sender instanceof Player) {
            $sender->getInventory()->clearAll();
            $this->getPlugin()->sendSuccess($sender, 'Your inventory was cleared.');
        } else {
            $this->sendPlayerOnlyMessage($sender);
        }

        return true;
    }
}
