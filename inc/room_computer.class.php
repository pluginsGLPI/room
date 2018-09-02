<?php

if (!defined('GLPI_ROOT')) {
    die('Sorry. You can\'t access directly to this file');
}

// / Group_User class - Relation between Group and User
class PluginRoomRoom_Computer extends CommonDBRelation
{
    // From CommonDBRelation
    public static $itemtype_1 = 'PluginRoomRoom';

    public static $items_id_1 = 'rooms_id';

    public static $itemtype_2 = 'Computer';

    public static $items_id_2 = 'computers_id';

    public $checks_and_logs_only_for_itemtype1 = true;
}
