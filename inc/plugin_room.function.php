<?php
/*
  ----------------------------------------------------------------------
  GLPI - Gestionnaire Libre de Parc Informatique
  Copyright (C) 2003-2008 by the INDEPNET Development Team.
  
  http://indepnet.net/   http://glpi-project.org/
  ----------------------------------------------------------------------
  
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
  ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: DOMBRE Julien
// Purpose of file:
// ----------------------------------------------------------------------

function plugin_room_initSession() {
	global $DB;
	
	if(plugin_room_isInstalled()){
		$_SESSION["glpiplugin_room_installed"]=1;
	}
}

function plugin_room_isInstalled(){
	return TableExists("glpi_plugin_room");
}

function plugin_room_AddDevice($rID,$cID){
	global $DB;
	if ($rID>0&&$cID>0){
		$query="SELECT ID FROM glpi_plugin_room_computer WHERE FK_computers='$cID'";
		if ($result = $DB->query($query)){
			if ($DB->numrows($result)==0){
				$query="INSERT INTO glpi_plugin_room_computer (FK_rooms,FK_computers) VALUES ('$rID','$cID');";
				$result = $DB->query($query);
				plugin_room_updateCountDevices($rID);
			}
		}
	}
}

function plugin_room_DeleteDevice($ID){
	global $DB;
	$query="SELECT FK_rooms FROM glpi_plugin_room_computer WHERE ID='$ID'";
	if ($result = $DB->query($query)){
		$IDroom=$DB->result($result,0,0);
		$query="DELETE FROM glpi_plugin_room_computer WHERE ID= '$ID';";
		$result = $DB->query($query);
		plugin_room_updateCountDevices($IDroom);
	}
}

function plugin_room_updateCountDevices($ID){
	global $DB;
	$query="SELECT count(ID) FROM glpi_plugin_room_computer WHERE FK_rooms='$ID'";
	if ($result = $DB->query($query)){
		$query2="UPDATE glpi_plugin_room SET count_linked='".$DB->result($result,0,0)."'  WHERE ID='$ID'";
		$DB->query($query2);
	}
}


// Example of an action heading
function plugin_room_showComputerRoom($type,$ID,$withtemplate=0){
	global $DB,$LANG,$CFG_GLPI;
	if (!$withtemplate){

		if ($ID>0){
			$query="SELECT glpi_plugin_room.*
				FROM glpi_plugin_room_computer 
				INNER JOIN glpi_plugin_room ON (glpi_plugin_room.ID = glpi_plugin_room_computer.FK_rooms) 
				WHERE FK_computers='$ID'";
			if ($result = $DB->query($query)){
				if ($DB->numrows($result)>0){
					$data=$DB->fetch_assoc($result);
					echo "<div align='center'>".$LANG['plugin_room'][20]." ";
					if (haveTypeRight(PLUGIN_ROOM_TYPE,'r')){
						echo "<a href=\"".$CFG_GLPI["root_doc"]."/plugins/room/room.form.php?ID=".$data["ID"]."\">".$data['name']."</a>";
					} else {
						echo $data['name'];
					}
					echo "</div>";
				}
			}
		}

	}
}

?>