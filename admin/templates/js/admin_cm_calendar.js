var cal = null;
var mv_cm_timer = null;

jQuery(document).ready(function( $ ) {
		confirmBackspaceNavigations();
	
		cal = new theCalender(
				{
						elementEventLocationSelector: '#mv-kalender-event-location',
						elementUserUnitSelector: '#mv-kalender-user-unit',
						elementMonthInputSelector: '#current_month',
						elementYearInputSelector: '#current_year',
						elementPrevMonthSelector: '.kalender-top-nav-prev',
						elementNextMonthSelector: '.kalender-top-nav-next',
						daySelector: '.kalender-entry',
						daySelectorClickCallback: mv_calendar_day_clicked
				}
		);
});

//////////////////////////////////////////////////////////////////////
// Verhindern, dass man die Seite mit Backspace verlässt.
// -> verhindert nervige Fehl-Klicks!
//////////////////////////////////////////////////////////////////////
function confirmBackspaceNavigations () {
    // http://stackoverflow.com/a/22949859/2407309
    var backspaceIsPressed = false
    jQuery(document).keydown(function(event){
        if (event.which == 8) {
            backspaceIsPressed = true
        }
    })
    jQuery(document).keyup(function(event){
        if (event.which == 8) {
            backspaceIsPressed = false
        }
    })
    jQuery(window).on('beforeunload', function(){
        if (backspaceIsPressed) {
            backspaceIsPressed = false
            return "Sind Sie sicher, dass Sie diese Seite verlassen wollen?"
        }
    })
} // confirmBackspaceNavigations




//////////////////////////////////////////////////////////////////////
// Wenn ein Kalendertag angeklickt wurde.
//////////////////////////////////////////////////////////////////////
function mv_calendar_day_clicked(data) {
		var base_url = jQuery('#url').val();
		
		//Set hidden input fields - may need them later, when we have to reload.. for example when changing event_location box.
		jQuery('#mv-times-list-current-date-day').val(data.day);
		jQuery('#mv-times-list-current-date-month').val(data.month);
		jQuery('#mv-times-list-current-date-year').val(data.year);
		
		jQuery('#mv-kalender-current-selected-day').val(data.day);
		jQuery('#mv-kalender-current-selected-month').val(data.month);
		jQuery('#mv-kalender-current-selected-year').val(data.year);

		if(null != mv_cm_timer) {
				mv_cm_timer.update(base_url, data.day, data.month, data.year);
		} else {
				mv_cm_timer = new mvCmTimer(base_url, data.day, data.month, data.year);
		}

		var timer_loader = new mvCmTimerLoad(base_url, data.event_location, data.user_unit_id, data.day, data.month, data.year);
		timer_loader.startRequest();
}

//////////////////////////////////////////////////////////////////////
// Kalender-Klasse
//////////////////////////////////////////////////////////////////////
class theCalender {
		//////////////////////////////////////////////////////////////////
		// constructor.
		//////////////////////////////////////////////////////////////////
		constructor(data) {
				this.elementEventLocationSelector = null;
				this.elementUserUnitSelector = null;
				this.elementMonthInputSelector = null;
				this.elementYearInputSelector = null;
				this.elementPrevMonthSelector = null;
				this.elementNextMonthSelector = null;
				this.daySelector = null;
				this.daySelectorClickCallback = null;
				this.actionDelayInMilliseconds = 700;
				this.loadingTimeout = null;
				this.baseUrl = "";
				
				if(typeof data != 'undefined') {
						if(typeof data.elementEventLocationSelector != 'undefined') {
								this.elementEventLocationSelector = data.elementEventLocationSelector;
						}
						
						if(typeof data.elementUserUnitSelector != 'undefined') {
								this.elementUserUnitSelector = data.elementUserUnitSelector;
						}
						
						if(typeof data.elementMonthInputSelector != 'undefined') {
								this.elementMonthInputSelector = data.elementMonthInputSelector;
						}
						
						if(typeof data.elementYearInputSelector != 'undefined') {
								this.elementYearInputSelector = data.elementYearInputSelector;
						}
						
						if(typeof data.elementPrevMonthSelector != 'undefined') {
								this.elementPrevMonthSelector = data.elementPrevMonthSelector;
						}
						
						if(typeof data.elementNextMonthSelector != 'undefined') {
								this.elementNextMonthSelector = data.elementNextMonthSelector;
						}
						
						if(typeof data.daySelector != 'undefined') {
								this.daySelector = data.daySelector;
						}
						
						if(typeof data.daySelectorClickCallback != 'undefined') {
								this.daySelectorClickCallback = data.daySelectorClickCallback;
						}
				}
				
				this.loadBaseUrl();
				this.initActionHandlers();
				this.updateCalendar();
				this.updateTimer();
		}
		
		//////////////////////////////////////////////////////////////////
		// Timer aktualisieren.
		//////////////////////////////////////////////////////////////////
		updateTimer() {
				//Timer aktualisieren
				var base_url = jQuery('#url').val();
				var event_location = jQuery('#elementEventLocationSelector').val();
				var user_unit_id = jQuery('#elementUserUnitSelector').val();
				var day = jQuery('#mv-times-list-current-date-day').val();
				var month = jQuery('#mv-times-list-current-date-month').val();
				var year = jQuery('#mv-times-list-current-date-year').val();
				
				var data = {
						base_url: base_url,
						event_location: event_location,
						user_unit_id: user_unit_id,
						day: day,
						month: month,
						year: year
				};

				mv_calendar_day_clicked(data);
		}
		
		//////////////////////////////////////////////////////////////////
		// Load Base url
		//////////////////////////////////////////////////////////////////
		loadBaseUrl() {
				this.baseUrl = jQuery('#url').val();
		}
		
		//////////////////////////////////////////////////////////////////
		// init action handlers.
		//////////////////////////////////////////////////////////////////
		initActionHandlers() {
				this.initEventLocationInputHandler();
				this.initUserUnitInputHandler();
				this.initMonthInputHandler();
				this.initYearInputHandler();
				this.initPrevMonthHandler();
				this.initNextMonthHandler();
		}
		
		//////////////////////////////////////////////////////////////////
		// Action Handler: Wenn sich der event-location input handler ändert.
		//////////////////////////////////////////////////////////////////
		initEventLocationInputHandler() {
				var self = this;
				
				if(null == this.elementEventLocationSelector) {
						console.log('Kann Input-Handler für Event-Location nicht initialisieren, weil elementEventLocationSelector nicht gesetzt ist.');
						return;
				}
				
				jQuery(this.elementEventLocationSelector).off('change');
				jQuery(this.elementEventLocationSelector).on('change', function() {
						//Kalender aktualisieren
						self.updateCalendar();
						
						//Dropdown-Liste für UserUnit (Team) aktualisieren.
						self.updateUserUnitList(self, jQuery(this).val());
						
						//Timer aktualisieren
						var base_url = jQuery('#url').val();
						var event_location = jQuery(this).val();
						var user_unit_id = 0;		//Switched to zero, so this must be a zero.
						var day = jQuery('#mv-times-list-current-date-day').val();
						var month = jQuery('#mv-times-list-current-date-month').val();
						var year = jQuery('#mv-times-list-current-date-year').val();
						
						var data = {
								base_url: base_url,
								event_location: event_location,
								user_unit_id: user_unit_id,
								day: day,
								month: month,
								year: year
						};
		
						mv_calendar_day_clicked(data);
				});
		}
		
		//////////////////////////////////////////////////////////////////
		// Action Handler: Wenn sich der user-unit input handler ändert.
		//////////////////////////////////////////////////////////////////
		initUserUnitInputHandler() {
				var self = this;
				
				if(null == this.elementUserUnitSelector) {
						console.log('Kann Input-Handler für UserUnit nicht initialisieren, weil elementUserUnitSelector nicht gesetzt ist.');
						return;
				}
				
				jQuery(this.elementUserUnitSelector).off('change');
				jQuery(this.elementUserUnitSelector).on('change', function() {
						//Kalender aktualisieren
						self.updateCalendar();
						
						//Dropdown-Liste für UserUnit (Team) aktualisieren.
						//self.updateUserUnitList(self, jQuery(this).val());
						
						//Timer aktualisieren
						var base_url = jQuery('#url').val();
						var event_location = 0;
						var user_unit_id = jQuery(this).val();
						var day = jQuery('#mv-times-list-current-date-day').val();
						var month = jQuery('#mv-times-list-current-date-month').val();
						var year = jQuery('#mv-times-list-current-date-year').val();
						
						var data = {
								base_url: base_url,
								event_location: event_location,
								user_unit_id: user_unit_id,
								day: day,
								month: month,
								year: year
						};
		
						mv_calendar_day_clicked(data);
				});
		}
		
		//////////////////////////////////////////////////////////////////
		// User-Unit Liste aktualisieren
		//////////////////////////////////////////////////////////////////
		updateUserUnitList(self, event_location) {
				jQuery(self.elementUserUnitSelector).off('change');		//Temporär deaktivieren, damit der nicht gleich getriggert wird, wenn wir den Wert auf 0 setzen.
				jQuery(self.elementUserUnitSelector).val(0);						//Reset chosen values..
				
				jQuery(self.elementUserUnitSelector + " option").removeAttr('disabled');
				jQuery(self.elementUserUnitSelector + " option").show();
						
				if(event_location == 0) {
						//Show all..
				} else {
						jQuery(self.elementUserUnitSelector).find('option').each(function() {
								var item = this;
								
								var event_location_id = jQuery(item).attr('data-attr-event-location-id');
								var value = jQuery(item).val();
								
								//Nicht die "Alle" box ausblenden!
								if(value == 0) {
										return;
								}
								
								//Ausblenden, wenn diese Option nicht zur aktuell gewählten Event-Location passt.
								if(event_location_id != event_location) {
										jQuery(item).attr("disabled", "disabled");
										jQuery(item).hide();
								}
						});
				}
				
				self.initUserUnitInputHandler();									//Und Action-Handler wieder aktivieren.
		}
		
		//////////////////////////////////////////////////////////////////
		// Action Handler: Wenn sich der Monat ändert.
		//////////////////////////////////////////////////////////////////
		initMonthInputHandler() {
				var self = this;
				
				if(null == this.elementMonthInputSelector) {
						console.log('Kann Input-Handler für Monat nicht initialisieren, weil elementMonthInputSelector nicht gesetzt ist.');
						return;
				}
				
				jQuery(this.elementMonthInputSelector).off('keyup');
				jQuery(this.elementMonthInputSelector).on('keyup', function() {
						self.updateCalendar();
				});
		}
		
		//////////////////////////////////////////////////////////////////
		// Action Handler: Wenn sich das Jahr ändert.
		//////////////////////////////////////////////////////////////////
		initYearInputHandler() {
				var self = this;
			
				if(null == this.elementYearInputSelector) {
						console.log('Kann Input-Handler für Jahr nicht initialisieren, weil elementYearInputSelector nicht gesetzt ist.');
						return;
				}
				
				jQuery(this.elementYearInputSelector).off('keyup');
				jQuery(this.elementYearInputSelector).on('keyup', function() {
						self.updateCalendar();
				});
		}
		
		//////////////////////////////////////////////////////////////////
		// Action Handler: Wenn jemand den Button für den
		// vorherigen Monat betätigt.
		//////////////////////////////////////////////////////////////////
		initPrevMonthHandler() {
				var self = this;
				
				if(null == this.elementPrevMonthSelector) {
						console.log('Kann Input-Handler für "vorheriger Monat" nicht initialisieren, weil elementPrevMonthSelector nicht gesetzt ist.');
						return;
				}
				
				jQuery(this.elementPrevMonthSelector).off('click');
				jQuery(this.elementPrevMonthSelector).on('click', function() {
						var month = jQuery(self.elementMonthInputSelector).val();
						var year = jQuery(self.elementYearInputSelector).val();
						
						month = parseInt(month);
						year = parseInt(year);
						
						if(isNaN(month)) {
								return;
						}
						
						if(isNaN(year)) {
								return;
						}
						
						if(month < 1 || month > 12) {
								return;
						}
						
						if(year < 2000 || year > 5000) {
								return;
						}
						
						var d = new Date(year, month-1, 1);
						d.setMonth(d.getMonth()-1);
						
						jQuery(self.elementMonthInputSelector).val(d.getMonth()+1);
						jQuery(self.elementYearInputSelector).val(d.getFullYear());
						
						self.updateCalendar();
				});
		}
		
		//////////////////////////////////////////////////////////////////
		// Action Handler: Wenn jemand den Button für den
		// nächsten Monat betätigt.
		//////////////////////////////////////////////////////////////////
		initNextMonthHandler() {
				var self = this;
				
				if(null == this.elementNextMonthSelector) {
						console.log('Kann Input-Handler für "nächster Monat" nicht initialisieren, weil elementNextMonthSelector nicht gesetzt ist.');
						return;
				}
				
				jQuery(this.elementNextMonthSelector).off('click');
				jQuery(this.elementNextMonthSelector).on('click', function() {
						var month = jQuery(self.elementMonthInputSelector).val();
						var year = jQuery(self.elementYearInputSelector).val();
						
						month = parseInt(month);
						year = parseInt(year);
						
						if(isNaN(month)) {
								return;
						}
						
						if(isNaN(year)) {
								return;
						}
						
						if(month < 1 || month > 12) {
								return;
						}
						
						if(year < 2000 || year > 5000) {
								return;
						}
						
						var d = new Date(year, month-1, 1);
						d.setMonth(d.getMonth()+1);
						
						jQuery(self.elementMonthInputSelector).val(d.getMonth()+1);
						jQuery(self.elementYearInputSelector).val(d.getFullYear());
						
						self.updateCalendar();
				});
		}
		
		//////////////////////////////////////////////////////////////////
		// Wenn ein Wochentag angklickt wird.
		//////////////////////////////////////////////////////////////////
		initDayClickHandler() {
				var self = this;
				
				if(null == this.daySelector) {
						console.log('Kann Input-Handler für "Kalendertag" nicht initialisieren, weil daySelector nicht gesetzt ist.');
						return;
				}
				
				jQuery(this.daySelector).off('click');
				jQuery(this.daySelector).on('click', function() {
						var event_location = jQuery(self.elementEventLocationSelector).val();
						var day = jQuery(this).attr('data-attr-day');
						var month = jQuery(this).attr('data-attr-month');
						var year = jQuery(this).attr('data-attr-year');
						var weekday = jQuery(this).attr('data-attr-weekday');
						
						var data = {
								event_location: event_location,
								day: day,
								month: month,
								year: year,
								weekday: weekday
						};
						
						//Tag mit CSS-Klasse versehen, um ihn optisch zu markieren.
						jQuery('.kalender-entry').removeClass('mv-current-selected-day');
						jQuery(this).addClass('mv-current-selected-day');
						
						if(null == self.daySelectorClickCallback) {
								console.log('Es ist keine Callback Funktion für initDayClickHandler hinterlegt. Es wird bei Klick auf einen Tag dadurch keine Aktion ausgelöst!');
								return;
						}
						
						self.daySelectorClickCallback(data);
				});
		}
		
		//////////////////////////////////////////////////////////////////
		// Kalender aktualisieren.
		//////////////////////////////////////////////////////////////////
		updateCalendar() {
				var self = this;
				
				if(this.loadingTimeout != null) {
						window.clearTimeout(this.loadingTimeout);
				}
				
				var event_location = jQuery(this.elementEventLocationSelector).val();
				var user_unit = jQuery(this.elementUserUnitSelector).val();
				var month = jQuery(this.elementMonthInputSelector).val();
				var year = jQuery(this.elementYearInputSelector).val();
				
				event_location = parseInt(event_location);
				user_unit = parseInt(user_unit);
				month = parseInt(month);
				year = parseInt(year);
				
				if(isNaN(event_location)) {
						event_location = 0;
				}
				
				if(isNaN(user_unit)) {
						user_unit = 0;
				}
				
				if(isNaN(month)) {
						return;
				}
				
				if(isNaN(year)) {
						return;
				}
				
				if(month < 1 || month > 12) {
						return;
				}
				
				if(year < 2000 || year > 5000) {
						return;
				}
					
				this.loadingTimeout = window.setTimeout(function() { self.updateCalendarHandler(event_location, user_unit, month, year); }, self.actionDelayInMilliseconds);
		}
		
		//////////////////////////////////////////////////////////////////
		// Kalenderdaten abrufen.
		//////////////////////////////////////////////////////////////////
		showLoadingText() {
				jQuery('.calendar-content').hide();
				jQuery('.calendar-content-loading').show();
		}
		
		//////////////////////////////////////////////////////////////////
		// Kalenderdaten abrufen.
		//////////////////////////////////////////////////////////////////
		updateCalendarHandler(event_location, user_unit, month, year) {
				this.showLoadingText();
				
				var self = this;
				
				var loader = new mvCmCalendarLoad(this.baseUrl, event_location, user_unit, month, year);
				loader.parent = self;
				
				var callbacks = [
						{
								//Upload company if not selected..
								callback: loader.loadCalendarData,
								result_callback: self.loadCalendarDataResult,
								type: 'ajax'
						}
				];
			
				var my_queue = new mvUploadQueue(loader, callbacks, this.error_callback);
				my_queue.process();
		}
		
		//////////////////////////////////////////////////////////////////
		// Anfrage-Ergebnis verarbeiten.
		//////////////////////////////////////////////////////////////////
		loadCalendarDataResult(queue, result) {
				//Try to parse the result
				try {
						var data = jQuery.parseJSON(result);
						
						if(data.status == "success") {
								if(typeof data.data.html != "undefined") {
										jQuery('.calendar-content').html(data.data.html);
										jQuery('.calendar-content-loading').hide();
										jQuery('.calendar-content').show();
								}
						}
						
						queue.data.parent.initDayClickHandler();
						
						//Mark current day (does it only, if it is contained in calender..)
						queue.data.parent.markCurrentDay();
						
						return true;
				} catch(e) {
						console.log("Ergebnis: ", result, "Exception: ", e);
				}
				
				return false;
		}
		
		//////////////////////////////////////////////////////////////////
		// Aktuellen Tag markieren.
		//////////////////////////////////////////////////////////////////
		markCurrentDay() {
				var day = jQuery('#mv-kalender-current-selected-day').val();
				var month = jQuery('#mv-kalender-current-selected-month').val();
				var year = jQuery('#mv-kalender-current-selected-year').val();
				var id = '#mv-kalender-entry-' + year + '-' + month + '-' + day;
				
				jQuery('.kalender-entry').removeClass('mv-current-selected-day');		//Bei den anderen Tagen ggf. entfernen.
				jQuery(id).addClass('mv-current-selected-day');		//Aktuellen Tag festlegen.
		}
}

//////////////////////////////////////////////////////////////////////
// Loader-Helper für Kalenderdaten.
//////////////////////////////////////////////////////////////////////
class mvCmCalendarLoad {
		//////////////////////////////////////////////////////////////////
		// Constructor.
		//////////////////////////////////////////////////////////////////
		constructor(base_url, event_location, user_unit, month, year) {
				this.event_location = event_location;
				this.user_unit = user_unit;
				this.month = month;
				this.year = year;
				this.baseUrl = ajaxurl;
		}
		
		//////////////////////////////////////////////////////////////////
		// Kalenderdaten - Querydaten aufbereiten.
		//////////////////////////////////////////////////////////////////
		loadCalendarData(queue) {
				/*
				var get_params = {
						s: 'cAdminCmCalendar',
						action: 'ajaxLoadMonth'
				};
				get_params = jQuery.param(get_params);
				*/
				var tmpUploadQueryBuilder = new mvUploadQueryBuilder();
				var get_params = tmpUploadQueryBuilder.makeByPlatform('wordpress', 'cAdminCmCalendar', 'ajaxLoadMonth');
				
				var request = {
						url: queue.data.baseUrl + '?' + get_params,
						post_data: {
								event_location: queue.data.event_location,
								user_unit: queue.data.user_unit,
								month: queue.data.month,
								year: queue.data.year
						},
						mode: 'POST'
				};
				
				return request;
		}
}




