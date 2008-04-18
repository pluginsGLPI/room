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
	}	
	function showForm($target,$ID,$withtemplate=''){
		global $CFG_GLPI, $LANG,$LANGROOM;

		if (!haveTypeRight(PLUGIN_ROOM_TYPE,"r")) return false;

		$spotted=false;
		if ($ID>0) {
			
			if($this->can($ID,'r')) {
				$spotted = true;	
			}
		} else {
			if ($this->can(-1,'w')){
				$spotted = true;	
			}
		} 
		if ($spotted){
			$canedit=$this->can($ID,'w');
		}

	}
	function showComputers($target,$ID){
		global $CFG_GLPI, $LANG,$LANGROOM;
		if (!haveTypeRight(PLUGIN_ROOM_TYPE,"r")) return false;


	}

}
?>