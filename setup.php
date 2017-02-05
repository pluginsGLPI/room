<?php
/*
 * -------------------------------------------------------------------------
 * GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2003-2009 by the INDEPNET Development Team.
 *
 * http://indepnet.net/ http://glpi-project.org
 * -------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of GLPI.
 *
 * GLPI is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * GLPI is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with GLPI; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 * --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: DOMBRE Julien
// Purpose of file:
// ----------------------------------------------------------------------
define("PLUGIN_ROOM_VERSION", "3.1.0");

// Initilisation du plugin (appelée à l'activation du plugin)
// Cette fonction définie les HOOKS avec GLPI et permet de déclarer de
// nouveaux objets d'inventaire.
function plugin_init_room() {
   global $PLUGIN_HOOKS, $CFG_GLPI, $LINK_ID_TABLE, $LANG;

   $PLUGIN_HOOKS['csrf_compliant']['room'] = true;
   $PLUGIN_HOOKS['assign_to_ticket']['room'] = true;
   $PLUGIN_HOOKS['assign_to_ticket_dropdown']['room'] = true;

   // Activation d'un onglet room dans les profils
   $PLUGIN_HOOKS['change_profile']['room'] = array(
      'PluginRoomProfile',
      'initProfile'
   );

   // Déclaration d'un nouvel objet d'inventaire Room
   Plugin::registerClass('PluginRoomRoom', array(
      'reservation_types' => true,
      'ticket_types' => true,
      'linkgroup_tech_types' => true,
   ));

   Plugin::registerClass('PluginRoomProfile', array(
      'addtabon' => 'Profile'
   ));

   if (Session::getLoginUserID()) {
      $PLUGIN_HOOKS['menu_toadd']['room'] = array(
         'assets' => 'PluginRoomMenu'
      );
   }
}

// Get the name and the version of the plugin - Needed
function plugin_version_room() {
   global $LANG;

   return array(
      'name' => $LANG['plugin_room'][0],
      'version' => PLUGIN_ROOM_VERSION,
      'license' => 'GPLv2+',
      'author' => 'Julien Dombre / Modif bogucool, Pascal Marier-Dionne et Claude Duvergier',
      'homepage' => 'https://github.com/pluginsGLPI/room',
      'minGlpiVersion' => '0.85'
   ); // For compatibility / no install in version < 0.85

}

// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_room_check_prerequisites() {
   if (version_compare(GLPI_VERSION, '0.85', '>=') && version_compare(GLPI_VERSION, '9.2', '<')) {
      return true;
   } else {
      _e('This plugin requires GLPI >= 0.85 && < 9.2', 'room');
      return false;
   }
}

// Incertain de ce que devrais vérifier cette méthode; je n'y touche donc pas / unsure as to what this function should check for; i wont modify it
function plugin_room_check_config() {
   return true;
}
?>
