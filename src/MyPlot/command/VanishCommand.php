<?php
declare(strict_types=1);

namespace MyPlot\command;

use MyPlot\MyPlotPermissions;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class VanishCommand extends BaseCommand
{
    public function __construct()
    {
        parent::__construct('vanish');

        $this->setPermission(MyPlotPermissions::RANK_LEGEND);
        $this->setPermissionMessage('You need the MyPlot legend permission to use this command.');
        $this->setDescription('Command used for making yourself vanish for Legend players');
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if ($sender instanceof Player) {
            if (!$this->getPlugin()->isVanished($sender)) {
                $this->getPlugin()->setVanished($sender, true);
                $this->getPlugin()->sendSuccess($sender, 'Vanish enabled.');
            } else {
                $this->getPlugin()->setVanished($sender, false);
                $this->getPlugin()->sendSuccess($sender, 'Vanish disabled.');
            }
        } else {
            $this->sendPlayerOnlyMessage($sender);
        }

        return true;
    }
}
