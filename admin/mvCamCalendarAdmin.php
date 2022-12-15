<?php

namespace mvclickandmeet_namespace;

if(!defined('ABSPATH')) { exit; }

class mvCamCalendarAdmin extends mvWpAdmin {
		//////////////////////////////////////////////////////////////////////////////
		// Admin Funktionalität initialisieren.
		//////////////////////////////////////////////////////////////////////////////
		public function initAdminFunctions() {
				$this->addAdminMenu('Click and Meet', 'Click and Meet - Kalender', 'manage_options', 'mvcam');
				$this->addAjaxAction('wp_ajax_cAdminCmCalendar_ajaxLoadMonth', 'ajaxLoadMonth');
				$this->addAjaxAction('wp_ajax_cAdminCmCalendar_ajaxLoadAppointments', 'ajaxLoadAppointments');
				$this->addAjaxAction('wp_ajax_cAdminCmCalendar_ajaxSaveAppointment', 'ajaxSaveAppointment');
				$this->addAjaxAction('wp_ajax_mvclickandmeet_ajaxSaveAppointment', 'ajaxSaveAppointment');
				$this->addAjaxAction('wp_ajax_cAdminCmCalendar_ajaxDeleteAppointment', 'ajaxDeleteAppointment');
				
				//CSS auf dieser - und nur auf dieser - Admin-Seite einbinden.
				if(isset($_GET['page']) && $_GET['page'] == 'mvcam') {
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
				wp_enqueue_style('mv-admin-styles-dtsel', $plugin_url . '/admin/templates/css/dtsel.css');
				wp_enqueue_style('mv-admin-styles-mvadmin', $plugin_url . '/admin/templates/css/mvadmin.css');
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Admin Text-Variablen festlegen.
		//////////////////////////////////////////////////////////////////////////////
		public function addTextVariables() {
				$this->setTextVariable('listing_title', 'Kalender');
				$this->setTextVariable('editor_title', 'Kalender');
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
				
				//$adminCmCalendar = new cAdminCmCalendar();
				$this->loadDefaultData();
				$this->drawEditor();
				
				$plugin_url = plugins_url( mv_core()->getPluginFolderName() );
				echo '<script src="' . $plugin_url . '/admin/templates/js/admin_cm_calendar.js"></script>';
				echo '<script src="' . $plugin_url . '/admin/templates/js/admin_cm_timer.js"></script>';
				echo '<script src="' . $plugin_url . '/admin/templates/js/mvCmTimerLoad.js"></script>';
				echo '<script src="' . $plugin_url . '/admin/templates/js/mvUploadQueue.js"></script>';
				echo '<script src="' . $plugin_url . '/admin/templates/js/mvUploadQueryBuilder.js"></script>';
				echo '<script src="' . $plugin_url . '/admin/templates/js/moment.js"></script>';
				echo '<script src="' . $plugin_url . '/admin/templates/js/dtsel.js"></script>';
				echo '<script src="' . $plugin_url . '/admin/templates/js/mvDate.js"></script>';
				echo '<script src="' . $plugin_url . '/admin/templates/js/mvTime.js"></script>';
				echo '<script src="' . $plugin_url . '/admin/templates/js/jquery-clock-timepicker.js"></script>';
				echo '<script src="' . $plugin_url . '/admin/templates/js/mvBootstrapModalEmulate.js"></script>';
				echo '<script src="' . $plugin_url . '/admin/templates/js/mvCmTimerSaveAppointment.js"></script>';
				echo '<script src="' . $plugin_url . '/admin/templates/js/mvCmTimerDeleteAppointment.js"></script>';
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

				$iCmAppointmentStatus = new mvAppointmentStatus();
				$appointment_status = $iCmAppointmentStatus->loadAllAsArray();
				
				$iCmSettings = new mvCmSettings();
				$cm_settings = $iCmSettings->loadSettingsIndexed();
						
				$renderer = mv_core()->get('mvRenderer');
				$renderer->assign('DATA', $this->data);
				$renderer->assign('TEMPLATE_URL', get_bloginfo('url'));
				$renderer->assign('EVENT_LOCATIONS', $event_locations);
				$renderer->assign('USER_UNITS', $user_units);
				$renderer->assign('CM_APPOINTMENT_STATUS', $appointment_status);
				$renderer->assign('CM_SETTINGS', $cm_settings);
				$renderer->render('admin/templates/site/adminCmCalendar/editor.php');
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
		// Standard-Daten laden.
		///////////////////////////////////////////////////////////////////
		public function loadDefaultData() {
				$day = date('d');
				$month = date('m');
				$year = date('Y');
				
				$iday = ltrim($day, '0');
				$imonth = ltrim($month, '0');
				$iyear = $year;
				
				//Lade Monat..
				$month = $this->loadMonth($imonth, $iyear);
				
				$data = array(
						'today_day' => date('j'),		// Tag des Monats ohne führende Nullen 	1 bis 31
						'today_month' => date('n'), // Monatszahl, ohne führende Nullen
						'today_year' => date('Y'),
						'current_day' => $iday,
						'current_month' => $imonth,
						'current_year' => $iyear,
						'month_data' => $month
				);
				
				$this->data['date'] = $data;
		}
		
		///////////////////////////////////////////////////////////////////
		// Lade den Monat
		///////////////////////////////////////////////////////////////////
		private function loadMonth($month, $year) {
				$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year); // 31
				
				$days = array();
				
				for($i = 1; $i <= $days_in_month; $i++) {
						$sql_date = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
						
						$tmp_time = strtotime($sql_date);
						
						
						$days[] = array(
								'day' => $i,
								'month' => $month,
								'year' => $year,
								'weekday' => date('N', $tmp_time),
								'weeknumber' => date('W', $tmp_time)
						);
				}
				
				$retval = array(
						'month' => $month,
						'year' => $year,
						'days_in_month' => $days_in_month,
						'days' => $days,
						'first_day_sql' => $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad("1", 2, '0', STR_PAD_LEFT),
						'last_day_sql' => $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($days_in_month, 2, '0', STR_PAD_LEFT)
				);
				
				//calculate weeks
				//$pattern = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-';
				
				$cal_week_start = date('W', strtotime($retval['first_day_sql']));
				$cal_week_end = date('W', strtotime($retval['last_day_sql']));
				
				$weeks_in_month = ($cal_week_end - $cal_week_start) + 1;
				
				$retval['cal_week_start'] = $cal_week_start;
				$retval['cal_week_end'] = $cal_week_end;
				$retval['weeks_in_month'] = $weeks_in_month;
				
				return $retval;
		}
		
		///////////////////////////////////////////////////////////////////
		// Monatsdaten laden.
		///////////////////////////////////////////////////////////////////
		public function ajaxLoadMonth() {
				$event_location = (int)mv_core()->getPostVar('event_location');
				$user_unit = (int)mv_core()->getPostVar('user_unit');
				$day = 1;
				$month = (int)mv_core()->getPostVar('month');
				$year = (int)mv_core()->getPostVar('year');
				
				$month_data = $this->loadMonth($month, $year);
				
				//Termin-Anzahl ermitteln.
				$iAppointment = new mvAppointment();
				$appointment_count = array();
				
				if($event_location == 0 && $user_unit == 0) {
						$appointment_count = $iAppointment->countAppointmentsByDays(
								$month_data['first_day_sql'] . " 00:00:00",
								$month_data['last_day_sql'] . " 23:59:59"
						);
				} else if($user_unit != 0) {
						$appointment_count = $iAppointment->countAppointmentsByDaysAndUserUnit(
								$user_unit,
								$month_data['first_day_sql'] . " 00:00:00",
								$month_data['last_day_sql'] . " 23:59:59"
						);
				} else if($event_location != 0) {
						$appointment_count = $iAppointment->countAppointmentsByDaysAndEventLocation(
								$event_location,
								$month_data['first_day_sql'] . " 00:00:00",
								$month_data['last_day_sql'] . " 23:59:59"
						);
				}
				
				foreach($appointment_count as $ap) {
						foreach($month_data['days'] as $index => $md) {
								if((int)$ap['day'] == (int)$md['day']) {
										$month_data['days'][$index]['status_count'][] = $ap;
										break;
								}
						}
				}
				
				//Termin-Anzahl Klassen zusammensetzen:
				foreach($month_data['days'] as $index => $md) {
						$classes = '';
						
						if(isset($md['status_count'])) {
								foreach($md['status_count'] as $status) {
										$classes .= " mv-status-count-" . $status['status'];
								}
						}
						
						if(strlen($classes) > 0) {
								$month_data['days'][$index]['status_count_classes'] = $classes;
						}
				}		
				
				//Daten zusammenstellen.
				$data = array(
						'today_day' => date('j'),		// Tag des Monats ohne führende Nullen 	1 bis 31
						'today_month' => date('n'), // Monatszahl, ohne führende Nullen
						'today_year' => date('Y'),
						'current_day' => $day,
						'current_month' => $month,
						'current_year' => $year,
						'month_data' => $month_data
				);
				
				$this->data['date'] = $data;
				
				//Render month calender..
				$renderer = mv_core()->get('mvRenderer');
				//$renderer->setTemplate($this->template);
				$renderer->assign('DATA', $this->data);
				//$renderer->assign('TEMPLATE_URL', cCMS::loadTemplateUrl(core()->get('site_id')));
				//$renderer->assign('NAVBAR_TITLE', $this->navbar_title);
				$html = $renderer->fetch('admin/templates/site/adminCmCalendar/calendar.php');
				
				$retval = array(
						'status' => 'success',
						'data' => array(
								'month_data' => $data,
								'html' => $html
						)
				);
				$retval = json_encode($retval);
				echo $retval;
				die;
		}
		
		///////////////////////////////////////////////////////////////////
		// Kalendereinträge laden.
		///////////////////////////////////////////////////////////////////
		public function ajaxLoadAppointments() {
				$event_location = (int)mv_core()->getPostVar('event_location');
				$user_unit_id = (int)mv_core()->getPostVar('user_unit_id');
				$day = (int)mv_core()->getPostVar('day');
				$month = (int)mv_core()->getPostVar('month');
				$year = (int)mv_core()->getPostVar('year');
				
				$sql_date = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
				$sql_date_from = $sql_date . ' 00:00:00';
				$sql_date_to = $sql_date . ' 23:59:59';
				
				//$user_id = (int)$_SESSION['user_id'];
				
				$iAppointment = new mvAppointment();
				$appointments = array();
				
				if($user_unit_id == 0 && $event_location == 0) {
						$appointments = $iAppointment->loadListByDateRange($sql_date_from, $sql_date_to);
				} else if($user_unit_id != 0) {
						$appointments = $iAppointment->loadListByDateRangeAndUserUnitId($user_unit_id, $sql_date_from, $sql_date_to);
				} else if($event_location != 0) {
						$appointments = $iAppointment->loadListByDateRangeAndEventLocation($event_location, $sql_date_from, $sql_date_to);
				}
				
				//Add more information to the list: eventLocation
				$iEventLocations = new mvEventLocations();
				$event_location_list = $iEventLocations->loadIndexedList();
				
				$iCmAppointmentStatus = new mvAppointmentStatus();
				$cm_appointment_status = $iCmAppointmentStatus->loadIndexedList();
				
				foreach($appointments as $index => $app) {
						if(is_array($event_location_list) && isset($event_location_list[$app['event_location_id']])) {
								$appointments[$index]['event_location'] = $event_location_list[$app['event_location_id']]['title'];
						} else {
								$appointments[$index]['event_location'] = "";
						}
						
						if(is_array($cm_appointment_status) && isset($cm_appointment_status[$app['status']])) {
								$appointments[$index]['event_status'] = $cm_appointment_status[$app['status']]['title'];
						} else {
								$appointments[$index]['event_status'] = "";
						}
				}
				
				$retval = array(
						'status' => 'success',
						'data' => array(
								'iday' => $day,
								'imonth' => $month,
								'iyear' => $year,
								'day' => str_pad($day, 2, '0', STR_PAD_LEFT),
								'month' => str_pad($month, 2, '0', STR_PAD_LEFT),
								'year' => $year,
								'appointments' => $appointments
						)
				);
				$retval = json_encode($retval);
				echo $retval;
				die;
		}
		
		///////////////////////////////////////////////////////////////////
		// Kalendereinträge laden.
		///////////////////////////////////////////////////////////////////
		public function ajaxSaveAppointment() {
				$appointment_id = (int)mv_core()->getPostVar('appointment_id');
				$day = (int)mv_core()->getPostVar('day');
				$month = (int)mv_core()->getPostVar('month');
				$year = (int)mv_core()->getPostVar('year');
				$hour = (int)mv_core()->getPostVar('hour');
				$minute = (int)mv_core()->getPostVar('minute');
				$event_location_id = (int)mv_core()->getPostVar('event_location_id');
				$user_unit_id = (int)mv_core()->getPostVar('user_unit_id');
				$duration_in_minutes = (int)mv_core()->getPostVar('duration_in_minutes');
				$status = (int)mv_core()->getPostVar('status');
				$comment_visitor_booking = mv_core()->getPostVar('comment_visitor_booking');
				
				$custom_form_dropdown = mv_core()->getPostVar('custom_form_dropdown');
				$firstname = mv_core()->getPostVar('firstname');
				$lastname = mv_core()->getPostVar('lastname');
				$email_address = mv_core()->getPostVar('email_address');
				$email_reminder = (int)mv_core()->getPostVar('email_reminder');
				$customers_number = mv_core()->getPostVar('customers_number');
				$phone = mv_core()->getPostVar('phone');
				$street = mv_core()->getPostVar('street');
				$plz = mv_core()->getPostVar('plz');
				$city = mv_core()->getPostVar('city');
				
				$checkin_date_day = (int)mv_core()->getPostVar('checkin_date_day');
				$checkin_date_month = (int)mv_core()->getPostVar('checkin_date_month');
				$checkin_date_year = (int)mv_core()->getPostVar('checkin_date_year');
				$checkin_time_hour = (int)mv_core()->getPostVar('checkin_time_hour');
				$checkin_time_minute = (int)mv_core()->getPostVar('checkin_time_minute');
				
				$checkout_date_day = (int)mv_core()->getPostVar('checkout_date_day');
				$checkout_date_month = (int)mv_core()->getPostVar('checkout_date_month');
				$checkout_date_year = (int)mv_core()->getPostVar('checkout_date_year');
				$checkout_time_hour = (int)mv_core()->getPostVar('checkout_time_hour');
				$checkout_time_minute = (int)mv_core()->getPostVar('checkout_time_minute');
				
				$checkin_comment = mv_core()->getPostVar('checkin_comment');
				$checkout_comment = mv_core()->getPostVar('checkout_comment');
				
				//Prüfe und erstelle Werte..
				$datetime = $this->buildDateTime($year, $month, $day, $hour, $minute);
				$datetime_checkin = $this->buildDateTime($checkin_date_year, $checkin_date_month, $checkin_date_day, $checkin_time_hour, $checkin_time_minute);
				$datetime_checkout = $this->buildDateTime($checkout_date_year, $checkout_date_month, $checkout_date_day, $checkout_time_hour, $checkout_time_minute);
				
				$last_save_datetime = mv_core()->getPostVar('last_save_datetime');
				
				//Erstelle Datenstruktur für das Speichern in der Datenbank.
				//Daten für Datenbank-Query zusammenstellen.
				$data = array(
						'title' => '',
						'datetime_of_event' => $datetime,
						'event_location_id' => $event_location_id,
						'user_unit_id' => $user_unit_id,
						'status' => $status,
						'created_by' => $_SESSION['user_id'],
						'datetime_checkin' => $datetime_checkin,
						'datetime_checkout' => $datetime_checkout,
						'visitor_user_id' => 0,
						'checkin_by' => 0,
						'checkout_by' => 0,
						'comment_checkin' => $checkin_comment,
						'comment_checkout' => $checkout_comment,
						'comment_visitor_booking' => $comment_visitor_booking,
						'reminder_user_mail' => $email_address,
						'reminder_active' => $email_reminder,
						'reminder_user_mail_sent' => 0,
						'reminder_user_mail_sent_datetime' => '0000-00-00 00:00',
						'duration_in_minutes' => $duration_in_minutes,
						'custom_form_dropdown' => $custom_form_dropdown,
						'firstname' => $firstname,
						'lastname' => $lastname,
						'email_address' => $email_address,
						'customers_number' => $customers_number,
						'phone' => $phone,
						'street' => $street,
						'street_number' => '',
						'plz' => $plz,
						'city' => $city,
						'last_save_datetime' => $last_save_datetime
				);
				
				
				if($appointment_id == 0) {
						$this->createAppointment($data);
				} else {
						$this->updateAppointment($appointment_id, $data);
				}
				
				$retval = array(
						'status' => 'success',
						'data' => array(
								'base_url' => '',
								'day' => $day,
								'month' => $month,
								'year' => $year
						)
				);
				$retval = json_encode($retval);
				echo $retval;
				die;
		}
		
		///////////////////////////////////////////////////////////////////
		// Termin erstellen.
		///////////////////////////////////////////////////////////////////
		private function createAppointment($data) {
				$data['last_save_datetime'] = date('Y-m-d H:i:s');
				
				$appointment = new mvAppointment();
				$appointment->createInDb($data);
		}
		
		///////////////////////////////////////////////////////////////////
		// Termin aktualisieren.
		///////////////////////////////////////////////////////////////////
		private function updateAppointment($id, $data) {
				$appointment = new mvAppointment();
				
				//Try to load the appointment.
				$server_data = $appointment->loadById($id);
				
				if(NULL == $server_data) {
						$retval = array(
								'status' => 'error',
								'error' => 'unknown_appointment'
						);
						
						$retval = json_encode($retval);
						echo $retval;
						die;
				}
				
				if($server_data['last_save_datetime'] != $data['last_save_datetime']) {
						$retval = array(
								'status' => 'error',
								'error' => 'last_save_datetime_mismatch'
						);
						
						$retval = json_encode($retval);
						echo $retval;
						die;
				}
				
				$data['last_save_datetime'] = date('Y-m-d H:i:s');
				$appointment->updateInDB($id, $data);
		}
		
		///////////////////////////////////////////////////////////////////
		// SQL Datums-String aus Einzelwerten erstellen.
		///////////////////////////////////////////////////////////////////
		private function buildDateTime($year, $month, $day, $hour, $minute) {
				$sql_date_string = 
						str_pad($year, 4, "0", STR_PAD_LEFT) . '-' . 
						str_pad($month, 2, "0", STR_PAD_LEFT) . '-' . 
						str_pad($day, 2, "0", STR_PAD_LEFT) . ' ' .
						str_pad($hour, 2, "0", STR_PAD_LEFT) . ':' .
						str_pad($minute, 2, "0", STR_PAD_LEFT);
						
				return $sql_date_string;
		}
		
		///////////////////////////////////////////////////////////////////
		// Kalendereinträge laden.
		///////////////////////////////////////////////////////////////////
		public function ajaxDeleteAppointment() {
				$appointment_id = (int)mv_core()->getPostVar('appointment_id');
				
				if($appointment_id != 0) {
						$appointment = new mvAppointment();
						$appointment->deleteById((int)$appointment_id);
				}
				
				$retval = array(
						'status' => 'success',
						'data' => array(
						)
				);
				$retval = json_encode($retval);
				echo $retval;
				die;
		}
}

