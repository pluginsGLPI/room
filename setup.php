<?php

define('PLUGIN_ROOM_VERSION', '3.1.2-jb');

// Initilisation du plugin (appelée à l'activation du plugin)
// Cette fonction définie les HOOKS avec GLPI et permet de déclarer de
// nouveaux objets d'inventaire.
function plugin_init_room()
{
    global $PLUGIN_HOOKS, $CFG_GLPI, $LINK_ID_TABLE, $LANG;

    $PLUGIN_HOOKS['csrf_compliant']['room'] = true;
    $PLUGIN_HOOKS['assign_to_ticket']['room'] = true;
    $PLUGIN_HOOKS['assign_to_ticket_dropdown']['room'] = true;

    // Activation d'un onglet room dans les profils
    $PLUGIN_HOOKS['change_profile']['room'] = [
        'PluginRoomProfile',
        'initProfile',
    ];

    // Déclaration d'un nouvel objet d'inventaire Room
    Plugin::registerClass('PluginRoomRoom', [
        'reservation_types' => true,
        'ticket_types' => true,
        'linkgroup_tech_types' => true,
    ]);

    Plugin::registerClass('PluginRoomProfile', [
        'addtabon' => 'Profile',
    ]);

    if (Session::getLoginUserID()) {
        $PLUGIN_HOOKS['menu_toadd']['room'] = [
            'assets' => 'PluginRoomMenu',
        ];
    }
}

// Get the name and the version of the plugin - Needed
function plugin_version_room()
{
    global $LANG;

    return [
        'name' => $LANG['plugin_room'][0],
        'version' => PLUGIN_ROOM_VERSION,
        'license' => 'GPLv2+',
        'author' => 'Julien Dombre / Modif bogucool, Pascal Marier-Dionne et Claude Duvergier',
        'homepage' => 'https://github.com/pluginsGLPI/room',
        'minGlpiVersion' => '9.5',
    ];
}

// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_room_check_prerequisites()
{
    if (version_compare(GLPI_VERSION, '9.5', '>=') && version_compare(GLPI_VERSION, '9.6', '<=')) {
        return true;
    } else {
        if (method_exists('Plugin', 'messageIncompatible')) {
            echo Plugin::messageIncompatible('core', '9.5', '9.6');
        } else {
            echo "This plugin requires GLPI >= 9.5 && <= 9.6";
        }
        return false;
    }
}

// Incertain de ce que devrais vérifier cette méthode; je n'y touche donc pas
// unsure as to what this function should check for; i wont modify it
function plugin_room_check_config()
{
    return true;
}
