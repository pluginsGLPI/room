<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2006 by the INDEPNET Development Team.

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


include_once ("inc/plugin_room.class.php");
include_once ("inc/plugin_room.function.php");

// Init plugin
function plugin_init_room() {
	global $PLUGIN_HOOKS,$CFG_GLPI;

	$PLUGIN_HOOKS['init_session']['room'] = 'plugin_room_initSession';

	if (isset($_SESSION["glpiID"])){

		$PLUGIN_HOOKS['config_page']['room'] = 'config.php';

		// Plugin Installed 
		if (isset($_SESSION["glpiplugin_room_installed"])){
			// Room may be deleted and specific to an entity
			array_push($CFG_GLPI["specif_entities_tables"],"glpi_plugin_room");
			array_push($CFG_GLPI["deleted_tables"],"glpi_plugin_room");

			pluginNewType('room',"PLUGIN_ROOM_TYPE",1050,"plugin_room","glpi_plugin_room","room.form.php");

			if (haveTypeRight(PLUGIN_ROOM_TYPE,'r')){
				$PLUGIN_HOOKS['menu_entry']['room'] = true;
				$PLUGIN_HOOKS['submenu_entry']['room']['add'] = 'room.form.php';
				$PLUGIN_HOOKS['submenu_entry']['room']['search'] = 'index.php';
			}
		}
	}
}

// Get the name and the version of the plugin - Needed
function plugin_version_room(){
	global $LANGROOM;

	return array( 'name'    => $LANGROOM[0],
		'minGlpiVersion' => '0.71', // Optional but recommended
		'version' => '0.1');
}

// Define rights for the plugin types
function plugin_room_haveTypeRight($type,$right){
	switch ($type){
		case PLUGIN_ROOM_TYPE :
			return haveRight("computer",$right);
			break;
	}
}


// Define search option for types of the plugins
function plugin_room_getSearchOption(){
	global $LANGROOM,$LANG;
	$sopt=array();

	// Part header
	$sopt[PLUGIN_ROOM_TYPE]['common']=$LANGROOM[0];
	
	$sopt[PLUGIN_ROOM_TYPE][1]['table']='glpi_plugin_room';
	$sopt[PLUGIN_ROOM_TYPE][1]['field']='name';
	$sopt[PLUGIN_ROOM_TYPE][1]['linkfield']='name';
	$sopt[PLUGIN_ROOM_TYPE][1]['name']=$LANG["common"][16];
	
	$sopt[PLUGIN_ROOM_TYPE][2]['table']='glpi_dropdown_plugin_room_type';
	$sopt[PLUGIN_ROOM_TYPE][2]['field']='name';
	$sopt[PLUGIN_ROOM_TYPE][2]['linkfield']='type';
	$sopt[PLUGIN_ROOM_TYPE][2]['name']=$LANG["common"][17];
	
	$sopt[PLUGIN_ROOM_TYPE][3]['table']='glpi_plugin_room';
	$sopt[PLUGIN_ROOM_TYPE][3]['field']='comments';
	$sopt[PLUGIN_ROOM_TYPE][3]['linkfield']='comments';
	$sopt[PLUGIN_ROOM_TYPE][3]['name']=$LANG["common"][25];
		
	$sopt[PLUGIN_ROOM_TYPE][5]['table']='glpi_plugin_room';
	$sopt[PLUGIN_ROOM_TYPE][5]['field']='number';
	$sopt[PLUGIN_ROOM_TYPE][5]['linkfield']='number';
	$sopt[PLUGIN_ROOM_TYPE][5]['name']=$LANGROOM[4];

	$sopt[PLUGIN_ROOM_TYPE][6]['table']='glpi_dropdown_plugin_room_access';
	$sopt[PLUGIN_ROOM_TYPE][6]['field']='name';
	$sopt[PLUGIN_ROOM_TYPE][6]['linkfield']='access';
	$sopt[PLUGIN_ROOM_TYPE][6]['name']=$LANGROOM[5];

	$sopt[PLUGIN_ROOM_TYPE][7]['table']='glpi_plugin_room';
	$sopt[PLUGIN_ROOM_TYPE][7]['field']='buy';
	$sopt[PLUGIN_ROOM_TYPE][7]['linkfield']='buy';
	$sopt[PLUGIN_ROOM_TYPE][7]['name']=$LANG["financial"][14];

	$sopt[PLUGIN_ROOM_TYPE][8]['table']='glpi_plugin_room';
	$sopt[PLUGIN_ROOM_TYPE][8]['field']='printer';
	$sopt[PLUGIN_ROOM_TYPE][8]['linkfield']='printer';
	$sopt[PLUGIN_ROOM_TYPE][8]['name']=$LANGROOM[6];

	$sopt[PLUGIN_ROOM_TYPE][9]['table']='glpi_plugin_room';
	$sopt[PLUGIN_ROOM_TYPE][9]['field']='videoprojector';
	$sopt[PLUGIN_ROOM_TYPE][9]['linkfield']='videoprojector';
	$sopt[PLUGIN_ROOM_TYPE][9]['name']=$LANGROOM[7];

	$sopt[PLUGIN_ROOM_TYPE][10]['table']='glpi_plugin_room';
	$sopt[PLUGIN_ROOM_TYPE][10]['field']='wifi';
	$sopt[PLUGIN_ROOM_TYPE][10]['linkfield']='wifi';
	$sopt[PLUGIN_ROOM_TYPE][10]['name']=$LANGROOM[8];

	$sopt[PLUGIN_ROOM_TYPE][11]['table']='glpi_plugin_room';
	$sopt[PLUGIN_ROOM_TYPE][11]['field']='comments';
	$sopt[PLUGIN_ROOM_TYPE][11]['linkfield']='';
	$sopt[PLUGIN_ROOM_TYPE][11]['name']=$LANG["common"][25];

	$sopt[PLUGIN_ROOM_TYPE][13]['table']='glpi_plugin_room';
	$sopt[PLUGIN_ROOM_TYPE][13]['field']='opening';
	$sopt[PLUGIN_ROOM_TYPE][13]['linkfield']='';
	$sopt[PLUGIN_ROOM_TYPE][13]['name']=$LANGROOM[11];

	$sopt[PLUGIN_ROOM_TYPE][12]['table']='glpi_plugin_room';
	$sopt[PLUGIN_ROOM_TYPE][12]['field']='limits';
	$sopt[PLUGIN_ROOM_TYPE][12]['linkfield']='';
	$sopt[PLUGIN_ROOM_TYPE][12]['name']=$LANGROOM[12];

	$sopt[PLUGIN_ROOM_TYPE][14]['table']='glpi_users';
	$sopt[PLUGIN_ROOM_TYPE][14]['field']='name';
	$sopt[PLUGIN_ROOM_TYPE][14]['linkfield']='FK_users1';
	$sopt[PLUGIN_ROOM_TYPE][14]['name']=$LANGROOM[10]." 1";

	$sopt[PLUGIN_ROOM_TYPE][15]['table']='glpi_users';
	$sopt[PLUGIN_ROOM_TYPE][15]['field']='name';
	$sopt[PLUGIN_ROOM_TYPE][15]['linkfield']='FK_users2';
	$sopt[PLUGIN_ROOM_TYPE][15]['name']=$LANGROOM[10]." 2";

	$sopt[PLUGIN_ROOM_TYPE][16]['table']='glpi_plugin_room';
	$sopt[PLUGIN_ROOM_TYPE][16]['field']='text1';
	$sopt[PLUGIN_ROOM_TYPE][16]['linkfield']='';
	$sopt[PLUGIN_ROOM_TYPE][16]['name']=$LANGROOM[13];

	$sopt[PLUGIN_ROOM_TYPE][17]['table']='glpi_plugin_room';
	$sopt[PLUGIN_ROOM_TYPE][17]['field']='text2';
	$sopt[PLUGIN_ROOM_TYPE][17]['linkfield']='';
	$sopt[PLUGIN_ROOM_TYPE][17]['name']=$LANGROOM[14];

	$sopt[PLUGIN_ROOM_TYPE][18]['table']='glpi_dropdown_plugin_room_dropdown1';
	$sopt[PLUGIN_ROOM_TYPE][18]['field']='name';
	$sopt[PLUGIN_ROOM_TYPE][18]['linkfield']='dropdown1';
	$sopt[PLUGIN_ROOM_TYPE][18]['name']=$LANGROOM[15];

	$sopt[PLUGIN_ROOM_TYPE][19]['table']='glpi_dropdown_plugin_room_dropdown2';
	$sopt[PLUGIN_ROOM_TYPE][19]['field']='name';
	$sopt[PLUGIN_ROOM_TYPE][19]['linkfield']='dropdown2';
	$sopt[PLUGIN_ROOM_TYPE][19]['name']=$LANGROOM[16];
	
	$sopt[PLUGIN_ROOM_TYPE][30]['table']='glpi_plugin_room';
	$sopt[PLUGIN_ROOM_TYPE][30]['field']='ID';
	$sopt[PLUGIN_ROOM_TYPE][30]['linkfield']='';
	$sopt[PLUGIN_ROOM_TYPE][30]['name']=$LANG["common"][2];
	
	$sopt[PLUGIN_ROOM_TYPE][80]['table']='glpi_entities';
	$sopt[PLUGIN_ROOM_TYPE][80]['field']='completename';
	$sopt[PLUGIN_ROOM_TYPE][80]['linkfield']='FK_entities';
	$sopt[PLUGIN_ROOM_TYPE][80]['name']=$LANG["entity"][0];
	
	return $sopt;
}

// Define dropdown relations
function plugin_room_getDatabaseRelations(){
	// 
	return array(	"glpi_dropdown_plugin_room_type"=>array("glpi_plugin_room"=>"type"),
			"glpi_dropdown_plugin_room_access"=>array("glpi_plugin_room"=>"access"),
			"glpi_dropdown_plugin_room_dropdown1"=>array("glpi_plugin_room"=>"dropdown1"),
			"glpi_dropdown_plugin_room_dropdown2"=>array("glpi_plugin_room"=>"dropdown2"),
			"glpi_entities"=>array("glpi_plugin_room"=>"FK_entities"),
			"glpi_users"=>array("glpi_plugin_room"=>array('FK_users1','FK_users2')),
		);
}


// Define Dropdown tables to be manage in GLPI :
function plugin_room_getDropdown(){
	global $LANG,$LANGROOM;
	// Table => Name
	return array( "glpi_dropdown_plugin_room_type"=>$LANG["common"][17],
			"glpi_dropdown_plugin_room_acess"=>$LANGROOM[5],
			"glpi_dropdown_plugin_room_dropdown1"=>$LANGROOM[15],
			"glpi_dropdown_plugin_room_dropdown2"=>$LANGROOM[16],);
}

function plugin_example_addLeftJoin($type,$ref_table,$new_table,$linkfield){

	// Example of standard LEFT JOIN  clause but use it ONLY for specific LEFT JOIN
	// No need of the function if you do not have specific cases
//	switch ($new_table){
//		case "glpi_dropdown_plugin_room_dropdown1" :
//			return " LEFT JOIN $new_table ON ($ref_table.$linkfield = $new_table.ID) ";
//			break;
//	}
	return "";
}

?>