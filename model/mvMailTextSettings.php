<?php

namespace mvclickandmeet_namespace;

class mvMailTextSettings extends mvWpModel {
		public function __construct() {
				$data = array(
						//Table and module definitions.
						'module_title' => 'ClickAndMeet - E-Mail-Texte',
						'table_name' => 'aloha_mail_text_settings',
						'db_version' => '1.0',
						'id_field' => 'id',
						
						//Data fields for the model.
						'fields' => array(
								parent::getFieldArray($name = 'id', $type = 'INT', $length = 11, $default_value = "", $auto_increment = true),
								parent::getFieldArray($name = 'title', $type = 'VARCHAR', $length = 256, $default_value = ''),
								parent::getFieldArray($name = 'email_text', $type = 'TEXT', $length = "", $default_value = '')
						)
				);
			
				parent::__construct($data);
		}
		
		/////////////////////////////////////////////////////////////////////////////////////
		// Erweiterte Datenbank-Installation Queries durchführen.
		/////////////////////////////////////////////////////////////////////////////////////
		public function installDB() {
				global $wpdb;
				
				$sql = 'INSERT INTO `aloha_mail_text_settings` (`id`, `title`, `email_text`) VALUES ' .
						'(1,	\'Kunden-Mail: Text-Mail (Plain)\',	\'Datum: %date%\r\nZeit: %time%\r\n\r\nVeranstaltungs-Ort: %betriebsstaette%\r\nMitarbeiter/Abteilung: %abteilung%\r\n\r\nDie von Ihnen übermittelten Kundendaten:\r\n\r\nVorname: %firstname%\r\nNachname: %lastname%\r\nE-Mail Adresse: %email_address%\r\nDürfen wir Sie per E-Mail an den Termin erinnern? %reminder_yes_no%\r\nTelefonnummer: %phone%\r\nStraße: %street%\r\nPlz: %plz%\r\nOrt: %city%\r\n\r\nDer von Ihnen eingegebene Kommentar zum Termin:\r\n%comment%\'), ' .
						'(2,	\'Kunden-Mail: HTML-Mail\',	\'Sehr geehrter Kunde,<br />\r\n<br />\r\nvielen Dank für die Buchung eines Termines über unser Online-Portal.<br />\r\n<br />\r\nHier finden Sie noch einmal alle Informationen zusammengefasst:<br />\r\n<br />\r\nDatum: %date%<br />\r\nZeit: %time%<br />\r\n<br />\r\nVeranstaltungs-Ort: %betriebsstaette%<br />\r\nMitarbeiter/Abteilung: %abteilung%<br />\r\n<br />\r\nDie von Ihnen übermittelten Kundendaten:<br />\r\n<br />\r\nVorname: %firstname%<br />\r\nNachname: %lastname%<br />\r\nE-Mail Adresse: %email_address%<br />\r\nDürfen wir Sie per E-Mail an den Termin erinnern? %reminder_yes_no%<br />\r\nTelefonnummer: %phone%<br />\r\nStraße: %street%<br />\r\nPlz: %plz%<br />\r\nOrt: %city%<br />\r\n<br />\r\nDer von Ihnen eingegebene Kommentar zum Termin:<br />\r\n%comment%<br />\r\n<br />\r\n<br />\r\nMit freundlichen Grüßen<br />\r\n<br />\r\nIhr Team von Click&Meet - demo2.clickandmeet.org<br />\r\n<br />\r\n<br />\'), ' .
						'(3,	\'Kunden-Mail: Betreff\',	\'Kontaktanfrage\'), ' .
						'(4,	\'Betriebsstätte: E-Mail Betreff\',	\'Kontaktanfrage über Webseite\'), ' .
						'(5,	\'Betriebsstätte: E-Mail Text HTML\',	\'Eine neue Buchung wurde über die Webseite vorgenommen.\r\n<br />\r\nBuchungsinformationen:\r\n<br />\r\nDatum: %date%<br />\r\nZeit: %time%<br />\r\n<br />\r\nVeranstaltungs-Ort: %betriebsstaette%<br />\r\nMitarbeiter/Abteilung: %abteilung%<br />\r\n<br />\r\nKundendaten:<br />\r\n<br />\r\nVorname: %firstname%<br />\r\nNachname: %lastname%<br />\r\nE-Mail Adresse: %email_address%<br />\r\nDürfen wir per E-Mail an den Termin erinnern? %reminder_yes_no%<br />\r\nTelefonnummer: %phone%<br />\r\nStraße: %street%<br />\r\nPlz: %plz%<br />\r\nOrt: %city%<br />\r\n<br />\r\nKommentar zum Termin:<br />\r\n%comment%<br />\r\n<br />\r\n<br />\r\nBITTE ANTWORTEN SIE NICHT DIREKT AUF DIESE E-MAIL!\'), ' .
						'(6,	\'Betriebsstätte: E-Mail Text (Plain)\',	\'Datum: %date%\r\nZeit: %time%\r\n\r\nVeranstaltungs-Ort: %betriebsstaette%\r\nMitarbeiter/Abteilung: %abteilung%\r\n\r\nDie von Ihnen übermittelten Kundendaten:\r\n\r\nVorname: %firstname%\r\nNachname: %lastname%\r\nE-Mail Adresse: %email_address%\r\nDürfen wir Sie per E-Mail an den Termin erinnern? %reminder_yes_no%\r\nTelefonnummer: %phone%\r\nStraße: %street%\r\nPlz: %plz%\r\nOrt: %city%\r\n\r\nDer von Ihnen eingegebene Kommentar zum Termin:\r\n%comment%\'), ' .
						'(7,	\'Abteilung/Team/Mitarbeiter: E-Mail Betreff\',	\'Kontaktanfrage über Webseite\'), ' .
						'(8,	\'Abteilung/Team/Mitarbeiter: E-Mail Text (Plain)\',	\'Datum: %date%\r\nZeit: %time%\r\n\r\nVeranstaltungs-Ort: %betriebsstaette%\r\nMitarbeiter/Abteilung: %abteilung%\r\n\r\nDie von Ihnen übermittelten Kundendaten:\r\n\r\nVorname: %firstname%\r\nNachname: %lastname%\r\nE-Mail Adresse: %email_address%\r\nDürfen wir Sie per E-Mail an den Termin erinnern? %reminder_yes_no%\r\nTelefonnummer: %phone%\r\nStraße: %street%\r\nPlz: %plz%\r\nOrt: %city%\r\n\r\nDer von Ihnen eingegebene Kommentar zum Termin:\r\n%comment%\'), ' .
						'(9,	\'Abteilung/Team/Mitarbeiter: E-Mail Text HTML\',	\'Eine neue Buchung wurde über die Webseite vorgenommen.\r\n<br />\r\nBuchungsinformationen:\r\n<br />\r\nDatum: %date%<br />\r\nZeit: %time%<br />\r\n<br />\r\nVeranstaltungs-Ort: %betriebsstaette%<br />\r\nMitarbeiter/Abteilung: %abteilung%<br />\r\n<br />\r\nKundendaten:<br />\r\n<br />\r\nVorname: %firstname%<br />\r\nNachname: %lastname%<br />\r\nE-Mail Adresse: %email_address%<br />\r\nDürfen wir per E-Mail an den Termin erinnern? %reminder_yes_no%<br />\r\nTelefonnummer: %phone%<br />\r\nStraße: %street%<br />\r\nPlz: %plz%<br />\r\nOrt: %city%<br />\r\n<br />\r\nKommentar zum Termin:<br />\r\n%comment%<br />\r\n<br />\r\n<br />\r\nBITTE ANTWORTEN SIE NICHT DIREKT AUF DIESE E-MAIL!\');';

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
						$retval[$s['field_title']] = $s['field_value'];
				}
				
				return $retval;
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////////////
		// Load Customer groups data by id.
		///////////////////////////////////////////////////////////////////////////////////////////////////
		public static function loadById($id) {
				$retval = array();
				
				$db = mv_core()->get('db');
				$db->useInstance('systemdb');
				$db->setQuery('SELECT * FROM ' . $db->table('mail_text_settings') . ' WHERE id = :id LIMIT 1;');
				$db->bind(':id', (int)$id);
				$result = $db->execute();
				
				$data = $result->fetchArrayAssoc();
				
				if(empty($data)) {
						return NULL;
				}
		
				return $data;
		}
}
