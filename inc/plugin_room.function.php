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

function plugin_room_Install(){
	global $DB;

	if (!TableExists('glpi_plugin_room')){
		$query="CREATE TABLE  `glpi_plugin_room` (
			`ID` int(11) NOT NULL auto_increment,
			`name` varchar(255) collate utf8_unicode_ci default NULL,
			`FK_entities` int(11) NOT NULL default '0',
			`recursive` smallint(6) NOT NULL default '0',
			`deleted` smallint(6) NOT NULL default '0',
			`type` int(11) NOT NULL default '0',
			`date_mod` datetime default NULL,
			`size` smallint(6) NOT NULL default '0',
			`buy` datetime default NULL,
			`access` int(11) NOT NULL default '0',
			`printer` smallint(6) NOT NULL default '0',
			`videoprojector` smallint(6) NOT NULL default '0',
			`wifi` smallint(6) NOT NULL default '0',
			`comments` text collate utf8_unicode_ci,
			`opening` varchar(255) collate utf8_unicode_ci default NULL,
			`limits` varchar(255) collate utf8_unicode_ci default NULL,
			`text1` varchar(255) collate utf8_unicode_ci default NULL,
			`text2` varchar(255) collate utf8_unicode_ci default NULL,
			`dropdown1` int(11) NOT NULL default '0',
			`dropdown2` int(11) NOT NULL default '0',
			`tech_num` int(11) NOT NULL default '0',
			`FK_users` int(11) NOT NULL default '0',
			`is_template` smallint(6) NOT NULL default '0', # not used / for reservation search engine
			`location` smallint(6) NOT NULL default '0', # not used / for reservation search engine
			`state` smallint(6) NOT NULL default '0', # not used / for reservation search engine
			`FK_glpi_enterprise` smallint(6) NOT NULL default '0', # not used / for reservation search engine
			`FK_groups` smallint(6) NOT NULL default '0', # not used / for reservation search engine
			PRIMARY KEY  (`ID`),
			KEY `FK_entities` (`FK_entities`),
			KEY `deleted` (`deleted`),
			KEY `type` (`type`),
			KEY `name` (`name`),
			KEY `buy` (`buy`),
			KEY `dropdown1` (`dropdown1`),
			KEY `dropdown2` (`dropdown2`),
			KEY `tech_num` (`tech_num`),
			KEY `FK_users` (`FK_users`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;";
		$DB->query($query) or die("error adding glpi_plugin_room table " . $LANG["update"][90] . $DB->error());
	}
	if (!TableExists('glpi_plugin_room_computer')){
		$query="CREATE TABLE `glpi_plugin_room_computer` (
			`ID` int(11) NOT NULL auto_increment,
			`FK_computers` int(11) NOT NULL,
			`FK_rooms` int(11) NOT NULL,
			PRIMARY KEY  (`ID`),
			UNIQUE `FK_computers` (`FK_computers`),
			KEY `FK_rooms` (`FK_rooms`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		$DB->query($query) or die("error adding glpi_plugin_room_computer table " . $LANG["update"][90] . $DB->error());
	}
	if (!TableExists('glpi_dropdown_plugin_room_type')){
		$query="CREATE TABLE  `glpi_dropdown_plugin_room_type` (
		`ID` int(11) NOT NULL auto_increment,
		`name` varchar(255) collate utf8_unicode_ci default NULL,
		`comments` text collate utf8_unicode_ci,
		PRIMARY KEY  (`ID`),
		KEY `name` (`name`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		$DB->query($query) or die("error adding glpi_dropdown_plugin_room_type table " . $LANG["update"][90] . $DB->error());
	}

	if (!TableExists('glpi_dropdown_plugin_room_access')){
		$query="CREATE TABLE  `glpi_dropdown_plugin_room_access` (
		`ID` int(11) NOT NULL auto_increment,
		`name` varchar(255) collate utf8_unicode_ci default NULL,
		`comments` text collate utf8_unicode_ci,
		PRIMARY KEY  (`ID`),
		KEY `name` (`name`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		$DB->query($query) or die("error adding glpi_dropdown_plugin_room_access table " . $LANG["update"][90] . $DB->error());
	}

	if (!TableExists('glpi_dropdown_plugin_room_dropdown1')){
		$query="CREATE TABLE  `glpi_dropdown_plugin_room_dropdown1` (
		`ID` int(11) NOT NULL auto_increment,
		`name` varchar(255) collate utf8_unicode_ci default NULL,
		`comments` text collate utf8_unicode_ci,
		PRIMARY KEY  (`ID`),
		KEY `name` (`name`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		$DB->query($query) or die("error adding glpi_dropdown_plugin_room_dropdown1 table " . $LANG["update"][90] . $DB->error());
	}

	if (!TableExists('glpi_dropdown_plugin_room_dropdown2')){
		$query="CREATE TABLE  `glpi_dropdown_plugin_room_dropdown2` (
		`ID` int(11) NOT NULL auto_increment,
		`name` varchar(255) collate utf8_unicode_ci default NULL,
		`comments` text collate utf8_unicode_ci,
		PRIMARY KEY  (`ID`),
		KEY `name` (`name`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		$DB->query($query) or die("error adding glpi_dropdown_plugin_room_dropdown2 table " . $LANG["update"][90] . $DB->error());
	}

	$_SESSION["glpiplugin_room_installed"]=1;
	plugin_init_room();

	cleanCache("GLPI_HEADER_".$_SESSION["glpiID"]);
}

function plugin_room_Uninstall(){
	global $DB;

	if (isset($_SESSION["glpiplugin_room_installed"])){
		$query='DROP TABLE `glpi_plugin_room_computer`';
		$DB->query($query) ;
		$query='DROP TABLE `glpi_plugin_room`';
		$DB->query($query) ;
		$query='DROP TABLE `glpi_dropdown_plugin_room_type`';
		$DB->query($query) ;
		$query='DROP TABLE `glpi_dropdown_plugin_room_access`';
		$DB->query($query) ;
		$query='DROP TABLE `glpi_dropdown_plugin_room_dropdown1`';
		$DB->query($query) ;
		$query='DROP TABLE `glpi_dropdown_plugin_room_dropdown2`';
		$DB->query($query) ;

		$query="DELETE FROM `glpi_display` WHERE type=".COMPUTER_TYPE." AND num='1050'";
		$DB->query($query) ;

		unset($_SESSION["glpiplugin_room_installed"]);
		plugin_init_room();
		cleanCache("GLPI_HEADER_".$_SESSION["glpiID"]);
	}
}

function plugin_room_AddDevice($rID,$cID){
	global $DB;
	if ($rID>0&&$cID>0){
		$query="SELECT ID FROM glpi_plugin_room_computer WHERE FK_computers='$cID'";
		if ($result = $DB->query($query)){
			if ($DB->numrows($result)==0){
				$query="INSERT INTO glpi_plugin_room_computer (FK_rooms,FK_computers) VALUES ('$rID','$cID');";
				$result = $DB->query($query);
			}
		}
	}
}

function plugin_room_DeleteDevice($ID){
	global $DB;
	global $DB;
	$query="DELETE FROM glpi_plugin_room_computer WHERE ID= '$ID';";
	$result = $DB->query($query);
}


// Example of an action heading
function plugin_room_showComputerRoom($type,$ID,$withtemplate=0){
	global $DB,$LANGROOM,$CFG_GLPI;
	if (!$withtemplate){

		if ($ID>0){
			$query="SELECT glpi_plugin_room.*
				FROM glpi_plugin_room_computer 
				INNER JOIN glpi_plugin_room ON (glpi_plugin_room.ID = glpi_plugin_room_computer.FK_rooms) 
				WHERE FK_computers='$ID'";
			if ($result = $DB->query($query)){
				if ($DB->numrows($result)>0){
					$data=$DB->fetch_assoc($result);
					echo "<div align='center'>".$LANGROOM[20]." ";
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