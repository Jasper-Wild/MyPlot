<?php
declare(strict_types=1);

namespace MyPlot\command;

use MyPlot\MyPlotPermissions;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\player\Player;
use function count;

class TptoCommand extends BaseCommand
{
    /** @var array<string, array<string, string>> */
    private array $requests = [];

    public function __construct()
    {
        parent::__construct('tpto');

        $this->setPermission(MyPlotPermissions::DEFAULT_COMMAND_PERMISSION);
        $this->setDescription('Command used for sending and accepting teleport requests');
        $this->setUsage('/tpto <accept {player} | decline {player} | {player}>');
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if ($sender instanceof Player) {
            if (count($args) === 0) {
                throw new InvalidCommandSyntaxException();
            }

            if ($args[0] === 'a' || $args[0] === 'accept') {
                if (!isset($args[1])) {
                    $this->getPlugin()->sendError($sender, 'Specify a player.');
                } elseif (($player = $this->getPlugin()->getServer()->getPlayerExact($args[1])) instanceof Player) {
                    if (isset($this->requests[$sender->getName()][$player->getName()])) {
                        $player->teleport($sender->getPosition());
                        $this->getPlugin()->sendSuccess($sender, "Accepted the teleport request from {$player->getName()}.");
                        $this->getPlugin()->sendSuccess($player, "{$sender->getName()} accepted your teleport request.");
                        unset($this->requests[$sender->getName()][$player->getName()]);
                    } else {
                        $this->getPlugin()->sendError($sender, 'You do not have a pending request from that player.');
                    }
                } else {
                    $this->getPlugin()->sendError($sender, 'That player is not online.');
                }
            } elseif ($args[0] === 'd' || $args[0] === 'decline') {
                if (!isset($args[1])) {
                    $this->getPlugin()->sendError($sender, 'Specify a player.');
                } elseif (($player = $this->getPlugin()->getServer()->getPlayerExact($args[1])) instanceof Player) {
                    if (isset($this->requests[$sender->getName()][$player->getName()])) {
                        $this->getPlugin()->sendInfo($sender, "Declined the teleport request from {$player->getName()}.");
                        $this->getPlugin()->sendInfo($player, "{$sender->getName()} declined your teleport request.");
                        unset($this->requests[$sender->getName()][$player->getName()]);
                    } else {
                        $this->getPlugin()->sendError($sender, 'You do not have a pending request from that player.');
                    }
                } else {
                    $this->getPlugin()->sendError($sender, 'That player is not online.');
                }
            } elseif (($player = $this->getPlugin()->getServer()->getPlayerExact($args[0])) instanceof Player) {
                if ($sender->hasPermission(MyPlotPermissions::RANK_EMERALD)) {
                    $this->requests[$player->getName()][$sender->getName()] = $sender->getName();
                    $this->getPlugin()->sendSuccess($sender, "Teleport request sent to {$player->getName()}.");
                    $this->getPlugin()->sendInfo($player, "{$sender->getName()} wants to teleport to you. Use /tpto accept {$sender->getName()} or /tpto decline {$sender->getName()}.");
                } else {
                    $this->getPlugin()->sendError($sender, 'You need the MyPlot emerald permission to send teleport requests.');
                }
            } else {
                throw new InvalidCommandSyntaxException();
            }
        } else {
            $this->sendPlayerOnlyMessage($sender);
        }

        return true;
    }
}
