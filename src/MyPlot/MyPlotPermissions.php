<?php
declare(strict_types=1);

namespace MyPlot;

final class MyPlotPermissions
{
    public const DEFAULT_COMMAND_PERMISSION = "myplot.rank.default";
    public const RANK_VOTER = "myplot.rank.voter";
    public const RANK_EMERALD = "myplot.rank.emerald";
    public const RANK_LEGEND = "myplot.rank.legend";
    public const RANK_DEVELOPER = "myplot.rank.developer";

    private function __construct()
    {
    }
}
