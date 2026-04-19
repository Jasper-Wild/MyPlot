<?php
declare(strict_types=1);

namespace MyPlot\command;

use MyPlot\forms\MainForm;
use MyPlot\MyPlotPermissions;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class CreativeDimensionCommand extends BaseCommand
{
    public function __construct()
    {
        parent::__construct('creativedimension');

        $this->setPermission(MyPlotPermissions::DEFAULT_COMMAND_PERMISSION);
        $this->setAliases(['cd']);
        $this->setDescription('Command used for teleporting to Creative Dimension');
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if ($sender instanceof Player) {
            $sender->teleport($this->getPlugin()->getServer()->getWorldManager()->getWorldByName('Creative')->getSafeSpawn());
            $form = new MainForm($sender, $this->getPlugin()->getCommands()->getCommands());
            $form->sendForm();
        } else {
            $this->sendPlayerOnlyMessage($sender);
        }

        return true;
    }
}
