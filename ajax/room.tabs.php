<?php

include '../../../inc/includes.php';

if (!isset($_POST['id'])) {
    exit();
}

if (!isset($_POST['withtemplate'])) {
    $_POST['withtemplate'] = '';
}

$Room = new PluginRoomRoom();

if ($_POST['id'] > 0 && $Room->can($_POST['id'], READ)) {
    switch ($_REQUEST['glpi_tab']) {
        case -1: // Onglet Tous
            $Room->showComputers($_POST['target'], $_POST['id']);
            Reservation::showForItem($Room);
            break;
        default: // Logiquement Onglet Principal
            if ($_POST['id']) {
                if (!CommonGLPI::displayStandardTab($Room, $_POST['id'], $_REQUEST['glpi_tab'])) {
                    $Room->showComputers($_POST['target'], $_POST['id']);
                }
            }
            break;
    }
}
