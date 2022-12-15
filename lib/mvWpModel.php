<?php

namespace mvclickandmeet_namespace;

if(!class_exists('mvWpModel', false)) {
		class mvWpModel {
				//This will be an array, containing the data model!
				protected $data; 
				
				////////////////////////////////////////////////////////////////////////
				// Konstruktor: Initialises the model.
				// @param		__construct
				//					Has to be an array with sub-arrays, defining the data model
				//					like this:
				//					$data_array = array(
				//							'table_name' => 'mytable_name_in_mysql',
				//							'fields' => array(
				//									array(
				//											'name' => 'id',
				//											'type' => 'int',
				//											'length' => '11',
				//											'default_value' => 0,
				//											'auto_increment' => false/true,
				//									),
				//									[...]
				//							)
				//						);
				////////////////////////////////////////////////////////////////////////
				public function __construct($data_array) {
						$this->data = $data_array;
				}
				
				////////////////////////////////////////////////////////////////////////
				// Einen Datensatz anhand seiner id laden.
				////////////////////////////////////////////////////////////////////////
				public function load($id) {
						global $wpdb;
						
						$query = 'SELECT * FROM ' . $this->data['table_name']  . ' WHERE ' . $this->data['id_field'] . ' = %d LIMIT 1;';
						
						$sql = $wpdb->prepare(
								$query,
								$id
						);
						$result = $wpdb->get_results($sql, ARRAY_A);
						
						if(is_array($result) && isset($result[0])) {
								foreach($result[0] as $fieldname => $value) {
										foreach($this->data['fields'] as $index => $field_definition) {
												if($field_definition['name'] == $fieldname) {
														$this->data['fields'][$index]['value'] = $value;
												}
										}
								}
						}
						
						return false;
				}
				
				////////////////////////////////////////////////////////////////////////
				// Einen Datensatz in der Datenbank aktualisieren.
				////////////////////////////////////////////////////////////////////////
				public function update($id) {
						global $wpdb;
						
						$update_string = '';
						$values_array = array();
						
						//parse all input
						foreach($this->data['fields'] as $field) {
								if($field['name'] == $this->data['id_field']) {
										continue;
								}
								
								if(isset($_POST[$field['name']])) {
										//Feldnamen erweitern.
										if(strlen($update_string) > 0) {
												$update_string .= ', ';
										}
										
										$update_string .= $field['name'] . ' = %s';
										$values_array[] = wp_unslash($_POST[$field['name']]);
								}
						}
						
						//build where
						$where = 'WHERE ' . $this->data['id_field'] . ' = %s';
						$values_array[] = $id;
						
						//build query
						$query = 'UPDATE ' . $this->data['table_name'] . ' SET ' . $update_string . ' ' . $where;
						
						/*
						$tmp = vprintf($query,
								$values_array
						);
						*/
						
						$sql = $wpdb->prepare(
								$query,
								$values_array
						);
						$result = $wpdb->query($sql);
				}
				
				////////////////////////////////////////////////////////////////////////
				// Einen Datensatz in der Datenbank aktualisieren.
				////////////////////////////////////////////////////////////////////////
				public function updateFields($id, $data) {
						global $wpdb;
						
						$update_string = '';
						$values_array = array();
						
						//parse all input
						foreach($data as $index => $value) {
								//Feldnamen erweitern.
								if(strlen($update_string) > 0) {
										$update_string .= ', ';
								}
								
								$update_string .= $index . ' = %s';
								$values_array[] = $value;
						}
						
						//build where
						$where = 'WHERE ' . $this->data['id_field'] . ' = %s';
						$values_array[] = $id;

						//build query
						$query = 'UPDATE ' . $this->data['table_name'] . ' SET ' . $update_string . ' ' . $where;
						
						$sql = $wpdb->prepare(
								$query,
								$values_array
						);
						$result = $wpdb->query($sql);
				}
				
				////////////////////////////////////////////////////////////////////////
				// Einen Datensatz in der Datenbank erstellen.
				////////////////////////////////////////////////////////////////////////
				public function create() {
						global $wpdb;
						
						$fields = '';
						$values = '';
						$values_array = array();
						
						foreach($this->data['fields'] as $field) {
								if(isset($_POST[$field['name']])) {
										//Feldnamen erweitern.
										if(strlen($fields) > 0) {
												$fields .= ', ';
												$values .= ', ';
										}
										
										$fields .= $field['name'];
										$values .= '%s';
										
										$values_array[] = wp_unslash($_POST[$field['name']]);
								}
						}
						
						$query = 'INSERT INTO ' . $this->data['table_name'] . '( ' .
										$fields .
								') ' . 
								'VALUES(' . $values . ')';
								
						$sql = $wpdb->prepare(
								$query,
								$values_array
						);
						$wpdb->query($sql);
						
						return $wpdb->insert_id;
				}
				
				////////////////////////////////////////////////////////////////////////
				// Gibt das Datenmodell als Array zurück, wie es gerade in php hinterlegt ist.
				// Diese Funktion lädt keine Daten aus der Datenbank,
				// möchte man einen Eintrag aus der Datenbank abrufen, sollte dieser
				// zuvor mit load geladen werden.
				////////////////////////////////////////////////////////////////////////
				public function getDataAsArray() {
						return $this->data;
				}
				
				////////////////////////////////////////////////////////////////////////
				// Installiert das Datenmodell in der Datenbank.
				////////////////////////////////////////////////////////////////////////
				public function installTableInDb() {
						global $wpdb;
						
						$charset_collate = $wpdb->get_charset_collate();
						$sql = 'CREATE TABLE `' . $this->data['table_name'] . '` (';
						
						$fields = '';
						//Felder zusammensetzen.
						foreach($this->data['fields'] as $field) {
								if(strlen($fields) > 0) {
										$fields .= ',';
								}
								$fields .= "\n";
								
								$fields .= $field['name'] . ' ' . $field['type'];
								
								if($field['length'] != '') {
										$fields .= ' (' . $field['length'] . ')';
								}
								
								if($field['default_value'] === NULL) {
										$fields .= ' DEFAULT NULL';
								} else if($field['default_value'] === 0) {
										$fields .= ' DEFAULT 0';
								} else if($field['default_value'] != '') {
										$fields .= ' DEFAULT \'';
										$fields .= $field['default_value'];
										$fields .= '\'';
								}
								
								if($field['auto_increment'] === true) {
										$fields .= ' NOT NULL AUTO_INCREMENT';
								}
						}
						
						$sql .= $fields;
						//Indexe zusammensetzen.
						foreach($this->data['fields'] as $field) {
								if($field['type'] == 'INT' && $field['auto_increment'] === true) {
										$sql .= ',';
										$sql .= "\n";
										$sql .= 'PRIMARY KEY (' . $field['name'] . ')';
								}
						}
						
						$sql .= "\n";
						$sql .= ')';
						
						$sql .= $charset_collate;
						
						$sql .= ';';

						require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
						$result = dbDelta( $sql );
					
						//Set database version.
						$option_string = __NAMESPACE__ . '-db-version-' . $this->data['table_name'];
						update_option($option_string, $this->data['db_version'], false);
				}
				
				////////////////////////////////////////////////////////////////////////
				// Prüft, ob ein GET Parameter gesetzt ist. Falls ja, werden
				// die Datenbank-Tabellen installiert.
				////////////////////////////////////////////////////////////////////////
				public function installActionProcessor() {
						if(isset($_GET['action'])) {
								if($_GET['action'] === 'install_admin_database') {
										$this->installTableInDb();
										return true;
								}
						}
						
						return false;
				}
				
				////////////////////////////////////////////////////////////////////////
				// Einen Datensatz anhand seiner id löschen.
				////////////////////////////////////////////////////////////////////////
				public function delete($id) {
						global $wpdb;
						
						$table_name = $this->data['table_name'];
						$wpdb->delete( $table_name, array( 'id' => $id) );
				}
				
				////////////////////////////////////////////////////////////////////////
				// Eine Liste aller Einträge als Array laden.
				////////////////////////////////////////////////////////////////////////
				public function loadAllAsArray($options = array()) {
						global $wpdb;
				
						$table_name = $this->data['table_name'];
						
						$sql = 'SELECT * FROM ' . $table_name;
						$result = $wpdb->get_results($sql, ARRAY_A);
						
						return $result;
				}
				
				
				////////////////////////////////////////////////////////////////////////
				// Eine Liste aller Einträge als Array laden.
				////////////////////////////////////////////////////////////////////////
				public function loadPaged($index = 0, $results_per_page = 10, $options = array()) {
						global $wpdb;
				
						$table_name = $this->data['table_name'];
						
						$sql = $wpdb->prepare(
								'SELECT * FROM ' . $table_name . ' LIMIT %d, %d',
								array(
										$index,
										$results_per_page
								)
						);
						$result = $wpdb->get_results($sql, ARRAY_A);
						
						return $result;
				}
				
				////////////////////////////////////////////////////////////////////////
				// Anzahl an Einträgen laden.
				////////////////////////////////////////////////////////////////////////
				public function countTotal($options = array()) {
						global $wpdb;
				
						$table_name = $this->data['table_name'];
						
						$sql = 'SELECT count(*) as anzahl FROM ' . $table_name;
						$result = $wpdb->get_results($sql, ARRAY_A);
						
						if(!is_array($result)) {
								return 0;
						}
						
						if(!isset($result[0])) {
								return 0;
						}
						
						if(!isset($result[0]['anzahl'])) {
								return 0;
						}
						
						return (int)$result[0]['anzahl'];
				}
				
				////////////////////////////////////////////////////////////////////////
				// Give back an array for the definition of one field entry in 
				// the data arrays index fields.
				////////////////////////////////////////////////////////////////////////
				public static function getFieldArray($name, $type, $length = '', $default_value = NULL, $auto_increment = false) {
						return array(
								'name' => $name,
								'type' => $type,
								'length' => $length,
								'default_value' => $default_value,
								'auto_increment' => $auto_increment,
								'value' => NULL
						);
				}
				
				////////////////////////////////////////////////////////////////////////
				// Give back an array for the definition of one admin input field entry in
				// the data arrays index fields.
				//
				//	@param		data_field_name		the name of the data field (as it is known in the database, too).
				//	@param		title							Label for the field.
				//	@param		type							Type of the field (text, textarea, select, checkbox, ...)
				////////////////////////////////////////////////////////////////////////
				public static function getInputFieldArray($data_field_name, $title, $type = 'text', $default_value = '', $value = '', $placeholder = '', $description = '', $check_input = '', $html_id = '', $html_classes = '') {
						return array(
								'data_field_name' => $name,
								'title' => $title,
								'type' => $type,
								'default_value' => $default_value,
								'value'	=> $value,
								'placeholder' => $placeholder,
								'description' => $description,
								'check_input' => $auto_increment,
								'html_id' => $html_id,
								'html_classes' => $html_classes
						);
				}
				
				//////////////////////////////////////////////////////////////////////////////
				// Prüft, ob die Datenbank-Tabelle auf dem aktuellsten Stand ist.
				//////////////////////////////////////////////////////////////////////////////
				public function checkDatabaseInstallation() {
						global $wpdb;
						
						if(false == $this->checkDatabaseTableInstallation()) {
								return false;
						}
						
						//Check version..
						if(false == $this->checkDatabaseTableVersion()) {
								return false;
						}
						
						return true;
				}
				
				//////////////////////////////////////////////////////////////////////////////
				// Prüft, ob die Datenbank-Tabelle installiert ist.
				//////////////////////////////////////////////////////////////////////////////
				public function checkDatabaseTableInstallation() {
						global $wpdb;
						
						$table_name =  $this->data['table_name'] ;
						$query = 'SHOW TABLES LIKE \'' . $table_name . '\';';
						
						if(!$wpdb->get_var($query) == $table_name) {
								return false;
						}
						
						return true;
				}
				
				//////////////////////////////////////////////////////////////////////////////
				// Prüft, ob die Datenbank-Tabelle installiert ist.
				//////////////////////////////////////////////////////////////////////////////
				public function checkDatabaseTableVersion() {
						$option_string = __NAMESPACE__ . '-db-version-' . $this->data['table_name'];
						$option_value = get_option($option_string);
						
						if(false == $option_value) {
								return false;
						}
						
						if((float)$option_value < (float)$this->data['db_version']) {
								return false;
						}
						
						return true;
				}
				
				//////////////////////////////////////////////////////////////////////////////
				// Gibt den aktuellen Wert eines Feldes zurück.
				//////////////////////////////////////////////////////////////////////////////
				public function getValueForField($name) {
						foreach($this->data['fields'] as $field) {
								if($field['name'] == $name) {
										return $field['value'];
								}
						}
				}
				
				//////////////////////////////////////////////////////////////////////////////
				// Updates für die Datenbank-Installation.
				//////////////////////////////////////////////////////////////////////////////
				public function updateTableInDb() {
						$option_string = __NAMESPACE__ . '-db-version-' . $this->data['table_name'];
						$current_version = get_option($option_string);
						
						if((float)$current_version >= (float)$this->data['db_version']) {
								die('Datenbank Update kann nicht durchgeführt werden, da das Modul bereits über eine neuere Version verfügt. Tabelle: ' . $this->data['table_name']);
						}
						
						//This has to be implemented in the data model!
						$this->updateDB();
				}
				
				
		}
}