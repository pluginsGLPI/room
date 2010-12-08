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
			PRIMARY KEY  (`id`),
			KEY `entities_id` (`entities_id`),
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
	$query='DROP TABLE `glpi_dropdown_plugin_room_dropdown1`';
	$DB->query($query) ;
	$query='DROP TABLE `glpi_dropdown_plugin_room_dropdown2`';
	$DB->query($query) ;

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
			"glpi_dropdown_plugin_room_dropdown1"=>array("glpi_plugin_room_rooms"=>"dropdown1"),
			"glpi_dropdown_plugin_room_dropdown2"=>array("glpi_plugin_room_rooms"=>"dropdown2"),
			"glpi_entities"=>array("glpi_plugin_room_rooms"=>"entities_id"),
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
		return array('PluginRoomRoomType'=>$LANG['plugin_room'][9],'PluginRoomRoomAccessCond'=>$LANG['plugin_room'][5]);
	else
		return array();
}


/*function plugin_room_addLeftJoin($type,$ref_table,$new_table,$linkfield,&$already_link_tables){

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
*/

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
/*
function plugin_room_getAddSearchOption($itemtype){
	global $LANG;

	$sopt=array();

	if (haveRight("room",'r')){
		// Part header
		$sopt[PLUGIN_ROOM_TYPE]['common']=$LANG['plugin_room'][0];
		
		$sopt[PLUGIN_ROOM_TYPE][1]['table']='glpi_plugin_room';
		$sopt[PLUGIN_ROOM_TYPE][1]['field']='name';
		$sopt[PLUGIN_ROOM_TYPE][1]['linkfield']='name';
		$sopt[PLUGIN_ROOM_TYPE][1]['name']=$LANG["common"][16];
		$sopt[PLUGIN_ROOM_TYPE][1]['datatype']='itemlink';
		$sopt[PLUGIN_ROOM_TYPE][1]['itemlink_type']=PLUGIN_ROOM_TYPE;
		
		$sopt[PLUGIN_ROOM_TYPE][2]['table']='glpi_dropdown_plugin_room_type';
		$sopt[PLUGIN_ROOM_TYPE][2]['field']='name';
		$sopt[PLUGIN_ROOM_TYPE][2]['linkfield']='type';
		$sopt[PLUGIN_ROOM_TYPE][2]['name']=$LANG["common"][17];
	
		$sopt[PLUGIN_ROOM_TYPE][24]['table']='glpi_users';
		$sopt[PLUGIN_ROOM_TYPE][24]['field']='name';
		$sopt[PLUGIN_ROOM_TYPE][24]['linkfield']='tech_num';
		$sopt[PLUGIN_ROOM_TYPE][24]['name']=$LANG["common"][10];
	
		$sopt[PLUGIN_ROOM_TYPE][25]['table']='glpi_users';
		$sopt[PLUGIN_ROOM_TYPE][25]['field']='name';
		$sopt[PLUGIN_ROOM_TYPE][25]['linkfield']='FK_users';
		$sopt[PLUGIN_ROOM_TYPE][25]['name']=$LANG["common"][18];
		
		$sopt[PLUGIN_ROOM_TYPE][3]['table']='glpi_plugin_room';
		$sopt[PLUGIN_ROOM_TYPE][3]['field']='comments';
		$sopt[PLUGIN_ROOM_TYPE][3]['linkfield']='comments';
		$sopt[PLUGIN_ROOM_TYPE][3]['name']=$LANG["common"][25];
			
		$sopt[PLUGIN_ROOM_TYPE][5]['table']='glpi_plugin_room';
		$sopt[PLUGIN_ROOM_TYPE][5]['field']='size';
		$sopt[PLUGIN_ROOM_TYPE][5]['linkfield']='size';
		$sopt[PLUGIN_ROOM_TYPE][5]['name']=$LANG['plugin_room'][4];
	
		$sopt[PLUGIN_ROOM_TYPE][6]['table']='glpi_dropdown_plugin_room_access';
		$sopt[PLUGIN_ROOM_TYPE][6]['field']='name';
		$sopt[PLUGIN_ROOM_TYPE][6]['linkfield']='access';
		$sopt[PLUGIN_ROOM_TYPE][6]['name']=$LANG['plugin_room'][5];
	
		$sopt[PLUGIN_ROOM_TYPE][7]['table']='glpi_plugin_room';
		$sopt[PLUGIN_ROOM_TYPE][7]['field']='buy';
		$sopt[PLUGIN_ROOM_TYPE][7]['linkfield']='buy';
		$sopt[PLUGIN_ROOM_TYPE][7]['name']=$LANG["financial"][14];
	
		$sopt[PLUGIN_ROOM_TYPE][8]['table']='glpi_plugin_room';
		$sopt[PLUGIN_ROOM_TYPE][8]['field']='printer';
		$sopt[PLUGIN_ROOM_TYPE][8]['linkfield']='printer';
		$sopt[PLUGIN_ROOM_TYPE][8]['name']=$LANG['plugin_room'][6];
	
		$sopt[PLUGIN_ROOM_TYPE][9]['table']='glpi_plugin_room';
		$sopt[PLUGIN_ROOM_TYPE][9]['field']='videoprojector';
		$sopt[PLUGIN_ROOM_TYPE][9]['linkfield']='videoprojector';
		$sopt[PLUGIN_ROOM_TYPE][9]['name']=$LANG['plugin_room'][7];
	
		$sopt[PLUGIN_ROOM_TYPE][10]['table']='glpi_plugin_room';
		$sopt[PLUGIN_ROOM_TYPE][10]['field']='wifi';
		$sopt[PLUGIN_ROOM_TYPE][10]['linkfield']='wifi';
		$sopt[PLUGIN_ROOM_TYPE][10]['name']=$LANG['plugin_room'][8];
	
		$sopt[PLUGIN_ROOM_TYPE][11]['table']='glpi_plugin_room';
		$sopt[PLUGIN_ROOM_TYPE][11]['field']='comments';
		$sopt[PLUGIN_ROOM_TYPE][11]['linkfield']='';
		$sopt[PLUGIN_ROOM_TYPE][11]['name']=$LANG["common"][25];
	
		$sopt[PLUGIN_ROOM_TYPE][13]['table']='glpi_plugin_room';
		$sopt[PLUGIN_ROOM_TYPE][13]['field']='opening';
		$sopt[PLUGIN_ROOM_TYPE][13]['linkfield']='';
		$sopt[PLUGIN_ROOM_TYPE][13]['name']=$LANG['plugin_room'][11];
	
		$sopt[PLUGIN_ROOM_TYPE][12]['table']='glpi_plugin_room';
		$sopt[PLUGIN_ROOM_TYPE][12]['field']='limits';
		$sopt[PLUGIN_ROOM_TYPE][12]['linkfield']='';
		$sopt[PLUGIN_ROOM_TYPE][12]['name']=$LANG['plugin_room'][12];
		
		$sopt[PLUGIN_ROOM_TYPE][16]['table']='glpi_plugin_room';
		$sopt[PLUGIN_ROOM_TYPE][16]['field']='text1';
		$sopt[PLUGIN_ROOM_TYPE][16]['linkfield']='';
		$sopt[PLUGIN_ROOM_TYPE][16]['name']=$LANG['plugin_room'][13];
	
		$sopt[PLUGIN_ROOM_TYPE][17]['table']='glpi_plugin_room';
		$sopt[PLUGIN_ROOM_TYPE][17]['field']='text2';
		$sopt[PLUGIN_ROOM_TYPE][17]['linkfield']='';
		$sopt[PLUGIN_ROOM_TYPE][17]['name']=$LANG['plugin_room'][14];
	
		$sopt[PLUGIN_ROOM_TYPE][18]['table']='glpi_dropdown_plugin_room_dropdown1';
		$sopt[PLUGIN_ROOM_TYPE][18]['field']='name';
		$sopt[PLUGIN_ROOM_TYPE][18]['linkfield']='dropdown1';
		$sopt[PLUGIN_ROOM_TYPE][18]['name']=$LANG['plugin_room'][15];
	
		$sopt[PLUGIN_ROOM_TYPE][19]['table']='glpi_dropdown_plugin_room_dropdown2';
		$sopt[PLUGIN_ROOM_TYPE][19]['field']='name';
		$sopt[PLUGIN_ROOM_TYPE][19]['linkfield']='dropdown2';
		$sopt[PLUGIN_ROOM_TYPE][19]['name']=$LANG['plugin_room'][16];
		
		$sopt[PLUGIN_ROOM_TYPE][30]['table']='glpi_plugin_room';
		$sopt[PLUGIN_ROOM_TYPE][30]['field']='ID';
		$sopt[PLUGIN_ROOM_TYPE][30]['linkfield']='';
		$sopt[PLUGIN_ROOM_TYPE][30]['name']=$LANG["common"][2];
	
		$sopt[PLUGIN_ROOM_TYPE][31]['table']='glpi_computers';
		$sopt[PLUGIN_ROOM_TYPE][31]['field']='name';
		$sopt[PLUGIN_ROOM_TYPE][31]['linkfield']='';
		$sopt[PLUGIN_ROOM_TYPE][31]['name']=$LANG["Menu"][0];
		$sopt[PLUGIN_ROOM_TYPE][31]['forcegroupby']=true;
		$sopt[PLUGIN_ROOM_TYPE][31]['datatype']='itemlink';
		$sopt[PLUGIN_ROOM_TYPE][31]['itemlink_type']=COMPUTER_TYPE;
	
		$sopt[PLUGIN_ROOM_TYPE][32]['table']='glpi_plugin_room';
		$sopt[PLUGIN_ROOM_TYPE][32]['field']='count_linked';
		$sopt[PLUGIN_ROOM_TYPE][32]['linkfield']='';
		$sopt[PLUGIN_ROOM_TYPE][32]['name']=$LANG['plugin_room'][18];
		$sopt[PLUGIN_ROOM_TYPE][32]['meta']=1;
		
		$sopt[PLUGIN_ROOM_TYPE][80]['table']='glpi_entities';
		$sopt[PLUGIN_ROOM_TYPE][80]['field']='completename';
		$sopt[PLUGIN_ROOM_TYPE][80]['linkfield']='entities_id';
		$sopt[PLUGIN_ROOM_TYPE][80]['name']=$LANG["entity"][0];
	
		$sopt[COMPUTER_TYPE][1050]['table']='glpi_plugin_room';
		$sopt[COMPUTER_TYPE][1050]['field']='name';
		$sopt[COMPUTER_TYPE][1050]['linkfield']='';
		$sopt[COMPUTER_TYPE][1050]['name']=$LANG['plugin_room'][0]." - ".$LANG["common"][16];
		$sopt[COMPUTER_TYPE][1050]['forcegroupby']=true;
		$sopt[COMPUTER_TYPE][1050]['datatype']='itemlink';
		$sopt[COMPUTER_TYPE][1050]['itemlink_type']=PLUGIN_ROOM_TYPE;

		$sopt[COMPUTER_TYPE][1051]['table']='glpi_dropdown_plugin_room_type';
		$sopt[COMPUTER_TYPE][1051]['field']='name';
		$sopt[COMPUTER_TYPE][1051]['linkfield']='';
		$sopt[COMPUTER_TYPE][1051]['name']=$LANG['plugin_room'][0]." - ".$LANG['plugin_room'][9];
	}	
	return $sopt;
}
*/

// Aucune idee de ce que cela fait
// peut-etre ajouter un critère de recherche ?
/*
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
*/


/*
// Define actions :
function plugin_room_MassiveActions($type){
	global $LANG;
	switch ($type){
		case COMPUTER_TYPE :
			return array(
				"plugin_room_addComputer"=>$LANG['plugin_room'][17],
			);
			break;
	}
	return array();
}
*/

/*
// How to display specific actions ?
function plugin_room_MassiveActionsDisplay($type,$action){
	global $LANG;

	switch ($type){
		case COMPUTER_TYPE:
			switch ($action){
				case "plugin_room_addComputer":
				dropdownValue("glpi_plugin_room","rID");
				echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG["buttons"][2]."\" >";
				break;
			}
			break;
	}
	return "";
}
*/

/*
// How to process specific actions ?
function plugin_room_MassiveActionsProcess($data){
	global $LANG;
	
	
	switch ($data['action']){
		case 'plugin_room_addComputer':
			if ($data['device_type']==COMPUTER_TYPE && $data['rID']>0){
				foreach ($data['item'] as $key => $val){
					if ($val==1) {
						plugin_room_AddDevice($data['rID'],$key);
						
					}
				}
			}
			break;
	}
}
*/

?>
