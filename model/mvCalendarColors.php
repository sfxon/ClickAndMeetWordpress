<?php

namespace mvclickandmeet_namespace;

class mvCalendarColors extends mvWpModel {
		public function __construct() {
				$data = array(
						//Table and module definitions.
						'module_title' => 'ClickAndMeet - Farben',
						'table_name' => 'aloha_calendar_colors',
						'db_version' => '1.0',
						'id_field' => 'id',
						
						//Data fields for the model.
						'fields' => array(
								parent::getFieldArray($name = 'id', $type = 'INT', $length = 11, $default_value = "", $auto_increment = true),
								parent::getFieldArray($name = 'title', $type = 'VARCHAR', $length = 256, $default_value = ''),
								parent::getFieldArray($name = 'internal_identifier', $type = 'VARCHAR', $length = 128, $default_value = ''),
								parent::getFieldArray($name = 'field_value', $type = 'VARCHAR', $length = 32, $default_value = '')
						)
				);
			
				parent::__construct($data);
		}
		
		/////////////////////////////////////////////////////////////////////////////////////
		// Erweiterte Datenbank-Installation Queries durchführen.
		/////////////////////////////////////////////////////////////////////////////////////
		public function installDB() {
				global $wpdb;
				
				$sql = 'INSERT INTO `aloha_calendar_colors` (`id`, `title`, `internal_identifier`, `field_value`) VALUES ' . 
						'(1,	\'Kalender-Titel Hintergrund\',	\'cal_title_background\',	\'#428BCA\'), ' . 
						'(2,	\'Titel: KW - Hintergrund\',	\'cal_kw_background\',	\'#428BCA\'), ' . 
						'(3,	\'Wochennummer\',	\'cal_weeks_background\',	\'#428BCA\'), ' . 
						'(4,	\'Hintergrund-Farbe nicht buchbarer Termine\',	\'entry_no_booking_background\',	\'transparent\'), ' . 
						'(5,	\'Text-Farbe nicht buchbarer Termine\',	\'entry_no_booking_text\',	\'rgba(0, 0, 0, 0.3)\'), ' . 
						'(6,	\'Hintergrund-Farbe buchbarer Termine\',	\'entry_booking_background\',	\'#FFF\'), ' . 
						'(7,	\'Text-Farbe buchbarer Termine\',	\'entry_booking_text\',	\'#000\'), ' . 
						'(8,	\'Hintergrund-Farbe Heutiger Tag\',	\'entry_booking_today_background\',	\'\'), ' . 
						'(9,	\'Text-Farbe Heutiger Tag\',	\'entry_booking_today_text\',	\'#428BCA\'), ' . 
						'(10,	\'Hintergrund-Farbe Ausgewählter Tag\',	\'entry_booking_selected_background\',	\'#FF8211\'), ' . 
						'(11,	\'Text-Farbe Ausgewählter Tag\',	\'entry_booking_selected_text\',	\'#FFF\'), ' . 
						'(12,	\'Termin-Liste Titel Hintergrund\',	\'timer_title_background\',	\'#428BCA\'), ' . 
						'(13,	\'Termin-Liste Titel Text\',	\'timer_title_text\',	\'#FFF\'), ' . 
						'(14,	\'Timer-Liste Reihe 1 Hintergrund\',	\'timer_list_row_1_background\',	\'#EEE\'), ' . 
						'(15,	\'Timer-Liste Reihe 1 Text\',	\'timer_list_row_1_text\',	\'#000\'), ' . 
						'(16,	\'Timer-Liste Reihe 2 Hintergrund\',	\'timer_list_row_2_background\',	\'#FFF\'), ' . 
						'(17,	\'Timer-Liste Reihe 2 Text\',	\'timer_list_row_2_text\',	\'#000\'), ' . 
						'(18,	\'Jetzt Buchen Button Text\',	\'book_now_button_text\',	\'#FFF\'), ' . 
						'(19,	\'Jetzt Buchen Button Hintergrund\',	\'book_now_button_background\',	\'#428BCA\'), ' . 
						'(20,	\'Kalender-Titel Hintergrund\',	\'cal_title_text\',	\'#FFF\'), ' . 
						'(21,	\'Titel: KW - Textfarbe\',	\'cal_kw_text\',	\'#FFF\'), ' . 
						'(22,	\'Wochennummer - Textfarbe\',	\'cal_weeks_text\',	\'#FFF\');';

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
						$retval[$s['internal_identifier']] = $s['field_value'];
				}
				
				return $retval;
		}
}
