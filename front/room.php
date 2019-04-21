<?php

$NEEDED_ITEMS = [
    'search',
];

include '../../../inc/includes.php';

PluginRoomRoom::canView();

Html::header($LANG['plugin_room'][0], '', 'assets', 'pluginroommenu');

Search::show('PluginRoomRoom');

Html::footer();
