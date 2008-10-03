<?php
/*
  ----------------------------------------------------------------------
  GLPI - Gestionnaire Libre de Parc Informatique
  Copyright (C) 2003-2008 by the INDEPNET Development Team.
  
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

class PluginRoom  extends CommonDBTM {

	function PluginRoom () {
		$this->table="glpi_plugin_room";
		$this->type=PLUGIN_ROOM_TYPE;
		$this->dohistory=true;
		$this->entity_assign=true;
		$this->may_be_recursive=true;
	}	

	function prepareInputForUpdate($input) {
		// Backup initial values
		if (isset($input['buy']) && empty($input['buy'])) {
			$input['buy']="NULL";
		}

		return $input;
	}

	function prepareInputForAdd($input) {
		// Backup initial values
		if (isset($input['buy']) && empty($input['buy'])) {
			unset($input['buy']);
		}

		return $input;
	}

    function cleanDBonPurge($ID) {
                global $DB,$CFG_GLPI;
                $query = "DELETE FROM glpi_plugin_room_computer WHERE (FK_rooms = '$ID')";
                $result = $DB->query($query);

	}

	function defineTabs($withtemplate){
		global $LANG,$CFG_GLPI;

		$ong[1]=$LANG["title"][26];

		if (haveRight("reservation_central","r")){
			$ong[11]=$LANG["Menu"][17];
		}
				
		return $ong;
	}

	function showForm($target,$ID,$withtemplate=''){
		global $CFG_GLPI, $LANG;

		if (!haveTypeRight(PLUGIN_ROOM_TYPE,"r")) return false;

		$spotted=false;
		$use_cache=true;
		if ($ID>0) {
			
			if($this->can($ID,'r')) {
				$spotted = true;	
			}
		} else {
			$use_cache=false;
			if ($this->can(-1,'w')){
				$spotted = true;	
				$this->getEmpty();
			}
		} 
		if ($spotted){
			$canedit=$this->can($ID,'w');


			$this->showTabs($ID, $withtemplate,$_SESSION['glpi_onglet']);

			if ($canedit) {
				echo "<form name='form' method='post' action=\"$target\" enctype=\"multipart/form-data\">";
				if (empty($ID)||$ID<0){
					echo "<input type='hidden' name='FK_entities' value='".$_SESSION["glpiactive_entity"]."'>";
				}
			}
			echo "<div class='center' id='tabsbody'><table class='tab_cadre_fixe'>";
			echo "<tr>";
			if ($ID>0) {
				echo "<th colspan='2'>";
				echo $LANG["common"][2]." $ID";
				if (isMultiEntitiesMode()){
					echo "&nbsp;(".getDropdownName("glpi_entities",$this->fields["FK_entities"]).")";
				}
				echo "</th>";
				echo "<th>".$LANG["common"][26].": ".convDateTime($this->fields["date_mod"])."</th>";
				echo "<th>";
				if (isMultiEntitiesMode()){
					echo $LANG["entity"][9].":&nbsp;";
				
					if ($this->can($ID,'recursive')) {
						dropdownYesNo("recursive",$this->fields["recursive"]);					
					} else {
						echo getYesNo($this->fields["recursive"]);
					}
				} else {
					echo "&nbsp;";
				}
				echo "</th>";
			} else {
				echo "<th colspan='2'>";
				echo $LANG['plugin_room'][3];
				if (isMultiEntitiesMode()){
					echo "&nbsp;(".getDropdownName("glpi_entities",$this->fields["FK_entities"]).")";
				}
				echo "</th>";
				echo "<th colspan='2'>";
				if (isMultiEntitiesMode()){
					echo $LANG["entity"][9].":&nbsp;";
				
					if ($this->can($ID,'recursive')) {
						dropdownYesNo("recursive",$this->fields["recursive"]);					
					} else {
						echo getYesNo($this->fields["recursive"]);
					}
				} else {
					echo "&nbsp;";
				}
				echo "</th>";
			} 
			echo "</tr>";
			if (!$use_cache||!($CFG_GLPI["cache"]->start($ID."_".$_SESSION["glpilanguage"],"GLPI_".$this->type))) {
				echo "<tr class='tab_bg_1'><td>".$LANG["common"][16].":		</td>";
				echo "<td colspan='3'>";
				autocompletionTextField("name","glpi_plugin_room","name",$this->fields["name"],80,$this->fields["FK_entities"]);
				echo "</td></tr>";

				echo "<tr class='tab_bg_1'><td>".$LANG["common"][17].":		</td>";
				echo "<td>";
				dropdownValue("glpi_dropdown_plugin_room_type","type",$this->fields["type"]);
				echo "</td>";

				echo "<td>".$LANG['plugin_room'][5].":		</td>";
				echo "<td>";
				dropdownValue("glpi_dropdown_plugin_room_access","access",$this->fields["access"]);
				echo "</td></tr>";

				echo "<tr class='tab_bg_1'><td>".$LANG["common"][18].":		</td>";
				echo "<td>";
				dropdownUsersID("FK_users",$this->fields["FK_users"],"all",1,$this->fields["FK_entities"]);
				echo "</td>";

				echo "<td>".$LANG["common"][10].":		</td>";
				echo "<td>";
				dropdownUsersID("tech_num",$this->fields["tech_num"],"interface",1,$this->fields["FK_entities"]);
				echo "</td></tr>";

				echo "<tr class='tab_bg_1'><td>".$LANG['plugin_room'][4].":		</td>";
				echo "<td>";
				dropdownInteger("size",$this->fields["size"],0,500);
				echo "</td><td colspan='2'>&nbsp;</td></tr>";

				echo "<tr class='tab_bg_1'><td>".$LANG["financial"][14].":		</td>";
				echo "<td>";
				showDateFormItem("buy",$this->fields["buy"],true,$canedit);
				echo "</td>";

				echo "<td>".$LANG['plugin_room'][6].":		</td>";
				echo "<td>";
				dropdownYesNo("printer",$this->fields["printer"]);
				echo "</td></tr>";

				echo "<tr class='tab_bg_1'><td>".$LANG['plugin_room'][7].":		</td>";
				echo "<td>";
				dropdownYesNo("videoprojector",$this->fields["videoprojector"]);
				echo "</td>";

				echo "<td>".$LANG['plugin_room'][8].":		</td>";
				echo "<td>";
				dropdownYesNo("wifi",$this->fields["wifi"]);
				echo "</td></tr>";

				echo "<tr class='tab_bg_1'><td>".$LANG['plugin_room'][13].":		</td>";
				echo "<td>";
				autocompletionTextField("text1","glpi_plugin_room","text1",$this->fields["text1"],50,$this->fields["FK_entities"]);
				echo "</td>";

				echo "<td>".$LANG['plugin_room'][15].":		</td>";
				echo "<td>";
				dropdownValue("glpi_dropdown_plugin_room_dropdown1","dropdown1",$this->fields["dropdown1"]);
				echo "</td></tr>";
				
				echo "<tr class='tab_bg_1'><td>".$LANG['plugin_room'][14].":		</td>";
				echo "<td>";
				autocompletionTextField("text2","glpi_plugin_room","text2",$this->fields["text2"],50,$this->fields["FK_entities"]);
				echo "</td>";

				echo "<td>".$LANG['plugin_room'][16].":		</td>";
				echo "<td>";
				dropdownValue("glpi_dropdown_plugin_room_dropdown2","dropdown2",$this->fields["dropdown2"]);
				echo "</td></tr>";

				echo "<tr class='tab_bg_1'><td>".$LANG['plugin_room'][11].":		</td>";
				echo "<td colspan='3'>";
				autocompletionTextField("opening","glpi_plugin_room","opening",$this->fields["opening"],80,$this->fields["FK_entities"]);
				echo "</td></tr>";

				echo "<tr class='tab_bg_1'><td>".$LANG['plugin_room'][12].":		</td>";
				echo "<td colspan='3'>";
				autocompletionTextField("limits","glpi_plugin_room","limits",$this->fields["limits"],80,$this->fields["FK_entities"]);
				echo "</td></tr>";

				echo "<tr>";
				echo "<td class='tab_bg_1' valign='top'>";
	
				// table commentaires
				echo $LANG["common"][25].":</td>";
				echo "<td colspan='3'  class='tab_bg_1'><textarea cols='70' rows='4' name='comments' >".$this->fields["comments"]."</textarea>";
	
				echo "</td>";
				echo "</tr>";
				if ($use_cache){
					$CFG_GLPI["cache"]->end();
				}
			}

			if ($canedit){
				echo "<tr>";

				if ($ID>0) {

					echo "<td class='tab_bg_2' valign='top' colspan='2'>";
					echo "<input type='hidden' name='ID' value=\"$ID\">\n";
					echo "<div class='center'><input type='submit' name='update' value=\"".$LANG["buttons"][7]."\" class='submit'></div>";
					echo "</td>\n\n";
		
					echo "<td class='tab_bg_2' valign='top'  colspan='2'>\n";
					echo "<input type='hidden' name='ID' value=\"$ID\">\n";
					if (!$this->fields["deleted"])
						echo "<div class='center'><input type='submit' name='delete' value=\"".$LANG["buttons"][6]."\" class='submit'></div>";
					else {
						echo "<div class='center'><input type='submit' name='restore' value=\"".$LANG["buttons"][21]."\" class='submit'>";
		
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='purge' value=\"".$LANG["buttons"][22]."\" class='submit'></div>";
					}
		
					echo "</td></tr>";
				} else {

					echo "<td class='tab_bg_2' valign='top' colspan='4'>";
					echo "<div class='center'><input type='submit' name='add' value=\"".$LANG["buttons"][8]."\" class='submit'></div>";
					echo "</td></tr>";
		
				}
				echo "</table></div></form>";
				
			} else { //  can't edit
				echo "</table></div>";
			} 

			echo "<div id='tabcontent'></div>";
			echo "<script type='text/javascript'>loadDefaultTab();</script>";

			return true;
		}
		return false;

	}
	function showComputers($target,$rID){
		global $CFG_GLPI, $LANG,$DB;
		if (!haveTypeRight(PLUGIN_ROOM_TYPE,"r")) return false;

		if ($this->getFromDB($rID)){
			$canedit=$this->can($rID,'w');
	
			$query = "SELECT glpi_computers.*, glpi_plugin_room_computer.ID AS IDD, glpi_entities.ID AS entity "
				." FROM glpi_plugin_room_computer, glpi_computers "
				." LEFT JOIN glpi_entities ON (glpi_entities.ID=glpi_computers.FK_entities) "
				." WHERE glpi_computers.ID = glpi_plugin_room_computer.FK_computers AND glpi_plugin_room_computer.FK_rooms = '$rID' "; 

			$query.=" ORDER BY glpi_entities.completename, glpi_computers.name";

			echo "<form method='post' name='document_form' id='document_form'  action=\"".$CFG_GLPI["root_doc"]."/plugins/room/room.form.php\">";
		
			echo "<br><br><div class='center'><table class='tab_cadre_fixe'>";
			echo "<tr><th colspan='".($canedit?3:2)."'>".$LANG["document"][19].":</th></tr><tr>";
			if ($canedit) {
				echo "<th>&nbsp;</th>";
			}
			echo "<th>".$LANG["common"][16]."</th>";
			echo "<th>".$LANG["entity"][0]."</th>";
			echo "</tr>";
					
			if ($result_linked=$DB->query($query)){
				if ($DB->numrows($result_linked)){
					while ($data=$DB->fetch_assoc($result_linked)){
						$ID="";
								
						if($CFG_GLPI["view_ID"]||empty($data["name"])){
							$ID= " (".$data["ID"].")";
						}
						$name= "<a href=\"".$CFG_GLPI["root_doc"]."/front/computer.form.php?ID=".$data["ID"]."\">".$data["name"]."$ID</a>";
		
						echo "<tr class='tab_bg_1'>";
	
						if ($canedit){
							echo "<td width='10'>";
							$sel="";
							if (isset($_GET["select"])&&$_GET["select"]=="all") $sel="checked";
							echo "<input type='checkbox' name='item[".$data["IDD"]."]' value='1' $sel>";
							echo "</td>";
						}
								
						echo "<td ".(isset($data['deleted'])&&$data['deleted']?"class='tab_bg_2_2'":"").">".$name."</td>";
						echo "<td class='center'>".getDropdownName("glpi_entities",$data['entity'])."</td>";
								
						echo "</tr>";
					}
				}
			}
		
			if ($canedit)	{
				echo "<tr class='tab_bg_1'><td colspan='2' class='center'>";
		
				echo "<input type='hidden' name='rID' value='$rID'>";
	
				dropdownValue("glpi_computers","cID",'',1,$this->fields['FK_entities']);
				
				echo "</td>";
				echo "<td class='center'>";
				echo "<input type='submit' name='additem' value=\"".$LANG["buttons"][8]."\" class='submit'>";
				echo "</td></tr>";
				echo "</table></div>" ;
				
				echo "<div class='center'>";
				echo "<table width='950px' align='center'>";
				echo "<tr><td><img src=\"".$CFG_GLPI["root_doc"]."/pics/arrow-left.png\" alt=''></td><td class='center'><a onclick= \"if ( markAllRows('document_form') ) return false;\" href='".$_SERVER['PHP_SELF']."?ID=$rID&amp;select=all'>".$LANG["buttons"][18]."</a></td>";
			
				echo "<td>/</td><td class='center'><a onclick= \"if ( unMarkAllRows('document_form') ) return false;\" href='".$_SERVER['PHP_SELF']."?ID=$rID&amp;select=none'>".$LANG["buttons"][19]."</a>";
				echo "</td><td align='left' width='80%'>";
				echo "<input type='submit' name='deleteitem' value=\"".$LANG["buttons"][6]."\" class='submit'>";
				echo "</td>";
				echo "</table>";
			
				echo "</div>";
	
	
			}else{
		
				echo "</table></div>"    ;
			}
			echo "</form>";
		}
		

	}

}
?>
