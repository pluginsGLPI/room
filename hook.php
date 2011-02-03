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

function plugin_room_install(){
	global $DB, $LANG;

	include_once (GLPI_ROOT."/plugins/room/inc/profile.class.php");

	if (!TableExists('glpi_plugin_room_rooms')){
		$query="CREATE TABLE  `glpi_plugin_room_rooms` (
			`id` int(11) NOT NULL auto_increment,
			`name` varchar(255) collate utf8_unicode_ci default NULL,
			`entities_id` int(11) NOT NULL default '0',
			`locations_id` int(11) NOT NULL default '0',
			`is_recursive` smallint(6) NOT NULL default '0',
			`is_deleted` smallint(6) NOT NULL default '0',
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
			PRIMARY KEY  (`id`),
			KEY `entities_id` (`entities_id`),
			KEY `is_deleted` (`is_deleted`),
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
			`id` int(11) NOT NULL auto_increment,
			`FK_computers` int(11) NOT NULL,
			`FK_rooms` int(11) NOT NULL,
			PRIMARY KEY  (`id`),
			UNIQUE `FK_computers` (`FK_computers`),
			KEY `FK_rooms` (`FK_rooms`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		$DB->query($query) or die("error adding glpi_plugin_room_computer table " . $LANG["update"][90] . $DB->error());
	}
	if (!TableExists('glpi_plugin_room_profiles')){
		$query="CREATE TABLE `glpi_plugin_room_profiles` (
			`id` int(11) NOT NULL auto_increment,
			`profiles_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_profiles (id)',
			`room` char(1) collate utf8_unicode_ci default NULL,
			PRIMARY KEY  (`id`),
			KEY `profiles_id` (`profiles_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		$DB->query($query) or die("error adding glpi_plugin_room_profiles table " . $LANG["update"][90] . $DB->error());
		// je pense qu'il faut aussi ici faire des insertions dans glpi_displaypreferences
	}
	if (!TableExists('glpi_plugin_room_roomtypes')){
		$query="CREATE TABLE  `glpi_plugin_room_roomtypes` (
		`id` int(11) NOT NULL auto_increment,
		`name` varchar(255) collate utf8_unicode_ci default NULL,
		`comment` text collate utf8_unicode_ci,
		PRIMARY KEY  (`id`),
		KEY `name` (`name`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		$DB->query($query) or die("error adding glpi_plugin_room_roomtypes table " . $LANG["update"][90] . $DB->error());
	}

	if (!TableExists('glpi_plugin_room_roomaccessconds')){
		$query="CREATE TABLE  `glpi_plugin_room_roomaccessconds` (
		`id` int(11) NOT NULL auto_increment,
		`name` varchar(255) collate utf8_unicode_ci default NULL,
		`comment` text collate utf8_unicode_ci,
		PRIMARY KEY  (`id`),
		KEY `name` (`name`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		$DB->query($query) or die("error adding glpi_plugin_room_roomaccessconds table " . $LANG["update"][90] . $DB->error());
	}

	if (!TableExists('glpi_plugin_room_dropdown1s')){
		$query="CREATE TABLE  `glpi_plugin_room_dropdown1s` (
		`id` int(11) NOT NULL auto_increment,
		`name` varchar(255) collate utf8_unicode_ci default NULL,
		`comment` text collate utf8_unicode_ci,
		PRIMARY KEY  (`id`),
		KEY `name` (`name`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		$DB->query($query) or die("error adding glpi_plugin_room_dropdown1s table " . $LANG["update"][90] . $DB->error());
	}
/*
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
*/
	PluginRoomProfile::createFirstAccess($_SESSION['glpiactiveprofile']['id']);

	return true;
}

function plugin_room_uninstall(){
	global $DB;

	$query='DROP TABLE `glpi_plugin_room_computer`';
	$DB->query($query) ;
	$query='DROP TABLE `glpi_plugin_room_rooms`';
	$DB->query($query) ;
	$query='DROP TABLE `glpi_plugin_room_profiles`';
	$DB->query($query) ;
	$query='DROP TABLE `glpi_plugin_room_roomtypes`';
	$DB->query($query) ;
	$query='DROP TABLE `glpi_plugin_room_roomaccessconds`';
	$DB->query($query) ;
	$query='DROP TABLE `glpi_plugin_room_dropdown1s`';
	$DB->query($query) ;
//	$query='DROP TABLE `glpi_dropdown_plugin_room_dropdown2`';
//	$DB->query($query) ;

	$tables_glpi = array("glpi_displaypreferences",
					"glpi_documents_items",
					"glpi_bookmarks",
					"glpi_logs");

	foreach($tables_glpi as $table_glpi)
		$DB->query("DELETE FROM `$table_glpi` WHERE `itemtype` = 'room';");

	return true;
}


// Define dropdown relations
function plugin_room_getDatabaseRelations(){
	$plugin = new Plugin();

	if ($plugin->isActivated("room")) {
		return array("glpi_plugin_room_roomtypes"=>array("glpi_plugin_room_rooms"=>"type"),
			"glpi_plugin_room_roomaccessconds"=>array("glpi_plugin_room_rooms"=>"access"),
			"glpi_plugin_room_dropdown1s"=>array("glpi_plugin_room_rooms"=>array("dropdown1","dropdown2")),
			//"glpi_dropdown_plugin_room_dropdown2"=>array("glpi_plugin_room_rooms"=>"dropdown2"),
			"glpi_entities"=>array("glpi_plugin_room_rooms"=>"entities_id"),
			"glpi_locations"=>array("glpi_plugin_room_rooms"=>"locations_id"),
			"glpi_profiles" => array ("glpi_plugin_room_profiles" => "profiles_id"),
			"glpi_users"=>array("glpi_plugin_room_rooms"=>array('FK_users','tech_num')));
	}
	else
		return array();
}


// Define Dropdown tables to be manage in GLPI :
// Definit les tables qui sont gérables via les intitulés
function plugin_room_getDropdown(){
	global $LANG;
/*
	return array( "glpi_dropdown_plugin_room_type"=>$LANG["common"][17],
			"glpi_dropdown_plugin_room_access"=>$LANG['plugin_room'][5],
			"glpi_dropdown_plugin_room_dropdown1"=>$LANG['plugin_room'][15],
			"glpi_dropdown_plugin_room_dropdown2"=>$LANG['plugin_room'][16],);
*/
	$plugin = new Plugin();

	if ($plugin->isActivated("room"))
		return array('PluginRoomRoomType'=>$LANG['plugin_room'][9],'PluginRoomRoomAccessCond'=>$LANG['plugin_room'][5],'PluginRoomDropdown1'=>$LANG['plugin_room']['dropdown'][2]);
	else
		return array();
}


function plugin_room_addLeftJoin($type,$ref_table,$new_table,$linkfield,&$already_link_tables){

	// Example of standard LEFT JOIN  clause but use it ONLY for specific LEFT JOIN
	// No need of the function if you do not have specific cases

	switch ($new_table){
		case "glpi_computers" :
			$out= " LEFT JOIN glpi_plugin_room_computer ON (glpi_plugin_room_rooms.id = glpi_plugin_room_computer.FK_rooms) ";
			$out.= " LEFT JOIN glpi_computers ON (glpi_computers.id = glpi_plugin_room_computer.FK_computers) ";
			return $out;
			break;
		case "glpi_plugin_room_rooms" : // From computers
			$out= " LEFT JOIN glpi_plugin_room_computer ON (glpi_computers.id = glpi_plugin_room_computer.FK_computers) ";
			$out.= " LEFT JOIN glpi_plugin_room_rooms ON (glpi_plugin_room_rooms.id = glpi_plugin_room_computer.FK_rooms) ";
			return $out;
			break;
		case "glpi_plugin_room_roomtypes" : // From computers
			$out=Search::addLeftJoin($type,$ref_table,$already_link_tables,"glpi_plugin_room_rooms",$linkfield);
			$out.= " LEFT JOIN glpi_plugin_room_roomtypes ON (glpi_plugin_room_roomtypes.ID = glpi_plugin_room_rooms.type) ";
			return $out;
			break;
	}
	return "";
}


function plugin_room_forceGroupBy($type) {
	return true;
	switch ($type){
		case 'PluginRoomRoom' :
				// Force add GROUP BY IN REQUEST
		return true;
		break;
	}
	return false;
}



// Actions sur les formulaires des objets du cœur - Item headings actions
//#######################################################################

// Define headings added by the plugin
// Fonction acivant l'onglet et retournant le contenu de l'entete de l'onglet du plugin.
// Cette fonction est automatiquement appelée via un hook déclaré dans setup.php.
// Cette fonction est systématiquement éxécutée à l'affichage de l'onglet.
function plugin_get_headings_room($item,$withtemplate) {
	global $LANG;

	if (get_class($item)=='Profile'||get_class($item)=='Computer') {
		// template case
		if ($item->getField('id') && !$withtemplate) {
        			return array(1 => $LANG['plugin_room']['profile'][1]);
		}
	}

	return false;

}


// Définition des fonctions appelées lors de l'affichage de l'onglet du plugins
// Cette fonction retourne un tableau avec toutes les fonctions à appeller pour
// remplir le corps de l'onglet en fonction du contexte d'éxécution.
// (Profils / Computer /....)
// Cette fonction est systématiquement éxécutée à l'affichage de l'onglet.
// Cette fonction est automatiquement appelée via un hook déclaré dans setup.php.
// Define headings actions added by the plugin	 
function plugin_headings_actions_room($item){
	
	switch (get_class($item)){
		case 'Computer' :
			return array(1 => "plugin_headings_room");

			break;
		case 'Profile' :
			return array(1 => 'plugin_headings_room');
			break;
	}
	return false;
}

// action heading
// Fonction permettant de remplir le corps de l'onglet du plugin
// Cette fonction est appelée par la fonction <plugin_headings_actions_room($item)>
function plugin_headings_room($item,$withtemplate=0) {
	global $CFG_GLPI;

	$PluginRoomProfile=new PluginRoomProfile();
	$Room=new PluginRoomRoom();
  
	switch (get_class($item)) {
		case 'Profile' :
			if (!$PluginRoomProfile->getFromDBByProfile($item->getField('id')))
			$PluginRoomProfile->createAccess($item->getField('id'));
			// Appel du formulaire
			$PluginRoomProfile->showForm($item->getField('id'), array('target' => $CFG_GLPI["root_doc"]."/plugins/room/front/profile.form.php"));
			break;
		case 'Computer' :
			$Room->plugin_room_showComputerRoom(get_class($item),$item->getField('id'));

			break;
		default :
			if (get_class($item)) {
				$Room->showForm($item->getField('id'));
			break;
		};
   	}
}

// Define search option for types of the plugins
function plugin_room_getAddSearchOptions($itemtype){
	global $LANG;
	$sopt=array();
	if ($itemtype=="Computer") {
		if (plugin_room_haveRight("room",'r')){
			$sopt[1050]['table']='glpi_plugin_room_rooms';
			$sopt[1050]['field']='name';
			$sopt[1050]['linkfield']='';
			$sopt[1050]['name']=$LANG['plugin_room'][0]." - ".$LANG["common"][16];
			$sopt[1050]['forcegroupby']=true;
			$sopt[1050]['datatype']='itemlink';
			$sopt[1050]['itemlink_type']='PluginRoomRoom';

			$sopt[1051]['table']='glpi_plugin_room_roomtypes';
			$sopt[1051]['field']='name';
			$sopt[1051]['linkfield']='';
			$sopt[1051]['name']=$LANG['plugin_room'][0]." - ".$LANG['plugin_room'][9];
			$sopt[1050]['forcegroupby']=true;
		}
	}
	return $sopt;
}


// Aucune idee de ce que cela fait
// peut-etre ajouter un critère de recherche ?

function plugin_room_addSelect($type,$ID,$num){
	global $SEARCH_OPTION;
	
	$table=$SEARCH_OPTION[$type][$ID]["table"];
	$field=$SEARCH_OPTION[$type][$ID]["field"];
	
	// Example of standard Select clause but use it ONLY for specific Select
	// No need of the function if you do not have specific cases
	switch ($table.".".$field){
		case "glpi_computers.count" :
			return " COUNT( glpi_computers.ID) AS ITEM_$num, ";
		break;
	}
	return "";
}




// Define actions :
function plugin_room_MassiveActions($type){
	global $LANG;
	switch ($type){
		case 'Computer' :
			return array(
				"plugin_room_addComputer"=>$LANG['plugin_room'][17],
			);
			break;

	}
	return array();
}



// How to display specific actions ?
function plugin_room_MassiveActionsDisplay($options=array()){
	global $LANG;

	$PluginRoomRoom= new PluginRoomRoom();
	switch ($options['itemtype']){

		case 'Computer':
			switch ($options['action']){
				case "plugin_room_addComputer":
				//dropdownValue("glpi_plugin_room","room_id");
				Dropdown::show("PluginRoomRoom");
				echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG["buttons"][2]."\" >";
				break;
			}
			break;
	}
	return "";
}



// How to process specific actions ?
function plugin_room_MassiveActionsProcess($data){
	global $LANG;
	
	$PluginRoomRoom = new PluginRoomRoom();

	switch ($data['action']){
		case 'plugin_room_addComputer':
			if ($data['itemtype']=='Computer' && $data['plugin_room_rooms_id']>0){
				foreach ($data['item'] as $key => $val){
					if ($val==1) {
						$PluginRoomRoom->plugin_room_AddDevice($data['plugin_room_rooms_id'],$key);
						
					}
				}
			}
			break;

	}
}


?>
