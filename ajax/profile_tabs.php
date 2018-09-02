<?php

include '../../../inc/includes.php';
header('Content-Type: text/html; charset=UTF-8');
Html::header_nocache();

Session::checkRight('profile', READ);

$prof = new plugin_room_profile();
if ($_POST['interface'] == 'room') {
    $prof->showrelationsForm($_POST['ID']);
}
