<?php

$NEEDED_ITEMS = [
    'search',
];

include '../../inc/includes.php';

PluginRoomRoom::canView();

Html::header($LANG['plugin_room'][0], $_SERVER['PHP_SELF'], 'assets', 'pluginroommenu');

Search::show('PluginRoomRoom');

Html::footer();
