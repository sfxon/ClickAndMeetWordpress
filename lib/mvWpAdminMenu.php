<?php

namespace mvclickandmeet_namespace;

class mvWpAdminMenu {
		var $text_variables = array();
		
		public function __construct() {
				$this->initTextVariables();
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Text Variablen initialisieren.
		//////////////////////////////////////////////////////////////////////////////
		public function initTextVariables() {
				$this->text_variables = array(
						'listing_title' => 'Listenansicht',
						'editor_title' => 'Editor Titel',
						'editor_title_new' => 'Neuen Eintrag erstellen',
						'editor_title_edit' => 'Eintrag bearbeiten'
				);
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
		// Text Variablen zurückgeben
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		public function getTextVariable($index) {
				if(isset($this->text_variables[$index])) {
						return $this->text_variables[$index];
				}
				
				return ('Text Variable ' . $index . 'not found in ' . __FILE__ . ', Zeile: ' . __LINE__);
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Zeile für Editor: Reiner Text
		//////////////////////////////////////////////////////////////////////////////
		public static function editTable_InfoRow($title, $id, $value) {
				$retval = 
						'<tr>' .
								'<th>' .
										'<label for="' .$id . '">' . $title . '</label>' .
										'<input type="hidden" id="' . $id . '" value="' . $value . '" />' .
								'</th>' .
								'<td>' .
										$value . 
								'</td>' .
						'</tr>';
				return $retval;
		}

		//////////////////////////////////////////////////////////////////////////////
		// Zeile für Editor: Checkbox
		//////////////////////////////////////////////////////////////////////////////
		public static function editTable_checkboxRow($title, $id, $name, $value, $field_value) {
				$checked = '';
				
				if($value == $field_value) {
						$checked = ' checked="checked"';
				}
				
				$retval = 
						'<tr>' .
								'<th>' .
										'<label for="' .$id . '">' . $title . '</label>' .
								'</th>' .
								'<td>' .
										'<input type="checkbox" name="' . $name . '" id="' . $id . '" value="' . htmlentities($field_value) . '"' . $checked . ' />';
								'</td>' .
						'</tr>';
				return $retval;
		}

		//////////////////////////////////////////////////////////////////////////////
		// Zeile für Editor: Select
		//////////////////////////////////////////////////////////////////////////////
		public static function editTable_selectRow($title, $id, $name, $value, $field_values, $id_field, $title_field) {
				$retval = 
						'<tr>' .
								'<th>' .
										'<label for="' .$id . '">' . $title . '</label>' .
								'</th>' .
								'<td>' .
										'<select name="' . $name . '" id="' . $id . '">';
										
												foreach($field_values as $current_value) {
														$selected = '';
								
														if($current_value[$id_field] == $value) {
																$selected = ' selected="selected"';
														}
														
														$retval .= '<option value="' . $current_value[$id_field] . '"' . $selected . '>' . htmlentities($current_value[$title_field]) . '</option>';
												}
										$retval .= '</select>' .
								'</td>' .
						'</tr>';
						
				return $retval;
		}

		//////////////////////////////////////////////////////////////////////////////
		// Zeile für Editor: Text Input
		//////////////////////////////////////////////////////////////////////////////
		public static function editTable_textRow($title, $id, $name, $value) {
				$retval = 
						'<tr>' .
								'<th>' .
										'<label for="' .$id . '">' . $title . '</label>' .
								'</th>' .
								'<td>' .
										'<input type="text" name="' . $name . '" id="' . $id . '" value="' . htmlentities($value) . '" />';
								'</td>' .
						'</tr>';
				return $retval;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Zeile für Editor: Textarea
		//////////////////////////////////////////////////////////////////////////////
		public static function editTable_textareaRow($title, $id, $name, $value) {
				$retval = 
						'<tr>' .
								'<th>' .
										'<label for="' .$id . '">' . $title . '</label>' .
								'</th>' .
								'<td>' .
										'<textarea name="' . $name . '" id="' . $id . '" rows="7">' . htmlentities($value) . '</textarea>';
								'</td>' .
						'</tr>';
				return $retval;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Zeile für Editor: Text Input
		//////////////////////////////////////////////////////////////////////////////
		public static function editTable_integerRow($title, $id, $name, $value) {
				$retval = 
						'<tr>' .
								'<th>' .
										'<label for="' .$id . '">' . $title . '</label>' .
								'</th>' .
								'<td>' .
										'<input type="text" name="' . $name . '" id="' . $id . '" value="' . (int)($value) . '" />';
								'</td>' .
						'</tr>';
				return $retval;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Zeile für Editor: Textarea
		//////////////////////////////////////////////////////////////////////////////
		public static function editTable_multipleImageSelector($title, $id, $name, $value) {
				$retval = 
						'<tr>' .
								'<td colspan="2" id="multiple-image-selector' . $id . '">' . 
										'<button type="button" class="mv-multiple-image-selector-add-image">Bild hinzufügen</button>' .
										'<div class="mv-multiple-image-selector-images" data-attr-selector-id="multiple-image-selector' . $id . '" data-attr-id="' . $id . '" data-attr-plugin="mvcam" data-attr-ajax-url="' . admin_url('admin-ajax.php') . '"></div>' .
								'</td>' .
						'</tr>';
				return $retval;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Zeile für Editor: Link..
		//////////////////////////////////////////////////////////////////////////////
		public static function editTable_LinkRow($title, $id, $value, $site_id_for_details, $no_link_message = NULL, $no_link_value = NULL) {
				//Wenn ein bestimmter Wert nicht gegeben sein darf (bspw., wenn der Wert nicht definiert ist).
				if($no_link_value !== NULL && $no_link_message !== NULL) {
						if($no_link_value == $site_id_for_details) {
								$retval = '<tr>' .
										'<td>&nbsp;</td>' .
										'<td>' .
												'<span style="color: red">' . $no_link_message . '</span>' .
										'</td>' .
								'</tr>';
								return $retval;
						}
				}
				
				//Alles okay mit dem Wert: Jetzt den Link zusammenstellen und anzeigen.
				$site_url = get_permalink($site_id_for_details);
				$url_template = $site_url . '?id=%d';
				
				$url = sprintf($url_template, $value);
			
				$retval =
						'<tr>' .
								'<td>&nbsp;</td>' .
								'<td>' .
										'<a href="' . $url . '" target="_blank">' . $title . '</a>' .
								'</td>' .
						'</tr>';
					
				return $retval;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Zeile für Editor: Link..
		//////////////////////////////////////////////////////////////////////////////
		public static function editTable_image($title, $id, $name, $value) {
				$preview_image = '';
				
				if($value != '') {
						$preview_image .= '<img src="' . $value . '" class="small-preview-image" />';
				}
			
				$retval =
						'<tr>' .
								'<th><label for="' .$id . '">' . $title . '</label></th>' .
								'<td colspan="2" id="multiple-image-selector' . $id . '">' .
										'<div class="mv-image-selector-single">' .
												'<button type="button" class="mv-image-selector-add-image">Bild hinzufügen</button>' . 
												'<input type="hidden" name="' . $name . '" value="' . $value . '" />' .
												'<div class="mv-image-selector-preview">' . $preview_image . '</div>' .
												/*
												'<button type="button" class="mv-multiple-image-selector-add-image">Bild hinzufügen</button>' .
												'<div class="mv-multiple-image-selector-images" data-attr-selector-id="multiple-image-selector' . $id . '" data-attr-id="' . $id . '" data-attr-plugin="mvcam" data-attr-ajax-url="' . admin_url('admin-ajax.php') . '"></div>' .
												*/
										'</div>' .
								'</td>' .
						'</tr>';
					
				return $retval;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Zeile für Editor: Many-To-Many-Selector.
		// Erwartete Werte im Data-Array:
		// 'name' => Name (ID) des Eingabefeldes
		// 'label' => Titel des Feldes.
		// 'type' => 'many_to_many_selector'
		// 'local_id_field' => Name des Feldes, aus dem die ID für das gerade bearbeitete Objekt entnommen wird.
		// 'values_object' => Daten-Modell für das Foreign-Model
		// 'values_object_id_field' => Name des Feldes aus dem Foreign-Model, aus dem die ID des Foreign Models entnommen wird.
		// 'values_object_title_field' => Name des Feldes aus dem Foreign-Model, mit dem Text der passend dazu angezeigt wird.
		// 'many_to_many_object' => Name der Mapping Tabelle, bzw. des Mapping Models.
		// 'many_to_many_object_local_id_field' => Name des Feldes im Mapping Table, in dem die ID des gerade bearbeiteten Objektes gespeichert wird.
		// 'many_to_many_object_values_object_id_field' => Name des Feldes im Mapping Table, in dem die ID des Foreign-Models gespeichert wird.
		//'many_to_many_object_sort_order_field' => Feld, in dem die Sortierung hinterlegt wird. Wenn leer, wird keine Sortiermöglichkeit angeboten und die Sortierung auch nicht gespeichert.
		//////////////////////////////////////////////////////////////////////////////
		public static function editTable_ManyToManySelector($data) {
				$retval = 
						'<tr ' .
								'id="many-to-many-selector-' . htmlspecialchars($data['name']) . '" ' .
								'class="mv-many-to-many-selector" ' .
								'data-attr-name="' . htmlspecialchars($data['name']) . '" ' .
								'data-attr-label="' . htmlspecialchars($data['label']) . '" ' .
								'data-attr-type="' . htmlspecialchars($data['type']) . '" ' .
								'data-attr-local_id_field="' . htmlspecialchars($data['local_id_field']) . '" ' .
								'data-attr-values_object="' . htmlspecialchars($data['values_object']) . '" ' .
								'data-attr-values_object_id_field="' . htmlspecialchars($data['values_object_id_field']) . '" ' .
								'data-attr-values_object_title_field="' . htmlspecialchars($data['values_object_title_field']) . '" ' .
								'data-attr-many_to_many_object="' . htmlspecialchars($data['many_to_many_object']) . '" ' .
								'data-attr-many_to_many_object_local_id_field="' . htmlspecialchars($data['many_to_many_object_local_id_field']) . '" ' .
								'data-attr-many_to_many_object_values_object_id_field="' . htmlspecialchars($data['many_to_many_object_values_object_id_field']) . '" ' .
								'data-attr-many_to_many_object_sort_order_field="' . htmlspecialchars($data['many_to_many_object_sort_order_field']) . '" ' .
								'data-attr-plugin="mvcam" ' .
								'data-attr-ajax-url="' . htmlentities(admin_url('admin-ajax.php')) . '" ' .
						'>' .
								'<th>' .
										'<label for="' .$data['name'] . '">' . $data['label'] . '</label>' .
								'</th>' .
								'<td>' .
										'<input type="hidden" name="' . htmlspecialchars($data['name']) . '" id="many-to-many-hidden-input-' . htmlspecialchars($data['name']) . '" class="many-to-many-hidden-input" />' .
										'<div class="mv-many-to-many-selector-options">' . 
												'<button type="button" class="mv-many-to-many-selector-add">Wert hinzufügen</button>' . 
												'<div class="mv-many-to-many-selector-loading-info" style="display: none;">Daten werden geladen..</div>' .
												'<div class="mv-many-to-many-selector-options-selector" style="display: none;"></div>' .
										'</div>' .
										'<div class="mv-many-to-many-selector-values-container">' .
												'<div class="mv-many-to-many-selector-values-title">' .
														'Aktuell gewählte Werte:' .
												'</div>' .
												'<div class="mv-many-to-many-selector-values">' .
														'Bereite Ladevorgang vor..' .
												'</div>' .
										'</div>' .
								'</td>' .
						'</tr>';
				return $retval;
		}
		//////////////////////////////////////////////////////////////////////////////
		// HTML-Code für ein Wordpress Admin-Menü anhand einer Definition erstellen.
		//////////////////////////////////////////////////////////////////////////////
		public function buildMenuByDefinition($action, $data, $menu_definition, $plugin_name) {
				$include_javascripts = array();
				
				$retval = '<style>
						.mv-admin-form fieldset { width: 50%; display: inline-block; vertical-align: top; padding: 20px; box-sizing: border-box; }
						.mv-admin-table { width: 100%; }
						.mv-admin-table th { vertical-align: top; text-align: right; width: 5%; }
						.mv-admin-table input { width: 100%; }
						.mv-admin-table textarea { width: 100%; }
						.mv-multiple-image-selector-images { margin-top: 20px; }
						.mv-multiple-image-selector-entry img { width: 100%; }
						.mv-multiple-image-selector-entry-left { width: 85%; display: inline-block; vertical-align: middle; position: relative; }
						.mv-multiple-image-selector-entry-right { padding-left: 5%; width: 10%; display: inline-block; vertical-align: middle; }
						.mv-multiple-image-selector-delete { position: absolute; top: 5px; left: 5px; background-color: #FFF; color: red; border: 1px solid red; padding: 5px; height: 25px; width: 25px; line-height: 5px; font-weight: bold; }
						.mv-multiple-image-selector-delete:hover { color: #000; border-color: #000; cursor: pointer; }
						.mv-image-selector-preview img { max-height: 100px; max-width: 100px; }
						.mv-many-to-many-selector-options { padding-left: 20px; margin-bottom: 10px; }
						.mv-many-to-many-selector-options-selector th { text-align: left; }
						.mv-many-to-many-selector-options-selector { background-color: #FFF; border: 1px solid #CCC; }
						.mv-many-to-many-selector-options-selector th { background-color: #808080; color: #FFF; padding: 5px; }
						.mv-many-to-many-selector-selectable-value { cursor: pointer; }
						.mv-many-to-many-selector-selectable-value td { padding-top: 5px; padding-bottom: 5px; padding-left: 5px; }
						.mv-many-to-many-selector-selectable-value:hover { background-color: #000080; color: #FFF; }
						.mv-many-to-many-selector-values-container { padding-left: 20px; }
						.mv-many-to-many-selector-values-title { font-weight: bold; }
						.mv-many-to-many-item-selected { border: 1px solid #77F; background-color: #CECEFF; margin-right: 5px; border-radius: 4px; padding: 0 4px; cursor: pointer; margin-top: 6px; margin-bottom: 6px; }
						.mv-many-to-many-item-remove-from-selection { padding-left: 6px; font-weight: bold; margin-top: -1px; display: inline-block; vertical-align: top; float: right; }
						.mv-many-to-many-item-remove-from-selection:hover { color: #F00; }
						.mv-many-to-many-item-sort-placeholder { height: 20px; background-color: #FFFF00; border: 1px solid #77F; margin-bottom: 6px; margin-top: 6px; border-radius: 4px; }
				</style>';
				
				$retval .= '<h1>' . $this->text_variables['editor_title'] . '</h1>' . "\n";
				$retval .= '<form action="' . $action . '" method="POST" class="mv-admin-form">' . "\n";
						
										foreach($menu_definition as $section) {
												$retval .= '<fieldset>';
														$retval .= '<legend>' . $section['title'] . '</legend>';
														$retval .= '<table class="mv-admin-table">' . "\n";
														
														//Loop through all the fields that should be displayed in this section -> according to the menu_definition
														foreach($section['fields'] as $field) {
																//Loop trough all the data entries, and try to get the field with the current name.
																$field_found = false;
																
																foreach($data['fields'] as $d) {
																		if($d['name'] == $field['name']) {
																				$field_found = true;
																			
																				switch($field['type']) {
																						case 'id':
																								if((int)$d['value'] != 0) {
																										$retval .= mvWpAdminMenu::editTable_InfoRow(
																												$title = $field['label'] . ': ',
																												$id = $field['name'],
																												$value = $d['value']
																										);
																								}
																								break;
																						
																						case 'text':
																								$retval .= mvWpAdminMenu::editTable_textRow(
																										$title = $field['label'] . ': ',
																										$id = $field['name'],
																										$name = $field['name'],
																										$value = $d['value']
																								);
																								break;
																								
																						case 'dropdown':
																								$retval .= mvWpAdminMenu::editTable_selectRow(
																										$title = $field['label'] . ': ',
																										$id = $field['name'],
																										$name = $field['name'],
																										$value = $d['value'],
																										$field_values = $field['values'],
																										$id_field = $field['id_field'],
																										$title_field = $field['title_field']
																								);
																								break;
																								
																						case 'float':
																								$retval .= mvWpAdminMenu::editTable_textRow(
																										$title = $field['label'] . ': ',
																										$id = $field['name'],
																										$name = $field['name'],
																										$value = $d['value']
																								);
																								break;
																								
																						case 'int':
																								$retval .= mvWpAdminMenu::editTable_integerRow(
																										$title = $field['label'] . ': ',
																										$id = $field['name'],
																										$name = $field['name'],
																										$value = $d['value']
																								);
																								break;
																								
																						case 'textarea':
																								$retval .= mvWpAdminMenu::editTable_textareaRow(
																										$title = $field['label'] . ': ',
																										$id = $field['name'],
																										$name = $field['name'],
																										$value = $d['value']
																								);
																								break;
																								
																						case 'page_link':
																								if((int)$d['value'] != 0) {
																										$retval .= mvWpAdminMenu::editTable_LinkRow(
																												$title = $field['label'],
																												$id = $field['name'],
																												$value = $d['value'],
																												$site_id_for_details = $field['site_id_for_details'],
																												$no_link_message = $field['no_link_message'],
																												$no_link_value = $field['no_link_value']
																										);
																								}
																								break;
																						
																						case 'image':
																								$retval .= mvWpAdminMenu::editTable_image(
																										$title = $field['label'] . ': ',
																										$id = $field['name'],
																										$name = $field['name'],
																										$value = $d['value']
																								);
																								$include_javascripts['image-selector'] = 'image-selector';
																								break;
																								
																						default:
																								echo 'Unbekannter Eingabe-Feld Typ ' . $field['type'] . ' in ' . __FILE__ . ', Zeile: ' . __LINE__;
																								die;		
																				}
																		}
																}
																
																//Zusätzliche Felder - also bspw. solche, die über Tabellen verknüpft sind, wie bspw. mehrere Bilder.
																if($field_found == false) {
																		switch($field['type']) {
																				case 'multiple-image-selector':
																						$retval .= mvWpAdminMenu::editTable_multipleImageSelector(
																								$title = $field['label'] . ': ',
																								$id = $field['name'],
																								$name = $field['name'],
																								$value = $d['value']
																						);
																						$include_javascripts[] = 'multiple-image-selector';
																						break;
																				case 'many_to_many_selector':
																								$retval .= mvWpAdminMenu::editTable_ManyToManySelector(
																										$data = $field
																								);
																								$include_javascripts['many-to-many-selector'] = 'many-to-many-selector';
																								break;
																				default:
																						echo 'Unbekannter Eingabe-Feld Typ ' . $field['type'] . ' in ' . __FILE__ . ', Zeile: ' . __LINE__ . ' für das Feld: ';
																						var_dump($field);
																						die;
																		}
																}
														}
														
														$retval .= '</table>';
												$retval .= '</fieldset>';
										}

						$retval .= '<div>';
						$retval .= '<button type="submit">Speichern</button>';
						$retval .= '</div>';
				$retval .= '</form>';
				
				$plugin_url = plugins_url( mv_core()->getPluginFolderName() );

				if(count($include_javascripts) > 0) {
						foreach($include_javascripts as $js) {
								if($js == 'multiple-image-selector') {
										$retval .= '<script src="' . $plugin_url . '/admin/js/multiple-image-selector.js"></script>';
								}
										
								if($js == 'image-selector') {
										$retval .= '<script src="' . $plugin_url . '/admin/js/image-selector.js"></script>';
								}
								if($js == 'many-to-many-selector') {
										$retval .= '<script src="' . $plugin_url . '/admin/js/many-to-many-selector.js"></script>';
								}
						}
				}
				
				return $retval;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// HTML-Code für den Datenbank-Installations-Screen erzeugen und zurückgeben.
		//////////////////////////////////////////////////////////////////////////////
		public static function buildDatabaseInstallationScreenHtml($module_name) {
				$html = '<h1>Datenbank installieren</h1>';
				$html .= '<p>Datenbank-Tabellen für das Plugin wurden nicht gefunden. Sie können die Tabellen mit nachfolgendem Button installieren, oder alternativ händisch einspielen (z.B. um ein Backup wiederherzustellen).</p>';
								
				$html .= '<form action="admin.php" method="GET" />';
						$html .= '<input type="hidden" name="page" value="' . $module_name . '" />';
						$html .= '<input type="hidden" name="action" value="install_admin_database" />';
						$html .= '<button type="submit">Datenbank-Tabellen jetzt installieren</button>';
				$html .= '</form>';
								
				return $html;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// HTML-Code für ein Wordpress Daten-Listing.
		//////////////////////////////////////////////////////////////////////////////
		public function buildListing($data_entries, $fields_to_display, $menu_slug, $page, $pages_total, $results_per_page = 10, $results_total = 0, $new_entry_link = '') {
				$retval = '<style>';
				$retval .= '.current_value { cursor: pointer; }';
				$retval .= '</style>';
				
				//Erfolgsmeldungen ausgeben.
				if(isset($_GET['success'])) {
						if($_GET['success'] == '1') {
								$retval .= '<div class="notice notice-success is-dismissible">';
										$retval .= '<p>' . __( 'Daten wurden gespeichert.', 'mvcam_expose' ) . '</p>';
								$retval .= '</div>';
						}
				}
				
				//Link zum Hinzufügen eines neuen Eintrages.
				$new_url = admin_url('admin.php?page=' . $menu_slug . '&amp;action=new');
				$retval .= '<p><a href="' . $new_url . '"><button type="submit">Neuen Eintrag erstellen</button></a></p>';
				
				//Listing zusammenstellen.
				$retval .= '<h1>' . $this->text_variables['listing_title'] . '</h1>';
				$retval .= '<table class="wp-list-table widefat fixed striped tags">';
						$retval .= '<thead>';
								$retval .= '<tr>';
										foreach($fields_to_display as $ftd) {
												$style = '';
												
												if(isset($ftd['width'])) {
														$style = ' style="width: ' . $ftd['width'] . '%"';
												}
												
												$retval .= '<th scope="col" id="' . $ftd['field_name'] . '" class="manage-column column-name column-primary desc"' . $style . '><span>' . $ftd['label'] . '</span></td>';
												
												
										}
								$retval .= '</tr>';
						$retval .= '</thead>';
						$retval .= '<tbody id="the-list" data-wp-lists="list:tag">';
								foreach($data_entries as $entry) {
										//Url zum Bearbeiten dieses Beitrages zusammensetzen.
										$edit_url = admin_url('admin.php?page=' . $menu_slug . '&amp;action=edit&amp;id=' . $entry['id']);
										$delete_url = admin_url('admin.php?page=' . $menu_slug . '&amp;action=delete&amp;id=' . $entry['id']);
									
										$retval .= '<tr>';
												//Html für einen Eintrag zusammenbauen, abhängig von den Feldern.
												foreach($fields_to_display as $ftd) {
														switch($ftd['type']) {
																case 'text':
																		$retval .= '<td>';
																				$retval .= '<strong>';
																						$retval .= '<a class="row-title" href="' . $edit_url . '">' . htmlspecialchars($entry[$ftd['field_name']]) . '</a>';
																				$retval .= '</strong>';
																		$retval .= '</td>';
																		break;
																case 'actions':
																		$retval .= '<td class="has-row-actions">';
																				if(isset($ftd['action']) && in_array('edit', $ftd['action'])) {				//Wenn der "Bearbeiten" Button angezeigt werden soll.
																						$retval .= '<a href="' . $edit_url . '">[Bearbeiten]</a>&nbsp;&nbsp;';
																				}
																				
																				if(isset($ftd['action']) && in_array('delete', $ftd['action'])) {			//Wenn der "Löschen" Button angezeigt werden soll.
																						$retval .= '<a href="' . $delete_url . '" style="color: #a00;">[Löschen]</a>&nbsp;&nbsp;';
																				}
																		$retval .= '</td>';
																		break;
																case 'switch':
																		$retval .= '<td class="mv-admin-switcher" data-attr-values="' . htmlspecialchars(json_encode($ftd['values']), ENT_QUOTES, 'UTF-8') . '">';
																				foreach($ftd['values'] as $val) {
																						if($val[$ftd['id_field']] == $entry[$ftd['field_name']]) {
																								$color = '';
																								if(isset($ftd['color_field']) && $val[$ftd['color_field']] != '') {
																										$color = ' style="color: ' . $val[$ftd['color_field']] . ';"';
																								}
																								$switch_id = $entry[$ftd['use_data_fields_value_for_index']];
																								$retval .= 
																										'<span ' . 
																												'class="current_value"' . 
																												$color . ' ' .
																												'data-attr-current-value="' . $val[$ftd['id_field']] . '" ' .
																												'data-attr-switch-id="' . $switch_id . '" ' .
																												'data-attr-url="' . admin_url('admin-ajax.php') . '" ' .
																												'data-attr-value-field="' . $ftd['id_field'] . '" ' .
																												'data-attr-page="' . $menu_slug . '" ' .
																												'data-attr-title-field="' . $ftd['title_field'] . '" ' .
																												'data-attr-color-field="' . $ftd['color_field'] . '" ' .
																										'>' .
																												$val[$ftd['title_field']] . 
																										'</span>';
																						}
																				}
																		$retval .= '</td>';
																		break;
																default:
																		$retval .= '<td>Unbekannter Feldtyp</td>';
																		break;
														}
												}
										$retval  .= '</tr>';
								}
						$retval .= '</tbody>';
				$retval .= '</table>';
				
				//TODO: Wir könnten diesen Code hier auslagern in eine eigene Funktion, um ihn dann über der Tabelle auch anzuzeigen! :)
				$retval .= '<div class="tablenav">';
						$retval .= '<div class="alignleft actions">';
								//Link zum Hinzufügen eines neuen Eintrages.
								$new_url = admin_url('admin.php?page=' . $menu_slug . '&amp;action=new');
								$retval .= '<a href="' . $new_url . '"><button type="submit">Neuen Eintrag erstellen</button></a>';
						$retval .= '</div>';
						$retval .= '<div class="tablenav-pages">';
								$retval .= '<span class="displaying-num">' . $results_total . ' Einträge</span>';
								//$retval .= '<span class="pagination-links">';
				
								$retval .= paginate_links( 
										array(
												'base' => add_query_arg( 'mvpage', '%#%' ),
												'format' => '',
												'prev_text' => __( '&laquo;', 'text-domain' ),
												'next_text' => __( '&raquo;', 'text-domain' ),
												'total' => $pages_total,
												'current' => $page
										)
								);
						$retval .= '</div>';
				$retval .= '</div>';
				
				$retval .= $this->addModuleDescription($menu_slug);
				
				return $retval;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Admin Editor für einen Eintrag.
		//////////////////////////////////////////////////////////////////////////////
		public function addModuleDescription($menu_slug) {	
				//$filename = basename(__FILE__);
				$filename = $menu_slug;
				$directory = dirname(dirname(__FILE__));
				$filename .= '.html';
				$path = $directory . '/documentation/user/admin/' . $filename;
				
				$retval = '';
				
				if(file_exists($path)) {
						$contents = file_get_contents($path);
						$contents = htmlentities($contents);
						
						$contents = str_replace(
								array(
										'&lt;h2&gt;', '&lt;/h2&gt;',
										'&lt;p&gt;', '&lt;/p&gt;',
										'&lt;code&gt;', '&lt;/code&gt;',
										'&lt;br /&gt;',
										'&lt;ul&gt;', '&lt;/ul&gt;',
										'&lt;li&gt;', '&lt;/li&gt;'
								),
								
								array(
										'<h2>', '</h2>',
										'<p>', '</p>',
										'<code>', '</code>',
										'<br />',
										'<ul>', '</ul>',
										'<li>', '</li>'
								),
								$contents
						);
						
						$retval = $contents;
				}
				
				return $retval;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Admin Editor für einen Eintrag.
		//////////////////////////////////////////////////////////////////////////////
		public function renderAdminEditor($action, $data, $menu_definition, $menu_slug) {	
				wp_enqueue_media();
			
				$output = $this->buildMenuByDefinition($action, $data, $menu_definition, $menu_slug);
				echo $output;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Admin Dialog "Eintrag löschen"
		//////////////////////////////////////////////////////////////////////////////
		public function renderDeleteDialog($action, $data, $menu_definition, $menu_slug) {
				$retval = '<h1>Eintrag löschen</h1>';
				$retval .= '<p>Wollen Sie diesen Eintrag wirklich löschen? (Dieser Vorgang kann nicht rückgängig gemacht werden!)</p>';
				$retval .= '<form action="' . $action . '" method="POST">';
				
						foreach($menu_definition as $section) {
								$retval .= '<fieldset>';
										$retval .= '<legend>' . $section['title'] . '</legend>';
										$retval .= '<table class="mv-admin-table">' . "\n";
										
										//Loop through all the fields that should be displayed in this section -> according to the menu_definition
										foreach($section['fields'] as $field) {
												//Loop trough all the data entries, and try to get the field with the current name.
												$field_found = false;
												
												foreach($data['fields'] as $d) {
														if($d['name'] == $field['name']) {
																$retval .= mvWpAdminMenu::editTable_InfoRow(
																		$title = $field['label'] . ': ',
																		$id = $field['name'],
																		$value = $d['value']
																);
														}
												}
										}
										
										$retval .= '</table>';
								$retval .= '</fieldset>';
						}
										
						
						$retval .= '<p>&nbsp;</p>';
						$retval .= '<button type="submit" style="color: red;">Eintrag löschen</button>&nbsp;&nbsp;';
										
						//Zurück-Button anzeigen
						
						$list_url = admin_url('admin.php?page=' . $menu_slug);
						$retval .= '<a href="' . $list_url . '">[Abbrechen]</a>';
				$retval .= '</form>';
				
				return $retval;
		}
}