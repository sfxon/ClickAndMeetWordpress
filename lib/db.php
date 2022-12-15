<?php

namespace mvclickandmeet_namespace;

class db {
		var $query;
		var $parameters;
		var $where_parameters;
		var $results;
		var $table;
		var $last_insert_id = false;
		
		/////////////////////////////////////////////////////////////////////////////////////////////////
		// Instanz abholen.
		/////////////////////////////////////////////////////////////////////////////////////////////////
		public function useInstance($instance_name) {
				//do nothing. Only for compatibility.
		}
		
		/////////////////////////////////////////////////////////////////////////////////////////////////
		// Query einstellen
		/////////////////////////////////////////////////////////////////////////////////////////////////
		function setQuery($query) {
				$this->query = $query;
				$this->parameters = array();
				$this->where_parameters = array();
		}
		
		/////////////////////////////////////////////////////////////////////////////////////////////////
		// Set table
		/////////////////////////////////////////////////////////////////////////////////////////////////
		function setTable($table) {
				$this->table = $table;
		}
		
		/////////////////////////////////////////////////////////////////////////////////////////////////
		// Parameter binden.
		/////////////////////////////////////////////////////////////////////////////////////////////////
		function bind($index, $value) {
				$this->parameters[$index] = $value;
		}
		
		/////////////////////////////////////////////////////////////////////////////////////////////////
		// WHERE-Parameter binden.
		/////////////////////////////////////////////////////////////////////////////////////////////////
		function bindWhere($index, $value) {
				$this->where_parameters[$index] = $value;
		}
		
		/////////////////////////////////////////////////////////////////////////////////////////////////
		// Query ausführen
		/////////////////////////////////////////////////////////////////////////////////////////////////
		function execute() {
				global $wpdb;
				
				if(count($this->parameters) > 0) {
						foreach($this->parameters as $index => $param) {
								$index = ltrim($index, ':');
								$this->query = str_replace(':' . $index, '%s', $this->query);
						}
					
						$prepared_statement = $wpdb->prepare($this->query, $this->parameters);

						$result = $wpdb->get_results(
								$wpdb->prepare(
										$this->query,
										$this->parameters
								),
								ARRAY_A
						);
				} else {
						$result = $wpdb->get_results($this->query, ARRAY_A);
				}
				
				if(NULL === $result) {
						die('Fehler bei der Datenbankabfragen in ' . __FILE__ . ', Zeile: ' . __LINE__ . 'Query: ' . $this->query);
				}
				
				$result = new cDBResult($result, '', $this->query);
				
				return $result;
		}
		
		/////////////////////////////////////////////////////////////////////////////////////////////////
		// Insert ausführen
		/////////////////////////////////////////////////////////////////////////////////////////////////
		function insert() {
				global $wpdb;
				
				if(!isset($this->table)) {
						echo 'query: ' . $this->query;
						die('Es ist keine Tabelle angegeben in ' . __FILE__ . ', Zeile: ' . __LINE__);
				}
				
				if(!isset($this->parameters)) {
						echo 'query: ' . $this->query;
						die('Es sind keine Parameter angegeben in ' . __FILE__ . ', Zeile: ' . __LINE__);
				}
				
				$new_params = array();
				
				foreach($this->parameters as $index => $value) {
						$new_params[ltrim($index, ":")] = $value;
				}
				
				if(count($new_params) > 0) {
						$result = $wpdb->insert(
								$this->table,
								$new_params
						);
				}
				
				$this->last_insert_id = $wpdb->insert_id;
				
				return true;
		}
		
		/////////////////////////////////////////////////////////////////////////////////////////////////
		// Letzte Insert-ID zurückgeben.
		/////////////////////////////////////////////////////////////////////////////////////////////////
		function insertId() {
				return $this->last_insert_id;
		}
		
		/////////////////////////////////////////////////////////////////////////////////////////////////
		// Tabellenname verarbeiten (ggf. prefixen).
		/////////////////////////////////////////////////////////////////////////////////////////////////
		function table($table_name) {
				return 'aloha_' . $table_name;
		}
		
		/////////////////////////////////////////////////////////////////////////////////////////////////
		// Insert ausführen
		/////////////////////////////////////////////////////////////////////////////////////////////////
		function update() {
				global $wpdb;
				
				if(!isset($this->table)) {
						echo 'query: ' . $this->query;
						die('Es ist keine Tabelle angegeben in ' . __FILE__ . ', Zeile: ' . __LINE__);
				}
				
				if(!isset($this->parameters)) {
						echo 'query: ' . $this->query;
						die('Es sind keine Parameter angegeben in ' . __FILE__ . ', Zeile: ' . __LINE__);
				}
				
				if(!isset($this->where_parameters)) {
						echo 'query: ' . $this->query;
						die('Es wurden keine WHERE Parameter für die Query angegeben in ' . __FILE__ . ', Zeile: ' . __LINE__);
				}
				
				//Update-Parameter festlegen.				
				$new_params = array();
				
				foreach($this->parameters as $index => $value) {
						$new_params[ltrim($index, ":")] = $value;
				}
				
				//Where Parameter festlegen.
				$where_params = array();
				
				foreach($this->where_parameters as $index => $value) {
						$where_params[ltrim($index, ":")] = $value;
				}
				
				if(count($new_params) > 0) {
						$result = $wpdb->update(
								$this->table,
								$new_params,
								$where_params
						);
				}
				
				return true;
		}
		
		/////////////////////////////////////////////////////////////////////////////////////////////////
		// Insert ausführen
		/////////////////////////////////////////////////////////////////////////////////////////////////
		function delete() {
				global $wpdb;
				
				if(!isset($this->table)) {
						echo 'query: ' . $this->query;
						die('Es ist keine Tabelle angegeben in ' . __FILE__ . ', Zeile: ' . __LINE__);
				}
				
				if(!isset($this->where_parameters)) {
						echo 'query: ' . $this->query;
						die('Es wurden keine WHERE Parameter für die Query angegeben in ' . __FILE__ . ', Zeile: ' . __LINE__);
				}
				
				//Where Parameter festlegen.
				$where_params = array();
				
				foreach($this->where_parameters as $index => $value) {
						$where_params[ltrim($index, ":")] = $value;
				}
				
				$wpdb->delete(
						$this->table,
						$where_params
				);
				
				return true;
		}
}
 
///////////////////////////////////////////////////////////////////////////////////////////////////
// Datenbank-Abfrageergebnis Klasse.
// Diese Klasse enthält das Ergebnis einer Datenbankabfrage.
///////////////////////////////////////////////////////////////////////////////////////////////////
class cDBResult {
		var $error;
		var $result;
		var $gotFirstRow;
		var $data;
		var $debug;
		var $connection;
		var $current_row = 0;
		
		/////////////////////////////////////////////////////////////////////////////////////////////////
		// Konstruktor
		/////////////////////////////////////////////////////////////////////////////////////////////////
		function __construct($result, $errormessage = '', $query = '') {
				if($errormessage == '') {
						$this->error = false;
				}
				$this->debug = true;
				$this->result = $result;
				$this->data   = '';
				$this->gotFirstRow = false;
				$this->current_row = 0;
		}
		
		/////////////////////////////////////////////////////////////////////////////////////////////////
		// Wert abfragen
		/////////////////////////////////////////////////////////////////////////////////////////////////
		function value($index) {
				if(!$this->gotFirstRow) {
						$this->next();
				}

				if( empty($this->data[$index]) ) {
						if(!isset($this->data[$index])) {
								echo $this->data[$index];
								
								if($this->debug == true) {
										echo mysql_error();
										echo $index;
								}
								echo 'Es ist ein Fehler aufgetreten. Bitte informieren Sie den Server Betreiber.';
								die;
						}

						return $this->data[$index];
				}

				return($this->data[$index]);
		}

		/////////////////////////////////////////////////////////////////////////////////////////////////
		// Wert abfragen
		// Gibt auch bei 0 Werten einen Wert zurück!
		/////////////////////////////////////////////////////////////////////////////////////////////////
		function valueNULL($index) {
				if(!$this->gotFirstRow) {
						$this->next();
				}

				return($this->data[$index]);
		}
		
		/////////////////////////////////////////////////////////////////////////////////////////////////
		// get next row
		/////////////////////////////////////////////////////////////////////////////////////////////////
		function next() {
				if(!isset($this->result[$this->current_row])) {
						return(false);
				}
				
				$this->data = $this->result[$this->current_row];
				
				$this->gotFirstRow = true;
				$this->current_row ++;
				
				return(true);
		}
		
		/////////////////////////////////////////////////////////////////////////////////////////////////
		// Assoziatives Array abrufen
		/////////////////////////////////////////////////////////////////////////////////////////////////
		function fetchArrayAssoc() {
				$retval = array();
				
				if(!$this->gotFirstRow) {
						$this->next();
				}
				
				return($this->data);
		}
}

?>