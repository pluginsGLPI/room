<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2009 by the INDEPNET Development Team.

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
// Original Author of file: DOMBRE Julien
// Purpose of file:
// ----------------------------------------------------------------------


// Init plugin
function plugin_init_room() {
	global $PLUGIN_HOOKS,$CFG_GLPI,$LINK_ID_TABLE,$LANG;

	if (isset($_SESSION["glpiID"])){
		// Room may be deleted and specific to an entity
		array_push($CFG_GLPI["specif_entities_tables"],"glpi_plugin_room");
		array_push($CFG_GLPI["deleted_tables"],"glpi_plugin_room");

		pluginNewType('room',"PLUGIN_ROOM_TYPE",1050,"PluginRoom","glpi_plugin_room","room.form.php",$LANG['plugin_room'][0],true);

		array_push($CFG_GLPI["reservation_types"],PLUGIN_ROOM_TYPE);

		if (haveTypeRight(PLUGIN_ROOM_TYPE,'r')){
			$PLUGIN_HOOKS['menu_entry']['room'] = true;
			$PLUGIN_HOOKS['submenu_entry']['room']['add'] = 'room.form.php';
			$PLUGIN_HOOKS['submenu_entry']['room']['search'] = 'index.php';
		} 

		if (haveTypeRight(PLUGIN_ROOM_TYPE,'w')){
			// Massive Action definition
			$PLUGIN_HOOKS['use_massive_action']['room']=1;
		}
		$PLUGIN_HOOKS['headings']['room'] = 'plugin_get_headings_room';
		$PLUGIN_HOOKS['headings_action']['room'] = 'plugin_headings_actions_room';
	}
}
// Get the name and the version of the plugin - Needed
function plugin_version_room(){
	global $LANG;

	return array( 'name'    => $LANG['plugin_room'][0],
		'version' => '2.0',
		'author'=>'Julien Dombre',
		'homepage'=>'http://glpi-project.org',
		'minGlpiVersion' => '0.72',// For compatibility / no install in version < 0.72
		);
}

function plugin_room_install(){
	global $DB, $LANG;

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
			`count_linked` smallint(6) NOT NULL default '0',
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

	plugin_init_room();

	cleanCache("GLPI_HEADER_".$_SESSION["glpiID"]);
	return true;
}

function plugin_room_uninstall(){
	global $DB;

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

	plugin_init_room();
	cleanCache("GLPI_HEADER_".$_SESSION["glpiID"]);

	return true;
}

// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_room_check_prerequisites(){
	if (GLPI_VERSION>=0.72){
		return true;
	} else {
		echo "GLPI version not compatible need 0.72";
	}
}


// Uninstall process for plugin : need to return true if succeeded : may display messages or add to message after redirect
function plugin_room_check_config(){
	return true;
}

// Define rights for the plugin types
function plugin_room_haveTypeRight($type,$right){
	switch ($type){
		case PLUGIN_ROOM_TYPE :
			return haveRight("computer",$right);
			break;
	}
}

?>
