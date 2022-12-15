<?php

namespace mvclickandmeet_namespace;

/////////////////////////////////////////////////////////////////////////////////////////////////////////
// This class provides the filter functionality for our plugins.
/////////////////////////////////////////////////////////////////////////////////////////////////////////

if(!class_exists('mvFilter', false)) {
		class mvFilter {
				/////////////////////////////////////////////////////////////////////////////////////////////////////////
				// Verfügbare Werte anhand der Filter Query abrufen.
				// Diese Funktion führt einen group_by über die aktuelle Query aus,
				// und gibt alle erhaltenen Werte zurück.
				/////////////////////////////////////////////////////////////////////////////////////////////////////////
				public function getAvailableValuesByFilterQuery($model_instance, $field_to_group_by) {
						//get values that have been sent by the GET query..
						$requested_parameters = $this->getAllSubmittedGetParameters($model_instance);
						
						if(false === $requested_parameters) {
								return false;
						}
						
						//Prüfen ob das Feld gepostet wurde, auf das wir hier filtern wollen.
						//Falls ja - entfernen wir den aus dem Array, damit wir für die Kombination ohne diesen Wert
						//trotzdem alle verfügbaren Kombinationen herausfinden können.
						if(isset($requested_parameters[$field_to_group_by])) {
								unset($requested_parameters[$field_to_group_by]);
						}
						
						//build query
						$query_string = $this->buildGroupByQuery($model_instance, $requested_parameters, $field_to_group_by);
						
						//execute query
						$result = $this->doQuery($query_string, $requested_parameters);
						
						$retval = array();
						
						foreach($result as $res) {
								$retval[] = $res[$field_to_group_by];
						}
						
						return $retval;
				}
				
				/////////////////////////////////////////////////////////////////////////////////////////////////////////
				// Über GET übergebene Werte prüfen und abrufen.
				/////////////////////////////////////////////////////////////////////////////////////////////////////////
				public function getAllSubmittedGetParameters($model_instance) {
						$retval = array();		//Will hold all the submitted fields with values, when they are valid for the model.
						
						if(isset($_GET['cam_filters'])) {
								$dbfields = $model_instance->getDataAsArray();		//Get the fields, as they are defined in the model.
								
								foreach($_GET['cam_filters'] as $filter_name => $filter_value) {
										foreach($dbfields['fields'] as $field) {
												if($filter_name == $field['name']) {
														$retval[$filter_name] = $filter_value;
												}
										}
								}
						} else {
								return false;
						}
						
						return $retval;
				}
				
				/////////////////////////////////////////////////////////////////////////////////////////////////////////
				// Group By Query erstellen.
				/////////////////////////////////////////////////////////////////////////////////////////////////////////
				public function buildGroupByQuery($model_instance, $requested_parameters, $field_to_group_by) {
						$dbdata = $model_instance->getDataAsArray();

						$query = 'SELECT ' . $field_to_group_by . ' FROM ';
						$query .= $dbdata['table_name'] . ' ';
						
						//Build where string by requested_parameters array.
						$where = $this->buildWhereStringByArray($requested_parameters);
						
						if(strlen($where) > 0) {
								$query .= 'WHERE ' . $where . ' ';
						}
						
						$query .= 'GROUP BY ' . $field_to_group_by;
						
						return $query;
				}
				
				/////////////////////////////////////////////////////////////////////////////////////////////////////////
				// Group By Query erstellen.
				/////////////////////////////////////////////////////////////////////////////////////////////////////////
				public function buildWhereStringByArray($requested_parameters) {
						$where = '';
						
						foreach($requested_parameters as $fieldname => $value) {
								if(strlen($where) > 0) {
										$where .= ' AND ';
								}
							
								$where .= $fieldname . ' = %s';
						}
						
						return $where;
				}
				
				/////////////////////////////////////////////////////////////////////////////////////////////////////////
				// Query ausführen.
				/////////////////////////////////////////////////////////////////////////////////////////////////////////
				public function doQuery($query_string, $requested_parameters) {
						global $wpdb;
						
						$result = '';
						
						if(count($requested_parameters) == 0) {
								$result = $wpdb->get_results($query_string, ARRAY_A);
						} else {
								$prepared_query = $wpdb->prepare($query_string, $requested_parameters);
								$result = $wpdb->get_results($prepared_query, ARRAY_A);
						}
						
						return $result;
				}
		}
}