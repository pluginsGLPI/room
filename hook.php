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

function plugin_room_install() {
   global $DB, $LANG;

   include_once (GLPI_ROOT . "/plugins/room/inc/profile.class.php");

   // Table for room assets
   if (! $DB->tableExists('glpi_plugin_room_rooms')) {
      $query = "CREATE TABLE  `glpi_plugin_room_rooms` (
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
                `comment` text collate utf8_unicode_ci,
                `opening` varchar(255) collate utf8_unicode_ci default NULL,
                `limits` varchar(255) collate utf8_unicode_ci default NULL,
                `text1` varchar(255) collate utf8_unicode_ci default NULL,
                `text2` varchar(255) collate utf8_unicode_ci default NULL,
                `dropdown1` int(11) NOT NULL default '0',
                `dropdown2` int(11) NOT NULL default '0',
                `tech_num` int(11) NOT NULL default '0',
                `users_id` int(11) NOT NULL default '0',
                `is_template` smallint(6) NOT NULL default '0', # not used / for reservation search engine
                `location` smallint(6) NOT NULL default '0', # not used / for reservation search engine
                `state` smallint(6) NOT NULL default '0', # not used / for reservation search engine
                `manufacturers_id` smallint(6) NOT NULL default '0', # not used / for reservation search engine
                `groups_id` smallint(6) NOT NULL default '0', # not used / for reservation search engine
                `groups_id_tech` int(11)  NOT NULL default '0' COMMENT 'Group in charge of the hardware. RELATION to glpi_groups (id)',
                PRIMARY KEY  (`id`),
                KEY `entities_id` (`entities_id`),
                KEY `is_deleted` (`is_deleted`),
                KEY `type` (`type`),
                KEY `name` (`name`),
                KEY `buy` (`buy`),
                KEY `dropdown1` (`dropdown1`),
                KEY `dropdown2` (`dropdown2`),
                KEY `tech_num` (`tech_num`),
                KEY `users_id` (`users_id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;";
      $DB->query($query) or die("error adding glpi_plugin_room table " . __('Error during the database update') . $DB->error());
   }

   // Table to link Rooms to Computers
   if (! $DB->TableExists('glpi_plugin_room_rooms_computers')) {
      $query = "CREATE TABLE `glpi_plugin_room_rooms_computers` (
                `id` int(11) NOT NULL auto_increment,
                `computers_id` int(11) NOT NULL,
                `rooms_id` int(11) NOT NULL,
                PRIMARY KEY  (`id`),
                UNIQUE `computers_id` (`computers_id`),
                KEY `rooms_id` (`rooms_id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
      $DB->query($query) or die("error adding glpi_plugin_room_rooms_computers table " . __('Error during the database update') . $DB->error());
   }

   // Table for Room types
   if (! $DB->TableExists('glpi_plugin_room_roomtypes')) {
      $query = "CREATE TABLE  `glpi_plugin_room_roomtypes` (
                `id` int(11) NOT NULL auto_increment,
                `name` varchar(255) collate utf8_unicode_ci default NULL,
                `comment` text collate utf8_unicode_ci,
                PRIMARY KEY  (`id`),
                KEY `name` (`name`)
                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
      $DB->query($query) or die("error adding glpi_plugin_room_roomtypes table " . __('Error during the database update') . $DB->error());
   }

   // Table for access conditions
   if (! $DB->TableExists('glpi_plugin_room_roomaccessconds')) {
      $query = "CREATE TABLE  `glpi_plugin_room_roomaccessconds` (
                `id` int(11) NOT NULL auto_increment,
                `name` varchar(255) collate utf8_unicode_ci default NULL,
                `comment` text collate utf8_unicode_ci,
                PRIMARY KEY  (`id`),
                KEY `name` (`name`)
                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
      $DB->query($query) or die("error adding glpi_plugin_room_roomaccessconds table " . __('Error during the database update') . $DB->error());
   }

   // Table for dropdowns
   if (! $DB->TableExists('glpi_plugin_room_dropdown1s')) {
      $query = "CREATE TABLE  `glpi_plugin_room_dropdown1s` (
                `id` int(11) NOT NULL auto_increment,
                `name` varchar(255) collate utf8_unicode_ci default NULL,
                `comment` text collate utf8_unicode_ci,
                PRIMARY KEY  (`id`),
                KEY `name` (`name`)
                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
      $DB->query($query) or die("error adding glpi_plugin_room_roomspecificities table " . __('Error during the database update') . $DB->error());
   }

   PluginRoomProfile::createFirstAccess($_SESSION['glpiactiveprofile']['id']);

   return true;
}

function plugin_room_uninstall() {
   global $DB;

   $query = 'DROP TABLE IF EXISTS `glpi_plugin_room_rooms_computers`';
   $DB->query($query);
   $query = 'DROP TABLE IF EXISTS `glpi_plugin_room_roomtypes`';
   $DB->query($query);
   $query = 'DROP TABLE IF EXISTS `glpi_plugin_room_roomaccessconds`';
   $DB->query($query);
   $query = 'DROP TABLE IF EXISTS `glpi_plugin_room_dropdown1s`';
   $DB->query($query);
   $query = 'DROP TABLE IF EXISTS `glpi_plugin_room_rooms`';
   $DB->query($query);

   $tables_glpi = array(
      "glpi_displaypreferences",
      "glpi_documents_items",
      "glpi_logs",
      'glpi_items_tickets',
      'glpi_reservationitems',
      'glpi_savedsearches',
   );

   foreach ($tables_glpi as $table_glpi)
      $DB->query("DELETE FROM `$table_glpi` WHERE `itemtype` = 'PluginRoomRoom';");

   return true;
}

// Define dropdown relations
function plugin_room_getDatabaseRelations() {
   $plugin = new Plugin();

   if ($plugin->isActivated("room")) {
      return array(
         "glpi_plugin_room_roomtypes" => array(
            "glpi_plugin_room_rooms" => "type"
         ),
         "glpi_plugin_room_roomaccessconds" => array(
            "glpi_plugin_room_rooms" => "access"
         ),
         "glpi_plugin_room_dropdown1s" => array(
            "glpi_plugin_room_rooms" => array(
               "dropdown1",
               "dropdown2"
            )
         ),
         "glpi_plugin_room_rooms" => array(
            "glpi_plugin_room_rooms_computers" => "rooms_id"
         ),
         "glpi_computers" => array(
            "glpi_plugin_room_rooms_computers" => "computers_id"
         ),
         "glpi_entities" => array(
            "glpi_plugin_room_rooms" => "entities_id"
         ),
         "glpi_locations" => array(
            "glpi_plugin_room_rooms" => "locations_id"
         ),
         "glpi_profiles" => array(
            "glpi_plugin_room_profiles" => "profiles_id"
         ),
         "glpi_users" => array(
            "glpi_plugin_room_rooms" => array(
               'users_id',
               'tech_num'
            )
         )
      );
   } else
      return array();
}

// Define Dropdown tables to be manage in GLPI :
// Definit les tables qui sont gérables via les intitulés
function plugin_room_getDropdown() {
   global $LANG;

   $plugin = new Plugin();

   if ($plugin->isActivated("room"))
      return array(
         'PluginRoomRoomType' => $LANG['plugin_room'][9],
         'PluginRoomRoomAccessCond' => $LANG['plugin_room'][5],
         'PluginRoomDropdown1' => $LANG['plugin_room']['dropdown'][2]
      );
   else
      return array();
}

function plugin_room_addLeftJoin($type, $ref_table, $new_table, $linkfield, &$already_link_tables) {

   // Example of standard LEFT JOIN clause but use it ONLY for specific LEFT JOIN
   // No need of the function if you do not have specific cases

   switch ($new_table) {
      case "glpi_computers":
         $out = " LEFT JOIN glpi_plugin_room_rooms_computers ON (glpi_plugin_room_rooms.id = glpi_plugin_room_rooms_computers.rooms_id) ";
         $out .= " LEFT JOIN glpi_computers ON (glpi_computers.id = glpi_plugin_room_rooms_computers.computers_id) ";
         return $out;
         break;
      case "glpi_plugin_room_rooms": // From computers
         $out = " LEFT JOIN glpi_plugin_room_rooms_computers ON (glpi_computers.id = glpi_plugin_room_rooms_computers.computers_id) ";
         $out .= " LEFT JOIN glpi_plugin_room_rooms ON (glpi_plugin_room_rooms.id = glpi_plugin_room_rooms_computers.rooms_id) ";
         return $out;
         break;
      case "glpi_plugin_room_roomtypes": // From computers
         $out = Search::addLeftJoin($type, $ref_table, $already_link_tables, "glpi_plugin_room_rooms", $linkfield);
         $out .= " LEFT JOIN glpi_plugin_room_roomtypes ON (glpi_plugin_room_roomtypes.id = glpi_plugin_room_rooms.type) ";
         return $out;
         break;
   }
   return "";
}

function plugin_room_forceGroupBy($type) {
   return true;
   switch ($type) {
      case 'PluginRoomRoom':
         // Force add GROUP BY IN REQUEST
         return true;
         break;
   }
   return false;
}

// Define search option for types of the plugins
function plugin_room_getAddSearchOptions($itemtype) {
   global $LANG;
   $sopt = array();
   if ($itemtype == "Computer") {
      if (PluginRoomRoom::canView()) {
         $sopt[1050]['table'] = 'glpi_plugin_room_rooms';
         $sopt[1050]['field'] = 'name';
         $sopt[1050]['linkfield'] = '';
         $sopt[1050]['name'] = $LANG['plugin_room'][0] . " - " . __('Name');
         $sopt[1050]['forcegroupby'] = true;
         $sopt[1050]['datatype'] = 'itemlink';
         $sopt[1050]['itemlink_type'] = 'PluginRoomRoom';

         $sopt[1051]['table'] = 'glpi_plugin_room_roomtypes';
         $sopt[1051]['field'] = 'name';
         $sopt[1051]['linkfield'] = '';
         $sopt[1051]['name'] = $LANG['plugin_room'][0] . " - " . $LANG['plugin_room'][9];
         $sopt[1050]['forcegroupby'] = true;
      }
   }
   return $sopt;
}

// Aucune idee de ce que cela fait
// peut-etre ajouter un critère de recherche ?

function plugin_room_addSelect($type, $ID, $num) {
   global $SEARCH_OPTION;

   $table = $SEARCH_OPTION[$type][$ID]["table"];
   $field = $SEARCH_OPTION[$type][$ID]["field"];

   // Example of standard Select clause but use it ONLY for specific Select
   // No need of the function if you do not have specific cases
   switch ($table . "." . $field) {
      case "glpi_computers.count":
         return " COUNT( glpi_computers.ID) AS ITEM_$num, ";
         break;
   }
   return "";
}

// Define actions :
function plugin_room_MassiveActions($type) {
   global $LANG;
   switch ($type) {
      case 'Computer':
         return array(
            "plugin_room_addComputer" => $LANG['plugin_room'][17]
         );
         break;

   }
   return array();
}

// How to display specific actions ?
function plugin_room_MassiveActionsDisplay($options = array()) {
   global $LANG;

   $PluginRoomRoom = new PluginRoomRoom();
   switch ($options['itemtype']) {

      case 'Computer':
         switch ($options['action']) {
            case "plugin_room_addComputer":
               Dropdown::show("PluginRoomRoom");
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"" . __('Post') . "\" >";
               break;
         }
         break;
   }
   return "";
}

// How to process specific actions ?
function plugin_room_MassiveActionsProcess($data) {
   global $LANG;

   $PluginRoomRoom = new PluginRoomRoom();

   switch ($data['action']) {
      case 'plugin_room_addComputer':
         if ($data['itemtype'] == 'Computer' && $data['plugin_room_rooms_id'] > 0) {
            foreach ($data['item'] as $key => $val) {
               if ($val == 1) {
                  $PluginRoomRoom->plugin_room_AddDevice($data['plugin_room_rooms_id'], $key);

               }
            }
         }
         break;

   }
}

function plugin_room_AssignToTicket($types) {
   global $LANG;

   if (in_array('PluginRoomRoom', $_SESSION['glpiactiveprofile']['helpdesk_item_type'])) {
      $types['PluginRoomRoom'] = $LANG['plugin_room'][0];
   }
   return $types;
}

?>
