<?php

namespace mvclickandmeet_namespace;

if(!defined('ABSPATH')) { exit; }

class mvCamConfigAdmin extends mvWpAdmin {
		//////////////////////////////////////////////////////////////////////////////
		// Admin Funktionalität initialisieren.
		//////////////////////////////////////////////////////////////////////////////
		public function initAdminFunctions() {
				$this->addAdminSubMenu('mvcam', 'Termin-Assistent', 'Termin-Assistent', 'manage_options', 'mvcam_cm_assistant');
				
				$this->addAjaxAction('wp_ajax_mvCamConfigAdmin_ajaxSaveMonth', 'ajaxSaveMonth');
				
				//CSS auf dieser - und nur auf dieser - Admin-Seite einbinden.
				if(isset($_GET['page']) && $_GET['page'] == 'mvcam_cm_assistant') {
						add_action('admin_enqueue_scripts', array($this, 'admin_style'));
				}
				
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Wenn die Seite im Admin aufgerufen wird, Werte initialisieren.
		//////////////////////////////////////////////////////////////////////////////
		public function adminPageRender() {
				parent::adminPageRender();
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Add CSS to this admin page..
		//////////////////////////////////////////////////////////////////////////////
		function admin_style() {
				$plugin_url = plugins_url( mv_core()->getPluginFolderName() );
				
				wp_enqueue_style('mv-admin-styles-mvadmin', $plugin_url . '/admin/templates/css/mvadmin.css');
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Admin Text-Variablen festlegen.
		//////////////////////////////////////////////////////////////////////////////
		public function addTextVariables() {
				$this->setTextVariable('listing_title', 'Termin-Assistent');
				$this->setTextVariable('editor_title', 'Termin-Assistent');
				$this->setTextVariable('editor_title_new', 'Neuen Eintrag erstellen');
				$this->setTextVariable('editor_title_edit', 'Eintrag bearbeiten');
		}
				
		//////////////////////////////////////////////////////////////////////////////
		// Wurde überschrieben, um eigene Art von Liste anzuzeigen.
		// Außerdem: Listing erweitern um Ajax Funktionalität für Switcher.
		//////////////////////////////////////////////////////////////////////////////
		public function renderList() {
				//Haupt-Content für den Editor rendern..
				define('TEXT_MODULE_TITLE_ADMIN_CM_CALENDAR', 'Kalender');
				
				$this->drawEditor();
				
				$plugin_url = plugins_url( mv_core()->getPluginFolderName() );
				echo '<script src="' . $plugin_url . '/admin/templates/js/mvUploadQueue.js"></script>';
				echo '<script src="' . $plugin_url . '/admin/templates/js/mvUploadQueryBuilder.js"></script>';
				echo '<script src="' . $plugin_url . '/admin/templates/js/mvDate.js"></script>';
				echo '<script src="' . $plugin_url . '/admin/templates/js/mvTime.js"></script>';
				echo '<script src="' . $plugin_url . '/admin/templates/js/admin_cc_config.js"></script>';
				echo '<script src="' . $plugin_url . '/admin/templates/js/admin_cc_config_check.js"></script>';
				echo '<script src="' . $plugin_url . '/admin/templates/js/admin_cc_config_upload.js"></script>';
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Rendere den Editor..
		//////////////////////////////////////////////////////////////////////////////
		public function drawEditor() {
				$iEventLocations = new mvEventLocations();
				$event_locations = $iEventLocations->loadAllAsArray();
				$ev_indexed = $this->getEventLocationsIndexed($event_locations);
				
				$iUserUnit = new mvUserUnit();
				$user_units = $iUserUnit->loadAllAsArray();
				
				foreach($user_units as $index => $uu) {
						if(isset($ev_indexed[$uu['event_location_id']])) {
								$user_units[$index]['title_long'] = $uu['title'] . ' - ' . $ev_indexed[$uu['event_location_id']]['title'];
						} else {
								$user_units[$index]['title_long'] = $uu['title'] . ' - Nicht zugeordnet';
						}
				}
				
				$date_from = date('d.m.Y');
				$date_to = '31.12.' . date('Y');
				
				$this->data = array();
				
				$renderer = mv_core()->get('mvRenderer');
				$renderer->assign('DATA', $this->data);
				$renderer->assign('DATE_FROM', $date_from);
				$renderer->assign('DATE_TO', $date_to);
				$renderer->assign('EVENT_LOCATIONS', $event_locations);
				$renderer->assign('USER_UNITS', $user_units);
				$renderer->render('admin/templates/site/adminMvCamConfig/editor.php');
				
		}
		
		//////////////////////////////////////////////////////////////////////////////////
		// Event-Locations indexiert laden.
		//////////////////////////////////////////////////////////////////////////////////
		private function getEventLocationsIndexed($event_locations) {
				$retval = array();
				
				foreach($event_locations as $el) {
						$retval[$el['id']] = $el;
				}
				
				return $retval;
		}
		
		///////////////////////////////////////////////////////////////////
		// Daten für einen Monat speichern.
		///////////////////////////////////////////////////////////////////
		public function ajaxSaveMonth() {
				//Load post data.
				$current_day = 1;
				$current_month = (int)mv_core()->getPostVar('current_month');
				$current_year = (int)mv_core()->getPostVar('current_year');
				
				$date_from_day = (int)mv_core()->getPostVar('date_from_day');
				$date_from_month = (int)mv_core()->getPostVar('date_from_month');
				$date_from_year = (int)mv_core()->getPostVar('date_from_year');
				
				$date_to_day = (int)mv_core()->getPostVar('date_to_day');
				$date_to_month = (int)mv_core()->getPostVar('date_to_month');
				$date_to_year = (int)mv_core()->getPostVar('date_to_year');
				
				$weekdays_and_times = mv_core()->getPostVar('weekdays_and_times');
				
				$appointment_duration_in_minutes = (int)mv_core()->getPostVar('appointment_duration_in_minutes');
				$appointment_count = (int)mv_core()->getPostVar('appointment_count');
				$event_location_id = (int)mv_core()->getPostVar('event_location_id');
				$user_unit_id = (int)mv_core()->getPostVar('user_unit_id');
				
				
				
				//Allgemein benötigte Daten laden.
				$iEventLocations = new mvEventLocations();
				$this->event_locations = $iEventLocations->loadAllAsArray();
				$ev_indexed = $this->getEventLocationsIndexed($this->event_locations);
				
				$iUserUnit = new mvUserUnit();
				$this->user_units = $iUserUnit->loadAllAsArray();
				
				foreach($user_units as $index => $uu) {
						if(isset($ev_indexed[$uu['event_location_id']])) {
								$this->user_units[$index]['title_long'] = $uu['title'] . ' - ' . $ev_indexed[$uu['event_location_id']]['title'];
						} else {
								$this->user_units[$index]['title_long'] = $uu['title'] . ' - Nicht zugeordnet';
						}
				}
				
				//Delete Mode anhand event Location und user unit id ermitteln.
				$creation_mode = 'all';
				
				if($user_unit_id != 0) {
						$creation_mode = 'user_unit';
				} else if($event_location_id != 0) {
						$creation_mode = 'event_location';
				}
				
				//Wenn wir gerade den Startmonat verarbeiten, beginnen wir erst ab dem gewählten Tag.
				if($current_month == $date_from_month && $current_year == $date_from_year) {
						$current_day = $date_from_day;
				}
				
				//Check date to..
				$day_to = cal_days_in_month(CAL_GREGORIAN, $current_month, $current_year);
				
				//Wenn wir gerade den Endmonat verarbeiten, enden wir am letzten Tag..
				if($current_month == $date_to_month && $current_year == $date_to_year) {
						$day_to = $date_to_day;
				}
				
				//Durch die Tage des Monats laufen..
				for($i = $current_day; $i <= $day_to; $i++) {
						$day = $current_year . '-' . str_pad($current_month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);

						$timestamp = strtotime($day);
						$weekday = date('N', $timestamp);		//N 	Numerische Repräsentation des Wochentages gemäß ISO-8601 (in PHP 5.1.0 hinzugefügt) 	1 (für Montag) bis 7 (für Sonntag)
						$status = false;
						$data = false;
						
						foreach($weekdays_and_times as $wat) {
								if(!isset($wat['weekday']) || !isset($wat['status'])) {
										continue;
								}
								
								if($wat['weekday'] > $weekday) {
										break;
								}
								
								if(isset($wat['data']) && is_array($wat['data']) && count($wat['data']) > 0) {
										$data = $wat['data'];
								}
								
								if($wat['weekday'] == $weekday) {
										$status = $wat['status'];
										break;
								}
						}
						
						//Wenn der Wochentag aktiviert ist -> lege die Zeiten für diesen Wochentag an..
						if($status == "true") {
								//Durch die Zeiten laufen..
								$this->insertTimes(
										$i,
										$current_month,
										$current_year,
										$data,
										$appointment_duration_in_minutes,
										$appointment_count,
										$event_location_id,
										$user_unit_id,
										$creation_mode
								);
						}
				}
				
				//Say okay..
				$retval = array(
						'status' => 'success',
						'data' => array(
						)
				);
				$retval = json_encode($retval, JSON_PRETTY_PRINT);
				echo $retval;
				die;
		}
		
		///////////////////////////////////////////////////////////////////
		// Zeiten einfügen.
		///////////////////////////////////////////////////////////////////
		private function insertTimes($day, $month, $year, $times, $appointment_duration_in_minutes, $appointment_count, $event_location_id, $user_unit_id, $creation_mode) {
				foreach($times as $time) {
						$hour = (int)$time['timeFrom']['hour'];
						$minute = (int)$time['timeFrom']['minute'];
						$hour_to = (int)$time['timeTo']['hour'];
						$minute_to = (int)$time['timeTo']['minute'];
						
						do {
								//insert..
								for($i = 0; $i < $appointment_count; $i++) {
										//echo 'Füge neuen dingens ein am: ' . $day . '.' . $month . '.' . $year . ' ' . $hour . ':' . $minute . "\n";
										$this->insertAppointmentByMode($day, $month, $year, $hour, $minute, $appointment_duration_in_minutes, $event_location_id, $user_unit_id, $creation_mode);
								}
								
								$minute += (int)($appointment_duration_in_minutes);
								
								if($minute > 59) {
										//Das ist etwas umständlich, aber so bekommen wir auch Zeitspannen hin, die länger als 1 Stunde gehen.
										$add_hours = (int)($minute / 60);
										$minute = (int)($minute % 60);	//Rest ausrechnen..
										//$minute -= (int)60;
										$hour += (int)($add_hours);
								}
								
								if($hour == $hour_to && $minute >= $minute_to) {
										break;
								}
						} while($hour < $hour_to);
				}
		}
		
		///////////////////////////////////////////////////////////////////
		// Modi prüfen, auswerten und je nach Modi Termin einfügen.
		///////////////////////////////////////////////////////////////////
		private function insertAppointmentByMode($day, $month, $year, $hour, $minute, $appointment_duration_in_minutes, $event_location_id, $user_unit_id, $creation_mode) {
				if($creation_mode == 'all') {
						//Laufe durch alle user-units..
						foreach($this->user_units as $uu) {
								$user_unit_id = $uu['id'];
								$event_location_id = $uu['event_location_id'];
								
								$this->insertAppointment($day, $month, $year, $hour, $minute, $appointment_duration_in_minutes, $event_location_id, $user_unit_id);
						}
				} else if($creation_mode == 'user_unit') {
						//Erstelle nur für die gewählte user-unit (event location mit eintragen!)
						$event_location_id = 0;
						
						foreach($this->user_units as $uu) {
								if($uu['id'] == $user_unit_id) {
										$event_location_id = $uu['event_location_id'];
										break;
								}
						}
						
						if($event_location_id == 0) {
								return;
						}
						
						$this->insertAppointment($day, $month, $year, $hour, $minute, $appointment_duration_in_minutes, $event_location_id, $user_unit_id);
				} else if($creation_mode == 'event_location') {
						//Erstelle für event-location
						foreach($this->user_units as $uu) {
								if($uu['event_location_id'] == $event_location_id) {
										$user_unit_id = $uu['id'];
										
										$this->insertAppointment($day, $month, $year, $hour, $minute, $appointment_duration_in_minutes, $event_location_id, $user_unit_id);
								}
						}
				}
		}
				
		///////////////////////////////////////////////////////////////////
		// Termin einfügen.
		///////////////////////////////////////////////////////////////////
		private function insertAppointment($day, $month, $year, $hour, $minute, $appointment_duration_in_minutes, $event_location_id, $user_unit_id) {
				$day = str_pad($day, 2, '0', STR_PAD_LEFT);
				$month = str_pad($month, 2, '0', STR_PAD_LEFT);
				$hour = str_pad($hour, 2, '0', STR_PAD_LEFT);
				$minute = str_pad($minute, 2, '0', STR_PAD_LEFT);
				
				$time_string = $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $minute . ':00';
				
				//Daten für Datenbank-Query zusammenstellen.
				$data = array(
						'title' => 'Generated by adminCcConfig',
						'datetime_of_event' => $time_string,
						'event_location_id' => $event_location_id,
						'user_unit_id' => $user_unit_id,
						'status' => 1,
						'created_by' => $_SESSION['user_id'],
						'datetime_checkin' => '0000-00-00 00:00',
						'datetime_checkout' => '0000-00-00 00:00',
						'visitor_user_id' => 0,
						'checkin_by' => 0,
						'checkout_by' => 0,
						'comment_checkin' => "",
						'comment_checkout' => "",
						'comment_visitor_booking' => "",
						'reminder_user_mail' => "",
						'reminder_active' => 1,
						'reminder_user_mail_sent' => 0,
						'reminder_user_mail_sent_datetime' => '0000-00-00 00:00',
						'duration_in_minutes' => $appointment_duration_in_minutes,
						'firstname' => '',
						'lastname' => '',
						'email_address' => '',
						'customers_number' => '',
						'phone' => '',
						'street' => '',
						'street_number' => '',
						'plz' => '',
						'city' => '',
						'last_save_datetime' => date('Y-m-d H:i:s')
				);
				
				$appointment = new mvAppointment();
				$appointment->createInDb($data);
		}
}

