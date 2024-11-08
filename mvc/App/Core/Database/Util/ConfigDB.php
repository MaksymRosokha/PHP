<?php

declare(strict_types=1);

namespace Arakviel\DB\Util;

/**
 * Class used to configure connection of Database.
 * @author Vereshchagin Olexandr
 */
final class ConfigDB
{

    /**
     * Load properties from JSON file
     * Setup file ConfigDB.json to stores DB Information
     * @return array
     */
    public static function importFromJSON(): array
    {
        $pathToJson = self::fileBuildPath(__DIR__, "..", "..", "..", "Config", basename(__CLASS__) . ".json");
        $configFile = file_get_contents(filename: $pathToJson);
        return json_decode($configFile, true);
    }

    private static function fileBuildPath(): string
    {
        return join(DIRECTORY_SEPARATOR, func_get_args());
    }
}
