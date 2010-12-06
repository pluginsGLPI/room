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


// Initilisation du plugin (appelée à l'activation du plugin)
// Cette fonction définie les HOOKS avec GLPI et permet de déclarer de
// nouveaux objets d'inventaire.
function plugin_init_room() {
	global $PLUGIN_HOOKS,$CFG_GLPI,$LINK_ID_TABLE,$LANG;

	if (isset($_SESSION["glpiID"])){
		// Déclaration d'un nouvel objet d'inventaire Room
		Plugin::registerClass('PluginRoomRoom',array(
			'reservation_types' => true,
			));

		// Activation d'un onglet room dans les profils ?
		// array('Class','fonction') ?
		$PLUGIN_HOOKS['change_profile']['room'] = array('PluginRoomProfile','changeProfile');

		if (plugin_room_haveRight('room','r')){
			//Activation du plugin dans le menu plugins
			$PLUGIN_HOOKS['menu_entry']['room'] = 'index.php';
			//Activation du bouton ADD et pointage vers le formulaire
			$PLUGIN_HOOKS['submenu_entry']['room']['add'] = 'front/room.form.php?new=1';
			//Activation du bouton SEARCH et pointage vers le formulaire
			$PLUGIN_HOOKS['submenu_entry']['room']['search'] = 'index.php';
		} 

/*		if (plugin_room_haveRight('room','w')){
			// Massive Action definition
			$PLUGIN_HOOKS['use_massive_action']['room']=1;
		}*/
		
		// Gestion des onglets
		// Définition de la fonction appelée pour remplir l'entete de l'onglet du plugin
		$PLUGIN_HOOKS['headings']['room'] = 'plugin_get_headings_room';
		// Définition de la fonction appelée pour récupérer la listes des actions à effectuer
		// pour remplir le corps de l'onglet du plugin
		$PLUGIN_HOOKS['headings_action']['room'] = 'plugin_headings_actions_room';
	}
}

// Get the name and the version of the plugin - Needed
function plugin_version_room(){
	global $LANG;

	return array( 'name'    => $LANG['plugin_room'][0],
		'version' => '2.1.0',
		'author'=>'Julien Dombre / Modif bogucool',
		'homepage'=>'http://glpi-project.org',
		'minGlpiVersion' => '0.78',// For compatibility / no install in version < 0.78
		);
}

// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_room_check_prerequisites(){
	if (GLPI_VERSION>=0.78){
		return true;
	} else {
		echo "GLPI version not compatible need 0.78";
	}
}


// Uninstall process for plugin : need to return true if succeeded : may display messages or add to message after redirect
function plugin_room_check_config(){
	return true;
}

function plugin_room_haveRight($module,$right){
	$matches=array(
			""  => array("","r","w"),
			"r" => array("r","w"),
			"w" => array("w"),
			"1" => array("1"),
			"0" => array("0","1"),
		      );
//	if (isset($_SESSION["glpi_plugin_room_profile"][$module])&&in_array($_SESSION["glpi_plugin_room_profile"][$module],$matches[$right]))
		return true;
//
//	else
//		return false;
}

?>
