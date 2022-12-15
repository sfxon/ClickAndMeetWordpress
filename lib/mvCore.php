<?php

namespace mvclickandmeet_namespace;

class mvCore {
		var $models = false;
		var $data = array();
		var $modules = array();
		
		//////////////////////////////////////////////////////////////////////////////
		// Verschiedene Module initialisieren, die wir immer mal wieder benötigen.
		//////////////////////////////////////////////////////////////////////////////
		public function __construct() {
				$this->modules['db'] = new db();
				$this->modules['mvRenderer'] = new mvRenderer();
		}
			
		
		//////////////////////////////////////////////////////////////////////////////
		// Get POST Variable
		//////////////////////////////////////////////////////////////////////////////
		public static function getPostVar($name) {
				if(isset($_POST[$name])) {
						return $_POST[$name];
				}
				
				return false;
		}

		//////////////////////////////////////////////////////////////////////////////
		// Get GET Variable
		//////////////////////////////////////////////////////////////////////////////
		public static function getGetVar($name) {
				if(isset($_GET[$name])) {
						return $_GET[$name];
				}
				
				return false;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Get GET Variable
		//////////////////////////////////////////////////////////////////////////////
		public function registerAdminInstallation() {
				//We use the first model we find, to register the menu and instantiate the database installation..
				if(!is_array($this->models)) {
						die('Error in mvCore->registerAdminInstallation. No valid module found (1). See file: ' . __FILE__ . ', LINE: ' . __LINE__);
				}
				
				$found_module = false;
				
				foreach($this->models as $model) {
						$class_name = __NAMESPACE__ . '\\' . $model . 'Admin';		//Get Admin Filename
						
						if(!class_exists($class_name)) {
								continue;
						}
						
						if(!method_exists($class_name, 'initAdminFunctions')) {
								continue;
						}
						
						$instance = new $class_name();
						
						$found_module = true;
						break;
				}
				
				if(!$found_module) {
						die('Error in mvCore->registerAdminInstallation. No valid module found (2). See file: ' . __FILE__ . ', LINE: ' . __LINE__);
				}
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Gibt den aktuellen Wert eines Feldes zurück.
		//////////////////////////////////////////////////////////////////////////////
		public function checkModelInstallation() {
				foreach($this->models as $model) {
						$class_name = __NAMESPACE__ . '\\' . $model;
						
						//Prüfe ob es das Model so gibt (Klassenname)
						if(!class_exists($class_name)) {
								die('Klasse "' . $model . '" existiert nicht in ' . __FILE__ . ', Zeile: ' . __LINE__);
						}
						
						//Prüfen, ob Datenbank-Installation okay ist für dieses Model..
						$instance = new $class_name;
						$result = $instance->checkDatabaseInstallation();
						
						if(false === $result) {
								//check if install action was called or show install screen
								if(isset($_GET['action']) && $_GET['action'] == 'install_admin_database') {
										$this->installDatabaseTables();
										$slug = $_GET['page'];
										header('Location: ' . admin_url('admin.php?page=' . $slug . '&installation_successful=1'));
										die;
								}
								
								//Datenbank-Modul fehlt..
								return false;
						}
				}
				
				return true;
		}

		//////////////////////////////////////////////////////////////////////////////
		// Datenbank-Modelle in der Datenbank installieren.
		//////////////////////////////////////////////////////////////////////////////		
		private function installDatabaseTables() {
				foreach($this->models as $model) {
						$class_name = __NAMESPACE__ . '\\' . $model;
						
						//Prüfe ob es das Model so gibt (Klassenname)
						if(!class_exists($class_name)) {
								die('Klasse "' . $model . '" existiert nicht in ' . __FILE__ . ', Zeile: ' . __LINE__);
						}
						
						//Prüfen, ob Datenbank-Installation okay ist für dieses Model..
						$instance = new $class_name;
						
						if(method_exists($instance, 'checkDatabaseInstallation')) {
								if(!$instance->checkDatabaseTableInstallation()) {
										if(method_exists($instance, 'installTableInDb')) {
												$instance->installTableInDb();
										} else {
												die('Modul fordert Datenbank-Tabelle in Datenbank-Check, hat aber keine Installationsroutine für die Installation: ' . __FILE__ . ', Zeile: ' . __LINE__);
										}
										
										//Erweiterte Installation (führt z.B SQL Queries aus, die zusätzliche Werte wie Standard-Werte schreiben..
										if(method_exists($instance, 'installDB')) {
												$instance->installDB();
										}
								} elseif(!$instance->checkDatabaseTableVersion()) {
										if(method_exists($instance, 'updateTableInDb')) {
												$instance->updateTableInDb();
										} else {
												die('Modul fordert Datenbank-Tabellen-Update in Datenbank-Check, hat aber keine Funktionsroutine für das Update: ' . __FILE__ . ', Zeile: ' . __LINE__);
										}
								}
						}
				}
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Datenbank-Modelle festlegen.
		//////////////////////////////////////////////////////////////////////////////
		public function setModels($models) {
				$this->models = $models;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Plugin Ordner ermitteln.
		//////////////////////////////////////////////////////////////////////////////
		public function getPluginFolderName() {
				return basename(dirname(dirname(__FILE__)));
		}
		//////////////////////////////////////////////////////////////////////////////
		// Daten aus globalem Speicher abfragen.
		//////////////////////////////////////////////////////////////////////////////
		public function getData($name, $index) {
				if(isset($this->data[$name])) {
						if(isset($this->data[$name][$index])) {
								return $this->data[$name][$index];
						}
				}
				
				return NULL;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Daten in globalen Speicher schieben.
		//////////////////////////////////////////////////////////////////////////////
		public function setData($name, $index, $data) {
				$this->data[$name][$index] = $data;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Daten in globalen Speicher schieben.
		//////////////////////////////////////////////////////////////////////////////
		public function get($title) {
				if(isset($this->modules[$title])) {
						return $this->modules[$title];
				}
				
				return false;
		}
}