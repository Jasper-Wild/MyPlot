<?php
declare(strict_types=1);

namespace MyPlot\forms\subforms;

use libforms\elements\Dropdown;
use libforms\elements\Input;
use MyPlot\forms\ComplexMyPlotForm;
use MyPlot\MyPlot;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use function count;
use function ksort;

class WarpForm extends ComplexMyPlotForm
{

    public function __construct(Player $player)
    {
        $plugin = MyPlot::getInstance();
        $elements = [];
        $plotNames = [];
        $plots = $plugin->getProvider()->getPlotsByOwner($player->getName(), $player->getWorld()->getFolderName());
        $hasPlots = !empty($plots);
        ksort($plots);
        if ($hasPlots) {
            for ($i = 0, $iMax = count($plots); $i < $iMax; $i++) {
                $plotNames[$i] = 'Plot #' . ($i + 1) . ' (' . $plots[$i]->X . ';' . $plots[$i]->Z . ')';
            }
        } else {
            $plotNames[] = TextFormat::DARK_BLUE . "No plots found";
        }
        ksort($plotNames);
        $elements[] = new Dropdown("Select your plots:", $plotNames, -1);
        $elements[] = new Input($plugin->getLanguage()->get("warp.formxcoord"), "0");
        $elements[] = new Input($plugin->getLanguage()->get("warp.formzcoord"), "0");
        parent::__construct(
            $player,
            TextFormat::BLACK . $plugin->getLanguage()->translateString("form.header", [$plugin->getLanguage()->get("warp.form")]),
            $elements,
            function (Player $player, ?array $data = []) use ($plugin, $plots, $hasPlots): void {
                if ($data === null) {
                    return;
                }

                $selectedPlot = $data[0] ?? null;
                $xInput = trim((string) ($data[1] ?? ""));
                $zInput = trim((string) ($data[2] ?? ""));

                if ($xInput !== "" || $zInput !== "") {
                    if ($xInput === "" || $zInput === "") {
                        $player->sendMessage(TextFormat::RED . "Please verify that both the input boxes are filled.");
                        return;
                    }

                    $player->getServer()->dispatchCommand(
                        $player,
                        $plugin->getLanguage()->get("command.name") . " " . $plugin->getLanguage()->get("warp.name") . " " . ((int) $xInput) . ";" . ((int) $zInput) . ' "' . ($player->getWorld()->getFolderName()) . '"',
                        true
                    );
                    return;
                }

                if ($hasPlots && is_int($selectedPlot) && isset($plots[$selectedPlot])) {
                    $plot = $plots[$selectedPlot];
                    $player->getServer()->dispatchCommand(
                        $player,
                        $plugin->getLanguage()->get("command.name") . " " . $plugin->getLanguage()->get("warp.name") . " " . ($plot->X) . ";" . ($plot->Z) . ' "' . ($player->getWorld()->getFolderName()) . '"',
                        true
                    );
                    return;
                }

                $player->sendMessage(TextFormat::RED . "Please select a plot or enter both coordinates.");
            }
        );
    }

    public function getName(): string
    {
        return "Teleport";
    }
}
