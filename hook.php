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

   $install = false;
   $upgradeFrom2 = false;
   $upgradeFrom3Beta = false;

   if (! TableExists('glpi_plugin_room_rooms')) {
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
   } elseif (!FieldExists('glpi_plugin_room_rooms', 'groups_id_tech')) { // Mise à jour entre 3.0.4 et 3.1.0; la table existe, mais il manque "groups_id_tech"
      $query = "ALTER TABLE `glpi_plugin_room_rooms`
                    ADD COLUMN `groups_id_tech` INT NOT NULL DEFAULT '0' COMMENT 'Group in charge of the hardware. RELATION to glpi_groups (id)' AFTER `groups_id`;";
      $result = $DB->query($query) or die('Error when adding `glpi_plugin_room_rooms`.`groups_id_tech` field. ' . __('Error during the database update') . $DB->error());
   } elseif (FieldExists('glpi_plugin_room_rooms', 'FK_users')) { // mise à jour depuis 3.0.0 Beta; la table existe, mais avec les mauvais noms de champs
      $upgradeFrom3Beta = true;
      $query = "alter TABLE  `glpi_plugin_room_rooms`
                change FK_users users_id int(11) not null default 0,
                change FK_glpi_enterprise id_manufacturers smallint(11) not null default 0,
                change FK_groups id_groups smallint(11) not null default 0,
                change comments comment text collate utf8_unicode_ci;";
      $result = $DB->query($query) or die("error renaming glpi_plugin_room fields from Beta version " . __('Error during the database update') . $DB->error());
      if ($result) {
         $query = "ALTER TABLE `glpi_plugin_room_rooms` DROP INDEX `FK_users` ,
                   ADD KEY `users_id` ( `users_id` );";
         $result = $DB->query($query) or die("error renaming glpi_plugin_room fields from Beta version " . __('Error during the database update') . $DB->error());
      }
      $query = "ALTER TABLE `glpi_plugin_room_rooms`
                    ADD COLUMN `groups_id_tech` INT NOT NULL DEFAULT '0' COMMENT 'Group in charge of the hardware. RELATION to glpi_groups (id)' AFTER `groups_id`;";
      $result = $DB->query($query) or die('Error when adding `glpi_plugin_room_rooms`.`groups_id_tech` field. ' . __('Error during the database update') . $DB->error());

   }
   if (TableExists('glpi_plugin_room')) { // il existe une table correspondant à l'ancienne nomenclature; voir à transférer les enregistrement contenus dans celle-ci.
      if (! $upgradeFrom3Beta) // Sauf si on vient de la version beta; on présume que l'usager aura transféré manuellement ses informations.
{
         $upgradeFrom2 = true;
         $query = "SELECT COUNT(*) FROM glpi_plugin_room";
         $result = $DB->query($query);
         if ($result) { // insertion des enregistrements de l'ancienne à la nouvelle table, pour peu qu'existent le entities_id et FK_users concernés
            $query = "INSERT INTO glpi_plugin_room_rooms(id, name, entities_id, is_recursive, is_deleted, `type`, date_mod, size, count_linked, buy, access, printer, videoprojector, wifi, comment, opening, limits, text1, text2, dropdown1, dropdown2, tech_num, users_id, is_template, location, state, manufacturers_id, groups_id)
                      SELECT ID, name, FK_entities, recursive, deleted, `type`, date_mod, size, count_linked, buy, access, printer, videoprojector, wifi, comments, opening, limits, text1, text2, dropdown1, dropdown2, tech_num, FK_users, is_template, location, state, FK_glpi_enterprise, FK_groups
                      FROM glpi_plugin_room r WHERE
                         ((EXISTS(SELECT * FROM glpi_users u where u.id = r.FK_users) OR r.FK_users = 0) AND
                         (EXISTS(SELECT * FROM glpi_entities e where e.id = r.FK_entities) OR r.FK_entities = 0));";
            $result = 0;
            $result = $DB->query($query) or die("error copying glpi_plugin_room records into new table " . __('Error during the database update') . $DB->error());
            if ($result) {
               $query = "DROP TABLE glpi_plugin_room;";
               $result = $DB->query($query);
            }
         } else {
            $query = "DROP TABLE glpi_plugin_room;";
            $result = $DB->query($query);
         }
      } else {
         $query = "DROP TABLE glpi_plugin_room;";
         $result = $DB->query($query);
      }
   } else {
      $install = true;
   }
   if ($upgradeFrom2 || $install) { // Si on arrive d'une version standard ou que c'est une installation vanille
      if (! TableExists('glpi_plugin_room_rooms_computers')) {
         $query = "CREATE TABLE `glpi_plugin_room_rooms_computers` (
                   `id` int(11) NOT NULL auto_increment,
                   `computers_id` int(11) NOT NULL,
                   `rooms_id` int(11) NOT NULL,
                   PRIMARY KEY  (`id`),
                   UNIQUE `computers_id` (`computers_id`),
                   KEY `rooms_id` (`rooms_id`)
                   ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
         $result = $DB->query($query) or die("error adding glpi_plugin_room_rooms_computers table " . __('Error during the database update') . $DB->error());
         if ($result) {
            if (TableExists('glpi_plugin_room_computer')) {
               $result = 0;
               $query = "SELECT COUNT(*) FROM glpi_plugin_room_computer";
               $result = $DB->query($query);

               if ($result) {
                  $query = "INSERT INTO glpi_plugin_room_rooms_computers(id, rooms_id, computers_id)
                            SELECT ID, FK_rooms, FK_computers
                            FROM glpi_plugin_room_computer ;";
                  $result = 0;
                  $result = $DB->query($query) or die("error copying glpi_plugin_room_computer records into new table " . __('Error during the database update') . $DB->error());
                  if ($result) {
                     $query = "DROP TABLE glpi_plugin_room_computer;";
                     $result = $DB->query($query);
                  }
               } else {
                  $query = "DROP TABLE glpi_plugin_room_computer;";
                  $result = $DB->query($query);
               }
            }
         }
      }
   } elseif ($upgradeFrom3Beta) {
      $query = "rename table `glpi_plugin_room_computer` to `glpi_plugin_room_rooms_computers`;";
      $result = $DB->query($query) or die("error renaming glpi_plugin_room_computer table from BETA version " . __('Error during the database update') . $DB->error());
      if ($result) {
         $query = "alter TABLE  `glpi_plugin_room_rooms_computers`
                   change FK_rooms rooms_id int(11) not null ,
                   change FK_computers id_computers int(11) not null,
                   DROP INDEX `FK_rooms` ,
                   ADD KEY `rooms_id` ( `rooms_id` ),
                   DROP INDEX `FK_computers` ,
                   ADD UNIQUE `id_computers` ( `id_computers` );";
         $result = $DB->query($query) or die("error renaming glpi_plugin_room_rooms_computers fields from Beta version " . __('Error during the database update') . $DB->error());
      }
   }

   if (! TableExists('glpi_plugin_room_profiles')) {
      $query = "CREATE TABLE `glpi_plugin_room_profiles` (
                `id` int(11) NOT NULL auto_increment,
                `profiles_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_profiles (id)',
                `room` char(1) collate utf8_unicode_ci default NULL,
                PRIMARY KEY  (`id`),
                KEY `profiles_id` (`profiles_id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
      $DB->query($query) or die("error adding glpi_plugin_room_profiles table " . __('Error during the database update') . $DB->error());
      // je pense qu'il faut aussi ici faire des insertions dans glpi_displaypreferences
      // (Edit PMD) ainsi que dans glpi_bookmark, glpi_doc_device -> glpi_documents_items et glpi_logs -> glpi_history
   } elseif (FieldExists('glpi_plugin_room_profiles', 'FK_profiles')) {
      $query = "UPDATE `glpi_plugin_room_profiles`
                CHANGE `FK_profiles` `profiles_id`, `ID` `id`";
      $DB->query($query) or die("error updating table glpi_plugin_room_computer" . __('Error during the database update') . $DB->error());
   }

   if (! TableExists('glpi_plugin_room_roomtypes')) {
      $query = "CREATE TABLE  `glpi_plugin_room_roomtypes` (
                `id` int(11) NOT NULL auto_increment,
                `name` varchar(255) collate utf8_unicode_ci default NULL,
                `comment` text collate utf8_unicode_ci,
                PRIMARY KEY  (`id`),
                KEY `name` (`name`)
                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
      $DB->query($query) or die("error adding glpi_plugin_room_roomtypes table " . __('Error during the database update') . $DB->error());
   }
   if (TableExists('glpi_dropdown_plugin_room_type') && ! $upgradeFrom3Beta) { // il existe une table correspondant à l'ancienne nomenclature;
     // voir à transférer les enregistrement contenus dans celle-ci.
     // Sauf si on arrive de la version beta
      $query = "SELECT COUNT(*) FROM glpi_dropdown_plugin_room_type";
      $result = $DB->query($query);
      if ($result) { // insertion des enregistrements de l'ancienne à la nouvelle table, pour peu qu'existent le entities_id et FK_users concernés
         $query = "INSERT INTO glpi_plugin_room_roomtypes(id, name, comment)
                   SELECT id, name, comments
                   FROM glpi_dropdown_plugin_room_type ;";
         $result = 0;
         $result = $DB->query($query) or die("error copying glpi_dropdown_plugin_room_type records into new table " . __('Error during the database update') . $DB->error());
         if ($result) {
            $query = "DROP TABLE glpi_dropdown_plugin_room_type;";
            $result = $DB->query($query);
         }
      }
   }
   if (! TableExists('glpi_plugin_room_roomaccessconds')) {
      $query = "CREATE TABLE  `glpi_plugin_room_roomaccessconds` (
                `id` int(11) NOT NULL auto_increment,
                `name` varchar(255) collate utf8_unicode_ci default NULL,
                `comment` text collate utf8_unicode_ci,
                PRIMARY KEY  (`id`),
                KEY `name` (`name`)
                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
      $DB->query($query) or die("error adding glpi_plugin_room_roomaccessconds table " . __('Error during the database update') . $DB->error());
      if (TableExists('glpi_dropdown_plugin_room_access')) {
         $query = "SELECT COUNT(*) FROM glpi_dropdown_plugin_room_access";
         $result = $DB->query($query);
         if ($result) {
            $query = "INSERT INTO glpi_plugin_room_roomaccessconds (id, name, comment)
                      SELECT id, name, comments FROM glpi_dropdown_plugin_room_access";
            $result = 0;
            $result = $DB->query($query) or die("error copying glpi_dropdown_plugin_room_access records into new table " . __('Error during the database update') . $DB->error());
            if ($result) {
               $query = "DROP TABLE glpi_dropdown_plugin_room_access;";
               $result = $DB->query($query);
            }
         }
      }
   }

   if (! TableExists('glpi_plugin_room_dropdown1s')) {
      $query = "CREATE TABLE  `glpi_plugin_room_dropdown1s` (
                `id` int(11) NOT NULL auto_increment,
                `name` varchar(255) collate utf8_unicode_ci default NULL,
                `comment` text collate utf8_unicode_ci,
                PRIMARY KEY  (`id`),
                KEY `name` (`name`)
                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
      $DB->query($query) or die("error adding glpi_plugin_room_roomspecificities table " . __('Error during the database update') . $DB->error());
   }

   if (TableExists('glpi_dropdown_plugin_room_dropdown1') && ! $upgradeFrom3Beta) {
      $query = "SELECT COUNT(*) FROM glpi_dropdown_plugin_room_dropdown1";
      $result = $DB->query($query);
      if ($result) {
         $query = "INSERT INTO glpi_plugin_room_dropdown1s (id, name, comment)
                   SELECT id, name, comments FROM glpi_dropdown_plugin_room_dropdown1";
         $result = 0;
         $result = $DB->query($query) or die("error copying glpi_dropdown_plugin_room_dropdown1 records into new table " . __('Error during the database update') . $DB->error());
         if ($result) {
            $query = "DROP TABLE glpi_dropdown_plugin_room_dropdown1;";
            $result = $DB->query($query);
         }
      } else {
         $query = "DROP TABLE glpi_dropdown_plugin_room_dropdown1;";
         $result = $DB->query($query);
      }
   }

   if (TableExists('glpi_dropdown_plugin_room_dropdown2') && ! $upgradeFrom3Beta) {
      $query = "SELECT * FROM glpi_dropdown_plugin_room_dropdown2";
      $result = $DB->query($query);
      if ($result) {
         $idOffset = 0;
         $query = "SELECT MAX(id) FROM glpi_plugin_room_dropdown1s";
         $result = 0;
         $result = $DB->query($query) or die("error copying glpi_dropdown_plugin_room_dropdown2 records into new table " . __('Error during the database update') . $DB->error());
         if ($result) { // Il existait des valeurs dans la table glpi_dropdown_plugin_room_dropdown1 qui ont été transférées dams glpi_plugin_room_roomspecificities
                       // On doit décaler les valeurs de glpi_dropdown_plugin_room_dropdown2.
            $row = $DB->fetch_array($result);
            if ($row[0]) {
               $idOffset = $row[0];
            }
         }
         $query = "INSERT INTO glpi_plugin_room_dropdown1s  (id, name, comment)
                   SELECT (ID + " . $idOffset . "), name, comments FROM glpi_dropdown_plugin_room_dropdown2";
         $result = 0;
         $result = $DB->query($query) or die("error copying glpi_dropdown_plugin_room_dropdown2 records into new table " . __('Error during the database update') . $DB->error());
         if ($result) {
            if ($idOffset > 0) {
               // Nous avons décalé les id des spécificités de la table glpi_dropdown_plugin_room_dropdown2; on doit modifier les enregistrements de la table glpi_plugin_room_rooms en conséquence.
               $result = 0;
               $query = "UPDATE glpi_plugin_room_rooms
                         SET `dropdown2` = (`dropdown2` + " . $idOffset . ")
                         WHERE `dropdown2` > 0;";
               $result = $DB->query($query) or die("error updating dropdown2 values to new ids. " . __('Error during the database update') . $DB->error());
            }
            $query = "DROP TABLE glpi_dropdown_plugin_room_dropdown2;";
            $result = $DB->query($query);
         }
      } else {
         $query = "DROP TABLE glpi_dropdown_plugin_room_dropdown2;";
         $result = $DB->query($query);
      }
   }

   if ($upgradeFrom2 || $upgradeFrom3Beta) {
      $query = "UPDATE `glpi_reservationitems`
                SET itemtype = 'PluginRoomRoom'
                WHERE itemtype = '1050';";
      $result = $DB->query($query) or die("error updating glpi_reservationitems records for prior room reservations " . __('Error during the database update') . $DB->error());
      $query = "UPDATE `glpi_displaypreferences`
                SET itemtype = 'PluginRoomRoom'
                WHERE itemtype = '1050';";
      $result = $DB->query($query) or die("error updating glpi_displaypreferences records for prior room display preferences " . __('Error during the database update') . $DB->error());
      $query = "UPDATE `glpi_logs`
                SET itemtype = 'PluginRoomRoom'
                WHERE itemtype = '1050';";
      $result = $DB->query($query) or die("error updating glpi_logs records for prior room modifications history  " . __('Error during the database update') . $DB->error());
      $query = "UPDATE `glpi_documents_items`
                SET itemtype = 'PluginRoomRoom'
                WHERE itemtype = '1050';";
      $result = $DB->query($query) or die("error updating glpi_document_items records for prior room documents" . __('Error during the database update') . $DB->error());
      $query = "UPDATE `glpi_bookmarks`
                SET itemtype = 'PluginRoomRoom'
                WHERE itemtype = '1050';";
      $result = $DB->query($query) or die("error updating glpi_bookmarks records for prior room bookmarks" . __('Error during the database update') . $DB->error());
      $query = "UPDATE `glpi_bookmarks_users`
                SET itemtype = 'PluginRoomRoom'
                WHERE itemtype = '1050';";
      $result = $DB->query($query) or die("error updating glpi_document_items records for prior room private bookmarks" . __('Error during the database update') . $DB->error());
   }

   PluginRoomProfile::createFirstAccess($_SESSION['glpiactiveprofile']['id']);
   $migration = new Migration('3.0.4');
   $migration->dropTable('glpi_plugin_room_profiles');

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
      "glpi_bookmarks",
      "glpi_logs",
      'glpi_items_tickets',
      'glpi_reservationitems',
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
