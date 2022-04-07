<?php

function plugin_room_install()
{
    global $DB;

    include_once GLPI_ROOT . '/plugins/room/inc/profile.class.php';

    // Table for room assets
    if (!$DB->tableExists('glpi_plugin_room_rooms')) {
        $query = <<<'EOS'
            CREATE TABLE `glpi_plugin_room_rooms` (
                `id` int(11) NOT NULL auto_increment,
                `name` varchar(255) collate utf8_unicode_ci default NULL,
                `entities_id` int(11) NOT NULL default 0,
                `locations_id` int(11) NOT NULL default 0,
                `is_recursive` smallint(6) NOT NULL default 0,
                `is_deleted` smallint(6) NOT NULL default 0,
                `type` int(11) NOT NULL default 0,
                `date_mod` datetime default NULL,
                `size` smallint(6) NOT NULL default 0,
                `count_linked` smallint(6) NOT NULL default 0,
                `buy` datetime default NULL,
                `access` int(11) NOT NULL default 0,
                `printer` smallint(6) NOT NULL default 0,
                `videoprojector` smallint(6) NOT NULL default 0,
                `wifi` smallint(6) NOT NULL default 0,
                `comment` text collate utf8_unicode_ci,
                `opening` varchar(255) collate utf8_unicode_ci default NULL,
                `limits` varchar(255) collate utf8_unicode_ci default NULL,
                `text1` varchar(255) collate utf8_unicode_ci default NULL,
                `text2` varchar(255) collate utf8_unicode_ci default NULL,
                `dropdown1` int(11) NOT NULL default 0,
                `dropdown2` int(11) NOT NULL default 0,
                `tech_num` int(11) NOT NULL default 0,
                `users_id` int(11) NOT NULL default 0,
                `is_template` smallint(6) NOT NULL default 0, # not used / for reservation search engine
                `location` smallint(6) NOT NULL default 0, # not used / for reservation search engine
                `state` smallint(6) NOT NULL default 0, # not used / for reservation search engine
                `manufacturers_id` smallint(6) NOT NULL default 0, # not used / for reservation search engine
                `groups_id` smallint(6) NOT NULL default 0, # not used / for reservation search engine
                `groups_id_tech` int(11) NOT NULL default 0 COMMENT "Group in charge of the hardware. RELATION to glpi_groups (id)",
                PRIMARY KEY (`id`),
                KEY `entities_id` (`entities_id`),
                KEY `is_deleted` (`is_deleted`),
                KEY `type` (`type`),
                KEY `name` (`name`),
                KEY `buy` (`buy`),
                KEY `dropdown1` (`dropdown1`),
                KEY `dropdown2` (`dropdown2`),
                KEY `tech_num` (`tech_num`),
                KEY `users_id` (`users_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC
EOS;
        $DB->query($query) || die('error adding glpi_plugin_room table ' . __('Error during the database update', 'room') . $DB->error());
    }

    // Table to link Rooms to Computers
    if (!$DB->TableExists('glpi_plugin_room_rooms_computers')) {
        $query = <<<'EOS'
            CREATE TABLE `glpi_plugin_room_rooms_computers` (
                `id` int(11) NOT NULL auto_increment,
                `computers_id` int(11) NOT NULL,
                `rooms_id` int(11) NOT NULL,
                PRIMARY KEY (`id`),
                UNIQUE `computers_id` (`computers_id`),
                KEY `rooms_id` (`rooms_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;
EOS;
        $DB->query($query) || die('error adding glpi_plugin_room_rooms_computers table ' . __('Error during the database update', 'room') . $DB->error());
    }

    // Table for Room types
    if (!$DB->TableExists('glpi_plugin_room_roomtypes')) {
        $query = <<<'EOS'
            CREATE TABLE `glpi_plugin_room_roomtypes` (
                `id` int(11) NOT NULL auto_increment,
                `name` varchar(255) collate utf8_unicode_ci default NULL,
                `comment` text collate utf8_unicode_ci,
                PRIMARY KEY (`id`),
                KEY `name` (`name`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;
EOS;
        $DB->query($query) || die('error adding glpi_plugin_room_roomtypes table ' . __('Error during the database update', 'room') . $DB->error());
    }

    // Table for access conditions
    if (!$DB->TableExists('glpi_plugin_room_roomaccessconds')) {
        $query = <<<'EOS'
            CREATE TABLE `glpi_plugin_room_roomaccessconds` (
                `id` int(11) NOT NULL auto_increment,
                `name` varchar(255) collate utf8_unicode_ci default NULL,
                `comment` text collate utf8_unicode_ci,
                PRIMARY KEY (`id`),
                KEY `name` (`name`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;
EOS;
        $DB->query($query) || die('error adding glpi_plugin_room_roomaccessconds table ' . __('Error during the database update', 'room') . $DB->error());
    }

    // Table for dropdowns
    if (!$DB->TableExists('glpi_plugin_room_dropdown1s')) {
        $query = <<<'EOS'
            CREATE TABLE `glpi_plugin_room_dropdown1s` (
                `id` int(11) NOT NULL auto_increment,
                `name` varchar(255) collate utf8_unicode_ci default NULL,
                `comment` text collate utf8_unicode_ci,
                PRIMARY KEY (`id`),
                KEY `name` (`name`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;
EOS;
        $DB->query($query) || die('error adding glpi_plugin_room_roomspecificities table ' . __('Error during the database update', 'room') . $DB->error());
    }

    PluginRoomProfile::createFirstAccess($_SESSION['glpiactiveprofile']['id']);

    return true;
}

function plugin_room_uninstall()
{
    global $DB;

    // Drop plugin tables
    $tables = [
        'glpi_plugin_room_rooms_computers',
        'glpi_plugin_room_roomtypes',
        'glpi_plugin_room_roomaccessconds',
        'glpi_plugin_room_dropdown1s',
        'glpi_plugin_room_rooms',
    ];

    foreach ($tables as $table) {
        $DB->query("DROP TABLE IF EXISTS `$table`;");
    }

    // Delete reservations
    $DB->query('DELETE FROM `glpi_reservations` WHERE `reservationitems_id` in
	    (SELECT id FROM `glpi_reservationitems` WHERE `itemtype` = "PluginRoomRoom");');

    // Delete logs, items and other things from glpi tables
    $tables_glpi = [
        'glpi_displaypreferences',
        'glpi_documents_items',
        'glpi_logs',
        'glpi_items_tickets',
        'glpi_reservationitems',
        'glpi_savedsearches',
    ];

    foreach ($tables_glpi as $table_glpi) {
        $DB->query('DELETE FROM `$table_glpi` WHERE `itemtype` = "PluginRoomRoom";');
    }

    return true;
}

// Define dropdown relations
function plugin_room_getDatabaseRelations()
{
    $plugin = new Plugin();

    if ($plugin->isActivated('room')) {
        return [
            'glpi_plugin_room_roomtypes' => [
                'glpi_plugin_room_rooms' => 'type',
            ],
            'glpi_plugin_room_roomaccessconds' => [
                'glpi_plugin_room_rooms' => 'access',
            ],
            'glpi_plugin_room_dropdown1s' => [
                'glpi_plugin_room_rooms' => [
                    'dropdown1',
                    'dropdown2',
                ],
            ],
            'glpi_plugin_room_rooms' => [
                'glpi_plugin_room_rooms_computers' => 'rooms_id',
            ],
            'glpi_computers' => [
                'glpi_plugin_room_rooms_computers' => 'computers_id',
            ],
            'glpi_entities' => [
                'glpi_plugin_room_rooms' => 'entities_id',
            ],
            'glpi_locations' => [
                'glpi_plugin_room_rooms' => 'locations_id',
            ],
            'glpi_profiles' => [
                'glpi_plugin_room_profiles' => 'profiles_id',
            ],
            'glpi_users' => [
                'glpi_plugin_room_rooms' => [
                    'users_id',
                    'tech_num',
                ],
            ],
        ];
    } else {
        return [];
    }
}

// Define Dropdown tables to be manage in GLPI :
// Definit les tables qui sont gérables via les intitulés
function plugin_room_getDropdown()
{
    $plugin = new Plugin();

    if ($plugin->isActivated('room')) {
        return [
            'PluginRoomRoomType' => PluginRoomRoomType::getTypeName(2),
            'PluginRoomRoomAccessCond' => PluginRoomRoomAccessCond::getTypeName(2),
            'PluginRoomDropdown1' => PluginRoomDropdown1::getTypeName(2),
        ];
    } else {
        return [];
    }
}

function plugin_room_addLeftJoin($type, $ref_table, $new_table, $linkfield, &$already_link_tables)
{
    // Example of standard LEFT JOIN clause but use it ONLY for specific LEFT JOIN
    // No need of the function if you do not have specific cases

    switch ($new_table) {
        case 'glpi_computers':
            $out = <<<'EOS'
                LEFT JOIN glpi_plugin_room_rooms_computers
                    ON (glpi_plugin_room_rooms.id = glpi_plugin_room_rooms_computers.rooms_id)
EOS;
            $out .= <<<'EOS'
                LEFT JOIN glpi_computers
                    ON (glpi_computers.id = glpi_plugin_room_rooms_computers.computers_id)
EOS;
            return $out;
            break;
        case 'glpi_plugin_room_rooms': // From computers
            $out = <<<'EOS'
                LEFT JOIN glpi_plugin_room_rooms_computers
                    ON (glpi_computers.id = glpi_plugin_room_rooms_computers.computers_id)
EOS;
            $out .= <<<'EOS'
                LEFT JOIN glpi_plugin_room_rooms
                    ON (glpi_plugin_room_rooms.id = glpi_plugin_room_rooms_computers.rooms_id)
EOS;
            return $out;
            break;
        case 'glpi_plugin_room_roomtypes': // From computers
            $out = Search::addLeftJoin($type, $ref_table, $already_link_tables, 'glpi_plugin_room_rooms', $linkfield);
            $out .= <<<'EOS'
                LEFT JOIN glpi_plugin_room_roomtypes
                    ON (glpi_plugin_room_roomtypes.id = glpi_plugin_room_rooms.type)
EOS;
            return $out;
            break;
    }
    return '';
}

function plugin_room_forceGroupBy($type)
{
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
function plugin_room_getAddSearchOptions($itemtype)
{
    $sopt = [];
    if ($itemtype == 'Computer') {
        if (PluginRoomRoom::canView()) {
            $sopt[1050]['table'] = 'glpi_plugin_room_rooms';
            $sopt[1050]['field'] = 'name';
            $sopt[1050]['linkfield'] = '';
            $sopt[1050]['name'] = __('Room Management', 'room') . ' - ' . __('Name', 'room');
            $sopt[1050]['forcegroupby'] = true;
            $sopt[1050]['datatype'] = 'itemlink';
            $sopt[1050]['itemlink_type'] = 'PluginRoomRoom';

            $sopt[1051]['table'] = 'glpi_plugin_room_roomtypes';
            $sopt[1051]['field'] = 'name';
            $sopt[1051]['linkfield'] = '';
            $sopt[1051]['name'] = __('Room Management', 'room') . ' - ' . __('Type of Room', 'room');
            $sopt[1050]['forcegroupby'] = true;
        }
    }
    return $sopt;
}

// Aucune idee de ce que cela fait
// peut-etre ajouter un critère de recherche ?

function plugin_room_addSelect($type, $ID, $num)
{
    global $SEARCH_OPTION;

    $table = $SEARCH_OPTION[$type][$ID]['table'];
    $field = $SEARCH_OPTION[$type][$ID]['field'];

    // Example of standard Select clause but use it ONLY for specific Select
    // No need of the function if you do not have specific cases
    switch ($table . '.' . $field) {
        case 'glpi_computers.count':
            return ' COUNT( glpi_computers.ID) AS ITEM_$num, ';
            break;
    }
    return '';
}

// Define actions :
function plugin_room_MassiveActions($type)
{
    switch ($type) {
        case 'Computer':
            return [
                'plugin_room_addComputer' => __('Add a Room', 'room'),
            ];
            break;
    }
    return [];
}

// How to display specific actions ?
function plugin_room_MassiveActionsDisplay($options = [])
{
    $PluginRoomRoom = new PluginRoomRoom();
    switch ($options['itemtype']) {
        case 'Computer':
            switch ($options['action']) {
                case 'plugin_room_addComputer':
                    Dropdown::show('PluginRoomRoom');
                    echo '&nbsp;<input type="submit" name="massiveaction" class="submit" value="' . __('Post', 'room') . '" >';
                    break;
            }
            break;
    }
    return '';
}

// How to process specific actions ?
function plugin_room_MassiveActionsProcess($data)
{
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

function plugin_room_AssignToTicket($types)
{
    if (in_array('PluginRoomRoom', $_SESSION['glpiactiveprofile']['helpdesk_item_type'])) {
        $types['PluginRoomRoom'] = __('Room Management', 'room');
    }
    return $types;
}
