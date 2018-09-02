<?php

if (!defined('GLPI_ROOT')) {
    die('Sorry. You can\'t access directly to this file');
}

// Class for a Dropdown
class PluginRoomRoomAccessCond extends CommonDropdown
{
    public static $rightname = 'plugin_room';

    public static function getTypeName($nb = 0)
    {
        global $LANG;

        return $LANG['plugin_room'][5];
    }
}
