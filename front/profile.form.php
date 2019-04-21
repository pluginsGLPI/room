<?php

include '../../../inc/includes.php';

Session::checkRight('profile', READ);

$prof = new PluginRoomProfile();

// Save profile
if (isset($_POST['update'])) {
    $prof->update($_POST);
    Html::back();
}
