<?php

namespace mvclickandmeet_namespace;

$mvCamCalendarFullShortcodeHandler = new mvCamCalendarFullShortcodeHandler();

class mvCamCalendarFullShortcodeHandler {
		var $shortcode_text = 'clickandmeet_cal';
		
		/////////////////////////////////////////////////////////////////////////
		// Konstruktor. Installiert auch alle Event Handler.
		/////////////////////////////////////////////////////////////////////////
		function __construct() {
				//Install shortcode handler.
				add_shortcode($this->shortcode_text, array( $this, 'shortcodeHandler'));
				
				//Add custom CSS in header.
				add_action('wp_head', array( $this, 'my_custom_styles'), 1000);
				
				//Add Javascript and CSS files
				add_action('wp_enqueue_scripts', array( $this, 'add_javascript_and_css'));
				
				//Install ajax Call Handlers.
				add_action( 'wp_ajax_mvcm_ajaxLoadCalendar', array($this, 'mvcm_ajaxLoadCalendar'));
				add_action( 'wp_ajax_mvcm_ajaxLoadCalendar', array($this, 'mvcm_ajaxLoadCalendar'));        // to logged in users
				add_action( 'wp_ajax_cFrontendCm_ajaxLoadAppointments', array($this, 'ajaxLoadAppointments'));
				add_action( 'wp_ajax_mvcm_ajaxLoadMonth', array($this, 'ajaxLoadMonth'));
				add_action( 'wp_ajax_cFrontendCm_ajaxChooseAppointment', array($this, 'ajaxChooseAppointment'));
				
				//Have to install nopriv Handlers, too, because this is seen when user is not logged in!
				add_action( 'wp_ajax_nopriv_mvcm_ajaxLoadCalendar', array($this, 'mvcm_ajaxLoadCalendar'));
				add_action( 'wp_ajax_nopriv_mvcm_ajaxLoadCalendar', array($this, 'mvcm_ajaxLoadCalendar'));        // to logged in users
				add_action( 'wp_ajax_nopriv_cFrontendCm_ajaxLoadAppointments', array($this, 'ajaxLoadAppointments'));
				add_action( 'wp_ajax_nopriv_mvcm_ajaxLoadMonth', array($this, 'ajaxLoadMonth'));
				add_action( 'wp_ajax_nopriv_cFrontendCm_ajaxChooseAppointment', array($this, 'ajaxChooseAppointment'));
				
		}
		
		/////////////////////////////////////////////////////////////////////////
		// Shortcode verarbeiten.
		/////////////////////////////////////////////////////////////////////////
		public function shortcodeHandler($atts) {
				//Parse Attributes	-> We have to use lowercase and 
				$atts = shortcode_atts(array(
						'ladenzeigen' => '1',
						'ladenid' => '0',
						'teamzeigen' => '1',
						'teamid' => '0',
						'selectlocationfirst' => '0',
						'selectteamfirst' => '0',		//Mit dieser Option wird der Kalender erst angezeigt, wenn ein Team ausgewählt wurde (und wieder ausgeblendet, wenn kein Team gewählt ist).
						'time' => ''
				), $atts);
			
				//Output the shortcode text to a variable
				$text = $this->shortcodeHandlerOutput($atts);
				
				return $text;
		}
		
		/////////////////////////////////////////////////////////////////////////
		// CSS im Header ausgeben (ACHTUNG! in STYLE einbetten!)
		/////////////////////////////////////////////////////////////////////////
		public function my_custom_styles() {
				$mvCalendarColors = new mvCalendarColors();
				$calendar_colors = $mvCalendarColors->loadIndexedList();

				//Load the CMS Entry for the login page.
				$renderer = mv_core()->get('mvRenderer');
				$renderer->assign('MV_CALENDAR_COLORS', $calendar_colors);
				echo $renderer->fetch('frontend/calendarFull/shortcode_output_css.php');
		}
		
		/////////////////////////////////////////////////////////////////////////
		// Javascript hinzufügen.
		/////////////////////////////////////////////////////////////////////////
		public function add_javascript_and_css() {
				global $post;
				
				if(!isset($post->post_content)) {
						return;
				}
				
				$baseUrl = plugins_url( mv_core()->getPluginFolderName() );
				
				//CSS
				wp_register_style( 'mvcam_cm_calendar-css', $baseUrl . "/frontend/template/css/cm_calendar.css" );
    		wp_enqueue_style( 'mvcam_cm_calendar-css' );
				
				//Load this javascript only, when page has the editor integrated.
				if(has_shortcode($post->post_content, $this->shortcode_text)) {
						//Javascript
						wp_enqueue_script('mvcam_cm_calendar-js', $baseUrl . "/frontend/template/js/cm_calendar.js", array( 'jquery' ), false, false);
						wp_enqueue_script('mvcam_cm_timer-js', $baseUrl . "/frontend/template/js/cm_timer.js", array( 'mvcam_cm_calendar-js' ), false, false);
						wp_enqueue_script('mvcam_cm_cal_load_initial-js', $baseUrl . "/frontend/template/js/mvCmCalLoadInitial.js", array( 'mvcam_cm_timer-js' ), false, false);
						wp_enqueue_script('mvcam_upload-queue-js', $baseUrl . "/frontend/template/js/mvUploadQueue.js", array( 'mvcam_cm_cal_load_initial-js' ), false, false);
						wp_enqueue_script('mvcam_upload_query_builder-js', $baseUrl . "/frontend/template/js/mvUploadQueryBuilder.js", array( 'mvcam_upload-queue-js' ), false, false);
						wp_enqueue_script('mvcam_mv-form-js', $baseUrl . "/frontend/template/js/mvForm.js", array( 'mvcam_upload_query_builder-js' ), false, false);
						wp_enqueue_script('mvcam_cm_timer_load-js', $baseUrl . "/frontend/template/js/mvCmTimerLoad.js", array( 'mvcam_mv-form-js' ), false, false);
						wp_enqueue_script('mvcam_cm_moment-js', $baseUrl . "/frontend/template/js/moment.js", array( 'mvcam_cm_timer_load-js' ), false, false);
						wp_enqueue_script('mvcam-mvFormSend-js', $baseUrl . "/frontend/template/js/mvFormSend.js", array( 'mvcam_cm_moment-js' ), false, false);
				}
		}
		
		/////////////////////////////////////////////////////////////////////////
		// Ausgabe des Shortcode Handlers.
		/////////////////////////////////////////////////////////////////////////
		private function shortcodeHandlerOutput($atts) {
				$errormessage = '';
				
				//Farben und Texte aus Einstellungen laden.
				$mvCalendarColors = new mvCalendarColors();
				$calendar_colors = $mvCalendarColors->loadIndexedList();
				
				$mvBookingFormTexts = new mvBookingFormTexts();
				$calendar_texts = $mvBookingFormTexts->loadIndexedList();
				
				$mvCmSettings = new mvCmSettings();
				$cm_settings = $mvCmSettings->loadIndexedList();

				//Load the CMS Entry for the login page.
				$renderer = mv_core()->get('mvRenderer');
				$renderer->assign('MV_CALENDAR_COLORS', $calendar_colors);
				$renderer->assign('MV_CALENDAR_TEXTS', $calendar_texts);
				$renderer->assign('CM_SETTINGS', $cm_settings);
				$renderer->assign('TEMPLATE_URL', admin_url( 'admin-ajax.php' ));
				$renderer->assign('SHORTCODE_ATTRIBUTES', $atts);
				return $renderer->fetch('frontend/calendarFull/shortcode_output.php');
		}
		
		/////////////////////////////////////////////////////////////////////////
		// Kalender laden..
		/////////////////////////////////////////////////////////////////////////
		public function mvcm_ajaxLoadCalendar() {
				$day = (int)mv_core()->getPostVar('day');
				$month = (int)mv_core()->getPostVar('month');
				$year = (int)mv_core()->getPostVar('year');
				
				$showLadenAuswahl = (int)mv_core()->getPostVar('showLadenAuswahl');
				$preSelectLadenAuswahlId = (int)mv_core()->getPostVar('preSelectLadenAuswahlId');
				$showTeamMitarbeiterAuswahl = (int)mv_core()->getPostVar('showTeamMitarbeiterAuswahl');
				$preSelectTeamMitarbeiterAuswahl = (int)mv_core()->getPostVar('preSelectTeamMitarbeiterAuswahl');
				
				$settings = array(
						'showLadenAuswahl' => $showLadenAuswahl,
						'preSelectLadenAuswahlId' => $preSelectLadenAuswahlId,
						'showTeamMitarbeiterAuswahl' => $showTeamMitarbeiterAuswahl,
						'preSelectTeamMitarbeiterAuswahl' => $preSelectTeamMitarbeiterAuswahl
				);
				
				//Aktuellen Monat laden, wenn Monat oder Jahr gleich 0 sind.
				if($month == 0 || $year == 0) {
						$day = date('j');			//Tag des Monats ohne führende Nullen
						$month = date('n');		//Monatszahl, ohne führende Nullen
						$year = date('Y');		//Jahr vierstellig
				}
				
				//Load calendar stuff..
				$cal = new mvCalendar();
				
				$cal_data = $cal->loadDefaultData($day, $month, $year);
				$cal_html = $cal->drawCalendarContainer($cal_data);
				$timer_html = $cal->drawTimerContainer($cal_data);
				$event_location_html = $cal->drawEventLocationContainer($cal_data, $settings);
				$user_unit_html = $cal->drawUserUnitsContainer($cal_data, $settings);
				
				$retval = array(
						'status' => 'success',
						'data' => array(
								'cal_data' => $cal_data,
								'calendar_html' => $cal_html,
								'timer_html' => $timer_html,
								'event_location_html' => $event_location_html,
								'user_unit_html' => $user_unit_html
						)
				);
				$retval = json_encode($retval, JSON_PRETTY_PRINT);
				echo $retval;
				die;
		}
		
		/////////////////////////////////////////////////////////////////////////
		// Termine
		/////////////////////////////////////////////////////////////////////////
		public function ajaxLoadAppointments() {
				$event_location = (int)mv_core()->getPostVar('event_location');
				$user_unit_id = (int)mv_core()->getPostVar('user_unit_id');
				$day = (int)mv_core()->getPostVar('day');
				$month = (int)mv_core()->getPostVar('month');
				$year = (int)mv_core()->getPostVar('year');
				
				$cal = new mvCalendar();
				$data = $cal->loadAppointments($event_location, $user_unit_id, $day, $month, $year);
				
				$retval = json_encode($data);
				echo $retval;
				die;
		}
		
		/////////////////////////////////////////////////////////////////////////////////
		// Kunde möchte Termin wählen.
		/////////////////////////////////////////////////////////////////////////////////
		public function ajaxChooseAppointment() {
				$appointment_id = (int)mv_core()->getPostVar('appointment_id');
				$custom_form_dropdown = htmlspecialchars(trim(mv_core()->getPostVar('custom_form_dropdown')));
				$firstname = htmlspecialchars(trim(mv_core()->getPostVar('firstname')));
				$lastname = htmlspecialchars(trim(mv_core()->getPostVar('lastname'))); 
				$email_address = htmlspecialchars(trim(mv_core()->getPostVar('email_address')));
				$email_reminder = (int)mv_core()->getPostVar('email_reminder');
				$customers_number = htmlspecialchars(trim(mv_core()->getPostVar('customers_number')));
				$phone = htmlspecialchars(trim(mv_core()->getPostVar('phone')));
				$street = htmlspecialchars(trim(mv_core()->getPostVar('street')));
				$plz = htmlspecialchars(trim(mv_core()->getPostVar('plz')));
				$city = htmlspecialchars(trim(mv_core()->getPostVar('city')));
				$comment_visitor_booking = htmlspecialchars(trim(mv_core()->getPostVar('comment_visitor_booking')));
				$time_setting = mv_core()->getPostVar('time_setting');
				
				//Check appointment_id
				$iAppointment = new mvAppointment();
				$data = $iAppointment->loadById((int)$appointment_id);
				
				if(false == $data) {
						$this->finishWithError('appointment_id');
						die;
				}
				
				if($data['status'] != 1) {
						$this->finishWithError('appointment_id');
						die;
				}
				
				//Check if appointment is in the past..
				$tmp_time = strtotime($data['datetime_of_event']);
				$tmp_time = date('Y-m-d', $tmp_time);
				$tmp_time_compare = date('Y-m-d');
				
				if($tmp_time < $tmp_time_compare) {
						$this->finishWithError('appointment_id');
						die;
				}
				
				//Check custom form dropdown
				$myoptions = get_option('mvcam_general_options');
				$form_dropdown_values = '';
				
				if(is_array($myoptions) && isset($myoptions['mvcam_general__form_dropdown_values'])) {
						$form_dropdown_values = $myoptions['mvcam_general__form_dropdown_values'];
				}
				
				$form_dropdown_values = str_getcsv($form_dropdown_values, '|', "\"");
				
				if(is_array($form_dropdown_values) && count($form_dropdown_values) > 0) {
						$tmpfound = false;
						
						foreach($form_dropdown_values as $tmpval) {
								if(htmlspecialchars($tmpval) == $custom_form_dropdown) {
										$tmpfound = true;
										break;
								}
						}
						
						if($tmpfound != true) {
								$custom_form_dropdown = substr($custom_form_dropdown, 0, 10);	//Security Fix -> use custom - but not all, to prevent injection of stuff!
						}
				} else {
						$custom_form_dropdown = substr($custom_form_dropdown, 0, 10);	//Security Fix -> use custom - but not all, to prevent injection of stuff!
				}

				//Check firstname
				if(strlen($firstname) < 2) {
						$this->finishWithError('firstname');
						die;
				}

				//Check lastname
				if(strlen($lastname) < 2) {
						$this->finishWithError('lastname');
						die;
				}

				//Check email_address
				if(strlen($email_address) < 5) {
						$this->finishWithError('email_address');
						die;
				}

				//Prepare data for saving.
				unset($data['id']);
				$data['comment_visitor_booking'] = $comment_visitor_booking;
				$data['reminder_active'] = $email_reminder;
				$data['custom_form_dropdown'] = $custom_form_dropdown;
				$data['firstname'] = $firstname;
				$data['lastname'] = $lastname;
				$data['email_address'] = $email_address;
				$data['customers_number'] = $customers_number;
				$data['phone'] = $phone;
				$data['street'] = $street;
				$data['plz'] = $plz;
				$data['city'] = $city;
				$data['status'] = 2;
				$data['last_save_datetime'] = date('Y-m-d H:i:s');
				
				$iAppointment->updateInDb($appointment_id, $data);
			
				$retval = array(
						'status' => 'success',
						'data' => array()
				);
				$retval = json_encode($retval);
				echo $retval;
				
				$data['time_setting'] = $time_setting;
				
				//Prepare E-Mail
				$this->sendMails($data);
				
				die;
		}
		
		/////////////////////////////////////////////////////////////////////////////////
		// Mit Fehlermeldung beenden.
		/////////////////////////////////////////////////////////////////////////////////
		public function finishWithError($error) {
				$retval = array(
						'status' => 'error',
						'error' => $error
				);
				$retval = json_encode($retval, JSON_PRETTY_PRINT);
				echo $retval;
				die;
		}
		
		/////////////////////////////////////////////////////////////////////////////
		// Init all needed data.
		/////////////////////////////////////////////////////////////////////////////
		public function initData() {
				$this->data = array(
						
				);
		}
		
		
		/////////////////////////////////////////////////////////////////////////////////
		// E-Mail versenden.
		/////////////////////////////////////////////////////////////////////////////////
		public function sendMails($data) {
				$mail_to = $data['email_address'];
				
				//Check, if html or plain mail should be sent.
				//Fix this in future: make multipart mail.
				$myoptions = get_option('mvcam_general_options');
				$html_or_plain = 'plain';
				
				if(is_array($myoptions) && isset($myoptions['mvcam_general__email_html_or_plain'])) {
						$html_or_plain = $myoptions['mvcam_general__email_html_or_plain'];
				}
				
				$mail_id_plain = 1;
				$mail_id_html = 2;
				$mail_id_subject = 3;
				
				$mail_data = $data;
				
				//Datum und Zeit zusammenstellen.
				$mail_data['date'] = date('d.m.Y', strtotime($data['datetime_of_event']));
				$mail_data['time'] = date('H:i', strtotime($data['datetime_of_event']));
				
				//Event Location zusammenstellen.
				$iEventLocations = new mvEventLocations();
				$event_location = $iEventLocations->loadById((int)$data['event_location_id']);
				$event_location_data = $event_location;
				
				if(false == $event_location) {
						$event_location = '';
				} else {
						$event_location = $event_location['title'];
				}
				
				$mail_data['betriebsstaette'] = $event_location;
				
				//Abteilung zusammenstellen:
				$iUserUnit = new mvUserUnit();
				$user_unit = $iUserUnit->loadById((int)$data['user_unit_id']);
				$user_unit_data = $user_unit;
				
				if(false == $user_unit) {
						$user_unit = '';
				} else {
						$user_unit = $user_unit['title'];
				}
				
				$mail_data['abteilung'] = $use_unit;
				
				//Kommentar:
				$mail_data['comment'] = $data['comment_visitor_booking'];
				
				//Ja/Nein: Erinnerungsmail
				if($data['reminder_active'] == 1) {
						$mail_data['reminder_yes_no'] = 'Ja';
				} else {
						$mail_data['reminder_yes_no'] = 'Nein';
				}
				
				//Zeit-Format für Zielzeit (bzw. Ende der Veranstaltung)
				$time_to = 'no';
				
				if($data['time_setting'] == 'from_to') {
						$time_to = $this->buildEndTime_FromTo($data);
				} else if($data['time_setting'] == 'duration') {
						$time_to = $this->buildEndTime_Duration($data);
				}
				
				$mail_data['time_to'] = $time_to;
				
				//E-Mail an Kunden senden
				$mailTextBuilder = new mvMailTextBuilder($mail_id_plain, $mail_id_html, $mail_id_subject, $mail_data);
				
				//Custom E-Mail Texte für Kunden-E-Mail:
				if($html_or_plain == 'html') {
						if(NULL !== $user_unit_data && strlen($user_unit_data['custom_email_html']) > 0) {		//bei user_units
								$mailTextBuilder->mail_text_html = $mailTextBuilder->parseText($user_unit_data['custom_email_html'], $mail_data);
						} else if(NULL !== $event_location_data && strlen($event_location_data['custom_email_html']) > 0) {		//falls keine user_units, versuche es bei event_locations
								$mailTextBuilder->mail_text_html = $mailTextBuilder->parseText($event_location_data['custom_email_html'], $mail_data);
						}
				} else {
						if(NULL !== $user_unit_data && strlen($user_unit_data['custom_email_plain']) > 0) {		//bei user_units
								$mailTextBuilder->mail_text_plain = $mailTextBuilder->parseText($user_unit_data['custom_email_plain'], $mail_data);
						} else if(NULL !== $event_location_data && strlen($event_location_data['custom_email_plain']) > 0) {		//falls keine user_units, versuche es bei event_locations
								$mailTextBuilder->mail_text_plain = $mailTextBuilder->parseText($event_location_data['custom_email_plain'], $mail_data);
						}
				}
				
				$to = $mail_to;
				$subject = $mailTextBuilder->mail_text_subject;
				$body = '';
				$headers = '';
				
				if($html_or_plain == 'html') {
						$body = $mailTextBuilder->mail_text_html;
						$headers = array('Content-Type: text/html; charset=UTF-8');
				} else {
						$body = $mailTextBuilder->mail_text_plain;
				}
				 
				wp_mail($to, $subject, $body, $headers);
				
				//Prüfen, ob E-Mails an Betriebsstätte aktiviert sind:
				if(NULL !== $event_location_data && $event_location_data['booking_info_by_mail'] == "1") {
						if($event_location_data['email_address'] != "") {
								$mailTextBuilder = new mvMailTextBuilder(6, 5, 4, $mail_data);
								
								$to = $event_location_data['email_address'];
								$subject = $mailTextBuilder->mail_text_subject;
								$body = '';
								$header = '';
								
								if($html_or_plain == 'html') {
										$body = $mailTextBuilder->mail_text_html;
										$headers = array('Content-Type: text/html; charset=UTF-8');
								} else {
										$body = $mailTextBuilder->mail_text_plain;
								}
								
						}
						
						wp_mail($to, $subject, $body, $headers);
				}
				
				
				//Prüfen, ob E-Mails an Event-Location aktiviert sind:
				if(NULL !== $user_unit_data && $user_unit_data['booking_info_by_mail'] == "1") {
						if($user_unit_data['email_address'] != "") {
								$mailTextBuilder = new mvMailTextBuilder(8, 9, 7, $mail_data);
								
								$to = $user_unit_data['email_address'];
								$subject = $mailTextBuilder->mail_text_subject;
								$body = '';
								$headers = '';
								
								if($html_or_plain == 'html') {
										$body = $mailTextBuilder->mail_text_html;
										$headers = array('Content-Type: text/html; charset=UTF-8');
								} else {
										$body = $mailTextBuilder->mail_text_plain;
								}
								
								
								wp_mail($to, $subject, $body, $headers);
						}
				}
		}
		
		/////////////////////////////////////////////////////////////////////////////////
		// Monat laden.
		/////////////////////////////////////////////////////////////////////////////////
		public function ajaxLoadMonth() {
				$event_location_id = (int)mv_core()->getPostVar('event_location');
				$user_unit = (int)mv_core()->getPostVar('user_unit');
				$day = 1;
				$month = (int)mv_core()->getPostVar('month');
				$year = (int)mv_core()->getPostVar('year');
				
				$cal = new mvCalendar();
				$data = $cal->loadMonthCalender($event_location_id, $user_unit, $day, $month, $year);
				
				$retval = array(
						'status' => 'success',
						'data' => $data
				);
				$retval = json_encode($retval);
				echo $retval;
				die;
		}
		
		/////////////////////////////////////////////////////////////////////////////////
		// Ziel-Zeit bauen bei "Von, bis"
		/////////////////////////////////////////////////////////////////////////////////
		public function buildEndTime_FromTo($data) {
				$output = "";
				$time_to = strtotime('+' . $data['duration_in_minutes'] . ' minutes', strtotime($data['datetime_of_event']));
				$time_to = date('H:i', $time_to);
				
				return $time_to;
		}
		
		/////////////////////////////////////////////////////////////////////////////////
		// Zielzeit bauen, bei "Dauer"
		/////////////////////////////////////////////////////////////////////////////////
		public function buildEndTime_Duration($data) {
				$output = "";
				$duration_in_minutes = (int)$data['duration_in_minutes'];
				$hours = (int)($duration_in_minutes / 60);
				$minutes = (int)($duration_in_minutes % 60);
				
				if($hours > 0) {
						//Stunden ausgeben.
						$output .= (string)$hours;
								
						if($hours == 1) {
								$output .= " Stunde ";
						} else {
								$output .= " Stunden ";
						}
								
						//Minuten nur anzeigen, wenn die Stunde nicht rund ist..
						if($minutes > 0) {
								$output .= (string)$minutes + ' ';
								
								if($minutes == 1) {
										$output .= ' Minute';
								} else {
										$output .= ' Minuten';
								}
						}
				} else {
						//Nur Minuten ausgeben (weniger als eine Stunde)
						$output .= (string)$duration_in_minutes;
					
						if($duration_in_minutes == 1) {
								$output .= ' Minute';
						} else {
								$output .= ' Minuten';
						}
				}
				
				return $output;
		}
		
		
}