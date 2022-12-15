<div class="kalender-body">
<?php
		$current_day = 1;
		$weekday= 0;
		$current_week_number = $this->data['DATA']['date']['month_data']['cal_week_start'];
		
		//Leere Tage ausgeben, bevor der Monat beginnt (Immer dann, wenn der Monat mitten in einer Woche beginnt.)
		if ($this->data['DATA']['date']['month_data']['days'][0]['weekday'] != 1) {
				echo 
						'<div class="kalender-row">' .
								'<div class="kalender-weeknumber">' . print_svg_text_box(ltrim($this->data['current_week_number'], '0')) . '</div>';
								
				$current_week_number = $current_week_number+1;
				$weekday = 1;
				
				for($weekday_looper=1; $weekday_looper < $this->data['DATA']['date']['month_data']['days'][0]['weekday']; $weekday_looper++) {
						$weekday = $weekday_looper+1;
						
						echo '<div class="kalender-entry out-of-date-range"></div>';
				}
		}
		
		//Tage des Monats ausgeben.
		foreach($this->data['DATA']['date']['month_data']['days'] as $day) {
				//Wochentag ausgeben.
				if($weekday == 0) {
						echo 
								'<div class="kalender-row">' . 
										'<div class="kalender-weeknumber">' . print_svg_text_box(ltrim($day['weeknumber'], '0')) . '</div>';
								
						$current_week_number =$current_week_number+1;
						$weekday =1;
				}
				
				//herausfinden, ob wir den aktuellen Tag rendern. (also heute) -> Diesen mit einer CSS Klasse markieren.
				$mark_as_current_day = " test";
				
				if($day['day'] == $this->data['DATA']['date']['today_day'] && $day['month'] == $this->data['DATA']['date']['today_month'] && $day['year'] == $this->data['DATA']['date']['today_year']) {
						$mark_as_current_day = " mv-calendar-current-day";
				}
				
				//herausfinden, ob Status-Count klassen im Tag hinterlegt sind (geben an, welche Stati die vergebenen Wochentage so haben..
				$status_count_classes = "";
				
				if(isset($day['status_count_classes'])) {
						$status_count_classes = $day['status_count_classes'];
				}
				
				echo 
						'<div id="mv-kalender-entry-' . $day['year'] . '-' . $day['month'] . '-' . $day['day'] . '" class="kalender-entry' . $mark_as_current_day . $status_count_classes . '" data-attr-day="' . $day['day'] . '" data-attr-month="' . $day['month'] . '" data-attr-year="' . $day['year'] . '" data-attr-weekday="' . $day['weekday'] . '">' .
								print_svg_text_box($day['day']) .
						'</div>';
				
				
			 //Nächsten Wochentag berechnen..
				if($weekday < 7) {
						$weekday = $weekday+1;
				} else {
						$weekday = 0;
				}
				
				if($weekday == 0) {
						echo '</div>';
				}
		}
		
		function print_svg_text_box($text) {
				return '<svg viewBox="0 0 47 47"><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" style="font-familiy: arial; font-size: 24px;" fill="#FFF">' . $text . '</text></svg>';
		}
?>
</div>