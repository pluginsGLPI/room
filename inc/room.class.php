<?php

class PluginRoomRoom extends CommonDBTM
{
    public $dohistory = true;

    public static $rightname = 'plugin_room';

    protected $usenotepad = true;

    public static function getTypeName($nb = 0)
    {
        global $LANG;

        return $LANG['plugin_room'][0];
    }

    public function prepareInputForUpdate($input)
    {
        // Backup initial values
        if (isset($input['buy']) && empty($input['buy'])) {
            $input['buy'] = 'NULL';
        }

        return $input;
    }

    public function prepareInputForAdd($input)
    {
        // Backup initial values
        if (isset($input['buy']) && empty($input['buy'])) {
            unset($input['buy']);
        }

        return $input;
    }

    // Cette fonction propose des critères de filtrage pour la page des salles
    public function rawSearchOptions()
    {
        global $LANG;

        $tab = [];

        $tab[] = [
            'id' => 'common',
            'name' => $LANG['plugin_room'][0],
        ];

        $tab[] = [
            'id' => '1',
            'table' => $this->getTable(),
            'field' => 'name',
            'linkfield' => 'name',
            'name' => __('Name'),
            'datatype' => 'itemlink',
            'itemlink_type' => $this->getType(),
        ];

        $tab[] = [
            'id' => '2',
            'table' => 'glpi_plugin_room_roomtypes',
            'field' => 'name',
            'linkfield' => 'type',
            'name' => __('Type'),
        ];

        $tab[] = [
            'id' => '26',
            'table' => 'glpi_groups',
            'field' => 'completename',
            'linkfield' => 'groups_id_tech',
            'name' => __('Group in charge of the hardware'),
            'condition' => '`is_assign`',
            'datatype' => 'dropdown',
        ];

        $tab[] = [
            'id' => '24',
            'table' => 'glpi_users',
            'field' => 'name',
            'linkfield' => 'tech_num',
            'name' => __('Technician in charge of the hardware'),
        ];

        $tab[] = [
            'id' => '25',
            'table' => 'glpi_users',
            'field' => 'name',
            'linkfield' => 'users_id',
            'name' => __('Alternate username'),
        ];

        $tab[] = [
            'id' => '3',
            'table' => $this->getTable(),
            'field' => 'comment',
            'linkfield' => 'comment',
            'name' => __('Comments'),
        ];

        $tab += Location::rawSearchOptionsToAdd();

        $tab[] = [
            'id' => '5',
            'table' => $this->getTable(),
            'field' => 'size',
            'linkfield' => 'size',
            'name' => $LANG['plugin_room'][4],
        ];

        $tab[] = [
            'id' => '6',
            'table' => 'glpi_plugin_room_roomaccessconds',
            'field' => 'name',
            'linkfield' => 'access',
            'name' => $LANG['plugin_room'][5],
        ];

        $tab[] = [
            'id' => '7',
            'table' => $this->getTable(),
            'field' => 'buy',
            'linkfield' => 'buy',
            'name' => __('Date of purchase'),
        ];

        $tab[] = [
            'id' => '8',
            'table' => $this->getTable(),
            'field' => 'printer',
            'linkfield' => 'printer',
            'name' => $LANG['plugin_room'][6],
        ];

        $tab[] = [
            'id' => '9',
            'table' => $this->getTable(),
            'field' => 'videoprojector',
            'linkfield' => 'videoprojector',
            'name' => $LANG['plugin_room'][7],
        ];

        $tab[] = [
            'id' => '10',
            'table' => $this->getTable(),
            'field' => 'wifi',
            'linkfield' => 'wifi',
            'name' => $LANG['plugin_room'][8],
        ];

        $tab[] = [
            'id' => '11',
            'table' => $this->getTable(),
            'field' => 'comment',
            'linkfield' => '',
            'name' => __('Comments'),
        ];

        $tab[] = [
            'id' => '13',
            'table' => $this->getTable(),
            'field' => 'opening',
            'linkfield' => '',
            'name' => $LANG['plugin_room'][11],
        ];

        $tab[] = [
            'id' => '12',
            'table' => $this->getTable(),
            'field' => 'limits',
            'linkfield' => '',
            'name' => $LANG['plugin_room'][12],
        ];

        $tab[] = [
            'id' => '16',
            'table' => $this->getTable(),
            'field' => 'text1',
            'linkfield' => '',
            'name' => $LANG['plugin_room'][13],
        ];

        $tab[] = [
            'id' => '17',
            'table' => $this->getTable(),
            'field' => 'text2',
            'linkfield' => '',
            'name' => $LANG['plugin_room'][14],
        ];

        $tab[] = [
            'id' => '18',
            'table' => 'glpi_plugin_room_dropdown1s',
            'field' => 'name',
            'linkfield' => 'dropdown1',
            'name' => $LANG['plugin_room'][15],
        ];

        $tab[] = [
            'id' => '19',
            'table' => 'glpi_plugin_room_dropdown1s',
            'field' => 'name',
            'linkfield' => 'dropdown2',
            'name' => $LANG['plugin_room'][16],
        ];

        $tab[] = [
            'id' => '30',
            'table' => $this->getTable(),
            'field' => 'id',
            'linkfield' => '',
            'name' => __('ID'),
        ];

        $tab[] = [
            'id' => '31',
            'table' => $this->getTable(),
            'field' => 'name',
            'linkfield' => '',
            'name' => __('Computers'),
            'forcegroupby' => true,
            'datatype' => 'itemlink',
            'itemlink_type' => $this->getType(),
        ];

        $tab[] = [
            'id' => '32',
            'table' => $this->getTable(),
            'field' => 'count_linked',
            'linkfield' => '',
            'name' => $LANG['plugin_room'][18],
            'meta' => 1,
        ];

        $tab[] = [
            'id' => '80',
            'table' => 'glpi_entities',
            'field' => 'completename',
            'linkfield' => 'entities_id',
            'name' => __('Entity'),
        ];

        return $tab;
    }

    // Cette fonction définie les onglets à afficher sur la fiche de l'objet
    // Cette fonction retourne un tableau [id de l'onglet->titre onglet]
    public function defineTabs($options = [])
    {
        $ong = [];

        $this->addDefaultFormTab($ong);
        if (Session::haveRight('reservation', READ)) {
            // Affiche "Réservations"
            $this->addStandardTab('Reservation', $ong, $options);
        }
        $this->addStandardTab('Ticket', $ong, $options);
        $this->addStandardTab('Item_Problem', $ong, $options);
        $this->addStandardTab('Document_Item', $ong, $options);
        $this->addStandardTab('Notepad', $ong, $options);
        $this->addStandardTab('Log', $ong, $options);

        return $ong;
    }

    // Cette fonction affiche le formulaire de l'objet (en création ou en édition/consultation)
    // Cette fonction est appelée par /front/room.form.php
    // showForm(ID de l'objet,tableau pour les options)
    public function showForm($ID, $options = [])
    {
        global $CFG_GLPI, $LANG;

        if (!self::canView()) {
            return false;
        }

        if (!$this->canView()) {
            return false;
        }

        // Si la salle éxiste
        if ($ID > 0) {
            $this->check($ID, READ);
        } else { // C'est une nouvelle salle
            $this->check(-1, CREATE);
            $this->getEmpty();
        }

        // entete du formulaire avec affichage du type d'objet, de l'entite et de la recursivite
        // au niveau affichage la première ligne du tableau
        $this->showFormHeader($options);

        // Composition du formulaire de l'objet salle
        // seconde ligne du tableau
        echo '<tr class="tab_bg_1">';
        if ($ID > 0) { // La salle éxiste déjà : affichage de la derniere modif
            echo '<th colspan="4">' . __('Last update') . ': ' . Html::convDateTime($this->fields['date_mod']) . '</th>';
        } else { // C'est une nouvelle salle
            echo '<th colspan="4">&nbsp;</th>';
        }
        echo '</tr>';

        // Reste du tableau
        // Nom de la salle
        echo '<tr class="tab_bg_1"><td>' . __('Name') . ':		</td>';
        echo '<td>';
        Html::autocompletionTextField($this, 'name');
        echo '</td>';
        echo '<td>' . __('Location') . ':		</td>';
        echo '<td>';
        Dropdown::show(
            'Location',
            [
                'value' => $this->fields['locations_id'],
                'entity' => $this->fields['entities_id'],
            ]
        );
        echo '</td></tr>';

        // Dropdown du type
        echo '<tr class="tab_bg_1"><td>' . __('Type') . ':		</td>';
        echo '<td>';
        Dropdown::show(
            'PluginRoomRoomType',
            [
                'name' => 'type',
                'value' => $this->fields['type'],
            ]
        );
        echo '</td>';

        // Dropdown des Conditions d'accès
        echo '<td>' . $LANG['plugin_room'][5] . ':		</td>';
        echo '<td>';
        Dropdown::show(
            'PluginRoomRoomAccessCond',
            [
                'name' => 'access',
                'value' => $this->fields['access'],
            ]
        );
        echo '</td></tr>';

        // Dropdown de l'usager
        echo '<tr class="tab_bg_1"><td>' . __('Alternate username') . ':		</td>';
        echo '<td>';
        User::Dropdown([
            'name' => 'users_id',
            'value' => $this->fields['users_id'],
            'entity' => $this->fields['entities_id'],
            'right' => 'all',
        ]);
        echo '</td>';

        // Dropdown du Responsable technique
        echo '<td>' . __('Technician in charge of the hardware') . ':		</td>';
        echo '<td>';
        User::Dropdown([
            'name' => 'tech_num',
            'value' => $this->fields['tech_num'],
            'entity' => $this->fields['entities_id'],
            'right' => 'interface',
        ]);
        echo '</td></tr>';

        // Nombres de place
        echo '<tr class="tab_bg_1"><td>' . $LANG['plugin_room'][4] . ':		</td>';
        echo '<td>';
        Dropdown::showNumber(
            'size',
            [
                'value' => $this->fields['size'],
                'min' => 0,
                'max' => 500,
            ]
        );
        echo '</td>';

        // Dropdown du Groupe responsable technique
        echo '<td>' . __('Group in charge of the hardware') . '</td><td>';
        Group::dropdown([
            'name' => 'groups_id_tech',
            'value' => $this->fields['groups_id_tech'],
            'entity' => $this->fields['entities_id'],
            'condition' => '`is_assign`',
        ]);
        echo '</td></tr>';

        // Date d'achat
        echo '<tr class="tab_bg_1"><td>' . __('Date of purchase') . ':		</td>';
        echo '<td>';
        Html::showDateField(
           'buy',
            [
                'value' => $this->fields['buy'],
                'maybeempty' => true,
                'canedit' => true,
            ]
        );
        echo '</td>';

        // Moyen d'impression
        echo '<td>' . $LANG['plugin_room'][6] . ':		</td>';
        echo '<td>';
        Dropdown::showYesNo('printer', $this->fields['printer']);
        echo '</td></tr>';

        // Videoprojecteur
        echo '<tr class="tab_bg_1"><td>' . $LANG['plugin_room'][7] . ':		</td>';
        echo '<td>';
        Dropdown::showYesNo('videoprojector', $this->fields['videoprojector']);
        echo '</td>';

        // wifi
        echo '<td>' . $LANG['plugin_room'][8] . ':		</td>';
        echo '<td>';
        Dropdown::showYesNo('wifi', $this->fields['wifi']);
        echo '</td></tr>';

        // Spécificité 1
        echo '<tr class="tab_bg_1"><td>' . $LANG['plugin_room'][13] . ':		</td>';
        echo '<td>';
        Html::autocompletionTextField($this, 'text1');
        echo '</td>';

        // Spécificité 3
        echo '<td>' . $LANG['plugin_room'][15] . ':		</td>';
        echo '<td>';
        Dropdown::show(
            'PluginRoomDropdown1',
            [
                'name' => 'dropdown1',
                'value' => $this->fields['dropdown1'],
            ]
        );
        echo '</td></tr>';

        // Spécificité 2
        echo '<tr class="tab_bg_1"><td>' . $LANG['plugin_room'][14] . ':		</td>';
        echo '<td>';
        Html::autocompletionTextField($this, 'text2');
        echo '</td>';

        // Spécificité 4
        echo '<td>' . $LANG['plugin_room'][16] . ':		</td>';
        echo '<td>';
        Dropdown::show(
            'PluginRoomDropdown1',
            [
                'name' => 'dropdown2',
                'value' => $this->fields['dropdown2'],
            ]
        );
        echo '</td></tr>';

        // Horaires d'ouverture
        echo '<tr class="tab_bg_1"><td>' . $LANG['plugin_room'][11] . ':		</td>';
        echo '<td colspan="3">';
        Html::autocompletionTextField($this, 'opening');
        echo '</td></tr>';

        // limitations
        echo '<tr class="tab_bg_1"><td>' . $LANG['plugin_room'][12] . ':		</td>';
        echo '<td colspan="3">';
        Html::autocompletionTextField($this, 'limits');
        echo '</td></tr>';

        // Commentaires
        echo '<tr>';
        echo '<td class="tab_bg_1" valign="top">';
        echo __('Comments') . ':</td>';
        echo '<td colspan="3" class="tab_bg_1">';
        echo '<textarea cols="70" rows="4" name="comment">' . $this->fields['comment'] . '</textarea>';
        echo '</td>';
        echo '</tr>';

        // Affichage des boutons
        $this->showFormButtons($options);

        return true;
    }

    // cette fonction doit servir à remplir la rubrique ordinateur de la fiche room
    public function showComputers($target, $room_id)
    {
        global $CFG_GLPI, $LANG, $DB;

        if (!self::canView()) {
            return false;
        }

        if ($this->getFromDB($room_id)) {
            $canedit = $this->can($room_id, UPDATE);

            $query = <<<EOS
                SELECT
                    glpi_computers.*,
                    glpi_plugin_room_rooms_computers.id AS idd,
                    glpi_entities.id AS entity
                FROM
                    glpi_plugin_room_rooms_computers,
                    glpi_computers
                    LEFT JOIN glpi_entities
                        ON (glpi_entities.id=glpi_computers.entities_id)
                WHERE
                    glpi_computers.id = glpi_plugin_room_rooms_computers.computers_id
                    AND glpi_plugin_room_rooms_computers.rooms_id = {$room_id}
EOS;

            $query .= ' ORDER BY glpi_entities.completename, glpi_computers.name';

            echo '<form method="post" name="document_form" id="document_form" ' .
                'action="' . $CFG_GLPI['root_doc'] . '/plugins/room/front/room.form.php">';

            echo '<br><br><div class="center"><table class="tab_cadre_fixe">';
            echo '<tr><th colspan="' . ($canedit ? 3 : 2) . '">' . __('Associated items') . ':</th></tr><tr>';
            if ($canedit) {
                echo '<th>&nbsp;</th>';
            }
            echo '<th>' . __('Name') . '</th>';
            echo '<th>' . __('Select the desired entity') . '</th>';
            echo '</tr>';

            if ($result_linked = $DB->query($query)) {
                if ($DB->numrows($result_linked)) {
                    while ($data = $DB->fetch_assoc($result_linked)) {
                        $ID = '';

                        if ($_SESSION['glpiis_ids_visible'] || empty($data['name'])) {
                            $ID = ' (' . $data['id'] . ')';
                        }
                        $name =
                            '<a href="' . $CFG_GLPI['root_doc'] . '/front/computer.form.php?id=' . $data['id'] . '">'
                            . $data['name'] . $ID . '</a>';

                        echo '<tr class="tab_bg_1">';

                        if ($canedit) {
                            echo '<td width="10">';
                            $sel = '';
                            if (isset($_GET['select']) && $_GET['select'] == 'all') {
                                $sel = 'checked';
                            }
                            echo '<input type="checkbox" name="item[' . $data['idd'] . ']" value="1" ' . $sel . '>';
                            echo '</td>';
                        }

                        echo
                            '<td' . (isset($data['is_deleted']) && $data['is_deleted'] ? ' class="tab_bg_2_2"' : '') . '>'
                            . $name
                            . '</td>';
                        echo
                            '<td class="center">'
                            . Dropdown::getDropdownName('glpi_entities', $data['entity'])
                            . '</td>';

                        echo '</tr>';
                    }
                }
            }

            if ($canedit) {
                echo '<tr class="tab_bg_1"><td colspan="2" class="center">';

                echo '<input type="hidden" name="room_id" value="' . $room_id . '">';
                Dropdown::show('Computer');
                echo '</td>';
                echo '<td class="center">';
                echo '<input type="submit" name="additem" value="' . __('Add') . '" class="submit">';
                echo '</td></tr>';
                echo '</table></div>';

                echo '<div class="center">';
                echo '<table width="950px" align="center">';
                echo '<tr>';
                echo '<td><img src="' . $CFG_GLPI['root_doc'] . '/pics/arrow-left.png" alt=""></td>';
                echo '<td class="center">'
                    . '<a '
                    . 'onclick="if (markCheckboxes(\'document_form\')) return false;" '
                    . 'href="' . $_SERVER['PHP_SELF'] . '?ID=' . $room_id . '&amp;select=all">'
                    . __('Check All') . '</a>';
                echo '</td>';

                echo '<td>/</td>';
                echo '<td class="center">'
                    . '<a '
                    . 'onclick="if (unMarkCheckboxes(\'document_form\')) return false;" '
                    . 'href="' . $_SERVER['PHP_SELF'] . '?ID=' . $room_id . '&amp;select=none">'
                    . __('Uncheck All') . '</a>';
                echo '</td>';
                echo '<td align="left" width="80%">';
                echo '<input type="submit" name="deleteitem" value="' . __('To delete') . '" class="submit">';
                echo '</td>';
                echo '</table></div>';
            } else {
                echo '</table></div>';
            }

            Html::closeForm();
        }
    }

    // cette fonction sert à remplir la rubrique room de l'onglet ajouté à la fiche ordinateur
    public function plugin_room_showComputerRoom($itemtype, $ID, $withtemplate = '')
    {
        global $DB, $LANG, $CFG_GLPI;

        $item = new $itemtype();
        $canread = $item->can($ID, READ);
        $canedit = $item->can($ID, UPDATE);

        $Room = new self();

        if ($ID > 0) {
            $query = <<<EOS
                SELECT
                    `glpi_plugin_room_rooms`.*,
                    u.`id` as resp_id,
                    CONCAT(u.`firstname` , " ", u.`realname`) as resp
                FROM
                    `glpi_plugin_room_rooms_computers`
                    LEFT JOIN `glpi_plugin_room_rooms`
                        ON (`glpi_plugin_room_rooms`.`id` = `glpi_plugin_room_rooms_computers`.`rooms_id`)
                    LEFT JOIN `glpi_users` as u
                        ON u.`id` = `glpi_plugin_room_rooms`.`tech_num`
                 WHERE
                    `computers_id` = {$ID}
EOS;
            $result = $DB->query($query);
            $number = $DB->numrows($result);
            if (Session::isMultiEntitiesMode()) {
                $colsup = 1;
            } else {
                $colsup = 0;
            }
            echo '<div align="center"><table class="tab_cadre_fixe">';
            echo '<tr><th colspan="' . (1 + $colsup) . '">' . $LANG['plugin_room'][20] . '</th></tr>';
            echo '<th>' . $LANG['plugin_room'][19] . '</th>';
            echo '<th>' . $LANG['plugin_room'][10] . '</th></tr>';
            echo '<th>';
            if ($result = $DB->query($query)) {
                if ($DB->numrows($result) > 0) {
                    $data = $DB->fetch_assoc($result);

                    if (self::canView()) {
                        echo '<a '
                            . 'href="' . $CFG_GLPI['root_doc'] . '/plugins/room/front/room.form.php?id=' . $data['id'] . '">'
                            . $data['name'] . '</a>';
                        echo '</th>';
                        echo '<th>'
                            . '<a href="' . $CFG_GLPI['root_doc'] . '/front/user.form.php?id=' . $data['resp_id'] . '">'
                            . $data['resp'] . '</a></th>';
                    } else {
                        echo $data['name'];
                    }
                    echo '</div>';
                }
            }
            echo '</tr></table>';
        }
    }

    public function plugin_room_AddDevice($room_id, $computer_id)
    {
        global $DB;
        if ($room_id > 0 && $computer_id > 0) {
            $query = 'SELECT ID FROM glpi_plugin_room_rooms_computers WHERE computers_id = ' . $computer_id;
            if ($result = $DB->query($query)) {
                if ($DB->numrows($result) == 0) {
                    $query = <<<EOS
                        INSERT INTO glpi_plugin_room_rooms_computers
                            (rooms_id, computers_id)
                        VALUES
                            ({$room_id}, {$computer_id})
EOS;
                    $result = $DB->query($query);
                    $this->plugin_room_updateCountDevices($room_id);
                }
            }
        }
    }

    public function plugin_room_DeleteDevice($ID)
    {
        global $DB;
        $query = 'SELECT rooms_id FROM glpi_plugin_room_rooms_computers WHERE ID = ' . $ID;
        if ($result = $DB->query($query)) {
            $IDroom = $DB->result($result, 0, 0);
            $query = 'DELETE FROM glpi_plugin_room_rooms_computers WHERE ID = ' . $ID;
            $result = $DB->query($query);
            $this->plugin_room_updateCountDevices($IDroom);
        }
    }

    public function plugin_room_updateCountDevices($ID)
    {
        global $DB;
        $query = 'SELECT count(ID) FROM glpi_plugin_room_rooms_computers WHERE rooms_id = ' . $ID;
        if ($result = $DB->query($query)) {
            $query2 = <<<EOS
                UPDATE
                    glpi_plugin_room_rooms
                SET
                    count_linked = {$DB->result($result, 0, 0)}
                WHERE ID = {$ID}
EOS;
            $DB->query($query2);
        }
    }
}
