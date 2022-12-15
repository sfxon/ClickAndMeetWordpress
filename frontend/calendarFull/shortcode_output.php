<div class="container mv-outer-calendar-container">
		<div class="form-frontend-cm" id="kalender-success-container" style="display: none;">
        <h2><?php echo $this->data['MV_CALENDAR_TEXTS']['booking_success_title']; ?></h2>
        <p><?php echo $this->data['MV_CALENDAR_TEXTS']['booking_success_text']; ?></p>
        <div id="mv-kalender-success-appointment-info"></div>
    </div>

    <main class="form-frontend-cm" id="kalender-content-container">
          <div>
              <div class="mv-kalender-location-container">
                  <div id="mv-event-location-container"></div>
                  <div id="mv-user-unit-container"></div>
              </div>
              
        			<?php
									//Calculate, if calender container is display initially..
									$show_calendar_initially = false;
									
									if($this->data['SHORTCODE_ATTRIBUTES']['selectteamfirst'] != 1 && $this->data['SHORTCODE_ATTRIBUTES']['selectlocationfirst'] != 1) {
											$show_calendar_initially = true;
									}
									
							?>
              
              <div id="mv-kalender-timer-container"<?php if(!$show_calendar_initially) { echo ' style="display: none;"'; } ?>>
                  <div id="mv-kalender-container"></div>
                  <div id="mv-timer-container"></div>
              </div>
              
              <div id="mv-kalender-book-now" style="display: none">
                  <h2><?php echo $this->data['MV_CALENDAR_TEXTS']['booking_mask_title']; ?></h2>
                  <div id="mv-kalender-book-now-info"></div>
                  
                  <p><?php echo $this->data['MV_CALENDAR_TEXTS']['booking_mask_required']; ?></p>
                  
                  <div id="mv-kalender-book-now-fields">
                      <input type="hidden" id="editor-appointment-id" value="0" />
                      <?php
											
											if($this->data['CM_SETTINGS']['custom_form_dropdown'] == 1) {
                          $form_data = '';
													
													$myoptions = get_option('mvcam_general_options');
													$form_dropdown_values = '';
													
													if(is_array($myoptions) && isset($myoptions['mvcam_general__form_dropdown_values'])) {
															$form_dropdown_values = $myoptions['mvcam_general__form_dropdown_values'];
													}
													
													$form_dropdown_values = str_getcsv($form_dropdown_values, '|', "\"");
													
													if(is_array($form_dropdown_values) && count($form_dropdown_values) > 0) {
															?>
															<div class="form-group">
																	<label for="editor-custom-form-dropdown" class="control-label"><?php echo $this->data['MV_CALENDAR_TEXTS']['custom_form_dropdown_title']; ?></label>
																	<select class="form-control" id="editor-custom-form-dropdown">
                                  		<?php
																					$i = 0;
																			
																					foreach($form_dropdown_values as $tmpval) {
																							echo '<option value="' . htmlspecialchars($tmpval) . '">' . htmlspecialchars($tmpval) . '</option>';
																							$i++;
																					}
																			?>
                                  </select>
																	<div class="mv-error" id="editor-custom-form-dropdown-error" style="display: none"></div>
															</div>
															<?php
													}
                      } else {
                          ?>
                          <input type="hidden" class="form-control" id="editor-custom-form-dropdown" value="" />
                          <?php
                      }
											
											
                  
                      if($this->data['CM_SETTINGS']['form_use_firstname'] == 1) {
                          ?>
                          <div class="form-group">
                              <label for="editor-firstname" class="control-label"><?php echo $this->data['MV_CALENDAR_TEXTS']['booking_mask_firstname']; ?></label>
                              <input type="text" class="form-control" id="editor-firstname">
                              <div class="mv-error" id="editor-firstname-error" style="display: none"></div>
                          </div>
                          <?php
                      } else {
                          ?>
                          <input type="hidden" class="form-control" id="editor-firstname" value="Keine Angabe" />
                          <?php
                      }
                      
                      if($this->data['CM_SETTINGS']['form_use_lastname'] == 1) {
                          ?>
                          <div class="form-group">
                              <label for="editor-lastname" class="control-label"><?php echo $this->data['MV_CALENDAR_TEXTS']['booking_mask_lastname']; ?></label>
                              <input type="text" class="form-control" id="editor-lastname">
                              <div class="mv-error" id="editor-lastname-error" style="display: none"></div>
                          </div>
                          <?php
                      } else {
                          ?>
                          <input type="hidden" class="form-control" id="editor-lastname" value="Keine Angabe" />
                          <?php
                      }
                      ?>
                      
                      <div class="form-group">
                          <label for="editor-email-address" class="control-label"><?php echo $this->data['MV_CALENDAR_TEXTS']['booking_mask_email_address']; ?></label>
                          <input type="text" class="form-control" id="editor-email-address">
                          <div class="mv-error" id="editor-email-address-error" style="display: none"></div>
                      </div>
                      
                      <?php
                      if($this->data['CM_SETTINGS']['form_use_ask_for_reminder'] == 1) {
                          ?>
                          <div class="form-group">
                              <label for="editor-email-reminder" class="control-label"><?php echo $this->data['MV_CALENDAR_TEXTS']['booking_mask_reminder_text']; ?></label>
                              <select id="editor-email-reminder" class="form-control">
                                  <option value="0"><?php echo $this->data['MV_CALENDAR_TEXTS']['booking_mask_reminder_text_no']; ?></option>
                                  <option value="1"><?php echo $this->data['MV_CALENDAR_TEXTS']['booking_mask_reminder_text_yes']; ?></option>
                              </select>
                              <div class="mv-error" id="editor-email-reminder-error" style="display: none"></div>
                          </div>
                      <?php
                      } else {
                          ?>
                          <input type="hidden" class="form-control" id="editor-email-reminder" value="0" />
                          <?php
                      }
        
                      if($this->data['CM_SETTINGS']['form_use_customers_number'] == 1) {
                          ?>
                          <div class="form-group">
                              <label for="editor-customers-number" class="control-label"><?php echo $this->data['MV_CALENDAR_TEXTS']['booking_mask_customers_number']; ?></label>
                              <input type="text" class="form-control" id="editor-customers-number" value="" />
                              <div class="mv-error" id="editor-customers-number-error" style="display: none"></div>
                          </div>
                          <?php
                      } else {
                          ?>
                          <input type="hidden" class="form-control" id="editor-customers-number" value="" />
                          <?php
                      }
                      
                      if($this->data['CM_SETTINGS']['form_use_phone'] == 1) {
                          ?>
                          <div class="form-group">
                              <label for="editor-phone" class="control-label"><?php echo $this->data['MV_CALENDAR_TEXTS']['booking_mask_phone']; ?></label>
                              <input type="text" class="form-control" id="editor-phone">
                              <div class="mv-error" id="editor-phone-error" style="display: none"></div>
                          </div>
                          <?php
                      } else {
                          ?>
                          <input type="hidden" class="form-control" id="editor-phone" value="" />
                          <?php
                      }
                      
                      if($this->data['CM_SETTINGS']['form_use_street'] == 1) {
                          ?>
                          <div class="form-group">
                              <label for="editor-street" class="control-label"><?php echo $this->data['MV_CALENDAR_TEXTS']['booking_mask_street']; ?></label>
                              <input type="text" class="form-control" id="editor-street">
                              <div class="mv-error" id="editor-street-error" style="display: none"></div>
                          </div>
                          <?php
                      } else {
                          ?>
                          <input type="hidden" class="form-control" id="editor-street" value="" />
                          <?php
                      }
                      
                      if($this->data['CM_SETTINGS']['form_use_plz'] == 1 || $this->data['CM_SETTINGS']['form_use_city'] == 1) {
                          ?>
                          <div class="row">
                              <?php
                              if($this->data['CM_SETTINGS']['form_use_plz'] == 1) {
                                  ?>
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label for="editor-plz" class="control-label"><?php echo $this->data['MV_CALENDAR_TEXTS']['booking_mask_text_plz']; ?></label>
                                          <input type="text" class="form-control" id="editor-plz" value="">
                                          <div class="mv-error" id="editor-plz-error" style="display: none"></div>
                                      </div>
                                  </div>
                                  <?php
                              } else {
                                  ?>
                                  <input type="hidden" class="form-control" id="editor-plz" value="" />
                                  <?php
                              }
                              
                              if($this->data['CM_SETTINGS']['form_use_city'] == 1) {
                                  ?>
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label for="editor-city" class="control-label"><?php echo $this->data['MV_CALENDAR_TEXTS']['booking_mask_city']; ?></label>
                                          <input type="text" class="form-control" id="editor-city" value="">
                                          <div class="mv-error" id="editor-city-error" style="display: none"></div>
                                      </div>
                                  </div>
                                  <?php
                              } else {
                                  ?>
                                  <input type="hidden" class="form-control" id="editor-city" value="" />
                                  <?php
                              }
                              ?>
                          </div>
                      <?php
                      }
                      
                      if($this->data['CM_SETTINGS']['form_use_comment'] == 1) {
                          ?>
                          <div class="form-group">
                              <label for="editor-comment-visitor-booking" class="control-label"><?php echo $this->data['MV_CALENDAR_TEXTS']['booking_mask_comment']; ?></label>
                              <textarea class="form-control" id="editor-comment-visitor-booking"></textarea>
                              <div class="mv-error" id="editor-comment-visitor-booking-error" style="display: none"></div>
                          </div>
                          <?php
                      } else {
                          ?>
                          <input type="hidden" class="form-control" id="editor-comment-visitor-booking" value="" />
                          <?php
                      }
                      ?>
                      
                      <div class="form-group">
                          <label for="editor-accept-agb" class="control-label"><?php echo $this->data['MV_CALENDAR_TEXTS']['booking_mask_datenschutz_title']; ?></label>
                          <select id="editor-accept-agb" class="form-control">
                              <option value="0"><?php echo $this->data['MV_CALENDAR_TEXTS']['booking_mask_datenschutz_no']; ?></option>
                              <option value="1"><?php echo $this->data['MV_CALENDAR_TEXTS']['booking_mask_datenschutz_yes']; ?></option>
                          </select>
                          <div class="mv-error" id="editor-accept-agb-error" style="display: none"></div>
                      </div>
                      
                      <div id="mv-kalender-book-now-info-2"></div>
                      
                      <div class="modal-footer">
                          <div class="mv-error" id="mv-editor-save-error" style="display: none"></div>
                          <div id="mv-additional-error-message" class="mv-error" style="display: none;"></div>
                          <button type="button" class="btn btn-primary" id="mv-editor-save"><?php echo $this->data['MV_CALENDAR_TEXTS']['button_book_now']; ?></button>
                          
                         <div id="mv-editor-saving" style="display: none;"><?php echo $this->data['MV_CALENDAR_TEXTS']['book_now_loading']; ?></div>
                      </div>
                  </div>
              </div>
          </div>              
    </main>
</div>

<input type="hidden" id="mv-base-url" value="<?php echo $this->data['TEMPLATE_URL']; ?>" />
<input type="hidden" id="url" value="<?php echo $this->data['TEMPLATE_URL']; ?>" />
<input type="hidden" id="mv_url_controller" value="cFrontendCm" />

<input type="hidden" autocomplete="off" id="mv-error-text-editor-firstname" value="<?php echo htmlspecialchars($this->data['MV_CALENDAR_TEXTS']['booking_mask_error_firstname']); ?>" />
<input type="hidden" autocomplete="off" id="mv-error-text-editor-lastname" value="<?php echo htmlspecialchars($this->data['MV_CALENDAR_TEXTS']['booking_mask_error_lastname']); ?>" />
<input type="hidden" autocomplete="off" id="mv-error-text-editor-email-address" value="<?php echo htmlspecialchars($this->data['MV_CALENDAR_TEXTS']['booking_mask_error_email_address']); ?>" />
<input type="hidden" autocomplete="off" id="mv-error-text-editor-accept-agb" value="<?php echo htmlspecialchars($this->data['MV_CALENDAR_TEXTS']['booking_mask_datenschutz_error']); ?>" />
<input type="hidden" autocomplete="off" id="mv-error-text-editor-required-fields" value="<?php echo htmlspecialchars($this->data['MV_CALENDAR_TEXTS']['booking_error_text']); ?>" />
<input type="hidden" autocomplete="off" id="mv-text-termin-datum" value="<?php echo htmlspecialchars($this->data['MV_CALENDAR_TEXTS']['booking_mask_date_title']); ?>" />
<input type="hidden" autocomplete="off" id="mv-text-termin-zeit" value="<?php echo htmlspecialchars($this->data['MV_CALENDAR_TEXTS']['booking_mask_time_title']); ?>" />
<input type="hidden" autocomplete="off" id="mv-text-termin-event-location" value="<?php echo htmlspecialchars($this->data['MV_CALENDAR_TEXTS']['event_location_title']); ?>" />
<input type="hidden" autocomplete="off" id="mv-text-termin-user-unit" value="<?php echo htmlspecialchars($this->data['MV_CALENDAR_TEXTS']['user_unit_title']); ?>" />
<input type="hidden" autocomplete="off" id="mv-shortcode-attributes-showLadenAuswahl" value="<?php echo htmlspecialchars($this->data['SHORTCODE_ATTRIBUTES']['ladenzeigen']); ?>" />
<input type="hidden" autocomplete="new-password" id="mv-shortcode-attributes-preSelectLadenAuswahlId" value="<?php echo htmlspecialchars($this->data['SHORTCODE_ATTRIBUTES']['ladenid']); ?>" />
<input type="hidden" autocomplete="off" id="mv-shortcode-attributes-showTeamMitarbeiterAuswahl" value="<?php echo htmlspecialchars($this->data['SHORTCODE_ATTRIBUTES']['teamzeigen']); ?>" />
<input type="hidden" autocomplete="off" id="mv-shortcode-attributes-preSelectTeamMitarbeiterAuswahl" value="<?php echo htmlspecialchars($this->data['SHORTCODE_ATTRIBUTES']['teamid']); ?>" />
<input type="hidden" autocomplete="off" id="mv-shortcode-attributes-selectLocationFirst" value="<?php echo htmlspecialchars($this->data['SHORTCODE_ATTRIBUTES']['selectlocationfirst']); ?>" />
<input type="hidden" autocomplete="off" id="mv-shortcode-attributes-selectTeamFirst" value="<?php echo htmlspecialchars($this->data['SHORTCODE_ATTRIBUTES']['selectteamfirst']); ?>" />
<input type="hidden" autocomplete="off" id="mv-shortcode-attributes-time" value="<?php echo htmlspecialchars($this->data['SHORTCODE_ATTRIBUTES']['time']); ?>" />


