<?php

namespace mvclickandmeet_namespace;

class mvCmSettings extends mvWpModel {
		public function __construct() {
				$data = array(
						//Table and module definitions.
						'module_title' => 'ClickAndMeet - Formular Einstellungen',
						'table_name' => 'aloha_cm_settings',
						'db_version' => '1.2',
						'id_field' => 'id',
						
						//Data fields for the model.
						'fields' => array(
								parent::getFieldArray($name = 'id', $type = 'INT', $length = 11, $default_value = "", $auto_increment = true),
								parent::getFieldArray($name = 'title', $type = 'VARCHAR', $length = 256, $default_value = ''),
								parent::getFieldArray($name = 'field_title', $type = 'VARCHAR', $length = 64, $default_value = ''),
								parent::getFieldArray($name = 'field_value', $type = 'VARCHAR', $length = 2048, $default_value = '')
						)
				);
			
				parent::__construct($data);
		}
		
		/////////////////////////////////////////////////////////////////////////////////////
		// Erweiterte Datenbank-Installation Queries durchführen.
		/////////////////////////////////////////////////////////////////////////////////////
		public function installDB() {
				global $wpdb;
				
				$sql = 'INSERT INTO `aloha_cm_settings` (`id`, `title`, `field_title`, `field_value`) VALUES ' . 
						//(1,	\'Standard-Termindauer in Minuten\',	\'default_appointment_duration\',	\'25\'),
						//'(2,	\'Abteilung/Mitarbeiter/Team - Pflichtfeld\',	\'user_unit_id_required\',	\'1\'),
						'(3,	\'Kundennummer abfragen?\',	\'form_use_customers_number\',	\'1\'),
						(4,	\'Telefonnummer abfragen?\',	\'form_use_phone\',	\'1\'),
						(5,	\'Straße abfragen?\',	\'form_use_street\',	\'1\'),
						(6,	\'PLZ abfragen?\',	\'form_use_plz\',	\'1\'),
						(7,	\'Ort abfragen?\',	\'form_use_city\',	\'1\'),
						(8,	\'Kommentar abfragen?\',	\'form_use_comment\',	\'1\'),
						(9,	\'Vorname abfragen?\',	\'form_use_firstname\',	\'1\'),
						(10,	\'Nachname abfragen?\',	\'form_use_lastname\',	\'1\'),
						(11,	\'Benutzer um Erlaubnis nach Kontakt fragen?\',	\'form_use_ask_for_reminder\',	\'1\');';
				$wpdb->query($sql);
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
				
				//Update 1 - 2019-11-19 - Added contact column
				if($current_version >= 1.0 && $current_version < 1.2) {
						$sql = 'INSERT INTO `aloha_cm_settings` (`title`, `field_title`, `field_value`) VALUES 
								(\'Custom Dropdown-Auswahlfeld anzeigen?\',	\'custom_form_dropdown\',	\'0\');';
						$wpdb->query($sql);
						
						//Update this after the insert, to avoid errors.
						$option_string = __NAMESPACE__ . '-db-version-' . $this->data['table_name'];
						update_option($option_string, 1.2, false);
						$current_version = 1.2;
				}
		}
		
		//////////////////////////////////////////////////////////////////////
		// Einstellungen indexiert laden.
		//////////////////////////////////////////////////////////////////////
		function loadSettingsIndexed() {
				$settings = $this->loadAllAsArray();
				
				$retval = array();
				
				foreach($settings as $s) {
						$retval[$s['field_title']] = $s;
				}
				
				return $retval;
		}
		
		//////////////////////////////////////////////////////////////////////
		// Einstellungen indexiert laden.
		//////////////////////////////////////////////////////////////////////
		function loadIndexedList() {
				$settings = $this->loadAllAsArray();
				
				$retval = array();
				
				foreach($settings as $s) {
						$retval[$s['field_title']] = $s['field_value'];
				}
				
				return $retval;
		}
}
