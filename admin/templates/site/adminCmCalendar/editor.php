<?php

namespace mvclickandmeet_namespace;

function print_svg_text_box($text) {
		return '<svg viewBox="0 0 47 47"><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" style="font-familiy: arial; font-size: 24px;" fill="#FFF">' . $text . '</text></svg>';
}

//Secure this..
if (!defined( 'ABSPATH' ) ) exit;

//Now render..
?>

<h1>Kalender</h1>

<div class="container-fluid">	
		<div class="mv-box">
				<div class="mvbox">
						<div class="mvbox-body">
            		<div class="row" id="mvCmCalendarSuccessMessage" style="display: none;">
                		<div class="col-xs-12">
                    		<div class="alert alert-success" role="alert" >Infomeldung</div>
                    </div>
                </div>
								<div class="row">
										<div class="col-xs-12" id="mv-page-container">
                    		<div class="kalender">
                        		<div class="kalender-pre-header">
                            		<div class="kalender-event-location">
                                		<label for="mv-kalender-event-location">Laden</label>
                                    <select id="mv-kalender-event-location" class="form-control">
                                    		<option value="0">-- Alle --</option>
                                        
                                        <?php
																						foreach($this->data['EVENT_LOCATIONS'] as $el) {
                                            		echo '<option value="' . $el['id'] . '">' . htmlspecialchars($el['title']) . '</option>';
																						}
																				?>
                                    </select>
                                </div>
                                
                                <div class="kalender-user-unit">
                                		<label for="mv-kalender-user-unit">Team/Mitarbeiter</label>
                                  <select id="mv-kalender-user-unit" class="form-control">
                                    		<option value="0">-- Alle --</option>
                                     		
                                        <?php
																						foreach($this->data['USER_UNITS'] as $uu) {
                                            		echo '<option value="' . $uu['id'] . '" data-attr-event-location-id="' . $uu['event_location_id'] . '">' . htmlspecialchars($uu['title_long']) . '</option>';
																						}
																				?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="kalender-top">
                                <div class="kalender-month">
                                		<label for="current_month" class="kalender-top-label">Monat</label>
                                  <input type="text" value="<?php echo $this->data['DATA']['date']['current_month']; ?>" id="current_month" class="kalender-top-input" style="width: 80px;" />
                                </div>
                                <div class="kalender-year">
                                		<label for="current_month" class="kalender-top-label">Jahr</label>
                               		<input type="text" value="<?php echo $this->data['DATA']['date']['current_year']; ?>" id="current_year" class="kalender-top-input" style="width: 80px;" />
                                </div>
                                <div class="kalender-top-nav pull-right"><div class="kalender-top-nav-prev"><img src="<?php echo $this->data['TEMPLATE_URL'] . '/wp-content/plugins/mvcam/admin/templates/images/arrow-left.png'; ?>" /></div><div class="kalender-top-nav-next"><img src="<?php echo $this->data['TEMPLATE_URL'] . '/wp-content/plugins/mvcam/admin/templates/images/arrow_right.png'; ?>" /></div></div>
                                <div class="mv-clearfix"></div>
                        		</div>
                            
                            <div class="kalender-header">
                            		<?php
                                    echo '<div class="kalender-header-weeknumber">' . print_svg_text_box('KW') . '</div>' .
                                    '<div class="kalender-header-day">' . print_svg_text_box('Mo') . '</div>' .
                                    '<div class="kalender-header-day">' . print_svg_text_box('Di') . '</div>' .
                                    '<div class="kalender-header-day">' . print_svg_text_box('Mi') . '</div>' .
                                    '<div class="kalender-header-day">' . print_svg_text_box('Do') . '</div>' .
                                    '<div class="kalender-header-day">' . print_svg_text_box('Fr') . '</div>' .
                                    '<div class="kalender-header-day">' . print_svg_text_box('Sa') . '</div>' .
                                    '<div class="kalender-header-day">' . print_svg_text_box('So') . '</div>';
																?>
                            </div>                            
                            
                            <div class="calendar-content-loading">Kalenderdaten werden geladen.</div>
                            
                            <div class="calendar-content" style="display: none;">
                                
                            </div>
                        </div>
                        
                        <div class="times-list">
                       		<input type="hidden" id="mv-times-list-current-date-day" value="<?php echo $this->data['DATA']['date']['today_day']; ?>" />
                          <input type="hidden" id="mv-times-list-current-date-month" value="<?php echo $this->data['DATA']['date']['today_month']; ?>" />
                          <input type="hidden" id="mv-times-list-current-date-year" value="<?php echo $this->data['DATA']['date']['today_year']; ?>" />
                        		<div class="times-list-title">Termine</div>
                        		<div class="times-list-top">
                            		<div class="times-list-top-left">Wird aktualisiert</div>
                                <div class="times-list-top-right pull-right">
                                		<div class="times-list-top-right-count"></div>
                                    <div class="times-list-top-right-add">
                                    		<button type="button" class="btn btn-primary" id="mv-add-termin"><i class="fa fa-plus"></i> Termin hinzufügen</button>
                                    </div>
                                </div>
                                <div class="mv-clearfix"></div>
                            </div>
                            <div class="times-list-content">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="url" value="<?php echo $this->data['TEMPLATE_URL']; ?>" />

<div id="mv-cm-timer-template" style="display: none;" data-attr-appointment-data-json="">
		<div class="mv-cm-timer-row">
    		<div class="mv-cm-timer-time"></div>
        <div class="mv-cm-timer-description"></div>
        <div class="mv-cm-timer-user"></div>
        <div class="mv-cm-timer-status"></div>
        <div class="mv-cm-timer-action">
        		<button class="btn btn-primary mv-cm-timer-action-edit"><i class="fa fa-pen"></i></button>
        </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="AppointmentEditor" tabindex="-1" role="dialog" aria-labelledby="AppointmentEditorModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Schließen"><span aria-hidden="true">&times; Schließen/Abbrechen</span></button>
                <h4 class="modal-title" id="AppointmentEditorModalLabel">Termin bearbeiten</h4>
            </div>
            <div class="modal-body">
              	<input type="hidden" value="0"  id="editor-appointment-id" />
                
                <div class="row">
                		<div class="col-md-6">
                        <div class="form-group">
                            <label for="editor-date" class="control-label">Datum:</label>
                            <input type="text" class="form-control" id="editor-date">
                            <div class="mv-error" id="editor-date-error" style="display: none"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="editor-time" class="control-label">Uhrzeit:</label>
                            <input type="text" class="form-control" id="editor-time">
                            <div class="mv-error" id="editor-time-error" style="display: none"></div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="editor-event-location" class="control-label">Laden:</label>
                    <select id="editor-event-location" class="form-control">
                    		<option value="0">-- Bitte wählen --</option>
                        <?php
                        
                        foreach($this->data['EVENT_LOCATIONS'] as $el) {
                        		echo '<option value="' . $el['id'] . '">' . $el['title'] . '</option>';
                        }
												
												?>
                    </select>
                    <div class="mv-error" id="editor-event-location-error" style="display: none"></div>
                </div>
                
                <div class="form-group">
                    <label for="editor-user-unit-id" class="control-label">Team/Mitarbeiter:</label>
                    <select id="editor-user-unit-id" class="form-control">
                    		<option value="0">-- Bitte wählen --</option>
                        <?php
                        
                        foreach($this->data['USER_UNITS'] as $uu) {
                        		echo '<option value="' . $uu['id'] . '" data-attr-event-location-id="' . $uu['event_location_id'] . '">' . $uu['title_long'] . '</option>';
                        }
												
												?>
                    </select>
                    <div class="mv-error" id="editor-user-unit-id-error" style="display: none"></div>
                </div>
                
                <div class="form-group">
                    <label for="editor-duration-in-minutes" class="control-label">Dauer in Minuten:</label>
                    <input type="text" class="form-control" id="editor-duration-in-minutes">
                    <div class="mv-error" id="editor-duration-in-minutes-error" style="display: none"></div>
                </div>
                
                <div class="form-group">
                    <label for="editor-status" class="control-label">Status:</label>
                    <select id="editor-status" class="form-control">
                    		<option value="0">-- Bitte wählen --</option>
                        <?php
                        
                        foreach($this->data['CM_APPOINTMENT_STATUS'] as $as) {
                        		echo '<option value="' . $as['id'] . '">' . $as['title'] . '</option>';
                        }
												
												?>
                    </select>
                    <div class="mv-error" id="editor-status-error" style="display: none"></div>
                </div>
                
                <div class="form-group">
                		<?php
												
												//Titel für das Custom-Form-Dropdown laden.
												$mvBookingFormTexts = new mvBookingFormTexts();
												$calendar_texts = $mvBookingFormTexts->loadIndexedList();
												$custom_dropdown_title = "Eigenes Dropdown:";

												if(isset($calendar_texts['custom_form_dropdown_title'])) {
														$custom_dropdown_title = $calendar_texts['custom_form_dropdown_title'];
												}

										?>
                
                    <label for="editor-custom-form-dropdown" class="control-label"><?php echo htmlspecialchars($custom_dropdown_title); ?></label>
                    <input type="text" class="form-control" id="editor-custom-form-dropdown" />
                    <div class="mv-error" id="editor-custom-form-dropdown-error" style="display: none"></div>
                </div>
                
                <div class="form-group">
                    <label for="editor-comment-visitor-booking" class="control-label">Kommentar zum Termin:</label>
                    <textarea class="form-control" id="editor-comment-visitor-booking"></textarea>
                    <div class="mv-error" id="editor-comment-visitor-booking-error" style="display: none"></div>
                </div>
                
                <hr />
                <label class="control-label">Kundendaten:</label>
                
                <div class="form-group">
                    <label for="editor-firstname" class="control-label">Vorname:</label>
                    <input type="text" class="form-control" id="editor-firstname">
                    <div class="mv-error" id="editor-firstname-error" style="display: none"></div>
                </div>
                
                <div class="form-group">
                    <label for="editor-lastname" class="control-label">Nachname:</label>
                    <input type="text" class="form-control" id="editor-lastname">
                    <div class="mv-error" id="editor-lastname-error" style="display: none"></div>
                </div>
                
                <div class="form-group">
                    <label for="editor-email-address" class="control-label">E-Mail Adresse:</label>
                    <input type="text" class="form-control" id="editor-email-address">
                    <div class="mv-error" id="editor-email-address-error" style="display: none"></div>
                </div>
                
                <div class="form-group">
                    <label for="editor-email-reminder" class="control-label">E-Mail Reminder:</label>
                    <select id="editor-email-reminder" class="form-control">
                    		<option value="1">Aktiv</option>
                     		<option value="0">Deaktiviert</option>
                    </select>
                    <div class="mv-error" id="editor-email-reminder-error" style="display: none"></div>
                </div>

                <div class="form-group">
                    <label for="editor-customers-number" class="control-label">Kundennummer:</label>
                    <input type="text" class="form-control" id="editor-customers-number">
                    <div class="mv-error" id="editor-customers-number-error" style="display: none"></div>
                </div>
                
                <div class="form-group">
                    <label for="editor-phone" class="control-label">Telefonnummer:</label>
                    <input type="text" class="form-control" id="editor-phone">
                    <div class="mv-error" id="editor-phone-error" style="display: none"></div>
                </div>
                
                <div class="form-group">
                    <label for="editor-street" class="control-label">Straße:</label>
                    <input type="text" class="form-control" id="editor-street">
                    <div class="mv-error" id="editor-street-error" style="display: none"></div>
                </div>
                
                <div class="row">
                		<div class="col-md-6">
                        <div class="form-group">
                            <label for="editor-plz" class="control-label">PLZ:</label>
                            <input type="text" class="form-control" id="editor-plz">
                            <div class="mv-error" id="editor-plz-error" style="display: none"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="editor-city" class="control-label">Ort:</label>
                            <input type="text" class="form-control" id="editor-city">
                            <div class="mv-error" id="editor-city-error" style="display: none"></div>
                        </div>
                    </div>
                </div>
                
                <hr />
                <label class="control-label">CheckIn</label>
                <div class="row">
                		<div class="col-md-6">
                        <div class="form-group">
                            <label for="editor-check-in-date" class="control-label">CheckIn Datum:</label>
                            <input type="text" class="form-control" id="editor-check-in-date">
                            <div class="mv-error" id="editor-check-in-date-error" style="display: none"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="editor-check-in-time" class="control-label">CheckIn Zeit:</label>
                            <input type="text" class="form-control" id="editor-check-in-time">
                            <div class="mv-error" id="editor-check-in-time-error" style="display: none"></div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">

                    <label for="editor-comment-checkin" class="control-label">Kommentar zum CheckIn:</label>
                    <textarea class="form-control" id="editor-comment-checkin"></textarea>
                    <div class="mv-error" id="editor-comment-checkin-error" style="display: none"></div>
                </div>
                
                <label class="control-label">CheckOut</label>
                <div class="row">
                		<div class="col-md-6">
                        <div class="form-group">
                            <label for="editor-check-out-date" class="control-label">CheckOut Datum:</label>
                            <input type="text" class="form-control" id="editor-check-out-date">
                            <div class="mv-error" id="editor-check-out-date-error" style="display: none"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="editor-check-out-time" class="control-label">CheckOut Zeit:</label>
                            <input type="text" class="form-control" id="editor-check-out-time">
                            <div class="mv-error" id="editor-check-out-time-error" style="display: none"></div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="editor-comment-checkout" class="control-label">Kommentar zum CheckOut:</label>
                    <textarea class="form-control" id="editor-comment-checkout"></textarea>
                    <div class="mv-error" id="editor-comment-checkout-error" style="display: none"></div>
                </div>
                
                <hr />
                <label class="control-label">E-Mail Reminder</label>
                
                <div class="form-group">
                    <label for="editor-email-reminder-sent" class="control-label">E-Mail Reminder gesendet:</label>
                    <input type="text" class="form-control" id="editor-email-reminder-sent" readonly="readonly" value="Nein">
                    <div class="mv-error" id="editor-email-reminder-sent-error" style="display: none"></div>
                </div>
                
                <div class="form-group">
		                <label for="editor-email-reminder-sent-datetime" class="control-label">E-Mail Reminder Sendezeitpunkt</label>
    	              <input type="text" class="form-control" id="editor-email-reminder-sent-datetime" readonly="readonly">
                    <div class="mv-error" id="editor-reminder-sent-datetime-error" style="display: none"></div>
                </div>
            </div>
            <div class="modal-footer">
            		<div class="mv-error" id="mv-editor-save-error" style="display: none"></div>
                <button type="button" class="btn btn-danger" id="mv-editor-delete" style="float: left;">Löschen</button>
                <button type="button" class="btn btn-primary" id="mv-editor-save">Speichern</button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" value=""  id="default_appointment_duration" />
<input type="hidden" value="1" id="user_unit_id_required" />
<input type="hidden" value="" id="editor-last-save-datetime" />

<input type="hidden" id="mv-kalender-current-selected-day" value="<?php echo $this->data['DATA']['date']['today_day']; ?>" />
<input type="hidden" id="mv-kalender-current-selected-month" value="<?php echo $this->data['DATA']['date']['today_month']; ?>" />
<input type="hidden" id="mv-kalender-current-selected-year" value="<?php echo $this->data['DATA']['date']['today_year']; ?>" />