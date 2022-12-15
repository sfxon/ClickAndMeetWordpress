<?php

namespace mvclickandmeet_namespace;

class mvAppointment extends mvWpModel {
		var $frontendFieldsString = 'id, event_location_id, user_unit_id, title, datetime_of_event, status, duration_in_minutes';
		
		public function __construct() {
				$data = array(
						//Table and module definitions.
						'module_title' => 'ClickAndMeet - Termine',
						'table_name' => 'aloha_appointment',
						'db_version' => '1.2',
						'id_field' => 'id',
						
						//Data fields for the model.
						'fields' => array(
								parent::getFieldArray($name = 'id', $type = 'INT', $length = 11, $default_value = "", $auto_increment = true),
								parent::getFieldArray($name = 'title', $type = 'VARCHAR', $length = 256, $default_value = ''),
								parent::getFieldArray($name = 'datetime_of_event', $type = 'DATETIME', $length = '', $default_value = NULL),
								parent::getFieldArray($name = 'event_location_id', $type = 'INT', $length = 11, $default_value = 0),
								parent::getFieldArray($name = 'status', $type = 'INT', $length = 11, $default_value = 0),
								parent::getFieldArray($name = 'created_by', $type = 'INT', $length = 11, $default_value = 0),
								parent::getFieldArray($name = 'datetime_checkin', $type = 'DATETIME', $length = '', $default_value = NULL),
								parent::getFieldArray($name = 'datetime_checkout', $type = 'DATETIME', $length = '', $default_value = NULL),
								parent::getFieldArray($name = 'visitor_user_id', $type = 'INT', $length = 11, $default_value = 0),
								parent::getFieldArray($name = 'checkin_by', $type = 'INT', $length = 11, $default_value = 0),
								parent::getFieldArray($name = 'checkout_by', $type = 'INT', $length = 11, $default_value = 0),
								parent::getFieldArray($name = 'comment_checkin', $type = 'VARCHAR', $length = 2048, $default_value = ''),
								parent::getFieldArray($name = 'comment_checkout', $type = 'VARCHAR', $length = 2048, $default_value = ''),
								parent::getFieldArray($name = 'comment_visitor_booking', $type = 'VARCHAR', $length = 2048, $default_value = ''),
								parent::getFieldArray($name = 'reminder_user_mail', $type = 'VARCHAR', $length = 256, $default_value = ''),
								parent::getFieldArray($name = 'reminder_active', $type = 'INT', $length = 1, $default_value = 0),
								parent::getFieldArray($name = 'reminder_user_mail_sent', $type = 'INT', $length = 1, $default_value = 0),
								parent::getFieldArray($name = 'reminder_user_mail_sent_datetime', $type = 'DATETIME', $length = '', $default_value = NULL),
								parent::getFieldArray($name = 'duration_in_minutes', $type = 'INT', $length = 11, $default_value = 0),
								
								parent::getFieldArray($name = 'firstname', $type = 'VARCHAR', $length = 128, $default_value = ''),
								parent::getFieldArray($name = 'lastname', $type = 'VARCHAR', $length = 128, $default_value = ''),
								parent::getFieldArray($name = 'email_address', $type = 'VARCHAR', $length = 128, $default_value = ''),
								parent::getFieldArray($name = 'customers_number', $type = 'VARCHAR', $length = 128, $default_value = ''),
								parent::getFieldArray($name = 'phone', $type = 'VARCHAR', $length = 128, $default_value = ''),
								parent::getFieldArray($name = 'street_number', $type = 'VARCHAR', $length = 128, $default_value = ''),
								parent::getFieldArray($name = 'plz', $type = 'VARCHAR', $length = 20, $default_value = ''),
								parent::getFieldArray($name = 'city', $type = 'VARCHAR', $length = 128, $default_value = ''),
								parent::getFieldArray($name = 'street', $type = 'VARCHAR', $length = 128, $default_value = ''),
								
								parent::getFieldArray($name = 'user_unit_id', $type = 'INT', $length = 11, $default_value = 0),
								
								parent::getFieldArray($name = 'last_save_datetime', $type = 'DATETIME', $length = '', $default_value = NULL)
						)
				);
			
				parent::__construct($data);
		}
		
		/////////////////////////////////////////////////////////////////////////////////////
		// Erweiterte Datenbank-Installation Queries durchführen.
		/////////////////////////////////////////////////////////////////////////////////////
		public function installDB() {
				global $wpdb;
				
				//Do not insert demo data..
				/*
				$sql = 'INSERT INTO `aloha_appointment` (`id`, `title`, `email_address`, `status`, `event_location_id`, `booking_info_by_mail`) VALUES
						(1,	\'Küchenmöbel\',	\'clickandmeetwp-demo-team-kueche@mindfav.com\', 1, 1, 0);';
				$wpdb->query($sql);
				*/
		}
		
		/////////////////////////////////////////////////////////////////////////////////////
		// Datenbank-Updates durchführen.
		/////////////////////////////////////////////////////////////////////////////////////
		public function updateDB() {
				global $wpdb;
				
				$option_string = __NAMESPACE__ . '-db-version-' . $this->data['table_name'];
				$current_version = get_option($option_string);
				
				//Update 1 - 2019-11-19 - Added contact column
				if($current_version >= 0.0 && $current_version < 1.0) {
						$option_string = __NAMESPACE__ . '-db-version-' . $this->data['table_name'];
						update_option($option_string, 1.0, false);
						$current_version = 1.0;
				}
				
				//Update 1 - 2021-03-24 - Added column for custom dropdown
				if($current_version >= 1.0 && $current_version < 1.2) {
						$sql = 'ALTER TABLE `aloha_appointment` ADD `custom_form_dropdown` varchar(128);';
						$wpdb->query($sql);
						
						//Update after the insert, to avoid errors!					
						$option_string = __NAMESPACE__ . '-db-version-' . $this->data['table_name'];
						update_option($option_string, 1.2, false);
						$current_version = 1.2;
				}
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////////////
		// Anzahl der Termine in einem Zeitraum laden.
		// Gruppiert anhand der Tage in diesem Zeitraum.
		///////////////////////////////////////////////////////////////////////////////////////////////////
		public function countAppointmentsByDays($from, $to) {
				$db = mv_core()->get('db');
				//$db->useInstance('systemdb');
				$db->setQuery(
						'SELECT DAY(datetime_of_event) as day, COUNT(id) as anzahl, status ' .
						'FROM ' . $db->table('appointment') . ' ' .
						'WHERE ' .
								'datetime_of_event >= :datetime_of_event_from AND ' .
								'datetime_of_event <= :datetime_of_event_to ' .
						'GROUP BY DAY(datetime_of_event), status ' //.
						//'ORDER BY DAY(datetime_of_event), status;'
				);
				
				
				$db->bind('datetime_of_event_from', $from);
				$db->bind('datetime_of_event_to', $to);
				/*$db->bind('user_id', (int)$user_id);*/
				$result = $db->execute();
				
				$retval = array();
				
				while($result->next()) {
						$tmp = $result->fetchArrayAssoc();
						$retval[] = $tmp;
				}
				
				return $retval;
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////////////
		// Anzahl der Termine in einem Zeitraum laden.
		// Gruppiert anhand der Tage in diesem Zeitraum.
		///////////////////////////////////////////////////////////////////////////////////////////////////
		public function countAppointmentsByDaysAndEventLocation($event_location_id, $from, $to) {
				$db = mv_core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery(
						'SELECT DAY(datetime_of_event) as day, COUNT(id) as anzahl, status ' .
						'FROM ' . $db->table('appointment') . ' ' .
						'WHERE ' .
								'event_location_id = :event_location_id AND ' .
								'datetime_of_event >= :datetime_of_event_from AND ' .
								'datetime_of_event <= :datetime_of_event_to ' .
						'GROUP BY DAY(datetime_of_event), status; ' .
						'ORDER BY DAY(datetime_of_event), status;'
				);
				$db->bind('event_location_id', (int)$event_location_id);
				$db->bind('datetime_of_event_from', $from);
				$db->bind('datetime_of_event_to', $to);
				/*$db->bind('user_id', (int)$user_id);*/
				$result = $db->execute();
				
				$retval = array();
				
				while($result->next()) {
						$tmp = $result->fetchArrayAssoc();
						$retval[] = $tmp;
				}
				
				return $retval;
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////////////
		// Anzahl der Termine in einem Zeitraum laden.
		// Gruppiert anhand der Tage in diesem Zeitraum.
		///////////////////////////////////////////////////////////////////////////////////////////////////
		public function countAppointmentsByDaysAndUserUnit($user_unit_id, $from, $to) {
				$db = mv_core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery(
						'SELECT DAY(datetime_of_event) as day, COUNT(id) as anzahl, status ' .
						'FROM ' . $db->table('appointment') . ' ' .
						'WHERE ' .
								'user_unit_id = :user_unit_id AND ' .
								'datetime_of_event >= :datetime_of_event_from AND ' .
								'datetime_of_event <= :datetime_of_event_to ' .
						'GROUP BY DAY(datetime_of_event), status; ' .
						'ORDER BY DAY(datetime_of_event), status;'
				);
				$db->bind('user_unit_id', (int)$user_unit_id);
				$db->bind('datetime_of_event_from', $from);
				$db->bind('datetime_of_event_to', $to);
				/*$db->bind('user_id', (int)$user_id);*/
				$result = $db->execute();
				
				$retval = array();
				
				while($result->next()) {
						$tmp = $result->fetchArrayAssoc();
						$retval[] = $tmp;
				}
				
				return $retval;
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////////////
		// Load by Date from and to and user_id
		///////////////////////////////////////////////////////////////////////////////////////////////////
		public static function loadListByDateRange($date_from, $date_to) {
				$db = mv_core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery(
						'SELECT * ' .
						'FROM ' . $db->table('appointment') . ' ' .
						'WHERE ' .
								'datetime_of_event >= :datetime_of_event_from AND ' .
								'datetime_of_event <= :datetime_of_event_to ' .
						'ORDER BY datetime_of_event ASC;'
				);
				$db->bind('datetime_of_event_from', $date_from);
				$db->bind('datetime_of_event_to', $date_to);
				$result = $db->execute();
				
				$retval = array();
				
				while($result->next()) {
						$tmp = $result->fetchArrayAssoc();
						$retval[] = $tmp;
				}
				
				return $retval;
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////////////
		// Load by Date from and to and Event Location
		///////////////////////////////////////////////////////////////////////////////////////////////////
		public static function loadListByDateRangeAndEventLocation($event_location_id, $date_from, $date_to) {
				$db = mv_core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery(
						'SELECT * ' .
						'FROM ' . $db->table('appointment') . ' ' .
						'WHERE ' .
								'event_location_id = :event_location_id AND ' .
								'datetime_of_event >= :datetime_of_event_from AND ' .
								'datetime_of_event <= :datetime_of_event_to ' .
						'ORDER BY datetime_of_event ASC;'
				);
				$db->bind('event_location_id', (int)$event_location_id);
				$db->bind('datetime_of_event_from', $date_from);
				$db->bind('datetime_of_event_to', $date_to);
				$result = $db->execute();
				
				$retval = array();
				
				while($result->next()) {
						$tmp = $result->fetchArrayAssoc();
						$retval[] = $tmp;
				}
				
				return $retval;
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////////////
		// Load by Date from and to and user_unit
		///////////////////////////////////////////////////////////////////////////////////////////////////
		public static function loadListByDateRangeAndAndUserUnitId($user_unit_id, $date_from, $date_to) {
				$db = mv_core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery(
						'SELECT * ' .
						'FROM ' . $db->table('appointment') . ' ' .
						'WHERE ' .
								'user_unit_id = :user_unit_id AND ' .
								'datetime_of_event >= :datetime_of_event_from AND ' .
								'datetime_of_event <= :datetime_of_event_to AND ' .
						'ORDER BY datetime_of_event ASC;'
				);
				$db->bind('user_unit_id', (int)$user_unit_id);
				$db->bind('datetime_of_event_from', $date_from);
				$db->bind('datetime_of_event_to', $date_to);
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
						'INSERT INTO ' . $db->table('appointment') . ' ' .
            		'(title,datetime_of_event,event_location_id,user_unit_id,status,created_by,datetime_checkin,datetime_checkout,visitor_user_id,
								checkin_by,checkout_by,comment_checkin,comment_checkout,comment_visitor_booking,reminder_user_mail,reminder_active,
								reminder_user_mail_sent,reminder_user_mail_sent_datetime,duration_in_minutes,firstname,lastname,email_address,customers_number,phone,
								street_number,plz,city,street,last_save_datetime,
								custom_form_dropdown
								) ' .
								'VALUES' .
                '(:title,:datetime_of_event,:event_location_id,:user_unit_id,:status,:created_by,:datetime_checkin,:datetime_checkout,:visitor_user_id,
								:checkin_by,:checkout_by,:comment_checkin,:comment_checkout,:comment_visitor_booking,:reminder_user_mail,:reminder_active,
								:reminder_user_mail_sent,:reminder_user_mail_sent_datetime,:duration_in_minutes,:firstname,:lastname,:email_address,:customers_number,:phone,
								:street_number,:plz,:city,:street,:last_save_datetime,
								:custom_form_dropdown
								) '
				);
				$db->bind(':title', $data['title']);
				$db->bind(':datetime_of_event', $data['datetime_of_event']);
				$db->bind(':event_location_id', $data['event_location_id']);
				$db->bind(':user_unit_id', $data['user_unit_id']);
				$db->bind(':status', $data['status']);
				$db->bind(':created_by', $data['created_by']);
				$db->bind(':datetime_checkin', $data['datetime_checkin']);
				$db->bind(':datetime_checkout', $data['datetime_checkout']);
				$db->bind(':visitor_user_id', $data['visitor_user_id']);
				$db->bind(':checkin_by', $data['checkin_by']);
				$db->bind(':checkout_by', $data['checkout_by']);
				$db->bind(':comment_checkin', $data['comment_checkin']);
				$db->bind(':comment_checkout', $data['comment_checkout']);
				$db->bind(':comment_visitor_booking', $data['comment_visitor_booking']);
				$db->bind(':reminder_user_mail', $data['reminder_user_mail']);
				$db->bind(':reminder_active', $data['reminder_active']);
				$db->bind(':reminder_user_mail_sent', $data['reminder_user_mail_sent']);
				$db->bind(':reminder_user_mail_sent_datetime', $data['reminder_user_mail_sent_datetime']);
				$db->bind(':duration_in_minutes', $data['duration_in_minutes']);
				$db->bind(':firstname', $data['firstname']);
				$db->bind(':lastname', $data['lastname']);
				$db->bind(':email_address', $data['email_address']);
				$db->bind(':customers_number', $data['customers_number']);
				$db->bind(':phone', $data['phone']);
				$db->bind(':street_number', $data['street_number']);
				$db->bind(':plz', $data['plz']);
				$db->bind(':city', $data['city']);
				$db->bind(':street', $data['street']);
				$db->bind(':last_save_datetime', $data['last_save_datetime']);
				$db->bind(':custom_form_dropdown', $data['custom_form_dropdown']);
				
				$db->setTable($db->table('appointment'));
				$db->insert();
				
				return $db->insertId();
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////////////
		// Load Customer groups data by id.
		///////////////////////////////////////////////////////////////////////////////////////////////////
		public static function loadById($id) {
				$retval = array();
				
				$db = mv_core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery('SELECT * FROM ' . $db->table('appointment') . ' WHERE id = :id LIMIT 1;');
				$db->bind(':id', (int)$id);
				$result = $db->execute();
				
				$data = $result->fetchArrayAssoc();
				
				if(empty($data)) {
						return NULL;
				}
		
				return $data;
		}
		
		/////////////////////////////////////////////////////////////////////////////////
		// Update data in database.
		/////////////////////////////////////////////////////////////////////////////////
		public static function updateInDB($id, $data) {
				$db = mv_core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery(
						'UPDATE ' . $db->table('appointment') . ' SET ' .
								'title = :title, datetime_of_event = :datetime_of_event, event_location_id = :event_location_id, 
								user_unit_id = :user_unit_id, status = :status, created_by = :created_by, 
								datetime_checkin = :datetime_checkin, datetime_checkout = :datetime_checkout, visitor_user_id = :visitor_user_id, 
								checkin_by = :checkin_by, checkout_by = :checkout_by, comment_checkin = :comment_checkin, 
								comment_checkout = :comment_checkout, comment_visitor_booking = :comment_visitor_booking, 
								reminder_user_mail = :reminder_user_mail, reminder_active = :reminder_active, 
								reminder_user_mail_sent = :reminder_user_mail_sent, reminder_user_mail_sent_datetime = :reminder_user_mail_sent_datetime, 
								duration_in_minutes = :duration_in_minutes, firstname = :firstname, lastname = :lastname, 
								email_address = :email_address, customers_number = :customers_number, phone = :phone, 
								street_number = :street_number, plz = :plz, city = :city, street = :street, last_save_datetime = :last_save_datetime,
								custom_form_dropdown = :custom_form_dropdown ' .
						'WHERE ' .
								'id = :id'
				);
				$db->bind(':title', $data['title']);
				$db->bind(':datetime_of_event', $data['datetime_of_event']);
				$db->bind(':event_location_id', $data['event_location_id']);
				$db->bind(':user_unit_id', $data['user_unit_id']);
				$db->bind(':status', $data['status']);
				$db->bind(':created_by', $data['created_by']);
				$db->bind(':datetime_checkin', $data['datetime_checkin']);
				$db->bind(':datetime_checkout', $data['datetime_checkout']);
				$db->bind(':visitor_user_id', $data['visitor_user_id']);
				$db->bind(':checkin_by', $data['checkin_by']);
				$db->bind(':checkout_by', $data['checkout_by']);
				$db->bind(':comment_checkin', $data['comment_checkin']);
				$db->bind(':comment_checkout', $data['comment_checkout']);
				$db->bind(':comment_visitor_booking', $data['comment_visitor_booking']);
				$db->bind(':reminder_user_mail', $data['reminder_user_mail']);
				$db->bind(':reminder_active', $data['reminder_active']);
				$db->bind(':reminder_user_mail_sent', $data['reminder_user_mail_sent']);
				$db->bind(':reminder_user_mail_sent_datetime', $data['reminder_user_mail_sent_datetime']);
				$db->bind(':duration_in_minutes', $data['duration_in_minutes']);
				$db->bind(':firstname', $data['firstname']);
				$db->bind(':lastname', $data['lastname']);
				$db->bind(':email_address', $data['email_address']);
				$db->bind(':customers_number', $data['customers_number']);
				$db->bind(':phone', $data['phone']);
				$db->bind(':street_number', $data['street_number']);
				$db->bind(':plz', $data['plz']);
				$db->bind(':city', $data['city']);
				$db->bind(':street', $data['street']);
				$db->bind(':last_save_datetime', $data['last_save_datetime']);
				$db->bind(':custom_form_dropdown', $data['custom_form_dropdown']);
       
			  $db->bindWhere(':id', (int)$id);

				$db->setTable($db->table('appointment'));
				$db->update();
		}
		
		/////////////////////////////////////////////////////////////////////////////////
		// Delete data in database.
		/////////////////////////////////////////////////////////////////////////////////
		public static function deleteById($id) {
				$db = mv_core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery(
						'DELETE FROM ' . $db->table('appointment') . ' ' .
						'WHERE ' .
								'id = :id'
				);
				$db->bindWhere(':id', (int)$id);
				
				$db->setTable($db->table('appointment'));
				$db->delete();
		}
		
		/////////////////////////////////////////////////////////////////////////////////
		// Delete data in database.
		/////////////////////////////////////////////////////////////////////////////////
		public static function deleteByDateFromAndDateToAndAndStatus($between_from, $between_to, $status) {
				global $wpdb;
				
				$db = mv_core()->get('db');
				/*
				$db->useInstance('systemdb');
				*/
				$query = 
						'DELETE FROM ' . $db->table('appointment') . ' ' .
						'WHERE ' .
								'datetime_of_event >= %s AND ' .
								'datetime_of_event <= %s AND ' .
								'status = %s';
				/*
				$db->bindWhere(':between_from', $between_from);
				$db->bindWhere(':between_to', $between_to);
				$db->bindWhere(':status', (int)$status);
				*/
				
				$wpdb->query( 
						$wpdb->prepare( 
								$query,
								array(
										$between_from,
										$between_to,
										$status
								)
						)
				);
				
				
				
				
				//$db->setTable($db->table('appointment'));
				//$db->delete();
		}
		
		/////////////////////////////////////////////////////////////////////////////////
		// Delete data in database.
		/////////////////////////////////////////////////////////////////////////////////
		public static function deleteByDateFromAndDateToAndAndStatusAndUserUnit($between_from, $between_to, $status, $user_unit_id) {
				global $wpdb;
				
				$db = mv_core()->get('db');
				//$db->useInstance('systemdb');
				$query = 
						'DELETE FROM ' . $db->table('appointment') . ' ' .
						'WHERE ' .
								'datetime_of_event >= %s AND ' .
								'datetime_of_event <= %s AND ' .
								'user_unit_id = %s AND ' .
								'status = %s'
				;
				
				/*
				$db->bind(':between_from', $between_from);
				$db->bind(':between_to', $between_to);
				$db->bind(':user_unit_id', $user_unit_id);
				$db->bind(':status', (int)$status);
				*/
				
				$wpdb->query( 
						$wpdb->prepare( 
								$query,
								array(
										$between_from,
										$between_to,
										$user_unit_id,
										$status
								)
						)
				);
				
				
				/*
				$db->setTable($db->table('appointment'));
				$db->delete();
				*/
		}
		
		/////////////////////////////////////////////////////////////////////////////////
		// Delete data in database.
		/////////////////////////////////////////////////////////////////////////////////
		public static function deleteByDateFromAndDateToAndAndStatusAndEventLocation($between_from, $between_to, $status, $event_location_id) {
				global $wpdb;
				
				$db = mv_core()->get('db');

				$query = 
						'DELETE FROM ' . $db->table('appointment') . ' ' .
						'WHERE ' .
								'datetime_of_event >= %s AND ' .
								'datetime_of_event <= %s AND ' .
								'event_location_id = %s AND ' .
								'status = %s'
				;
				
				/*
				$db->bindWhere(':between_from', $between_from);
				$db->bindWhere(':between_to', $between_to);
				$db->bindWhere(':event_location_id', $event_location_id);
				$db->bindWhere(':status', (int)$status);
				
				$db->setTable($db->table('appointment'));
				$db->delete();
				*/
				
				$wpdb->query( 
						$wpdb->prepare( 
								$query,
								array(
										$between_from,
										$between_to,
										$event_location_id,
										$status
								)
						)
				);
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////////////
		// Load by Date from and to and user_id
		///////////////////////////////////////////////////////////////////////////////////////////////////
		public static function loadListByDateRangeAndStatus($date_from, $date_to, $status) {
				$db = mv_core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery(
						'SELECT * ' .
						'FROM ' . $db->table('appointment') . ' ' .
						'WHERE ' .
								'datetime_of_event >= :datetime_of_event_from AND ' .
								'datetime_of_event <= :datetime_of_event_to AND ' .
								'status = :status ' .
						'ORDER BY datetime_of_event ASC;'
				);
				$db->bind('datetime_of_event_from', $date_from);
				$db->bind('datetime_of_event_to', $date_to);
				$db->bind('status', (int)$status);
				$result = $db->execute();
				
				$retval = array();
				
				while($result->next()) {
						$tmp = $result->fetchArrayAssoc();
						$retval[] = $tmp;
				}
				
				return $retval;
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////////////
		// Load by Date from and to and Event Location
		///////////////////////////////////////////////////////////////////////////////////////////////////
		public static function loadListByDateRangeAndStatusAndEventLocation($event_location_id, $date_from, $date_to, $status) {
				$db = mv_core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery(
						'SELECT * ' .
						'FROM ' . $db->table('appointment') . ' ' .
						'WHERE ' .
								'event_location_id = :event_location_id AND ' .
								'datetime_of_event >= :datetime_of_event_from AND ' .
								'datetime_of_event <= :datetime_of_event_to AND ' .
								'status = :status ' .
						'ORDER BY datetime_of_event ASC;'
				);
				$db->bind('event_location_id', (int)$event_location_id);
				$db->bind('datetime_of_event_from', $date_from);
				$db->bind('datetime_of_event_to', $date_to);
				$db->bind('status', (int)$status);
				$result = $db->execute();
				
				$retval = array();
				
				while($result->next()) {
						$tmp = $result->fetchArrayAssoc();
						$retval[] = $tmp;
				}
				
				return $retval;
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////////////
		// Load by Date from and to and user_unit
		///////////////////////////////////////////////////////////////////////////////////////////////////
		public static function loadListByDateRangeAndStatusAndUserUnitId($user_unit_id, $date_from, $date_to, $status) {
				$db = mv_core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery(
						'SELECT * ' .
						'FROM ' . $db->table('appointment') . ' ' .
						'WHERE ' .
								'user_unit_id = :user_unit_id AND ' .
								'datetime_of_event >= :datetime_of_event_from AND ' .
								'datetime_of_event <= :datetime_of_event_to AND ' .
								'status = :status ' .
						'ORDER BY datetime_of_event ASC;'
				);
				$db->bind('user_unit_id', (int)$user_unit_id);
				$db->bind('datetime_of_event_from', $date_from);
				$db->bind('datetime_of_event_to', $date_to);
				$db->bind('status', (int)$status);
				$result = $db->execute();
				
				$retval = array();
				
				while($result->next()) {
						$tmp = $result->fetchArrayAssoc();
						$retval[] = $tmp;
				}
				
				return $retval;
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////////////
		// Anzahl der Termine in einem Zeitraum laden.
		// Gruppiert anhand der Tage in diesem Zeitraum.
		///////////////////////////////////////////////////////////////////////////////////////////////////
		public function countAppointmentsByDaysAndStatus($from, $to, $status) {
				$db = mv_core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery(
						'SELECT DAY(datetime_of_event) as day, COUNT(id) as anzahl, status ' .
						'FROM ' . $db->table('appointment') . ' ' .
						'WHERE ' .
								'datetime_of_event >= :datetime_of_event_from AND ' .
								'datetime_of_event <= :datetime_of_event_to AND ' .
								'status = :status ' .
						'GROUP BY DAY(datetime_of_event), status;'
				);
				$db->bind('datetime_of_event_from', $from);
				$db->bind('datetime_of_event_to', $to);
				$db->bind('status', (int)$status);
				/*$db->bind('user_id', (int)$user_id);*/
				$result = $db->execute();
				
				$retval = array();
				
				while($result->next()) {
						$tmp = $result->fetchArrayAssoc();
						$retval[] = $tmp;
				}
				
				return $retval;
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////////////
		// Anzahl der Termine in einem Zeitraum laden.
		// Gruppiert anhand der Tage in diesem Zeitraum.
		///////////////////////////////////////////////////////////////////////////////////////////////////
		public function countAppointmentsByDaysAndEventLocationAndStatus($event_location_id, $from, $to, $status) {
				$db = mv_core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery(
						'SELECT DAY(datetime_of_event) as day, COUNT(id) as anzahl, status ' .
						'FROM ' . $db->table('appointment') . ' ' .
						'WHERE ' .
								'event_location_id = :event_location_id AND ' .
								'datetime_of_event >= :datetime_of_event_from AND ' .
								'datetime_of_event <= :datetime_of_event_to AND ' .
								'status = :status ' .
						'GROUP BY DAY(datetime_of_event), status;'
				);
				$db->bind(':event_location_id', (int)$event_location_id);
				$db->bind(':datetime_of_event_from', $from);
				$db->bind(':datetime_of_event_to', $to);
				$db->bind(':status', (int)$status);
				$result = $db->execute();
				
				$retval = array();
				
				while($result->next()) {
						$tmp = $result->fetchArrayAssoc();
						$retval[] = $tmp;
				}
				
				return $retval;
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////////////
		// Anzahl der Termine in einem Zeitraum laden.
		// Gruppiert anhand der Tage in diesem Zeitraum.
		///////////////////////////////////////////////////////////////////////////////////////////////////
		public function countAppointmentsByDaysAndUserUnitAndStatus($user_unit_id, $from, $to, $status) {
				$db = mv_core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery(
						'SELECT DAY(datetime_of_event) as day, COUNT(id) as anzahl, status ' .
						'FROM ' . $db->table('appointment') . ' ' .
						'WHERE ' .
								'user_unit_id = :user_unit_id AND ' .
								'datetime_of_event >= :datetime_of_event_from AND ' .
								'datetime_of_event <= :datetime_of_event_to AND ' .
								'status = :status ' .
						'GROUP BY DAY(datetime_of_event), status;'
				);
				$db->bind(':user_unit_id', (int)$user_unit_id);
				$db->bind(':datetime_of_event_from', $from);
				$db->bind(':datetime_of_event_to', $to);
				$db->bind(':status', (int)$status);
				$result = $db->execute();
				
				$retval = array();
				
				while($result->next()) {
						$tmp = $result->fetchArrayAssoc();
						$retval[] = $tmp;
				}
				
				return $retval;
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////////////
		// Load by Date from and to and user_id
		///////////////////////////////////////////////////////////////////////////////////////////////////
		public function loadFrontendListByDateRangeAndStatus($date_from, $date_to, $status) {
				$db = mv_core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery(
						'SELECT ' . $this->frontendFieldsString . ' ' .
						'FROM ' . $db->table('appointment') . ' ' .
						'WHERE ' .
								'datetime_of_event >= :datetime_of_event_from AND ' .
								'datetime_of_event <= :datetime_of_event_to AND ' .
								'status = :status ' .
						'ORDER BY datetime_of_event ASC;'
				);
				$db->bind('datetime_of_event_from', $date_from);
				$db->bind('datetime_of_event_to', $date_to);
				$db->bind('status', (int)$status);
				$result = $db->execute();
				
				$retval = array();
				
				while($result->next()) {
						$tmp = $result->fetchArrayAssoc();
						$retval[] = $tmp;
				}
				
				return $retval;
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////////////
		// Load by Date from and to and Event Location
		///////////////////////////////////////////////////////////////////////////////////////////////////
		public function loadFrontendListByDateRangeAndStatusAndEventLocation($event_location_id, $date_from, $date_to, $status) {
				$db = mv_core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery(
						'SELECT ' . $this->frontendFieldsString . ' ' .
						'FROM ' . $db->table('appointment') . ' ' .
						'WHERE ' .
								'event_location_id = :event_location_id AND ' .
								'datetime_of_event >= :datetime_of_event_from AND ' .
								'datetime_of_event <= :datetime_of_event_to AND ' .
								'status = :status ' .
						'ORDER BY datetime_of_event ASC;'
				);
				$db->bind('event_location_id', (int)$event_location_id);
				$db->bind('datetime_of_event_from', $date_from);
				$db->bind('datetime_of_event_to', $date_to);
				$db->bind('status', (int)$status);
				$result = $db->execute();
				
				$retval = array();
				
				while($result->next()) {
						$tmp = $result->fetchArrayAssoc();
						$retval[] = $tmp;
				}
				
				return $retval;
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////////////
		// Load by Date from and to and user_unit
		///////////////////////////////////////////////////////////////////////////////////////////////////
		public function loadFrontendListByDateRangeAndStatusAndUserUnitId($user_unit_id, $date_from, $date_to, $status) {
				$db = mv_core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery(
						'SELECT ' . $this->frontendFieldsString . ' ' .
						'FROM ' . $db->table('appointment') . ' ' .
						'WHERE ' .
								'user_unit_id = :user_unit_id AND ' .
								'datetime_of_event >= :datetime_of_event_from AND ' .
								'datetime_of_event <= :datetime_of_event_to AND ' .
								'status = :status ' .
						'ORDER BY datetime_of_event ASC;'
				);
				$db->bind('user_unit_id', (int)$user_unit_id);
				$db->bind('datetime_of_event_from', $date_from);
				$db->bind('datetime_of_event_to', $date_to);
				$db->bind('status', (int)$status);
				$result = $db->execute();
				
				$retval = array();
				
				while($result->next()) {
						$tmp = $result->fetchArrayAssoc();
						$retval[] = $tmp;
				}
				
				return $retval;
		}
}
