<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file:
// Purpose of file:
// ----------------------------------------------------------------------



$NEEDED_ITEMS=array('reservation');

define('GLPI_ROOT', '../..');
include (GLPI_ROOT . "/inc/includes.php");


if(!isset($_GET["ID"])) $_GET["ID"] = -1;

$room=new PluginRoom();
if (isset($_POST["add"])){
	$room->check(-1,'w');

	$room->add($_POST);
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST["delete"])) {
	$room->check($_POST["ID"],'w');

	$room->delete($_POST);
	glpi_header($CFG_GLPI["root_doc"]."/plugins/room/index.php");
} else if (isset($_POST["purge"])) {
	$room->check($_POST["ID"],'w');

	$room->delete($_POST,1);
	glpi_header($CFG_GLPI["root_doc"]."/plugins/room/index.php");
} else if (isset($_POST["restore"])) {
	$room->check($_POST["ID"],'w');

	$room->restore($_POST);
	glpi_header($CFG_GLPI["root_doc"]."/plugins/room/index.php");
} else if (isset($_POST["update"])) {
	$room->check($_POST["ID"],'w');

	$room->update($_POST);
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST["additem"])){

	$room->check($_POST["rID"],'w');

	if ($_POST['rID']>0&&$_POST['cID']>0){
		plugin_room_AddDevice($_POST["rID"],$_POST['cID']);
	}
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST["deleteitem"])){

	$room->check($_POST["rID"],'w');

	if (count($_POST["item"])){
		foreach ($_POST["item"] as $key => $val){
			plugin_room_DeleteDevice($key);
		}
	}
	glpi_header($_SERVER['HTTP_REFERER']);
} else {
	$room->check($_GET["ID"],'r');

	if (!isset($_SESSION['glpi_onglet'])) $_SESSION['glpi_onglet']=1;
	if (isset($_GET['onglet'])) {
		$_SESSION['glpi_onglet']=$_GET['onglet'];
	}

	commonHeader($LANGROOM[0],$_SERVER['PHP_SELF'],"plugins","room");

	if ($room->showForm($_SERVER['PHP_SELF'],$_GET["ID"])){
		switch ($_SESSION['glpi_onglet']){
			case -1 :
				$room->showComputers($_SERVER['PHP_SELF'],$_GET["ID"]);
				showDeviceReservations($_SERVER['PHP_SELF'],PLUGIN_ROOM_TYPE,$_GET["ID"]);
				break;
			case 11 :
				showDeviceReservations($_SERVER['PHP_SELF'],PLUGIN_ROOM_TYPE,$_GET["ID"]);
				break;
			default :
				if ($_GET["ID"]){
					if (!displayPluginAction(PLUGIN_ROOM_TYPE,$_GET["ID"],$_SESSION['glpi_onglet'])){
						$room->showComputers($_SERVER['PHP_SELF'],$_GET["ID"]);
					}
				}
				break;
		}

	}
	commonFooter();
}


?>
