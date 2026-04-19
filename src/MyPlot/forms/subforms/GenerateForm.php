<?php
declare(strict_types=1);

namespace MyPlot\forms\subforms;

use libforms\elements\Input;
use libforms\elements\Toggle;
use MyPlot\forms\ComplexMyPlotForm;
use MyPlot\forms\interfaces\PlotAdminForm;
use MyPlot\MyPlot;
use MyPlot\MyPlotGenerator;
use MyPlot\Plot;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class GenerateForm extends ComplexMyPlotForm implements PlotAdminForm
{

    public function __construct()
    {
        $plugin = MyPlot::getInstance();
        $defaultSettings = $plugin->getDefaultWorldSettings();
        $settingKeys = array_keys($defaultSettings);

        $elements = [
            "world" => new Input($plugin->getLanguage()->get("generate.formworld"), "plots"),
            "generator" => new Input($plugin->getLanguage()->get("generate.formgenerator"), "", MyPlotGenerator::NAME)
        ];

        foreach ($defaultSettings as $key => $value) {
            $elements[$key] = is_bool($value)
                ? new Toggle($key, $value)
                : new Input($key, "", (string) $value);
        }

        $elements["teleport"] = new Toggle($plugin->getLanguage()->get("generate.formteleport"), false);

        parent::__construct(
            null,
            TextFormat::BLACK . $plugin->getLanguage()->translateString("form.header", [$plugin->getLanguage()->get("generate.form")]),
            $elements,
            function (Player $player, ?array $data = []) use ($plugin, $settingKeys) {
                $worldName = trim((string) array_shift($data));
                if ($player->getServer()->getWorldManager()->isWorldGenerated($worldName)) {
                    $player->sendMessage(TextFormat::RED . $plugin->getLanguage()->translateString("generate.exists", [$worldName]));
                    return;
                }
                $generatorName = $plugin->normalizeGeneratorName((string) array_shift($data));
                if (!$plugin->isGeneratorRegistered($generatorName)) {
                    $player->sendMessage(TextFormat::RED . $plugin->getLanguage()->translateString("generate.gexists", [$generatorName]));
                    return;
                }

                $settings = [];
                foreach ($settingKeys as $key) {
                    $settings[$key] = array_shift($data);
                }
                $settings = $plugin->normalizeWorldSettings($settings);
                $teleport = $plugin->normalizeBooleanInput(array_shift($data), false);

                if ($plugin->generateWorld($worldName, $generatorName, $settings)) {
                    if ($teleport) {
                        $plugin->teleportPlayerToPlot($player, new Plot($worldName, 0, 0));
                    }
                    $player->sendMessage($plugin->getLanguage()->translateString("generate.success", [$worldName]));
                } else {
                    $player->sendMessage(TextFormat::RED . $plugin->getLanguage()->translateString("generate.error"));
                }
            }
        );
    }

    public function getName(): string
    {
        return "Generate new plot world";
    }
}
