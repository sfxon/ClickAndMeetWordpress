<?php

namespace mvclickandmeet_namespace;

class mvWpAdmin {
		var $adminMenues = array();
		var $adminSubMenues = array();
		var $admin_menu_handler_set = false;
		var $use_abilities = array('list', 'new', 'edit', 'create', 'update', 'delete');		//These abilities are used by default.
		var $menu_definition = array();
		var $listing_definition = array();
		var $delete_dialog_definition = array();
		var $model = '';
		var $text_variables = array();
		var $options = array();
		
		public function __construct($options = false) {
				//Mögliche Optionen abfragen, speichern und jetzt schon nötige Verarbeitungen durchführen.
				if($options !== false) {
						//Optionen setzen.
						$this->options = array_merge($this->options, $options);
						//Model Fetching aktivieren, wenn es noch nicht aktiviert ist.
						if(isset($options['init_ajax_model_fetching']) == true) {
								$this->initAjaxModelFetching();
						}
				}
				//Admin init Funktion des Objektes aufrufen, das von dieser Klasse geerbt hat.
				if(method_exists($this, 'initAdminFunctions')) {
						$this->initAdminFunctions();
				}
				//Scripte und Stile einbinden die wir allgemein auf Admin-Seiten benötigen (bspw. jQuery UI für Sortable, Draggable, etc..)
				add_action('admin_enqueue_scripts', array($this, 'enqueueScriptsAndStyles'));
		}
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Model initialisieren.
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		public function setModel($model) {
				$this->model = $model;
		}
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Text Variablen festlegen.
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		public function setTextVariables($text_variables) {
				$this->text_variables = array_merge($this->text_variables, $text_variables);
		}
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Text Variablen festlegen.
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		public function setTextVariable($index, $text_variable) {
				$this->text_variables[$index] = $text_variable;
		}
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Admin Menü hinzufügen.
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		public function addAdminMenu($page_title, $menu_title, $capability, $menu_slug, $function = 'adminPage', $icon_url = '', $position = 99, $action_callback_function = 'adminPageActionHandler') {
				$this->adminMenues[] = array(
						'page_title' => $page_title,
						'menu_title' => $menu_title,
						'capability' => $capability,
						'menu_slug' => $menu_slug,
						'function' => array($this, $function),
						'icon_url' => $icon_url,
						'position' => $position,
						'action_callback_function' => $action_callback_function
				);
				
				if($this->admin_menu_handler_set === false) {
						$this->admin_menu_handler_set = true;
						
						add_action('admin_menu', array($this, 'adminMenuPages'), '99');
				}
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Ajax Actions hinzufügen.
		//////////////////////////////////////////////////////////////////////////////
		public function addAjaxAction($tag, $callback, $priority = 10) {
				add_action($tag, array($this, $callback), $priority);
		}
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Admin Sub-Menü hinzufügen.
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		public function addAdminSubMenu($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function = 'adminPage', $position = 101, $action_callback_function = 'adminPageActionHandler') {
				$this->adminSubMenues[] = array(
						'parent_slug' => $parent_slug,
						'page_title' => $page_title,
						'menu_title' => $menu_title,
						'capability' => $capability,
						'menu_slug' => $menu_slug,
						'function' => array($this, $function),
						'position' => $position,
						'action_callback_function' => $action_callback_function
				);
				
				if($this->admin_menu_handler_set === false) {
						$this->admin_menu_handler_set = true;
						
						add_action('admin_menu', array($this, 'adminMenuPages'), '101');
				}
		}
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Action Handler for Wordpress Action 'admin_menu'.
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		public function adminMenuPages() {
				//Add Menus
				foreach($this->adminMenues as $menu) {
						$hook_suffix = add_menu_page(
								$menu['page_title'], 
								$menu['menu_title'], 
								$menu['capability'],
								$menu['menu_slug'],
								$menu['function'],
								$menu['icon_url'],
								$menu['position']
						);
						
						if($menu['action_callback_function'] != '') {
								add_action( 'load-' . $hook_suffix , array($this, $menu['action_callback_function']) );
						}
				}
				
				//Add Submenus
				foreach($this->adminSubMenues as $menu) {
						$hook_suffix = add_submenu_page(
								$menu['parent_slug'],
								$menu['page_title'], 
								$menu['menu_title'], 
								$menu['capability'],
								$menu['menu_slug'],
								$menu['function'],
								$menu['position']
						);
						
						if($menu['action_callback_function'] != '') {
								add_action( 'load-' . $hook_suffix , array($this, $menu['action_callback_function']) );
						}
				}
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Admin Seite anzeigen.
		// Zeigt die Seite an sich an.
		// Das hier ist der "Default Handler" für Admin-Seiten.
		// Er deckt immer wieder die gleiche Funktionalität ab.
		//////////////////////////////////////////////////////////////////////////////
		public function adminPage() {
				//TODO: Wir müssten hier anhand des Menü-Slugs auch erst einmal überprüfen, in welchem Menü wir uns befinden..
				
				//Benutzer-Rechte überprüfen.
				if (!current_user_can('manage_options'))  {
						wp_die( __('You do not have sufficient permissions to access this page.') );
				}
				
				//Installation überprüfen. Wenn wir nicht genügend Rechte haben, Ausgabe stoppen, und stattdessen das Installations-Menü anzeigen.
				$result = mv_core()->checkModelInstallation();		//Wir überprüfen alle Datenbank-Modelle auf Vollständigkeit
				
				if($result == false) {
						//Wenn die Datenbank nicht installiert ist, und auch die Installations-Action nicht aufgerufen wurde,
						//zeigen wir den Installations-Bildschirm für das Plugin an.
						if($result == false) {
								$slug = $_GET['page'];
								echo mvWpAdminMenu::buildDatabaseInstallationScreenHtml($slug);
								return false;
						}
				}
				
				$this->adminPageRender();		//Ausgabe und Verarbeitung wurde ausgelagert, damit der Datenbank-Test zuvor davon unabhängig vorgenommen werden kann! Wir wollen ggf. erst direkt vor dem Rendern Werte initialisieren..
				
				
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Admin Seite darstellen.
		// Zeigt die Seite an sich an.
		// Das hier ist der "Default Handler" für Admin-Seiten.
		// Er deckt immer wieder die gleiche Funktionalität ab.
		//////////////////////////////////////////////////////////////////////////////
		public function adminPageRender() {
				//Ausgabe und Verarbeitung - je nachdem, welche Seite gerade aktiviert ist.
				$action = mvCore::getGetVar('action');
		
				switch($action) {
						case 'new':
								$this->renderNewEntryEditor();
								break;
						case 'edit':
								$this->renderEditEntryEditor();
								break;
						case 'delete':
								$this->renderDeleteDialog();
								break;
						//Default.........................
						default:
								$this->renderList();
								break;
				}
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Admin Page Action Handler verwenden.
		// Wird aufgerufen, bevor die Seite angezeigt wird.
		// Das hier ist der "Default Handler" für Admin-Seiten.
		// Er deckt immer wieder die gleiche Funktionalität ab.
		//////////////////////////////////////////////////////////////////////////////
		public function adminPageActionHandler() {
				
				//Ausgabe und Verarbeitung - je nachdem, welche Seite gerade aktiviert ist.
				$action = mvCore::getGetVar('action');
		
				switch($action) {
						case 'create':
								$this->createEntryInDatabase();
								die('done');
						case 'update':
								$this->updateEntryInDatabase();
								die('done');
						case 'delete_confirm':
								$this->deleteConfirm();
								die('done');
				}
				
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Einen Eintrag in der Datenbank erstellen.
		//////////////////////////////////////////////////////////////////////////////
		public function deleteConfirm() {
				die('Implement delete function!');
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Einen Eintrag in der Datenbank erstellen.
		//////////////////////////////////////////////////////////////////////////////
		public function createEntryInDatabase() {
				die('Implement create function!');
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Einen Eintrag in der Datenbank erstellen.
		//////////////////////////////////////////////////////////////////////////////
		public function updateEntryInDatabase() {
				die('Implement update function!');
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Eine Liste der aktuell angelegten Einträge anzeigen.
		//////////////////////////////////////////////////////////////////////////////
		public function renderList() {
				//Variablen vorbereiten.
				$index = 0;
				$page = 0;
				$pages_total = 0;
				$results_per_page = 25;
				$results_total = 0;
				
				//Anzahl an Datensätzen zählen.
				$class_name = $this->model;				
				$modelInstance = new $class_name();
				$results_total = $modelInstance->countTotal();
				
				//Anzahl an Seiten berechnen.
				if($results_total > 0) {
						$pages_total = ceil( $results_total / $results_per_page);
				}
				
				//Wenn wir Ergebnisse ab einer bestimmte Seite anzeigen sollen.
				if(isset($_GET['mvpage'])) {
						$page = (int)$_GET['mvpage'];
				}
				
				//Index berechnen.
				if($page > 0) {
						$index = $page - 1;
				}
				
				//Einträge paginiert laden..				
				$entries = $modelInstance->loadPaged(($index * $results_per_page), $results_per_page);
				
				//Menü Slug abrufen, damit wir die URLs zusammensetzen können.
				$menu_slug = $_GET['page'];
				
				//Menü rendern und ausgeben.
				$iMvWpAdminMenu = new mvWpAdminMenu();
				$iMvWpAdminMenu->setTextVariables($this->text_variables);
				
				$html = $iMvWpAdminMenu->buildListing(
						$entries, 
						$this->listing_definition, 
						$menu_slug, 
						$page, 
						$pages_total, 
						$results_per_page, 
						$results_total, 
						$new_entry_link = 'action=new'
				);
				
				echo $html;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Admin Editor für neuen Eintrag.
		//////////////////////////////////////////////////////////////////////////////		
		public function renderNewEntryEditor() {
				//Menü Slug abrufen, damit wir die URLs zusammensetzen können.
				$menu_slug = $_GET['page'];
				
				//$title = 'Neues Expose erstellen';
				$form_action_url = admin_url('admin.php?page=' . $menu_slug . '&amp;action=create');
				
				$class_name = $this->model;				
				$modelInstance = new $class_name();
				
				$data = $modelInstance->getDataAsArray();
				
				$iMvWpAdminMenu = new mvWpAdminMenu();
				$iMvWpAdminMenu->setTextVariables($this->text_variables);
				$iMvWpAdminMenu->setTextVariable('editor_title', $iMvWpAdminMenu->getTextVariable('editor_title_new'));		//Editor Überschrift setzen für "Neuen Eintrag"
				
				$html = $iMvWpAdminMenu->renderAdminEditor($form_action_url, $data, $this->menu_definition, $menu_slug);
				
				echo $html;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Admin Editor für das Bearbeiten eines Eintrages.
		//////////////////////////////////////////////////////////////////////////////
		public function renderEditEntryEditor() {
				//Menü Slug abrufen, damit wir die URLs zusammensetzen können.
				$menu_slug = $_GET['page'];
				
				//Versuche Daten zu laden.
				$id = (int)mvCore::getGetVar('id');
				
				$class_name = $this->model;				
				$modelInstance = new $class_name();
				
				$modelInstance->load($id);
				$data = $modelInstance->getDataAsArray();
				
				if(false === $data) {
						die('not allowed');
				} else {
						$title = 'Expose ' . /*"' . htmlspecialchars($data['title']) . '" */ 'bearbeiten';
						
						$form_action_url = admin_url('admin.php?page=' . $menu_slug . '&amp;action=update&amp;id=' . (int)$id);
						
						$iMvWpAdminMenu = new mvWpAdminMenu();
						$iMvWpAdminMenu->setTextVariables($this->text_variables);
						$iMvWpAdminMenu->setTextVariable('editor_title', $iMvWpAdminMenu->getTextVariable('editor_title_edit'));		//Editor Überschrift setzen für "Neuen Eintrag"
						$html = $iMvWpAdminMenu->renderAdminEditor($form_action_url, $data, $this->menu_definition, $menu_slug);
						
						echo $html;
				}
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Admin Dialog "Eintrag löschen"
		//////////////////////////////////////////////////////////////////////////////
		public function renderDeleteDialog() {
				//Menü Slug abrufen, damit wir die URLs zusammensetzen können.
				$menu_slug = $_GET['page'];
							
				$id = (int)mvCore::getGetVar('id');
				
				$class_name = $this->model;				
				$modelInstance = new $class_name();
				
				$modelInstance->load($id);
				$data = $modelInstance->getDataAsArray();
				
				if(false === $data) {
						die('not allowed');
				} else {
						$form_action_url = admin_url('admin.php?page=' . $menu_slug . '&amp;action=delete_confirm&amp;id=' . (int)$id);
						
						$iMvWpAdminMenu = new mvWpAdminMenu();
						$iMvWpAdminMenu->setTextVariables($this->text_variables);
						$html = $iMvWpAdminMenu->renderDeleteDialog($form_action_url, $data, $this->delete_dialog_definition, $menu_slug);
						
						echo $html;
				}
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Ajax Model Fetching aktivieren.
		// Wenn das aktiviert ist, lassen sich für beliebige Modelle aus
		// diesem Namespace Informationen per Ajax abrufen.
		//////////////////////////////////////////////////////////////////////////////
		public function initAjaxModelFetching() {
				$this->addAjaxAction('wp_ajax_mv_wp_admin_ajax_load_model_data_list', 'ajaxLoadModelDataList');
				$this->addAjaxAction('wp_ajax_mv_wp_admin_ajax_load_many_to_many_data', 'ajaxLoadManyToManyData');
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Ajax Model wird per ajax angefragt. Daten zusammenstellen.
		// TODO: Use wp_nonce here in future!!!
		//////////////////////////////////////////////////////////////////////////////
		public function ajaxLoadModelDataList() {
				$model_name = mv_core()->getPostVar('model_name');
				$id_field = mv_core()->getPostVar('id_field');
				$title_field = mv_core()->getPostVar('title_field');
				
				//Prüfen, ob wir überhaupt entsprechende Daten übergeben bekommen haben.
				if(false === $model_name || false === $id || false === $title) {
						$retval = array(
								'status' => 'error',
								'description' => 'Missing information'
						);
						$retval = json_encode($retval, JSON_PRETTY_PRINT);
						echo $retval;
						die;
				}
				
				//Prüfen, ob das Model so existiert.
				$class_name = __NAMESPACE__ . '\\' . $model_name;
				
				if(!class_exists($class_name, false)) {
						$retval = array(
								'status' => 'error',
								'description' => 'Datenmodell wurde nicht gefunden.'
						);
						$retval = json_encode($retval, JSON_PRETTY_PRINT);
						echo $retval;
						die;
				}
				
				//Datenmodell initialisieren.
				$model_instance = new $class_name();
				
				//Prüfen, ob die angegebenen Felder im Datenmodell überhaupt existieren.
				$fields = $model_instance->getDataAsArray();
				$fields_to_find = array(
						$id_field,
						$title_field
				);
				
				foreach($fields['fields'] as $field) {
						$tmp_index = array_search($field['name'], $fields_to_find);
						
						if(false !== $tmp_index) {
								unset($fields_to_find[$tmp_index]);
								
								//Wenn kein Element mehr im zu durchsuchenden Array ist, Schleife beenden. Wir müssen dann ja nicht weitersuchen..
								if(count($fields_to_find) == 0) {
										break;
								}
						}
				}
				
				if(count($fields_to_find) > 0) {
						$retval = array(
								'status' => 'error',
								'description' => 'Die zu verwendenten Felder kommen nicht im Datenmodell vor.'
						);
						$retval = json_encode($retval, JSON_PRETTY_PRINT);
						echo $retval;
						die;
				}
				
				//Liste mit Daten abrufen.
				$data = $model_instance->loadAllAsArray();
				
				if(count($data) == 0) {
						$retval = array(
								'status' => 'error',
								'description' => 'Es sind keine Daten für dieses Modell in der Datenbank vorhanden.'
						);
						$retval = json_encode($retval, JSON_PRETTY_PRINT);
						echo $retval;
						die;
				}
				
				//Ausgabe und Programmende
				$retval = array(
						'status' => 'success',
						'count' => count($data),
						'data' => $data
				);
				$retval = json_encode($retval, JSON_PRETTY_PRINT);
				echo $retval;
				die;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Ajax Model wird per ajax angefragt. Daten zusammenstellen.
		// TODO: Use wp_nonce here in future!!!
		//////////////////////////////////////////////////////////////////////////////
		public function ajaxLoadManyToManyData() {
				global $wpdb;
				
				$model_name = mv_core()->getPostVar('model_name');
				$id_field = mv_core()->getPostVar('id_field');
				$title_field = mv_core()->getPostVar('title_field');
				$many_to_many_object = mv_core()->getPostVar('many_to_many_object');
				$many_to_many_object_local_id_field = mv_core()->getPostVar('many_to_many_object_local_id_field');
				$many_to_many_object_values_object_id_field = mv_core()->getPostVar('many_to_many_object_values_object_id_field');
				$many_to_many_object_sort_order_field = mv_core()->getPostVar('many_to_many_object_sort_order_field');
				$id_field_value = mv_core()->getPostVar('id_field_value');
				
				//Prüfen, ob wir überhaupt entsprechende Daten übergeben bekommen haben.
				if(false === $model_name || false === $id || false === $title) {
						$retval = array(
								'status' => 'error',
								'description' => 'Missing information'
						);
						$retval = json_encode($retval, JSON_PRETTY_PRINT);
						echo $retval;
						die;
				}
				
				//Prüfen, ob das Model so existiert.
				$class_name = __NAMESPACE__ . '\\' . $model_name;
				
				if(!class_exists($class_name, false)) {
						$retval = array(
								'status' => 'error',
								'description' => 'Datenmodell wurde nicht gefunden.'
						);
						$retval = json_encode($retval, JSON_PRETTY_PRINT);
						echo $retval;
						die;
				}
				
				//Datenmodell initialisieren.
				$model_instance = new $class_name();
				
				//Prüfen, ob die angegebenen Felder im Datenmodell überhaupt existieren.
				$fields = $model_instance->getDataAsArray();
				$fields_to_find = array(
						$id_field,
						$title_field
				);
				
				foreach($fields['fields'] as $field) {
						$tmp_index = array_search($field['name'], $fields_to_find);
						
						if(false !== $tmp_index) {
								unset($fields_to_find[$tmp_index]);
								
								//Wenn kein Element mehr im zu durchsuchenden Array ist, Schleife beenden. Wir müssen dann ja nicht weitersuchen..
								if(count($fields_to_find) == 0) {
										break;
								}
						}
				}

				if(count($fields_to_find) > 0) {
						$retval = array(
								'status' => 'error',
								'description' => 'Die zu verwendenten Felder kommen nicht im Datenmodell vor.'
						);
						$retval = json_encode($retval, JSON_PRETTY_PRINT);
						echo $retval;
						die;
				}
				
				//Prüfen, ob wir das foreign Objekt erhalten haben.
				if(false === $many_to_many_object || false === $many_to_many_object_local_id_field || false === $many_to_many_object_values_object_id_field) {
						$retval = array(
								'status' => 'error',
								'description' => 'Missing information of foreign table.'
						);
						$retval = json_encode($retval, JSON_PRETTY_PRINT);
						echo $retval;
						die;
				}
				
				//Prüfen, ob das Model so existiert.
				$foreign_class_name = __NAMESPACE__ . '\\' . $many_to_many_object;
				
				if(!class_exists($foreign_class_name, false)) {
						$retval = array(
								'status' => 'error',
								'description' => 'Datenmodell des Foreign-Objektes wurde nicht gefunden.'
						);
						$retval = json_encode($retval, JSON_PRETTY_PRINT);
						echo $retval;
						die;
				}
				
				//Datenmodell des Fremdschlüssel-Objektes initialisieren.
				$foreign_model_instance = new $foreign_class_name();
				
				//Prüfen, ob die angegebenen Felder im Datenmodell überhaupt existieren.
				$foreign_fields = $foreign_model_instance->getDataAsArray();
				$foreign_fields_to_find = array(
						$many_to_many_object_local_id_field,
						$many_to_many_object_values_object_id_field
				);
				
				foreach($foreign_fields['fields'] as $field) {
						$tmp_index = array_search($field['name'], $foreign_fields_to_find);
						
						if(false !== $tmp_index) {
								unset($foreign_fields_to_find[$tmp_index]);
								
								//Wenn kein Element mehr im zu durchsuchenden Array ist, Schleife beenden. Wir müssen dann ja nicht weitersuchen..
								if(count($foreign_fields_to_find) == 0) {
										break;
								}
						}
				}
				
				if(count($foreign_fields_to_find) > 0) {
						$retval = array(
								'status' => 'error',
								'description' => 'Die zu verwendenten Felder des Foreign-Objektes kommen nicht im Datenmodell vor.'
						);
						$retval = json_encode($retval, JSON_PRETTY_PRINT);
						echo $retval;
						die;
				}
				
				//Query zusammensetzen.
				$data_table_name = $fields['table_name'];
				$data_table_alias = 'mv_tmp_tbl_1';
				$foreign_table_name = $foreign_fields['table_name'];
				$foreign_table_alias = 'mv_tmp_tbl_2';
				
				$query = 
						'SELECT ' . "\n" .
								$data_table_alias . '.' . $id_field . ', ' . "\n" .
								$data_table_alias . '.' . $title_field . ' ' . "\n" .
						'FROM ' . "\n" .
								$data_table_name . ' ' . $data_table_alias . ' ' . "\n" .
						'LEFT JOIN ' . $foreign_table_name . ' ' . $foreign_table_alias . ' ' . "\n" .
								'ON ' . $foreign_table_alias . '.' . $many_to_many_object_values_object_id_field . ' = ' . $data_table_alias . '.' . $id_field . ' ' . "\n" .
						'WHERE ' . "\n" .
								$foreign_table_alias . '.' . $many_to_many_object_local_id_field . ' = %s';
								
				$sql = $wpdb->prepare(
						$query,
						$id_field_value
				);
				$result = $wpdb->get_results($sql, ARRAY_A);
				
				//Wenn ein Fehler bei der Datenbankabfrage aufgetreten ist.
				if(!is_array($result)) {
						$retval = array(
								'status' => 'error',
								'description' => 'Es ist ein Fehler bei der Datenbank-Abfrage aufgetreten.'
						);
						$retval = json_encode($retval, JSON_PRETTY_PRINT);
						echo $retval;
						die;
				}
				
				$result_count = count($result);
				
				$retval = array(
						'status' => 'success',
						'count' => $result_count,
						'data' => $result
				);
				$retval = json_encode($retval, JSON_PRETTY_PRINT);
				echo $retval;
				die;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		//Scripte und Stile einbinden die wir allgemein auf Admin-Seiten benötigen (bspw. jQuery UI für Sortable, Draggable, etc..)
		//////////////////////////////////////////////////////////////////////////////
		public function enqueueScriptsAndStyles() {
				wp_enqueue_script('mv-pages-admin-script', '', array('jquery','jquery-ui-droppable','jquery-ui-draggable', 'jquery-ui-sortable'));
		}
}