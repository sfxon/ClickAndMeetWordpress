<?php

namespace mvclickandmeet_namespace;

class mvAppointmentStatus extends mvWpModel {
		public function __construct() {
				$data = array(
						//Table and module definitions.
						'module_title' => 'ClickAndMeet - Termin-Status',
						'table_name' => 'aloha_appointment_status',
						'db_version' => '1.0',
						'id_field' => 'id',
						
						//Data fields for the model.
						'fields' => array(
								parent::getFieldArray($name = 'id', $type = 'INT', $length = 11, $default_value = "", $auto_increment = true),
								parent::getFieldArray($name = 'title', $type = 'VARCHAR', $length = 256, $default_value = ''),
								parent::getFieldArray($name = 'description', $type = 'VARCHAR', $length = 2048, $default_value = ''),
								parent::getFieldArray($name = 'internal_required', $type = 'INT', $length = 1, $default_value = 0),
						)
				);
			
				parent::__construct($data);
		}
		
		/////////////////////////////////////////////////////////////////////////////////////
		// Erweiterte Datenbank-Installation Queries durchführen.
		/////////////////////////////////////////////////////////////////////////////////////
		public function installDB() {
				global $wpdb;
				
				$sql = 'INSERT INTO `aloha_appointment_status` (`id`, `title`, `description`, `internal_required`) VALUES
						(1,	\'Offen\',	\'Termin wurde noch nicht gebucht.\',	1),
						(2,	\'Gebucht\',	\'Termin wurde gebucht.\',	1),
						(3,	\'Storniert\',	\'Termin wurde vom Kunden storniert.\',	1),
						(4,	\'Eingecheckt\',	\'Besucher befindet sich im Haus, ist eingecheckt.\',	1),
						(5,	\'Abgeschlossen\',	\'Termin hat bereits stattgefunden. Besucher wurde eingecheckt und ausgecheckt.\',	1);';
				$wpdb->query($sql);
		}
		
		/////////////////////////////////////////////////////////////////////////////////////
		// Datenbank-Updates durchführen.
		/////////////////////////////////////////////////////////////////////////////////////
		public function updateDB() {
				$option_string = __NAMESPACE__ . '-db-version-' . $this->data['table_name'];
				$current_version = get_option($option_string);
				
				//Update 1 - 2019-11-19 - Added contact column
				if($current_version >= 0.0 && $current_version < 1.0) {
						$option_string = __NAMESPACE__ . '-db-version-' . $this->data['table_name'];
						update_option($option_string, 1.0, false);
						$current_version = 1.0;
				}
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////////////
		// Load Customer groups data by id.
		///////////////////////////////////////////////////////////////////////////////////////////////////
		public static function loadById($id) {
				$retval = array();
				
				$db = mv_core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery('SELECT * FROM ' . $db->table('cmappointmentstatus') . ' WHERE id = :id LIMIT 1;');
				$db->bind(':id', (int)$id);
				$result = $db->execute();
				
				$data = $result->fetchArrayAssoc();
				
				if(empty($data)) {
						return NULL;
				}
		
				return $data;
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////////////
		// Alle Einträge laden
		///////////////////////////////////////////////////////////////////////////////////////////////////
		public static function loadList() {
				$db = mv_core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery(
						'SELECT * ' .
						'FROM ' . $db->table('appointment_status') . ' ' .
						'ORDER BY title;'
				);
				$result = $db->execute();
				
				$retval = array();
				
				while($result->next()) {
						$tmp = $result->fetchArrayAssoc();
						$retval[] = $tmp;
				}
				
				return $retval;
				
		}
		
		/////////////////////////////////////////////////////////////////////////////////
		// Create data in database.
		/////////////////////////////////////////////////////////////////////////////////
		public static function createInDB($data) {
				$db = mv_core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery(
						'INSERT INTO ' . $db->table('cmappointmentstatus') . ' ' .
            		'(title,description,internal_required) ' .
								'VALUES' .
                '(:title,:description,:internal_required) '
				);
				$db->bind(':title', $data['title']);
				$db->bind(':description', $data['description']);
				$db->bind(':internal_required', $data['internal_required']);
				$db->execute();
				
				return $db->insertId();
		}
		
		/////////////////////////////////////////////////////////////////////////////////
		// Update data in database.
		/////////////////////////////////////////////////////////////////////////////////
		public static function updateInDB($id, $data) {
				$db = mv_core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery(
						'UPDATE ' . $db->table('cmappointmentstatus') . ' SET ' .
								'title = :title, description = :description, internal_required = :internal_required ' .
						'WHERE ' .
								'id = :id'
				);
				                    		$db->bind(':title', $data['title']);
                                		$db->bind(':description', $data['description']);
                                		$db->bind(':internal_required', $data['internal_required']);
                    				$db->bind(':id', (int)$id);
				$db->execute();
		}
		
		/////////////////////////////////////////////////////////////////////////////////
		// Delete data in database.
		/////////////////////////////////////////////////////////////////////////////////
		public static function deleteById($id) {
				$db = mv_core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery(
						'DELETE FROM ' . $db->table('cmappointmentstatus') . ' ' .
						'WHERE ' .
								'id = :id'
				);
				$db->bind(':id', (int)$id);
				$db->execute();
		}
		
		//////////////////////////////////////////////////////////////////////
		// Indexierte Liste laden.
		//////////////////////////////////////////////////////////////////////
		public function loadIndexedList() {
				$data = $this->loadList();
				
				$retval = array();
				
				foreach($data as $d) {
						$retval[$d['id']] = $d;
				}
				
				return $retval;
		}
}
