<?php

namespace mvclickandmeet_namespace;

if(!defined('ABSPATH')) { exit; }

class mvCamBatchDeleteAdmin extends mvWpAdmin {
		//////////////////////////////////////////////////////////////////////////////
		// Admin Funktionalität initialisieren.
		//////////////////////////////////////////////////////////////////////////////
		public function initAdminFunctions() {
				$this->addAdminSubMenu('mvcam', 'Lösch-Assistent', 'Lösch-Assistent', 'manage_options', 'mvcam_cm_batch_delete');
				
				$this->addAjaxAction('wp_ajax_mvCamBatchDeleteAdmin_ajaxDeleteMonths', 'ajaxDeleteMonths');
				
				//CSS auf dieser - und nur auf dieser - Admin-Seite einbinden.
				if(isset($_GET['page']) && $_GET['page'] == 'mvcam_cm_batch_delete') {
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
				$this->setTextVariable('listing_title', 'Lösch-Assistent');
				$this->setTextVariable('editor_title', 'Lösch-Assistent');
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
				echo '<script src="' . $plugin_url . '/admin/templates/js/admin_batch_delete.js"></script>';
				echo '<script src="' . $plugin_url . '/admin/templates/js/admin_batch_delete_check.js"></script>';
				echo '<script src="' . $plugin_url . '/admin/templates/js/admin_batch_delete_upload.js"></script>';
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
				
				$iCmAppointmentStatus = new mvAppointmentStatus();
				$appointment_status_list = $iCmAppointmentStatus->loadAllAsArray();
				
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
				$renderer->assign('APPOINTMENT_STATUS_LIST', $appointment_status_list);
				$renderer->render('admin/templates/site/adminMvCamBatchDelete/editor.php');
				
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
		public function ajaxDeleteMonths() {
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
				
				$event_location_id = (int)mv_core()->getPostVar('event_location_id');
				$user_unit_id = (int)mv_core()->getPostVar('user_unit_id');
				
				$statis = mv_core()->getPostVar('statis');
				
				//Delete Mode anhand event Location und user unit id ermitteln.
				$delete_mode = 'all';
				
				if($user_unit_id != 0) {
						$delete_mode = 'user_unit';
				} else if($event_location_id != 0) {
						$delete_mode = 'event_location';
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
						$weekday_status = false;
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
										$weekday_status = $wat['status'];
										break;
								}
						}
						
						//Wenn der Wochentag aktiviert ist -> lege die Zeiten für diesen Wochentag an..
						if($weekday_status == "true") {
								//Durch die Zeiten laufen..
								$this->deleteTimes(
										$i,
										$current_month,
										$current_year,
										$data,
										$event_location_id,
										$user_unit_id,
										$delete_mode,
										$statis
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
		private function deleteTimes($day, $month, $year, $times, $event_location_id, $user_unit_id, $delete_mode, $statis) {
				foreach($times as $time) {
						$hour = (int)$time['timeFrom']['hour'];
						$minute = (int)$time['timeFrom']['minute'];
						$hour_to = (int)$time['timeTo']['hour'];
						$minute_to = (int)$time['timeTo']['minute'];
						
						$sql_day = str_pad($day, 2, '0', STR_PAD_LEFT);
						$sql_month = str_pad($month, 2, '0', STR_PAD_LEFT);
						$sql_year = $year;
						$sql_hour_from = str_pad($hour, 2, '0', STR_PAD_LEFT);
						$sql_minute_from = str_pad($minute, 2, '0', STR_PAD_LEFT);
						$sql_hour_to = str_pad($hour_to, 2, '0', STR_PAD_LEFT);
						$sql_minute_to = str_pad($minute_to, 2, '0', STR_PAD_LEFT);
						
						$between_from = $sql_year . '-' . $sql_month . '-' . $sql_day . ' ' . $sql_hour_from . ':' . $sql_minute_from;
						$between_to = $sql_year . '-' . $sql_month . '-' . $sql_day . ' ' . $sql_hour_to . ':' . $sql_minute_to;
						
						foreach($statis as $status) {
								if($delete_mode == 'all') {
										$this->deleteAppointments($between_from, $between_to, $status);
								} else if($delete_mode == 'user_unit') {
										$this->deleteAppointmentsUserUnit($between_from, $between_to, $status, $user_unit_id);
								} else if($delete_mode == 'event_location') {
										$this->deleteAppointmentsEventLocation($between_from, $between_to, $status, $event_location_id);
								}
						}
				}
		}
		
		///////////////////////////////////////////////////////////////////
		// Termin einfügen.
		///////////////////////////////////////////////////////////////////
		private function deleteAppointments($between_from, $between_to, $status) {
				$appointment = new mvAppointment();
				$appointment->deleteByDateFromAndDateToAndAndStatus($between_from, $between_to, $status);
		}
		
		///////////////////////////////////////////////////////////////////
		// Anhand der User Unit löschen.
		///////////////////////////////////////////////////////////////////
		private function deleteAppointmentsUserUnit($between_from, $between_to, $status, $user_unit_id) {
				$appointment = new mvAppointment();
				$appointment->deleteByDateFromAndDateToAndAndStatusAndUserUnit($between_from, $between_to, $status, $user_unit_id);
		}
		
		///////////////////////////////////////////////////////////////////
		// Anhand der Event Location löschen.
		///////////////////////////////////////////////////////////////////
		private function deleteAppointmentsEventLocation($between_from, $between_to, $status, $event_location_id) {
				$appointment = new mvAppointment();
				$appointment->deleteByDateFromAndDateToAndAndStatusAndEventLocation($between_from, $between_to, $status, $event_location_id);
		}
}

