<?php

namespace mvclickandmeet_namespace;

class mvUserUnit extends mvWpModel {
		public function __construct() {
				$data = array(
						//Table and module definitions.
						'module_title' => 'ClickAndMeet - Abteilung/Team/Mitarbeiter',
						'table_name' => 'aloha_user_unit',
						'db_version' => '1.1',
						'id_field' => 'id',
						
						//Data fields for the model.
						'fields' => array(
								parent::getFieldArray($name = 'id', $type = 'INT', $length = 11, $default_value = "", $auto_increment = true),
								parent::getFieldArray($name = 'title', $type = 'VARCHAR', $length = 2048, $default_value = ''),
								parent::getFieldArray($name = 'email_address', $type = 'VARCHAR', $length = 128, $default_value = ''),
								parent::getFieldArray($name = 'status', $type = 'INT', $length = 11, $default_value = 1),
								parent::getFieldArray($name = 'event_location_id', $type = 'INT', $length = 11, $default_value = 0),
								parent::getFieldArray($name = 'booking_info_by_mail', $type = 'INT', $length = 1, $default_value = 1),
								parent::getFieldArray($name = 'custom_email_html', $type = 'TEXT', $length = '', $default_value = ''),
								parent::getFieldArray($name = 'custom_email_plain', $type = 'TEXT', $length = '', $default_value = '')
						)
				);
			
				parent::__construct($data);
		}
		
		/////////////////////////////////////////////////////////////////////////////////////
		// Erweiterte Datenbank-Installation Queries durchführen.
		/////////////////////////////////////////////////////////////////////////////////////
		public function installDB() {
				global $wpdb;
				
				$sql = 'INSERT INTO `aloha_user_unit` (`id`, `title`, `email_address`, `status`, `event_location_id`, `booking_info_by_mail`) VALUES
						(1,	\'Küchenmöbel\',	\'clickandmeetwp-demo-team-kueche@mindfav.com\', 1, 1, 0);';
				$wpdb->query($sql);
		}
		
		/////////////////////////////////////////////////////////////////////////////////////
		// Datenbank-Updates durchführen.
		/////////////////////////////////////////////////////////////////////////////////////
		public function updateDB() {
				global $wpdb;
				
				$option_string = __NAMESPACE__ . '-db-version-' . $this->data['table_name'];
				$current_version = get_option($option_string);
				
				//Update 1 - 2019-11-19 - Erstinstallation
				if($current_version >= 0.0 && $current_version < 1.0) {
						$option_string = __NAMESPACE__ . '-db-version-' . $this->data['table_name'];
						update_option($option_string, 1.0, false);
						$current_version = 1.0;
				}
				
				//Update 1 - 2021-03-13 - Added custom mails.
				if($current_version >= 1.0 && $current_version < 1.1) {
						$sql = 
								'ALTER TABLE `aloha_user_unit` ' .
										'ADD `custom_email_html` TEXT NULL, ' .
										'ADD `custom_email_plain` TEXT NULL;';
						$wpdb->query($sql);
						
						$option_string = __NAMESPACE__ . '-db-version-' . $this->data['table_name'];
						update_option($option_string, 1.1, false);
						$current_version = 1.1;
				}
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////////////
		// Load Customer groups data by id.
		///////////////////////////////////////////////////////////////////////////////////////////////////
		public static function loadById($id) {
				$retval = array();
				
				$db = mv_core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery('SELECT * FROM ' . $db->table('user_unit') . ' WHERE id = :id LIMIT 1;');
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
						'FROM ' . $db->table('user_unit') . ' ' .
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
						'INSERT INTO ' . $db->table('user_unit') . ' ' .
            		'(title,email_address,status,event_location_id,booking_info_by_mail) ' .
								'VALUES' .
                '(:title,:email_address,:status,:event_location_id,:booking_info_by_mail) '
				);
				$db->bind(':title', $data['title']);
				$db->bind(':email_address', $data['email_address']);
				$db->bind(':status', $data['status']);
				$db->bind(':event_location_id', $data['event_location_id']);
				$db->bind(':booking_info_by_mail', $data['booking_info_by_mail']);
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
						'UPDATE ' . $db->table('user_unit') . ' SET ' .
								'title = :title, email_address = :email_address, status = :status, event_location_id = :event_location_id, booking_info_by_mail = :booking_info_by_mail ' .
						'WHERE ' .
								'id = :id'
				);
				$db->bind(':title', $data['title']);
				$db->bind(':email_address', $data['email_address']);
				$db->bind(':status', $data['status']);
				$db->bind(':event_location_id', $data['event_location_id']);
				$db->bind(':booking_info_by_mail', $data['booking_info_by_mail']);
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
						'DELETE FROM ' . $db->table('user_unit') . ' ' .
						'WHERE ' .
								'id = :id'
				);
				$db->bind(':id', (int)$id);
				$db->execute();
		}
		
		//////////////////////////////////////////////////////////////////////
		// Indexierte Liste laden.
		//////////////////////////////////////////////////////////////////////
		function loadIndexedList() {
				$data = $this->loadList();
				
				$retval = array();
				
				foreach($data as $d) {
						$retval[$d['id']] = $d;
				}
				
				return $retval;
		}
}
