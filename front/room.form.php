<?php

/**
 * To open form for room object.
 */

$NEEDED_ITEMS = [
    'reservation',
    'plugin',
];

include '../../../inc/includes.php';

if (!isset($_GET['id'])) {
    $_GET['id'] = '';
}

if (!isset($_GET['withtemplate'])) {
    $_GET['withtemplate'] = '';
}

$room = new PluginRoomRoom();

if (isset($_POST['add'])) { // Ajout d'une salle
    $room->check(-1, CREATE, $_POST);

    $newID = $room->add($_POST);
    Html::back();
} else {
    if (isset($_POST['delete'])) { // Supression d'une salle
        $room->check($_POST['id'], DELETE);

        $room->delete($_POST);
        Html::redirect($CFG_GLPI['root_doc'] . '/plugins/room/index.php');
    } else {
        if (isset($_POST['purge'])) { // Purge de la salle
            $room->check($_POST['id'], PURGE);

            $room->delete($_POST, 1);
            Html::redirect($CFG_GLPI['root_doc'] . '/plugins/room/index.php');
        } else {
            if (isset($_POST['restore'])) { // Restauration de la salle
                $room->check($_POST['id'], PURGE);

                $room->restore($_POST);
                Html::redirect($CFG_GLPI['root_doc'] . '/plugins/room/index.php');
            } else {
                if (isset($_POST['update'])) { // Modification d'une salle
                    $room->check($_POST['id'], UPDATE);

                    $room->update($_POST);
                    Html::back();
                } else {
                    if (isset($_POST['additem'])) { // Ajout de la liaison à un ordinateur
                        $room->check($_POST['room_id'], UPDATE); // Ça devrait pas être rooms_id?

                        if ($_POST['room_id'] > 0 && $_POST['computers_id'] > 0) {
                            $room->plugin_room_AddDevice($_POST['room_id'], $_POST['computers_id']);
                        }
                        Html::back();
                    } else {
                        if (isset($_POST['deleteitem'])) { // Suppression de la liaison à un ordinateur
                            $room->check($_POST['room_id'], UPDATE);

                            if (count($_POST['item'])) {
                                foreach ($_POST['item'] as $key => $val) {
                                    $room->plugin_room_DeleteDevice($key);
                                }
                            }
                            Html::back();
                        } else { // Logiquement on passe ici pour visualiser une salle
                            $room->check($_GET['id'], READ);

                            // test l'onglet de départ a afficher à l'ouverture de la fiche
                            if (!isset($_SESSION['glpi_tab'])) {
                                $_SESSION['glpi_tab'] = 1;
                            }
                            if (isset($_GET['tab'])) {
                                $_SESSION['glpi_tab'] = $_GET['tab'];
                            }

                            Html::header($LANG['plugin_room'][0], '', 'assets', 'pluginroommenu');

                            $room->display($_GET);

                            Html::footer();
                        }
                    }
                }
            }
        }
    }
}
