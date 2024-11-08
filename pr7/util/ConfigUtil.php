<?php

/**
 * Class used to configure connection of database.
 * @author Rosokha Maksym
 */
final class ConfigUtil {

    /**
     * The name of the json configuration file
     */
    private const FILE_NAME = "ConfigDB.json";

    /**
     * Load properties from JSON file
     * Setup file ConfigDB.json to stores DB Information
     * @return array
     */
    public static function get(): array {
        $configFile = file_get_contents(filename: $_SERVER["DOCUMENT_ROOT"] . "/config/" . self::FILE_NAME);
        return json_decode($configFile, associative: true);
    }
}
