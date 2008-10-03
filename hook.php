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


// Define search option for types of the plugins
function plugin_room_getSearchOption(){
	global $LANG;
	$sopt=array();
	if (haveTypeRight(PLUGIN_ROOM_TYPE,'r')){
		// Part header
		$sopt[PLUGIN_ROOM_TYPE]['common']=$LANG['plugin_room'][0];
		
		$sopt[PLUGIN_ROOM_TYPE][1]['table']='glpi_plugin_room';
		$sopt[PLUGIN_ROOM_TYPE][1]['field']='name';
		$sopt[PLUGIN_ROOM_TYPE][1]['linkfield']='name';
		$sopt[PLUGIN_ROOM_TYPE][1]['name']=$LANG["common"][16];
		
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
	
		$sopt[PLUGIN_ROOM_TYPE][32]['table']='glpi_plugin_room';
		$sopt[PLUGIN_ROOM_TYPE][32]['field']='count_linked';
		$sopt[PLUGIN_ROOM_TYPE][32]['linkfield']='';
		$sopt[PLUGIN_ROOM_TYPE][32]['name']=$LANG['plugin_room'][18];
		$sopt[PLUGIN_ROOM_TYPE][32]['meta']=1;
		
		$sopt[PLUGIN_ROOM_TYPE][80]['table']='glpi_entities';
		$sopt[PLUGIN_ROOM_TYPE][80]['field']='completename';
		$sopt[PLUGIN_ROOM_TYPE][80]['linkfield']='FK_entities';
		$sopt[PLUGIN_ROOM_TYPE][80]['name']=$LANG["entity"][0];
	
		$sopt[COMPUTER_TYPE][1050]['table']='glpi_plugin_room';
		$sopt[COMPUTER_TYPE][1050]['field']='name';
		$sopt[COMPUTER_TYPE][1050]['linkfield']='';
		$sopt[COMPUTER_TYPE][1050]['name']=$LANG['plugin_room'][0]." - ".$LANG["common"][16];

		$sopt[COMPUTER_TYPE][1051]['table']='glpi_dropdown_plugin_room_type';
		$sopt[COMPUTER_TYPE][1051]['field']='name';
		$sopt[COMPUTER_TYPE][1051]['linkfield']='';
		$sopt[COMPUTER_TYPE][1051]['name']=$LANG['plugin_room'][0]." - ".$LANG['plugin_room'][9];
	}	
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
	global $LANG;
	// Table => Name
	return array( "glpi_dropdown_plugin_room_type"=>$LANG["common"][17],
			"glpi_dropdown_plugin_room_access"=>$LANG['plugin_room'][5],
			"glpi_dropdown_plugin_room_dropdown1"=>$LANG['plugin_room'][15],
			"glpi_dropdown_plugin_room_dropdown2"=>$LANG['plugin_room'][16],);
}

function plugin_room_addSelect($type,$ID,$num){
	global $SEARCH_OPTION;
	
	$table=$SEARCH_OPTION[$type][$ID]["table"];
	$field=$SEARCH_OPTION[$type][$ID]["field"];
	
	// Example of standard Select clause but use it ONLY for specific Select
	// No need of the function if you do not have specific cases
	switch ($table.".".$field){
		case "glpi_plugin_room.name":
			return $table.".".$field." AS ITEM_$num, ".$table.".ID AS ITEM_".$num."_2, ";
			break;
		case "glpi_computers.name" :
			return " GROUP_CONCAT( DISTINCT glpi_computers.$field SEPARATOR '$$$$') AS ITEM_$num, ";
		break;
		case "glpi_computers.count" :
			return " COUNT( glpi_computers.ID) AS ITEM_$num, ";
		break;
	}
	return "";
}

function plugin_room_addLeftJoin($type,$ref_table,$new_table,$linkfield,&$already_link_tables){

	// Example of standard LEFT JOIN  clause but use it ONLY for specific LEFT JOIN
	// No need of the function if you do not have specific cases

	switch ($new_table){
		case "glpi_computers" :
			$out= " LEFT JOIN glpi_plugin_room_computer ON (glpi_plugin_room.ID = glpi_plugin_room_computer.FK_rooms) ";
			$out.= " LEFT JOIN glpi_computers ON (glpi_computers.ID = glpi_plugin_room_computer.FK_computers) ";
			return $out;
			break;
		case "glpi_plugin_room" : // From computers
			$out= " LEFT JOIN glpi_plugin_room_computer ON (glpi_computers.ID = glpi_plugin_room_computer.FK_computers) ";
			$out.= " LEFT JOIN glpi_plugin_room ON (glpi_plugin_room.ID = glpi_plugin_room_computer.FK_rooms) ";
			return $out;
			break;
		case "glpi_dropdown_plugin_room_type" : // From computers
			$out=addLeftJoin($type,$ref_table,$already_link_tables,"glpi_plugin_room",$linkfield);
			$out.= " LEFT JOIN glpi_dropdown_plugin_room_type ON (glpi_dropdown_plugin_room_type.ID = glpi_plugin_room.type) ";
			return $out;
			break;
	}
	return "";
}


function plugin_room_giveItem($type,$field,$data,$num,$linkfield=""){
	global $CFG_GLPI, $INFOFORM_PAGES;

	switch ($field){
		case "glpi_plugin_room.name" :
			if (!empty($data["ITEM_".$num."_2"])){
				$out= "<a href=\"".$CFG_GLPI["root_doc"]."/".$INFOFORM_PAGES[PLUGIN_ROOM_TYPE]."?ID=".$data["ITEM_".$num."_2"]."\">";
				$out.= $data["ITEM_$num"];
				if ($CFG_GLPI["view_ID"]||empty($data["ITEM_$num"])) $out.= " (".$data["ITEM_".$num."_2"].")";
				$out.= "</a>";
				return $out;
			}
			
			break;
		case "glpi_computers.name" :

			$out="";
			$split=explode("$$$$",$data["ITEM_$num"]);
	
			$count_display=0;
			for ($k=0;$k<count($split);$k++)
				if (strlen(trim($split[$k]))>0){
					if ($count_display) $out.= "<br>";
					$count_display++;
					$out.= $split[$k];
				}
			return $out;
	}
	return "";
}

function plugin_room_forceGroupBy($type){
	switch ($type){
		case PLUGIN_ROOM_TYPE :
				// Force add GROUP BY IN REQUEST
		return true;
		break;
	}
	return false;
	}

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


function plugin_get_headings_room($type,$withtemplate){
	global $LANG;
	switch ($type){
		case COMPUTER_TYPE :
			// template case
			if ($withtemplate){
				return array();
			} else { // Non template case
				return array(1 => $LANG['plugin_room'][19]);
                        }
			break;
	}
	return false;
}

// Define headings actions added by the plugin	 
function plugin_headings_actions_room($type){

	switch ($type){
		case COMPUTER_TYPE :
			return array(1 => "plugin_room_showComputerRoom");

			break;
	}
	return false;
}


?>
