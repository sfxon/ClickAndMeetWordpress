<?php

namespace mvclickandmeet_namespace;

class cAdminCmCalendar extends cModule {
		var $template = 'admin';
		var $navbar_title = TEXT_MODULE_TITLE_ADMIN_CM_CALENDAR;
		var $navbar_id = 0;
		var $errors = array();
		var $errors_description = array();
		var $results = array();
		var $data;
		
		
		/*
		//////////////////////////////////////////////////////////////////////////////////
		// Hook us into the system
		//////////////////////////////////////////////////////////////////////////////////
		public static function setExecutionalHooks() {
				//If the user is not logged in..
				if(!isset($_SESSION['user_id'])) {
						header('Location: index.php/account');
						die;
				}
				
				//check the rights..
				if(false === cAccount::adminrightCheck('cAdminCmCalendar', 'USE_MODULE', (int)$_SESSION['user_id'])) {
						header('Location: index.php?s=cAdmin&error=1001');
						die;
				}
				
				//We use the Admin module for output.
				cAdmin::setSmallBodyExecutionalHooks();	
				
				//Now set our own hooks below the CMS hooks.
				$core = core();
				core()->setHook('cCore|process', 'process');
				core()->setHook('cRenderer|content', 'content');
				core()->setHook('cRenderer|footer', 'footer');
		}
		
		
	
	
		///////////////////////////////////////////////////////////////////
		// processData
		///////////////////////////////////////////////////////////////////
		function process() {
				$this->action = core()->getGetVar('action');
				$cAdmin = core()->getInstance('cAdmin');
				$cAdmin->appendBreadcrumb(TEXT_MODULE_TITLE_ADMIN_CM_CALENDAR, 'index.php?s=cAdminCmCalendar');
				
				switch($this->action) {
						case 'ajaxLoadMonth':
								$this->ajaxLoadMonth();
								die;
						case 'ajaxLoadAppointments':
								$this->ajaxLoadAppointments();
								die;
						case 'ajaxSaveAppointment':
								$this->ajaxSaveAppointment();
								die;
						case 'ajaxDeleteAppointment':
								$this->ajaxDeleteAppointment();
								die;
						default:
								$this->loadDefaultData();
								break;
				}
		}
		
		///////////////////////////////////////////////////////////////////
		// Kalendereinträge laden.
		///////////////////////////////////////////////////////////////////
		private function ajaxLoadAppointments() {
				$event_location = (int)core()->getPostVar('event_location');
				$user_unit_id = (int)core()->getPostVar('user_unit_id');
				$day = (int)core()->getPostVar('day');
				$month = (int)core()->getPostVar('month');
				$year = (int)core()->getPostVar('year');
				
				$sql_date = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
				$sql_date_from = $sql_date . ' 00:00:00';
				$sql_date_to = $sql_date . ' 23:59:59';
				
				$user_id = (int)$_SESSION['user_id'];
				
				$iAppointment = new cAppointment();
				$appointments = array();
				
				if($user_unit_id == 0 && $event_location == 0) {
						$appointments = $iAppointment->loadListByDateRangeAndUserId($sql_date_from, $sql_date_to, $user_id);
				} else if($user_unit_id != 0) {
						$appointments = $iAppointment->loadListByDateRangeAndUserIdAndUserUnitId($user_unit_id, $sql_date_from, $sql_date_to, $user_id);
				} else if($event_location != 0) {
						$appointments = $iAppointment->loadListByDateRangeAndUserIdAndEventLocation($event_location, $sql_date_from, $sql_date_to, $user_id);
				}
				
				//Add more information to the list: eventLocation
				$iEventLocations = new cEventLocations();
				$event_location_list = $iEventLocations->loadIndexedList();
				
				$iCmAppointmentStatus = new cCmAppointmentStatus();
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
		private function ajaxSaveAppointment() {
				$appointment_id = (int)core()->getPostVar('appointment_id');
				$day = (int)core()->getPostVar('day');
				$month = (int)core()->getPostVar('month');
				$year = (int)core()->getPostVar('year');
				$hour = (int)core()->getPostVar('hour');
				$minute = (int)core()->getPostVar('minute');
				$event_location_id = (int)core()->getPostVar('event_location_id');
				$user_unit_id = (int)core()->getPostVar('user_unit_id');
				$duration_in_minutes = (int)core()->getPostVar('duration_in_minutes');
				$status = (int)core()->getPostVar('status');
				$comment_visitor_booking = core()->getPostVar('comment_visitor_booking');
				
				$firstname = core()->getPostVar('firstname');
				$lastname = core()->getPostVar('lastname');
				$email_address = core()->getPostVar('email_address');
				$email_reminder = (int)core()->getPostVar('email_reminder');
				$customers_number = core()->getPostVar('customers_number');
				$phone = core()->getPostVar('phone');
				$street = core()->getPostVar('street');
				$plz = core()->getPostVar('plz');
				$city = core()->getPostVar('city');
				
				$checkin_date_day = (int)core()->getPostVar('checkin_date_day');
				$checkin_date_month = (int)core()->getPostVar('checkin_date_month');
				$checkin_date_year = (int)core()->getPostVar('checkin_date_year');
				$checkin_time_hour = (int)core()->getPostVar('checkin_time_hour');
				$checkin_time_minute = (int)core()->getPostVar('checkin_time_minute');
				
				$checkout_date_day = (int)core()->getPostVar('checkout_date_day');
				$checkout_date_month = (int)core()->getPostVar('checkout_date_month');
				$checkout_date_year = (int)core()->getPostVar('checkout_date_year');
				$checkout_time_hour = (int)core()->getPostVar('checkout_time_hour');
				$checkout_time_minute = (int)core()->getPostVar('checkout_time_minute');
				
				$checkin_comment = core()->getPostVar('checkin_comment');
				$checkout_comment = core()->getPostVar('checkout_comment');
				
				//Prüfe und erstelle Werte..
				$datetime = $this->buildDateTime($year, $month, $day, $hour, $minute);
				$datetime_checkin = $this->buildDateTime($checkin_date_year, $checkin_date_month, $checkin_date_day, $checkin_time_hour, $checkin_time_minute);
				$datetime_checkout = $this->buildDateTime($checkout_date_year, $checkout_date_month, $checkout_date_day, $checkout_time_hour, $checkout_time_minute);
				
				$last_save_datetime = core()->getPostVar('last_save_datetime');
				
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
								'base_url' => cCMS::loadTemplateUrl(core()->get('site_id')),
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
		// Kalendereinträge laden.
		///////////////////////////////////////////////////////////////////
		private function ajaxDeleteAppointment() {
				$appointment_id = (int)core()->getPostVar('appointment_id');
				
				if($appointment_id != 0) {
						$appointment = new cAppointment();
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
		
		///////////////////////////////////////////////////////////////////
		// Termin erstellen.
		///////////////////////////////////////////////////////////////////
		private function createAppointment($data) {
				$data['last_save_datetime'] = date('Y-m-d H:i:s');
				
				$appointment = new cAppointment();
				$appointment->createInDb($data);
		}
		
		///////////////////////////////////////////////////////////////////
		// Termin aktualisieren.
		///////////////////////////////////////////////////////////////////
		private function updateAppointment($id, $data) {
				$appointment = new cAppointment();
				
				//Try to load the appointment.
				$server_data = $appointment->loadById($id);
				
				if(false == $server_data) {
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
		// Monatsdaten laden.
		///////////////////////////////////////////////////////////////////
		private function ajaxLoadMonth() {
				$event_location = (int)core()->getPostVar('event_location');
				$user_unit = (int)core()->getPostVar('user_unit');
				$day = 1;
				$month = (int)core()->getPostVar('month');
				$year = (int)core()->getPostVar('year');
				
				$month_data = $this->loadMonth($month, $year);
				
				//Termin-Anzahl ermitteln.
				$iAppointment = new cAppointment();
				$appointent_count = array();
				
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
				$renderer = core()->getInstance('cRenderer');
				$renderer->setTemplate($this->template);
				$renderer->assign('DATA', $this->data);
				$renderer->assign('TEMPLATE_URL', cCMS::loadTemplateUrl(core()->get('site_id')));
				$renderer->assign('NAVBAR_TITLE', $this->navbar_title);
				$html = $renderer->fetch('site/adminCmCalendar/calendar.html');
				
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
		*/
		
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
		
		/*
		
		///////////////////////////////////////////////////////////////////
		// Draw the page content.
		///////////////////////////////////////////////////////////////////
		public function content() {
				$this->drawEditor();
		}
		
		///////////////////////////////////////////////////////////////////
		// Draw the editor.
		///////////////////////////////////////////////////////////////////
		*/
		
		function drawEditor() {
				$iEventLocations = new cEventLocations();
				$event_locations = $iEventLocations->loadListByUserId($_SESSION['user_id']);
				$ev_indexed = $this->getEventLocationsIndexed($event_locations);
				
				$iUserUnit = new cUserUnit();
				$user_units = $iUserUnit->loadList();
				
				foreach($user_units as $index => $uu) {
						if(isset($ev_indexed[$uu['event_location_id']])) {
								$user_units[$index]['title_long'] = $uu['title'] . ' - ' . $ev_indexed[$uu['event_location_id']]['title'];
						} else {
								$user_units[$index]['title_long'] = $uu['title'] . ' - Nicht zugeordnet';
						}
				}
				
				$iCmAppointmentStatus = new cCmAppointmentStatus();
				$appointment_status = $iCmAppointmentStatus->loadList();
				
				$iCmSettings = new cCmSettings();
				$cm_settings = $iCmSettings->loadSettingsIndexed();
						
				$renderer = core()->getInstance('cRenderer');
				$renderer->setTemplate($this->template);
				$renderer->assign('DATA', $this->data);
				$renderer->assign('TEMPLATE_URL', cCMS::loadTemplateUrl(core()->get('site_id')));
				$renderer->assign('NAVBAR_TITLE', $this->navbar_title);
				$renderer->assign('EVENT_LOCATIONS', $event_locations);
				$renderer->assign('USER_UNITS', $user_units);
				$renderer->assign('CM_APPOINTMENT_STATUS', $appointment_status);
				$renderer->assign('CM_SETTINGS', $cm_settings);
				$renderer->render('site/adminCmCalendar/editor.html');
		}
		
		/*
		
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
		
		//////////////////////////////////////////////////////////////////////////////////
		// Hook us into the system in the additional hooks..
		//////////////////////////////////////////////////////////////////////////////////
    public static function setAdditionalHooks() {
				core()->setHook('cCore|init', 'addMenuBarEntries');
				
    }
		
		//////////////////////////////////////////////////////////////////////////////////
		// Callback function, adds a menu item.
		//////////////////////////////////////////////////////////////////////////////////
		public static function addMenuBarEntries() {
				$cAdmin = core()->getInstance('cAdmin');
				
				if(false !== $cAdmin) {
						$admin_menu_entry_path = array(
								array(
										'position' => 250,
										'title' => 'Click&Meet'
								),
								array(
										'position' => 10,
										'title' => 'Kalender'
								)
						);
		        $cAdmin->registerAdminMenuEntry($admin_menu_entry_path, 'index.php?s=cAdminCmCalendar');
				}
		}
		
		/////////////////////////////////////////////////////////////////////////////////
		// Loads a content entry.
		/////////////////////////////////////////////////////////////////////////////////
		public function footer() {
				$additional_output = 
						"\n" . '<script src="data/templates/' . $this->template . '/js/mvUploadQueue.js"></script>' .
						"\n" . '<script src="data/templates/' . $this->template . '/js/mvTime.js"></script>' .
            "\n" . '<script src="data/templates/' . $this->template . '/js/mvDate.js"></script>' .
						"\n" . '<script src="data/templates/' . $this->template . '/js/admin_cm_calendar.js"></script>' .
						"\n" . '<script src="data/templates/' . $this->template . '/js/admin_cm_timer.js"></script>' .
						"\n" . '<script src="data/templates/' . $this->template . '/js/moment.js"></script>' .
						"\n" . '<script src="data/templates/' . $this->template . '/js/dtsel.js"></script>' .
						"\n" . '<script src="data/templates/' . $this->template . '/js/jquery-clock-timepicker.js"></script>' .	//https://github.com/loebi-ch/jquery-clock-timepicker
						"\n" . '<script src="data/templates/' . $this->template . '/js/mvCmTimerLoad.js"></script>' .
						"\n" . '<script src="data/templates/' . $this->template . '/js/mvCmTimerSaveAppointment.js"></script>' .
						"\n" . '<script src="data/templates/' . $this->template . '/js/mvCmTimerDeleteAppointment.js"></script>' .
						"\n";
				$renderer = core()->getInstance('cRenderer');
				$renderer->renderText($additional_output);
		}
		*/
}
?>