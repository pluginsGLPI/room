<?php

/**
 * To display an entry for the Rooms in the GLPI menus.
 *
 * @author DUVERGIER Claude
 */
class PluginRoomMenu extends CommonGLPI
{
    public static $rightname = 'plugin_room';

    public static function getMenuName()
    {
        return PluginRoomRoom::getTypeName();
    }

    public static function getMenuContent()
    {
        $menu = [];
        $menu['title'] = self::getMenuName();
        $menu['page'] = PluginRoomRoom::getSearchURL(false);
        $menu['links']['search'] = PluginRoomRoom::getSearchURL(false);
        if (PluginRoomRoom::canCreate()) {
            $menu['links']['add'] = PluginRoomRoom::getFormURL(false);
        }
	$menu['icon'] = self::getIcon();
        return $menu;
    }

    public static function removeRightsFromSession()
    {
        if (isset($_SESSION['glpimenu']['tools']['types']['PluginRoomMenu'])) {
            unset($_SESSION['glpimenu']['tools']['types']['PluginRoomMenu']);
        }
        if (isset($_SESSION['glpimenu']['tools']['content']['pluginroommenu'])) {
            unset($_SESSION['glpimenu']['tools']['content']['pluginroommenu']);
        }
    }

    static function getIcon() {
	return "fas fa-download";
    }
}
