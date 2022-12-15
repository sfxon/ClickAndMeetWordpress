<?php

namespace mvclickandmeet_namespace;

class mvBookingFormTexts extends mvWpModel {
		public function __construct() {
				$data = array(
						//Table and module definitions.
						'module_title' => 'ClickAndMeet - Texte',
						'table_name' => 'aloha_mv_booking_form_texts',
						'db_version' => '1.2',
						'id_field' => 'id',
						
						//Data fields for the model.
						'fields' => array(
								parent::getFieldArray($name = 'id', $type = 'INT', $length = 11, $default_value = "", $auto_increment = true),
								parent::getFieldArray($name = 'title', $type = 'VARCHAR', $length = 256, $default_value = ''),
								parent::getFieldArray($name = 'internal_identifier', $type = 'VARCHAR', $length = 128, $default_value = ''),
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
				
				$sql = 'INSERT INTO `aloha_mv_booking_form_texts` (`id`, `title`, `internal_identifier`, `field_value`) VALUES  
						(1,	\'Laden:\',	\'event_location_title\',	\'Betriebsstätte/Standort:\'),
						(2,	\'Laden: --Alle-- im Dropdown\',	\'event_locations_all\',	\'-- Alle --\'),
						(3,	\'Team/Mitarbeiter\',	\'user_unit_title\',	\'Abteilung:\'),
						(4,	\'Team/Mitarbeiter - -Alle- Text in Dropdown\',	\'user_unit_dropdown_all\',	\'-- Alle --\'),
						(5,	\'Monat\',	\'calendar_month_title\',	\'Monat\'),
						(6,	\'Jahr\',	\'calendar_year_title\',	\'Jahr\'),
						(7,	\'Titel der Terminauswahl-Liste\',	\'timer_title\',	\'Freie Termine\'),
						(8,	\'Kürzel Montag\',	\'cal_monday_short\',	\'Mo\'),
						(9,	\'Kürzel Dienstag\',	\'cal_tuesday_short\',	\'Di\'),
						(10,	\'Kürzel Mittwoch\',	\'cal_wednesday_short\',	\'Mi\'),
						(11,	\'Kürzel Donnerstag\',	\'cal_thursday_short\',	\'Do\'),
						(12,	\'Kürzel Freitag\',	\'cal_friday_short\',	\'Fr\'),
						(13,	\'Kürzel Samstag\',	\'cal_sat_short\',	\'Sa\'),
						(14,	\'Kürzel Sonntag\',	\'cal_sun_short\',	\'So\'),
						(15,	\'Kürzel Kalenderwoche\',	\'cal_kw_short\',	\'KW\'),
						(16,	\'Timer: Text hinter Termin-Auswahl\',	\'count_termine_title\',	\'Termin(e)\'),
						(17,	\'Text: Uhr\',	\'timer_time\',	\' Uhr\'),
						(18,	\'Text: \"Daten werden geladen\"\',	\'loading_data_title\',	\'Daten werden geladen\'),
						(19,	\'Buchungsmaske-Überschrift\',	\'booking_mask_title\',	\'Termin jetzt buchen\'),
						(20,	\'Text: \"Termin-Datum\"\',	\'booking_mask_date_title\',	\'Termin-Datum:\'),
						(21,	\'Text: \"Uhrzeit\"\',	\'booking_mask_time_title\',	\'Uhrzeit: \'),
						(22,	\'Text: \"Pflichtfeld\"\',	\'booking_mask_required\',	\'*Mit einem Stern markierte Felder sind Pflichtfelder.\'),
						(23,	\'Text Vorname\',	\'booking_mask_firstname\',	\'Vorname*:\'),
						(24,	\'Text Nachname\',	\'booking_mask_lastname\',	\'Nachname*:\'),
						(25,	\'Text \"E-Mail Adresse\"\',	\'booking_mask_email_address\',	\'E-Mail Adresse*: \'),
						(26,	\'Fehlertext: Vorname\',	\'booking_mask_error_firstname\',	\'Bitte geben Sie Ihren Vornamen ein.\'),
						(27,	\'Fehlertext: Nachname\',	\'booking_mask_error_lastname\',	\'Bitte geben Sie Ihren Nachname ein.\'),
						(28,	\'Fehlertext: E-Mail Adresse\',	\'booking_mask_error_email_address\',	\'Bitte geben Sie eine E-Mail Adresse ein.\'),
						(29,	\'Text: \"Dürfen wir Sie an den Termin erinnern?\"\',	\'booking_mask_reminder_text\',	\'Dürfen wir Sie an den Termin erinnern?*:\'),
						(30,	\'Text: Erinnerungs-Auswahl: Ja\',	\'booking_mask_reminder_text_yes\',	\'Ja\'),
						(31,	\'Text: Erinnerungs-Auswahl: Nein\',	\'booking_mask_reminder_text_no\',	\'Nein\'),
						(32,	\'Text Kundennummer\',	\'booking_mask_customers_number\',	\'Kundennummer:\'),
						(33,	\'Text: \"Telefonnummer\"\',	\'booking_mask_phone\',	\'Telefonnummer:\'),
						(34,	\'Text \"Straße\"\',	\'booking_mask_street\',	\'Straße\'),
						(35,	\'Text \"PLZ\"\',	\'booking_mask_text_plz\',	\'PLZ:\'),
						(36,	\'Text \"Ort:\"\',	\'booking_mask_city\',	\'Ort:\'),
						(37,	\'Text \"Kommentar zum Termin:\"\',	\'booking_mask_comment\',	\'Kommentar zum Termin:\'),
						(38,	\'Text \"Datenschutzerklärung..\"\',	\'booking_mask_datenschutz_title\',	\'Haben Sie unsere <a href=\"#\">Datenschutzbestimmungen</a> gelesen, und stimmen Sie Ihnen zu?*:\'),
						(39,	\'Text: Datenschutz: Fehler\',	\'booking_mask_datenschutz_error\',	\'Wir können Ihre Anfrage nur entgegennehmen, wenn Sie mit unseren Datenschutzbedinungen einverstanden sind.\'),
						(40,	\'Text: Datenschutz: Ja\',	\'booking_mask_datenschutz_yes\',	\'Ja, ich akzeptiere\'),
						(41,	\'Text: Datenschutz: Nein\',	\'booking_mask_datenschutz_no\',	\'Nein\'),
						(42,	\'Button \"Jetzt verbindlich Buchen\',	\'button_book_now\',	\'Termin verbindlich buchen\'),
						(43,	\'Text: Buchung wird vorgenommen (Ladeanimation)\',	\'book_now_loading\',	\'Ihre Terminanfrage wird bearbeitet. Bitte haben Sie einen Moment Geduld.\'),
						(44,	\'Buchungs-Erfolg: Text\',	\'booking_success_text\',	\'Die Buchung ist bei uns eingegangen. Sie erhalten in Kürze eine E-Mail mit Informationen zum Termin.<br />Informationen zum gebuchten Termin: \'),
						(45,	\'Buchungs-Erfolg: Titel\',	\'booking_success_title\',	\'Buchung erfolgreich\'),
						(46,	\'Fehlermeldung: Fehlende Angaben im Formular\',	\'booking_error_text\',	\'Es ist ein Fehler aufgetreten. Bitte überprüfen Sie Ihre Eingaben!\');';

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
						$sql = 'INSERT INTO `aloha_mv_booking_form_texts` (`title`, `internal_identifier`, `field_value`) VALUES
								(\'Titel des eigenen Auswahlfeldes im Formular:\',	\'custom_form_dropdown_title\',	\'Auswahl:\');';
						$wpdb->query($sql);
						
						//Update after the insert, to avoid errors!					
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
						$retval[$s['internal_identifier']] = $s['field_value'];
				}
				
				return $retval;
		}
}
