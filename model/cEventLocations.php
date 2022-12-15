<?php

namespace mvclickandmeet_namespace;

class mvEventLocations extends mvWpModel {
		public function __construct() {
				$data = array(
						//Table and module definitions.
						'module_title' => 'ClickAndMeet - Event-Location (Laden, Betriebsstätte, Geschäft, ...)',
						'table_name' => 'aloha_event_locations',
						'db_version' => '1.0',
						'id_field' => 'id',
						
						//Data fields for the model.
						'fields' => array(
								parent::getFieldArray($name = 'id', $type = 'INT', $length = 11, $default_value = "", $auto_increment = true),
								parent::getFieldArray($name = 'title', $type = 'VARCHAR', $length = 2048, $default_value = ''),
								parent::getFieldArray($name = 'description', $type = 'VARCHAR', $length = 2048, $default_value = ''),
								parent::getFieldArray($name = 'user_id', $type = 'INT', $length = 11, $default_value = 0),
								parent::getFieldArray($name = 'email_address', $type = 'VARCHAR', $length = 128, $default_value = ''),
								parent::getFieldArray($name = 'booking_info_by_mail', $type = 'INT', $length = 1, $default_value = 1),
								
						)
				);
			
				parent::__construct($data);
		}
		
		/////////////////////////////////////////////////////////////////////////////////////
		// Erweiterte Datenbank-Installation Queries durchführen.
		/////////////////////////////////////////////////////////////////////////////////////
		public function installDB() {
				global $wpdb;
				
				$sql = 'INSERT INTO `aloha_event_locations` (`id`, `title`, `description`, `user_id`, `email_address`, `booking_info_by_mail`) VALUES
						(1,	\'Hornberg Möbel, Essen\',	\'Unser Ladengeschäft in Essen. Straße und Anschrift, ...\', 0, \'clickandmeetwp-demo@mindfav.com\', 1);';
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
		// Alle Einträge laden
		///////////////////////////////////////////////////////////////////////////////////////////////////
		public static function loadListByUserId($user_id) {
				$db = core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery(
						'SELECT * ' .
						'FROM ' . $db->table('event_locations') . ' ' .
						'WHERE user_id = :user_id ' .
						'ORDER BY title;'
				);
				$db->bind('user_id', $user_id);
				$result = $db->execute();
				
				$retval = array();
				
				while($result->next()) {
						$tmp = $result->fetchArrayAssoc();
						$retval[] = $tmp;
				}
				
				return $retval;
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
		
		///////////////////////////////////////////////////////////////////////////////////////////////////
		// Load Customer groups data by id.
		///////////////////////////////////////////////////////////////////////////////////////////////////
		public static function loadById($id) {
				$retval = array();
				
				$db = core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery('SELECT * FROM ' . $db->table('event_locations') . ' WHERE id = :id LIMIT 1;');
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
				$db = core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery(
						'SELECT * ' .
						'FROM ' . $db->table('event_locations') . ' ' .
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
				$db = core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery(
						'INSERT INTO ' . $db->table('event_locations') . ' ' .
            		'(title,description,user_id,email_address,booking_info_by_mail) ' .
								'VALUES' .
                '(:title,:description,:user_id,:email_address,:booking_info_by_mail) '
				);
                		                		            		$db->bind(':title', $data['title']);
                            		            		$db->bind(':description', $data['description']);
                            		            		$db->bind(':user_id', $data['user_id']);
                            		            		$db->bind(':email_address', $data['email_address']);
                            		            		$db->bind(':booking_info_by_mail', $data['booking_info_by_mail']);
                    				$db->execute();
				
				return $db->insertId();
		}
		
		/////////////////////////////////////////////////////////////////////////////////
		// Update data in database.
		/////////////////////////////////////////////////////////////////////////////////
		public static function updateInDB($id, $data) {
				$db = core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery(
						'UPDATE ' . $db->table('event_locations') . ' SET ' .
								'title = :title, description = :description, user_id = :user_id, email_address = :email_address, booking_info_by_mail = :booking_info_by_mail ' .
						'WHERE ' .
								'id = :id'
				);
				                    		$db->bind(':title', $data['title']);
                                		$db->bind(':description', $data['description']);
                                		$db->bind(':user_id', $data['user_id']);
                                		$db->bind(':email_address', $data['email_address']);
                                		$db->bind(':booking_info_by_mail', $data['booking_info_by_mail']);
                    				$db->bind(':id', (int)$id);
				$db->execute();
		}
		
		/////////////////////////////////////////////////////////////////////////////////
		// Delete data in database.
		/////////////////////////////////////////////////////////////////////////////////
		public static function deleteById($id) {
				$db = core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery(
						'DELETE FROM ' . $db->table('event_locations') . ' ' .
						'WHERE ' .
								'id = :id'
				);
				$db->bind(':id', (int)$id);
				$db->execute();
		}
}
