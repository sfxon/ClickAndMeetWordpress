<style>
		<?php
		
		/* Kalender-Titel (Mo - Fr) */
		if(isset($this->data['MV_CALENDAR_COLORS']['cal_title_background'])) {
				?>
				.kalender-header-day {
						background-color: <?php echo $this->data['MV_CALENDAR_COLORS']['cal_title_background']; ?>;
				}
				<?php
		}
		
		if(isset($this->data['MV_CALENDAR_COLORS']['cal_title_text'])) {
				?>
				.kalender-header-day {
						fill: <?php echo $this->data['MV_CALENDAR_COLORS']['cal_title_text']; ?>;
				}
				<?php
		}
		
		/* KW im Kalender-Header */
		if(isset($this->data['MV_CALENDAR_COLORS']['cal_kw_background'])) {
				?>
				.kalender-header-weeknumber {
						background-color: <?php echo $this->data['MV_CALENDAR_COLORS']['cal_kw_background']; ?>;
				}
				<?php
		}
		
		if(isset($this->data['MV_CALENDAR_COLORS']['cal_kw_text'])) {
				?>
				.kalender-header-weeknumber {
						fill: <?php echo $this->data['MV_CALENDAR_COLORS']['cal_kw_text']; ?>;
				}
		<?php
		}
		
		/* Kalenderwochen Hintergrund */
		if(isset($this->data['MV_CALENDAR_COLORS']['cal_weeks_background'])) {
				?>
				.kalender-weeknumber {
						background-color: <?php echo $this->data['MV_CALENDAR_COLORS']['cal_weeks_background']; ?>;
				}
		<?php
		}
		
		if(isset($this->data['MV_CALENDAR_COLORS']['cal_weeks_text'])) {
				?>
				.kalender-weeknumber {
						fill: <?php echo $this->data['MV_CALENDAR_COLORS']['cal_weeks_text']; ?>;
				}
		<?php
		}
		
		/* Nicht Buchbarer Termin */
		if(isset($this->data['MV_CALENDAR_COLORS']['entry_no_booking_background'])) {
				?>
				.kalender-entry {
						background-color: <?php echo $this->data['MV_CALENDAR_COLORS']['entry_no_booking_background']; ?>;
				}
		<?php
		}
		
		if(isset($this->data['MV_CALENDAR_COLORS']['entry_no_booking_text'])) {
				?>
				.kalender-entry {
						fill: <?php echo $this->data['MV_CALENDAR_COLORS']['entry_no_booking_text']; ?>;
				}
		<?php
		}
		
		/* Buchbarer Termin */
		if(isset($this->data['MV_CALENDAR_COLORS']['entry_booking_background'])) {
				?>
				.kalender-entry.mv-status-count-1 {
						background-color: <?php echo $this->data['MV_CALENDAR_COLORS']['entry_booking_background']; ?>;
				}
		<?php
		}
		
		if(isset($this->data['MV_CALENDAR_COLORS']['entry_booking_text'])) {
				?>
				.kalender-entry.mv-status-count-1 {
						fill: <?php echo $this->data['MV_CALENDAR_COLORS']['entry_booking_text']; ?>;
				}
		<?php
		}
		
		/* Hintergrund: Heutiger Tag */
		if(isset($this->data['MV_CALENDAR_COLORS']['entry_booking_today_background'])) {
				?>
				.kalender-entry.mv-calendar-current-day {
						background-color: <?php echo $this->data['MV_CALENDAR_COLORS']['entry_booking_today_background']; ?>;
				}
		<?php
		}
		
		/* Ausgewählter Tag */
		if(isset($this->data['MV_CALENDAR_COLORS']['entry_booking_selected_background'])) {
				?>
				.kalender-entry.mv-current-selected-day {
						background-color: <?php echo $this->data['MV_CALENDAR_COLORS']['entry_booking_selected_background']; ?>;
				}
		<?php
		}
		
		if(isset($this->data['MV_CALENDAR_COLORS']['entry_booking_selected_text'])) {
				?>
				.kalender-entry.mv-current-selected-day {
						fill: <?php echo $this->data['MV_CALENDAR_COLORS']['entry_booking_selected_text']; ?>;
				}
		<?php
		}

		/* Textfarbe: Heutiger Tag - kommt erst hier, weil das auch zu sehen sein soll, wenn der Tag ausgewählt ist. */
		if(isset($this->data['MV_CALENDAR_COLORS']['entry_booking_today_text'])) {
				?>
				.kalender-entry.mv-calendar-current-day,
				.kalender-entry.mv-current-selected-day.mv-calendar-current-day {
						fill: <?php echo $this->data['MV_CALENDAR_COLORS']['entry_booking_today_text']; ?>;
				}
		<?php
		}
		
		/* Termin-Liste Titel und Rahmen der Box darunter */
		if(isset($this->data['MV_CALENDAR_COLORS']['timer_title_background'])) {
				?>
				.times-list-title {
						background-color: <?php echo $this->data['MV_CALENDAR_COLORS']['timer_title_background']; ?>;
				}
				
				.times-list-top {
						border: 1px solid <?php echo $this->data['MV_CALENDAR_COLORS']['timer_title_background']; ?>;
				}
		<?php
		}
		
		if(isset($this->data['MV_CALENDAR_COLORS']['timer_title_text'])) {
				?>
				.times-list-title {
						color: <?php echo $this->data['MV_CALENDAR_COLORS']['timer_title_text']; ?>;
				}
		<?php
		}
		
		/* Termin-Liste gerade Reihen */
		if(isset($this->data['MV_CALENDAR_COLORS']['timer_list_row_2_background'])) {
				?>
				.times-list-content .mv-cm-timer-row>div {
						background-color: <?php echo $this->data['MV_CALENDAR_COLORS']['timer_list_row_2_background']; ?>;
				}
		<?php
		}
		
		if(isset($this->data['MV_CALENDAR_COLORS']['timer_list_row_2_text'])) {
				?>
				.times-list-content .mv-cm-timer-row>div {
						color: <?php echo $this->data['MV_CALENDAR_COLORS']['timer_list_row_2_text']; ?>;
				}
		<?php
		}
		
		/* Termin-Liste ungerade Reihen */
		if(isset($this->data['MV_CALENDAR_COLORS']['timer_list_row_1_background'])) {
				?>
				.times-list-content .mv-cm-timer-row:nth-child(odd)>div {
						background-color: <?php echo $this->data['MV_CALENDAR_COLORS']['timer_list_row_1_background']; ?>;
				}
		<?php
		}
		
		if(isset($this->data['MV_CALENDAR_COLORS']['timer_list_row_1_text'])) {
				?>
				.times-list-content .mv-cm-timer-row:nth-child(odd)>div {
						color: <?php echo $this->data['MV_CALENDAR_COLORS']['timer_list_row_1_text']; ?>;
				}
		<?php
		}
		
		/* Termin-Liste gehoverte Reihen */
		if(isset($this->data['MV_CALENDAR_COLORS']['timer_list_row_hover_background'])) {
				?>
				.times-list-content .mv-cm-timer-row:hover>div {
						background-color: <?php echo $this->data['MV_CALENDAR_COLORS']['timer_list_row_hover_background']; ?>;
				}
		<?php
		}
		
		if(isset($this->data['MV_CALENDAR_COLORS']['timer_list_row_hover_text'])) {
				?>
				.times-list-content .mv-cm-timer-row:hover>div {
						color: <?php echo $this->data['MV_CALENDAR_COLORS']['timer_list_row_hover_text']; ?>;
				}
		<?php
		}
		
		/* Termin-Liste gehoverte Reihen */
		if(isset($this->data['MV_CALENDAR_COLORS']['timer_list_row_hover_background'])) {
				?>
				.times-list-content .mv-cm-timer-row:hover>div {
						background-color: <?php echo $this->data['MV_CALENDAR_COLORS']['timer_list_row_hover_background']; ?>;
				}
		<?php
		}
		
		if(isset($this->data['MV_CALENDAR_COLORS']['timer_list_row_hover_text'])) {
				?>
				.times-list-content .mv-cm-timer-row:hover>div {
						color: <?php echo $this->data['MV_CALENDAR_COLORS']['timer_list_row_hover_text']; ?>;
				}
		<?php
		}
		
		/* Submit  */
		if(isset($this->data['MV_CALENDAR_COLORS']['book_now_button_background'])) {
				?>
				#mv-editor-save {
						border-color: <?php echo $this->data['MV_CALENDAR_COLORS']['book_now_button_background']; ?>;
						background-color: <?php echo $this->data['MV_CALENDAR_COLORS']['book_now_button_background']; ?>;
				}
		<?php
		}
		
		if(isset($this->data['MV_CALENDAR_COLORS']['book_now_button_text'])) {
				?>
				#mv-editor-save {
						color: <?php echo $this->data['MV_CALENDAR_COLORS']['book_now_button_text']; ?>;
				}
		<?php
		}
?>
</style>