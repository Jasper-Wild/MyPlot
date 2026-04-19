<?php
declare(strict_types=1);

namespace MyPlot\command;

use MyPlot\MyPlotPermissions;
use MyPlot\task\CleanEntitiesTask;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class ClearEntitiesCommand extends BaseCommand
{
    public function __construct()
    {
        parent::__construct('ce');

        $this->setPermission(MyPlotPermissions::RANK_DEVELOPER);
        $this->setPermissionMessage('You need the MyPlot developer permission to use this command.');
        $this->setDescription('Command used for clearing unnecessary entities in worlds');
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if ($sender instanceof Player) {
            $this->getPlugin()->getScheduler()->scheduleDelayedTask(new CleanEntitiesTask($this->getPlugin()), 1);
        } else {
            $this->sendPlayerOnlyMessage($sender);
        }

        return true;
    }
}
