<?php

$NEEDED_ITEMS = [
    'search',
];

include '../../inc/includes.php';

PluginRoomRoom::canView();

Html::header((__'Room Management', 'room'), $_SERVER['PHP_SELF'], 'assets', 'pluginroommenu');

Search::show('PluginRoomRoom');

Html::footer();
