<?php

if (!defined('GLPI_ROOT')) {
    die('Sorry. You can\'t access directly to this file');
}

class PluginRoomProfile extends CommonDBTM
{
    public static $rightname = 'profile';

    public function getTabNameForItem(CommonGLPI $item, $withtemplate = 0)
    {
        if ($item->getType() == 'Profile') {
            return PluginRoomRoom::getTypeName(2);
        }
        return '';
    }

    public static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0)
    {
        global $CFG_GLPI;

        if ($item->getType() == 'Profile') {
            $ID = $item->getID();
            $prof = new self();

            self::addDefaultProfileInfos($ID, [
                'plugin_room' => 0,
            ]);
            $prof->showForm($ID);
        }
        return true;
    }

    public static function createFirstAccess($ID)
    {
        self::addDefaultProfileInfos(
            $ID,
            [
                'plugin_room' => ALLSTANDARDRIGHT | READNOTE | UPDATENOTE,
            ],
           true
       );
    }

    /**
     * @param int $profiles_id
     * @param array $rights
     * @param bool $drop_existing
     *
     * @return void
     */
    public static function addDefaultProfileInfos($profiles_id, $rights, $drop_existing = false)
    {
        global $DB;

        $profileRight = new ProfileRight();
        foreach ($rights as $right => $value) {
            $count_conditions = ['WHERE' => '`profiles_id` = ' . $profiles_id . ' AND `name` = "' . $right . '"'];
            if (countElementsInTable('glpi_profilerights', $count_conditions) && $drop_existing) {
                $profileRight->deleteByCriteria([
                    'profiles_id' => $profiles_id,
                    'name' => $right,
                ]);
            }
            if (!countElementsInTable('glpi_profilerights', $count_conditions)) {
                $myright['profiles_id'] = $profiles_id;
                $myright['name'] = $right;
                $myright['rights'] = $value;
                $profileRight->add($myright);

                // Add right to the current session
                $_SESSION['glpiactiveprofile'][$right] = $value;
            }
        }
    }

    /**
     * Show profile form
     *
     * @param int $profiles_id
     * @param bool $openform
     * @param bool $closeform
     *
     * @return void
     */
    public function showForm($profiles_id = 0, $openform = true, $closeform = true)
    {
        echo '<div class="firstbloc">';
        if (
            (
                $canedit = Session::haveRightsOr(
                    self::$rightname,
                    [
                        CREATE,
                        UPDATE,
                        PURGE,
                    ]
                )
            )
            && $openform
        ) {
            $profile = new Profile();
            echo '<form method="post" action="' . $profile->getFormURL() . '">';
        }

        $profile = new Profile();
        $profile->getFromDB($profiles_id);
        if ($profile->getField('interface') == 'central') {
            $rights = $this->getAllRights();
            $profile->displayRightsChoiceMatrix(
                $rights,
                [
                    'canedit' => $canedit,
                    'default_class' => 'tab_bg_2',
                    'title' => __('General'),
                ]
            );
        }

        if ($canedit && $closeform) {
            echo '<div class="center">';
            echo Html::hidden(
                'id',
                [
                    'value' => $profiles_id,
                ]
            );
            echo Html::submit(
                _sx('button', 'Save'),
                [
                    'name' => 'update',
                ]
            );
            echo '</div>\n';
            Html::closeForm();
        }
        echo '</div>';
    }

    public static function getAllRights($all = false)
    {
        global $LANG;
        $rights = [
            [
                'itemtype' => 'PluginRoomRoom',
                'label' => $LANG['plugin_room'][0],
                'field' => 'plugin_room',
            ],
        ];
        return $rights;
    }

    /**
     * Init profiles
     *
     * @param string $old_right
     */
    public static function translateARight($old_right)
    {
        switch ($old_right) {
            case '':
                return 0;
            case 'r':
                return READ;
            case 'w':
                return ALLSTANDARDRIGHT + READNOTE + UPDATENOTE;
            case '0':
            case '1':
                return $old_right;
            default:
                return 0;
        }
    }

    /**
     * Initialize profiles, and migrate it necessary
     */
    public static function initProfile()
    {
        global $DB;
        $profile = new self();

        // Add new rights in glpi_profilerights table
        foreach ($profile->getAllRights(true) as $data) {
            if (countElementsInTable('glpi_profilerights', ['WHERE' => '`name` = "' . $data['field'] . '"']) == 0) {
                ProfileRight::addProfileRights([
                    $data['field'],
                ]);
            }
        }

        foreach ($DB->request('SELECT *
                             FROM `glpi_profilerights`
                             WHERE `profiles_id` ' . $_SESSION['glpiactiveprofile']['id'] . '
                                AND `name` LIKE "%plugin_room%"') as $prof) {
            $_SESSION['glpiactiveprofile'][$prof['name']] = $prof['rights'];
        }
    }

    public static function removeRightsFromSession()
    {
        foreach (self::getAllRights(true) as $right) {
            if (isset($_SESSION['glpiactiveprofile'][$right['field']])) {
                unset($_SESSION['glpiactiveprofile'][$right['field']]);
            }
        }
    }
}
