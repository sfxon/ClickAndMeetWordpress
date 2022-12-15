class mvCmTimer {
		//////////////////////////////////////////////////////////////////
		// Constructor: Init almost empty.
		//////////////////////////////////////////////////////////////////
		constructor(base_url, url_controller, day, month, year) {
				this.update(base_url, url_controller, day, month, year);
		}
		
		//////////////////////////////////////////////////////////////////
		// Update
		//////////////////////////////////////////////////////////////////
		update(base_url, url_controller, day, month, year) {
				this.baseUrl = base_url;
				this.url_controller = url_controller;
				
				this.editor_title = "Neuen Termin anlegen";
				
				this.appointment_id = 0;
				
				this.day = day;
				this.month = month - 1;		//Need javascript style date (month indexed by zero..)
				this.year = year;
				
				this.time_hour = 10;
				this.time_minute = 30;
				
				this.event_location_id = 0;
				this.event_location_title = "";
				this.user_unit_id = 0;
				this.user_unit_title = "";
				this.duration_in_minutes = 0;
				this.status = 1;		//Status 1 ist ein Systemstatus: offen
				
				this.firstname = "";
				this.lastname = "";
				this.email_address = "";
				this.email_reminder = 0;
				this.customers_number = "";
				this.phone = "";
				this.street = "";
				this.plz = "";
				this.city = "";
				
				this.checkin_date_day = "";
				this.checkin_date_month = "";
				this.checkin_date_year = "";
				this.checkin_time_hour = "";
				this.checkin_time_minute = "";
				
				this.checkout_date_day = "";
				this.checkout_date_month = "";
				this.checkout_date_year = "";
				this.checkout_time_hour = "";
				this.checkout_time_minute = "";
				
				this.checkin_comment = "";
				this.checkout_comment = "";
				
				this.comment_visitor_booking = "";
				
				this.email_reminder_sent = 0;
				this.email_reminder_sent_datetime = "";

				this.initActions();
				
				this.errors = false;
		}
		
		//////////////////////////////////////////////////////////////////
		// Update
		//////////////////////////////////////////////////////////////////
		updateWithData(data) {
				this.editor_title = "Termin bearbeiten: Nr.: " + data.id;
				
				this.appointment_id = data.id;
				
				var tmp = moment(data.datetime_of_event, 'YYYY-MM-DD HH:mm:ss');
				this.day = tmp.date();
				this.month = tmp.month();		//Need javascript style date (month indexed by zero..)
				this.year = tmp.year();
				
				this.time_hour = tmp.hour();
				this.time_minute = tmp.minute();
				
				this.event_location_id = data.event_location_id;
				this.event_location_title = "";
				
				if(typeof data.event_location != 'undefined') {
						this.event_location_title = data.event_location;
				}
				
				this.user_unit_id = data.user_unit_id;
				this.user_unit_title = "";
				
				if(typeof data.user_unit != 'undefined') {
						this.user_unit_title = data.user_unit;
				}
				
				
				this.duration_in_minutes = data.duration_in_minutes;
				this.status = data.status;		//Status 1 ist ein Systemstatus: offen
				
				/*
				this.firstname = data.firstname;
				this.lastname = data.lastname;
				this.email_address = data.email_address;
				this.email_reminder = data.reminder_active;
				this.customers_number = data.customers_number;
				this.phone = data.phone;
				this.street = data.street;
				this.plz = data.plz;
				this.city = data.city;
				
				if(data.datetime_checkin != "0000-00-00 00:00:00" && data.datetime_checkin.length != 0) {
						tmp = moment(data.datetime_checkin, 'YYYY-MM-DD HH:mm:ss');
						
						this.checkin_date_day = tmp.date();
						this.checkin_date_month = tmp.month();
						this.checkin_date_year = tmp.year();
						this.checkin_time_hour = tmp.hour();
						this.checkin_time_minute = tmp.minute();
				} else {
						this.checkin_date_day = "";
						this.checkin_date_month = "";
						this.checkin_date_year = "";
						this.checkin_time_hour = "";
						this.checkin_time_minute = "";
				}
				
				if(data.datetime_checkout != "0000-00-00 00:00:00" && data.datetime_checkout.length != 0) {
						tmp = moment(data.datetime_checkout, 'YYYY-MM-DD HH:mm:ss');
						
						this.checkout_date_day = tmp.date();
						this.checkout_date_month = tmp.month();
						this.checkout_date_year = tmp.year();
						this.checkout_time_hour = tmp.hour();
						this.checkout_time_minute = tmp.minute();
				} else {
						this.checkout_date_day = "";
						this.checkout_date_month = "";
						this.checkout_date_year = "";
						this.checkout_time_hour = "";
						this.checkout_time_minute = "";
				}
				
				this.checkin_comment = data.comment_checkin;
				this.checkout_comment = data.comment_checkout;
				
				this.comment_visitor_booking = data.comment_visitor_booking;
				
				this.email_reminder_sent = data.reminder_user_mail_sent;
				this.email_reminder_sent_datetime = data.reminder_user_mail_sent_datetime;
				*/
				this.errors = false;

				//this.resetFieldValues(this);
				jQuery('#editor-appointment-id').val(this.appointment_id);

				this.showEditor(this);
		}
		
		//////////////////////////////////////////////////////////////////
		// Editor anzeigen
		//////////////////////////////////////////////////////////////////
		showEditor(self) {
				//Datum und Uhrzeit parsen.
				var d = new Date(self.year, self.month, self.day, self.time_hour, self.time_minute, 0, 0);
				var text_datum = moment(d).format('DD.MM.YYYY');
				var text_time = moment(d).format('HH:mm');
				
				text_datum = "<span>" + jQuery('#mv-text-termin-datum').val() + " </span><strong>" + text_datum + "</strong>";
				text_time = "<span>" + jQuery('#mv-text-termin-zeit').val() + " </span><strong>" + text_time + " Uhr</strong>";
				
				//Orts-Informationen mit anzeigen.
				var text_event_location = "";
				var text_user_unit = "";
				
				if(self.event_location_title.length > 0) {
						text_event_location = "<span>" + jQuery('#mv-text-termin-event-location').val() + " </span><strong>" + self.event_location_title + "</strong>";
				} else {
						text_event_location = '&nbsp;';
				}
				
				if(self.user_unit_title.length > 0) {
						text_user_unit = "<span>" + jQuery('#mv-text-termin-user-unit').val() + " </span><strong>" + self.user_unit_title + "</strong>";
				} else {
						text_user_unit = '&nbsp;';
				}
				
				//Editor anzeigen.
				var mv_form = new mvForm();
				
				mv_form.setKalenderBookInfo(text_datum, text_time, text_event_location, text_user_unit);
				mv_form.setKalenderBookInfoOnSuccessPage(text_datum, text_time, text_event_location, text_user_unit);
				
				mv_form.showForm();
				mv_form.initActions();
		}
		
		//////////////////////////////////////////////////////////////////
		// initActions
		//////////////////////////////////////////////////////////////////
		initActions() {
				this.initAddTerminButton();
		}
		
		//////////////////////////////////////////////////////////////////
		// init add termin button
		//////////////////////////////////////////////////////////////////
		initAddTerminButton() {
				var self = this;
			
				jQuery('#mv-add-termin').off('click');
				jQuery('#mv-add-termin').on('click', function() {
						self.hideAllErrors();
						
						//Reset field values
						self.resetFieldValuesNew(self);
						self.showEditor(self);
				});
		}
		
		//////////////////////////////////////////////////////////////////
		// Eingabefelder für Datum/Zeit in Checkin/Checkout mit
		// erweitertem Editor ausstattten.
		//////////////////////////////////////////////////////////////////
		initCheckCheckoutActionFields(self) {
				//Init calender in dialogbox
				var editor_calendar = new dtsel.DTS('input[id="editor-check-in-date"]', {
						direction: 'BOTTOM',
						dateFormat: 'dd.mm.YYYY',
						showTime: false,
						timeFormat: "HH:MM"
				});
				
				//Init time picker in dialogbox
				jQuery('#editor-check-in-time').clockTimePicker({
						alwaysSelectHoursFirst: true,
						autosize: false
				});
				
				//Init calender in dialogbox
				var editor_calendar = new dtsel.DTS('input[id="editor-check-out-date"]', {
						direction: 'BOTTOM',
						dateFormat: 'dd.mm.YYYY',
						showTime: false,
						timeFormat: "HH:MM"
				});
				
				//Init time picker in dialogbox
				jQuery('#editor-check-out-time').clockTimePicker({
						alwaysSelectHoursFirst: true,
						autosize: false
				});
		}
		
		//////////////////////////////////////////////////////////////////
		// Eingabe-Felder initialisieren für Editor "Neuer Eintrag"
		//////////////////////////////////////////////////////////////////
		resetFieldValuesNew(self) {
				self.errors = false;
				self.appointment_id = 0;
				
				self.time_hour = 10;
				self.time_minute = 30;
				self.event_location_id = 0;
				self.user_unit_id = 0;
				self.duration_in_minutes = jQuery('#default_appointment_duration').val();
				self.status = 1;
				self.comment_visitor_booking = "";
				
				self.firstname = "";
				self.lastname = "";
				self.email_address = "";
				self.email_reminder = 0;
				self.customers_number = "";
				self.phone = "";
				self.street = "";
				self.plz = "";
				self.city = "";
				
				self.checkin_date_day = "";
				self.checkin_date_month = "";
				self.checkin_date_year = "";
				self.checkin_time_hour = "";
				self.checkin_time_minute = "";
				
				self.checkout_date_day = "";
				self.checkout_date_month = "";
				self.checkout_date_year = "";
				self.checkout_time_hour = "";
				self.checkout_time_minute = "";
				
				self.checkin_comment = "";
				self.checkout_comment = "";
				
				self.email_reminder_sent = 0;
				self.email_reminder_sent_datetime = "";
				
				self.resetFieldValues(self);
		}
		
		//////////////////////////////////////////////////////////////////
		// Eingabe-Felder initialisieren.
		//////////////////////////////////////////////////////////////////
		resetFieldValues(self) {
				//Überschrift
				self.setEditorTitle(self, self.editor_title);
				
				//General Settings
				self.resetGeneralFieldValues(self);
				
				//Kundendaten
				self.resetCustomerFieldValues(self);
				
				//CheckIn/CheckOut
				self.resetCheckInOutFieldValues(self);
				
				//Reminder
				self.setReminderFieldValues(self);
		}
		
		//////////////////////////////////////////////////////////////////
		// Titel-Text des Editors festlegen.
		//////////////////////////////////////////////////////////////////
		setEditorTitle(self, title) {
				jQuery('#AppointmentEditorModalLabel').html(title);
		}
		
		//////////////////////////////////////////////////////////////////
		// Allgemeine Daten festlegen.
		//////////////////////////////////////////////////////////////////
		resetGeneralFieldValues(self) {
				//Termin-Datum
				var d = new Date(self.year, self.month, self.day, self.time_hour, self.time_minute);
				var date_string = moment(d).format('DD.MM.YYYY');
				jQuery('#editor-date').val(date_string);
				
				//Termin-Zeit
				var time_string = moment(d).format('HH:mm');
				jQuery('#editor-time').val(time_string);
				
				//Veranstaltungsort
				self.updateEventLocationListbox(self, self.event_location_id);
				
				//Abteilung/Mitarbeiter/Team
				self.updateUserUnitListbox(self, self.event_location_id, self.user_unit_id);
				
				//Dauer in Minuten
				jQuery('#editor-duration-in-minutes').val(self.duration_in_minutes);
				
				//Status
				jQuery('#editor-status').val(self.status);

				//Kommentar
				jQuery('#editor-comment-visitor-booking').val(self.comment_visitor_booking);
		}
		
		//////////////////////////////////////////////////////////////////
		// Listbox: Event
		//////////////////////////////////////////////////////////////////
		updateEventLocationListbox(self, event_location_id) {
				jQuery('#editor-event-location').val(event_location_id);
				
				jQuery('#editor-event-location').off('change');
				jQuery('#editor-event-location').on('change', function() {
						var item = this;
						var value = jQuery(item).val();
						
						jQuery('#editor-user-unit-id').val(0);		//Set this already to zero, in the other listbox.					
						self.updateUserUnitListbox(self, value);
				});
		}
		
		//////////////////////////////////////////////////////////////////
		// Listbox: Abteilung/Mitarbeiter/Team aktualisieren.
		//////////////////////////////////////////////////////////////////
		updateUserUnitListbox(self, event_location_id, user_unit_id) {
				jQuery('#editor-user-unit-id' + " option").removeAttr('disabled');
				jQuery('#editor-user-unit-id' + " option").show();
						
				if(event_location_id == 0) {
						//Show all..
				} else {
						jQuery('#editor-user-unit-id').find('option').each(function() {
								var item = this;
								
								var tmp_event_location_id = jQuery(item).attr('data-attr-event-location-id');
								var value = jQuery(item).val();
								
								//Nicht die "Alle" box ausblenden!
								if(value == 0) {
										return;
								}
								
								//Ausblenden, wenn diese Option nicht zur aktuell gewählten Event-Location passt.
								if(tmp_event_location_id != event_location_id) {
										jQuery(item).attr("disabled", "disabled");
										jQuery(item).hide();
								}
						});
				}
		}
		
		//////////////////////////////////////////////////////////////////
		// Werte für die Felder der Kundendaten setzen.
		//////////////////////////////////////////////////////////////////
		resetCustomerFieldValues(self) {
				jQuery('#editor-appointment-id').val(self.appointment_id);
				
				jQuery('#editor-user-unit-id').val(self.user_unit_id);
				jQuery('#editor-firstname').val(self.firstname);
				jQuery('#editor-lastname').val(self.lastname);
				jQuery('#editor-email-address').val(self.email_address);
				jQuery('#editor-email-reminder').val(self.email_reminder);
				jQuery('#editor-customers-number').val(self.customers_number);
				jQuery('#editor-phone').val(self.phone);
				jQuery('#editor-street').val(self.street);
				jQuery('#editor-plz').val(self.plz);
				jQuery('#editor-city').val(self.city);
		}
		
		//////////////////////////////////////////////////////////////////
		// Werte für die Felder der Kundendaten setzen.
		//////////////////////////////////////////////////////////////////
		resetCheckInOutFieldValues(self) {
				//Checkin Datum und Zeit berechnen.
				var checkin_date_string = "";
				var checkin_time_string = "";
			
				if(self.checkin_date_day === "" || self.checkin_date_month === "" || self.checkin_date_year === "" || self.checkin_time_hour === "" || self.checkin_time_minute === "") {
						checkin_date_string = "";
				} else {
						var checkin_d = new Date(self.checkin_date_year, self.checkin_date_month, self.checkin_date_day, self.checkin_time_hour, self.checkin_time_minute);
						
						checkin_date_string = moment(checkin_d).format('DD.MM.YYYY');
						checkin_time_string = moment(checkin_d).format('HH:mm');
				}
				
				//Checkin Datum festlegen
				jQuery('#editor-check-in-date').val(checkin_date_string);
				
				//Checkin Zeit festlegen
				jQuery('#editor-check-in-time').val(checkin_time_string);
				
				///////////////////////////////////////////
				//Checkout Datum und Zeit berechnen.
				var checkout_date_string = "";
				var checkout_time_string = "";
			
				if(self.checkout_date_day === "" || self.checkout_date_month === "" || self.checkout_date_year === "" || self.checkout_time_hour === "" || self.checkout_time_minute === "") {
						checkout_date_string = "";
				} else {
						var checkout_d = new Date(self.checkout_date_year, self.checkout_date_month, self.checkout_date_day, self.checkout_time_hour, self.checkout_time_minute);
						checkout_date_string = moment(checkout_d).format('DD.MM.YYYY');
						checkout_time_string = moment(checkout_d).format('HH:mm');
				}
				
				//Checkin Datum festlegen
				jQuery('#editor-check-out-date').val(checkout_date_string);
				
				//Checkin Zeit festlegen
				jQuery('#editor-check-out-time').val(checkout_time_string);
					
				///////////////////////////////////////////
				// Kommentare berechnen.
				jQuery('#editor-comment-checkin').val(self.checkin_comment);
				jQuery('#editor-comment-checkout').val(self.checkout_comment);
		}
		
		//////////////////////////////////////////////////////////////////
		// Feldwerte setzen.
		//////////////////////////////////////////////////////////////////
		setReminderFieldValues(self) {
				jQuery('#editor-email-reminder-sent').val(self.email_reminder_sent);
				jQuery('#editor-email-reminder-sent-datetime').val(self.email_reminder_sent_datetime);
		}
		
		//////////////////////////////////////////////////////////////////
		// Action-Handler: Speichern Buttn angeklickt.
		//////////////////////////////////////////////////////////////////
		initSaveButtonAction(self) {
				jQuery('#mv-editor-save').off('click');
				jQuery('#mv-editor-save').on('click', function() {
						self.hideAllErrors();
						
						//Alle eingegebenen Werte sammeln.
						if(true == self.checkInputFields(self)) {
								self.fetchAllDataFromInputFields(self);		
								var post_data = self.buildPostArrayFromData(self);
								self.submitData(self, post_data);
						}
				});
		}
		
		//////////////////////////////////////////////////////////////////
		// Alle Eingabefelder überprüfen.
		//////////////////////////////////////////////////////////////////
		checkInputFields(self) {
				self.errors = false;
				var accept_null = true;
				var do_not_accept_null = false;
				
				self.checkFieldInt(self, '#editor-appointment-id', accept_null, '#editor-appointment-id-error', 'Bitte geben Sie einen gültigen Wert ein!');
				
				//Wenn user_unit_id ein Pflichtfeld ist..
				var is_required = jQuery('#user_unit_id_required').val();
				
				if(is_required == 1) {
						self.checkFieldInt(self, '#editor-user-unit-id', do_not_accept_null, '#editor-user-unit-id-error', 'Bitte wählen Sie eine Abteilung.');
				}
				
				//Weitere Eingabefelder prüfen.
				self.checkFieldDate(self, '#editor-date', do_not_accept_null, '#editor-date-error', 'Bitte wählen Sie ein gültiges Datum.');
				self.checkFieldTime(self, '#editor-time', do_not_accept_null, '#editor-time-error', 'Bitte wählen Sie eine gültige Uhrzeit.');
				self.checkFieldInt(self, '#editor-event-location', do_not_accept_null, '#editor-event-location-error', 'Bitte wählen Sie einen Veranstaltungsort aus.');
				self.checkFieldInt(self, '#editor-duration-in-minutes', do_not_accept_null, '#editor-duration-in-minutes-error', 'Bitte geben Sie die Veranstaltungdauer aus.');
				self.checkFieldInt(self, '#editor-status', do_not_accept_null, '#editor-status-error', 'Bitte wählen Sie einen Status aus.');
				
				self.checkFieldDate(self, '#editor-check-in-date', accept_null, '#editor-check-in-date-error', 'Bitte wählen Sie ein gültiges Datum.');
				self.checkFieldTime(self, '#editor-check-in-time', accept_null, '#editor-check-in-time-error', 'Bitte wählen Sie eine gültige Uhrzeit.');
				
				self.checkFieldDate(self, '#editor-check-out-date', accept_null, '#editor-check-out-date-error', 'Bitte wählen Sie ein gültiges Datum.');
				self.checkFieldTime(self, '#editor-check-out-time', accept_null, '#editor-check-out-time-error', 'Bitte wählen Sie eine gültige Uhrzeit.');
				
				if(self.errors != false) {
						self.showGeneralError('Es ist ein Fehler aufgetreten. Bitte überprüfen Sie Ihre Eingaben!');
						return false;
				}
				
				return true;
		}
		
		//////////////////////////////////////////////////////////////////
		// Prüfe Integer Feld..
		//////////////////////////////////////////////////////////////////
		checkFieldInt(self, css_selector, accept_null, css_selector_error_container, error_message) {
				var value = jQuery(css_selector).val();
				var tmp = parseInt(value);
				
				if(isNaN(tmp)) {
						if(accept_null == false) {
								self.errors = true;
								self.outputFieldError(css_selector_error_container, error_message);
								return false;
						} else {
								return true;
						}
				}
				
				if(accept_null == false) {
						if(tmp == 0) {
								self.errors = true;
								self.outputFieldError(css_selector_error_container, error_message);
								return false;
						}
				}
				
				return true;
		}
		
		//////////////////////////////////////////////////////////////////
		// Prüfe Datums Feld..
		//////////////////////////////////////////////////////////////////
		checkFieldDate(self, css_selector, accept_null, css_selector_error_container, error_message) {
				var value = jQuery(css_selector).val();
				
				if(value == "") {
						if(accept_null) {
								return true;
						}
				}
				
				var d = new mvDate();
				var result = d.loadFromInputField(css_selector);
				
				if(false == result) {
						self.errors = true;
						self.outputFieldError(css_selector_error_container, error_message);
						return false;
				}
				
				return true;
		}
		
		//////////////////////////////////////////////////////////////////
		// Prüfe Zeit Feld..
		//////////////////////////////////////////////////////////////////
		checkFieldTime(self, css_selector, accept_null, css_selector_error_container, error_message) {	
				var value = jQuery(css_selector).val();
				
				if(value == "") {
						if(accept_null) {
								return true;
						}
				}
				
				var t = new mvTime();
				var result = t.loadFromInputField(css_selector);
				
				if(false == result) {
						self.errors = true;
						self.outputFieldError(css_selector_error_container, error_message);
						return false;
				}
				
				return true;
		}
		
		//////////////////////////////////////////////////////////////////
		// Prüfe Integer Feld..
		//////////////////////////////////////////////////////////////////
		outputFieldError(css_selector_error_container, error_message) {
				jQuery(css_selector_error_container).html(error_message);
				jQuery(css_selector_error_container).show();
		}
		
		//////////////////////////////////////////////////////////////////
		// Allgemeinen Fehler ausgeben.
		//////////////////////////////////////////////////////////////////
		showGeneralError(error_message) {
				jQuery('#mv-editor-save-error').html(error_message);
				jQuery('#mv-editor-save-error').show();
		}
		
		//////////////////////////////////////////////////////////////////
		// Alle Fehler ausblenden.
		//////////////////////////////////////////////////////////////////
		hideAllErrors() {
				jQuery('.mv-error').hide();
		}
		
		//////////////////////////////////////////////////////////////////
		// fetchAllDataFromInputFields
		//////////////////////////////////////////////////////////////////
		fetchAllDataFromInputFields(self) {
				//Fetch Appointment id
				self.appointment_id = jQuery('#editor-appointment-id').val();
				
				//Fetch Appointment Date
				var d = new mvDate();
				var result = d.loadFromInputField('#editor-date');
				
				self.day = d.day;
				self.month = (d.month + 1);
				self.year = d.year;
				
				//Fetch hour..
				var t = new mvTime();
				var result = t.loadFromInputField('#editor-time');
				
				self.time_hour = t.hour;
				self.time_minute = t.minute;
				
				//Fetch Event-Location, User-Unit-Id Duration, Status and Appointment comment
				self.event_location_id = jQuery('#editor-event-location').val();
				self.user_unit_id = jQuery('#editor-user-unit-id').val();
				self.duration_in_minutes = jQuery('#editor-duration-in-minutes').val();
				self.status = jQuery('#editor-status').val();		//Status 1 ist ein Systemstatus: offen
				self.comment_visitor_booking = jQuery('#editor-comment-visitor-booking').val();
				
				//Fetch information about the customer.
				self.firstname = jQuery('#editor-firstname').val();
				self.lastname = jQuery('#editor-lastname').val();
				self.email_address = jQuery('#editor-email-address').val();
				self.email_reminder = jQuery('#editor-email-reminder').val();
				self.customers_number = jQuery('#editor-customers-number').val();
				self.phone = jQuery('#editor-phone').val();
				self.street = jQuery('#editor-street').val();
				self.plz = jQuery('#editor-plz').val();
				self.city = jQuery('#editor-city').val();
				
				//Fetch checkin date values
				d = new mvDate();
				result = d.loadFromInputField('#editor-check-in-date');
				
				if(false != result) {
						self.checkin_date_day = d.day;
						self.checkin_date_month = (d.month + 1);
						self.checkin_date_year = d.year;
				} else {
						self.checkin_date_day = 0;
						self.checkin_date_month = 0;
						self.checkin_date_year = 0;
				}
				
				//Fetch checkin time values
				//Fetch hour..
				t = new mvTime();
				result = t.loadFromInputField('#editor-check-in-time');
				
				if(false != result) {
						self.checkin_time_hour = t.hour;
						self.checkin_time_minute = t.minute;
				} else {
						self.checkin_time_hour = 0;
						self.checkin_time_minute = 0;
				}
				
				//Fetch checkout date values
				//Fetch checkin date values
				d = new mvDate();
				result = d.loadFromInputField('#editor-check-out-date');
				
				if(false != result) {
						self.checkout_date_day = d.day;
						self.checkout_date_month = (d.month + 1);
						self.checkout_date_year = d.year;
				} else {
						self.checkout_date_day = 0;
						self.checkout_date_month = 0;
						self.checkout_date_year = 0;
				}
				//Fetch checkout time values
				//Fetch hour..
				t = new mvTime();
				result = t.loadFromInputField('#editor-check-out-time');
				
				if(false != result) {
						self.checkout_time_hour = t.hour;
						self.checkout_time_minute = t.minute;
				} else {
						self.checkout_time_hour = 0;
						self.checkout_time_minute = 0;
				}
				
				//Fetch additional comments
				self.checkin_comment = jQuery('#editor-comment-checkin').val();
				self.checkout_comment = jQuery('#editor-comment-checkout').val();
		}
		
		//////////////////////////////////////////////////////////////////
		// PostArray erstellen.
		//////////////////////////////////////////////////////////////////
		buildPostArrayFromData(self) {
				var post_data = {
						appointment_id: self.appointment_id,
						day: self.day,
						month: self.month,
						year: self.year,
						hour: self.time_hour,
						minute: self.time_minute,
						event_location_id: self.event_location_id,
						user_unit_id: self.user_unit_id,
						duration_in_minutes: self.duration_in_minutes,
						status: self.status,
						comment_visitor_booking: self.comment_visitor_booking,
						firstname: self.firstname,
						lastname: self.lastname,
						email_address: self.email_address,
						email_reminder: self.email_reminder,
						customers_number: self.customers_number,
						phone: self.phone,
						street: self.street,
						plz: self.plz,
						city: self.city,
						
						checkin_date_day: self.checkin_date_day,
						checkin_date_month: self.checkin_date_month,
						checkin_date_year: self.checkin_date_year,
						checkin_time_hour: self.checkin_time_hour,
						checkin_time_minute: self.checkin_time_minute,
						
						checkout_date_day: self.checkout_date_day,
						checkout_date_month: self.checkout_date_month,
						checkout_date_year: self.checkout_date_year,
						checkout_time_hour: self.checkout_time_hour,
						checkout_time_minute: self.checkout_time_minute,
						
						checkin_comment: self.checkin_comment,
						checkout_comment: self.checkout_comment
				};
				
				return post_data;
		}
		
		//////////////////////////////////////////////////////////////////
		// Daten speichern.
		//////////////////////////////////////////////////////////////////
		submitData(self, post_data) {
				var saver = new mvCmTimerSaveAppointment(self.baseUrl, post_data);
				saver.startRequest();
		}	
}
