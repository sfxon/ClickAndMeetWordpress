<?php

namespace mvclickandmeet_namespace;

if(!defined('ABSPATH')) { exit; }

class mvMailTextSettingsAdmin extends mvWpAdmin {
		//////////////////////////////////////////////////////////////////////////////
		// Admin Funktionalität initialisieren.
		//////////////////////////////////////////////////////////////////////////////
		public function initAdminFunctions() {
				$this->addAdminSubMenu('mvcam', 'E-Mail Texte', 'E-Mail Texte', 'manage_options', 'mvcam_mail_text_settings');
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Wenn die Seite im Admin aufgerufen wird, Werte initialisieren.
		//////////////////////////////////////////////////////////////////////////////
		public function adminPageRender() {
				$this->setModel('mvclickandmeet_namespace\\mvMailTextSettings');
				$this->initAdminEditorFields();
				$this->initAdminDeleteDialogFields();
				$this->initAdminListingDefinition();
				$this->addTextVariables();
				
				parent::adminPageRender();
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Admin Text-Variablen festlegen.
		//////////////////////////////////////////////////////////////////////////////
		public function addTextVariables() {
				$this->setTextVariable('listing_title', 'E-Mail Texte');
				$this->setTextVariable('editor_title', 'E-Mail Texte');
				$this->setTextVariable('editor_title_new', 'Neuen Eintrag erstellen');
				$this->setTextVariable('editor_title_edit', 'Eintrag bearbeiten');
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Listing Definition für den Admin erstellen.
		//////////////////////////////////////////////////////////////////////////////
		public function initAdminListingDefinition() {
				//Felder definieren, die angezeigt werden sollen.
				$fields_to_display = array(
						array(
								'label' => 'ID',
								'type' => 'text',
								'field_name' => 'id',
								'width' => '2%'
						),
						array(
								'label' => 'Name',
								'type' => 'text',
								'field_name' => 'title',
								'width' => '40%'
						),
						array(
								'label' => 'Aktion',
								'type' => 'actions',
								'action' => array('edit', 'delete'),
								'field_name' => 'actions'
						)
				);
				
				$this->listing_definition = $fields_to_display;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Admin Editor-Felder initialisieren.
		//////////////////////////////////////////////////////////////////////////////
		public function initAdminEditorFields() {
				//Menü Definition
				$menu_definition = array(
						array(
								'id' => 'section1',
								'title' => 'Allgemeine Einstellungen',
								'fields' => array(
										array(
												'name' => 'id',
												'label' => 'ID',
												'type' => 'id'
										),
										array(
												'name' => 'title',
												'label' => 'Name',
												'type' => 'text'
										),
										
										array(
												'name' => 'email_text',
												'label' => 'Text',
												'type' => 'textarea'
										)
								)
						)
				);
				
				$this->menu_definition = $menu_definition;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Admin Editor-Felder initialisieren.
		//////////////////////////////////////////////////////////////////////////////
		public function initAdminDeleteDialogFields() {
				//Menü Definition
				$delete_dialog_definition = array(
						array(
								'id' => 'section1',
								'title' => 'Allgemein',
								'fields' => array(
										array(
												'name' => 'id',
												'label' => 'ID',
												'type' => 'id'
										),
										array(
												'name' => 'title',
												'label' => 'Titel',
												'type' => 'text'
										)
								)
						)
				);
				
				$this->delete_dialog_definition = $delete_dialog_definition;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Einen Eintrag in der Datenbank erstellen.
		//////////////////////////////////////////////////////////////////////////////
		public function createEntryInDatabase() {
				$mvMailTextSettings = new mvMailTextSettings();
				$id = $mvMailTextSettings->create();
				
				$slug = $_GET['page'];
				header('Location: ' . admin_url('admin.php?page=' . $slug . '&action=edit&id=' . (int)$id));
				die;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Einen Eintrag in der Datenbank aktualisieren.
		//////////////////////////////////////////////////////////////////////////////
		function updateEntryInDatabase() {
				$id = (int)mvCore::getGetVar('id');
				
				$mvMailTextSettings = new mvMailTextSettings();
				$mvMailTextSettings->update($id);
				
				$slug = $_GET['page'];
				header('Location: ' . admin_url('admin.php?page=' . $slug . '&action=edit&id=' . (int)$id));
				die;
		}
		
		//////////////////////////////////////////////////////////////////////////////
		// Einen Eintrag aus der Datenbank löschen.
		//////////////////////////////////////////////////////////////////////////////
		public function deleteConfirm() {
				$id = (int)mvCore::getGetVar('id');
				
				$mvMailTextSettings = new mvMailTextSettings();
				$mvMailTextSettings->delete($id);
				
				$slug = $_GET['page'];
				header('Location: ' . admin_url('admin.php?page=' . $slug));
				die;
		}
}
