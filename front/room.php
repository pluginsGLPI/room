<?php

$NEEDED_ITEMS = [
    'search',
];

include '../../../inc/includes.php';

PluginRoomRoom::canView();

Html::header(__('Room Management'), '', 'assets', 'pluginroommenu');

Search::show('PluginRoomRoom');

Html::footer();
