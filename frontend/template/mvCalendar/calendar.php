<div class="kalender-body">
		<?php
		
		function print_svg_text_box($text) {
				return '<svg viewBox="0 0 47 47"><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" style="font-family: arial; font-size: 24px;">' . $text . '</text></svg>';
		}
		
    $current_day = 1;
    $weekday = 0;
    $current_week_number = $this->data['DATA']['date']['month_data']['cal_week_start'];

		if($this->data['DATA']['date']['month_data']['days'][0]['weekday'] != 1) {
				echo '
				<div class="kalender-row">
					<div class="kalender-weeknumber">' . print_svg_text_box(ltrim($current_week_number, '0')) . '</div>';
					
					$current_week_number = $current_week_number+1;
					$weekday = 1;
					
					for($weekday_looper = 1; $weekday_looper < $this->data['DATA']['date']['month_data']['days'][0]['weekday']; $weekday_looper++) {
							$weekday = $weekday_looper+1;
							
							echo '<div class="kalender-entry out-of-date-range"></div>';
					}
		}
    
		foreach($this->data['DATA']['date']['month_data']['days'] as $day) {
            if($weekday == 0) {
                echo '<div class="kalender-row">';
										echo '<div class="kalender-weeknumber">' . print_svg_text_box(ltrim($day['weeknumber'], '0')) . '</div>';
                    $current_week_number = $current_week_number+1;
                    $weekday = 1;
            }
            
            $mark_as_current_day = " test";
            
            if($day['day'] == $this->data['DATA']['date']['today_day'] && $day['month'] == $this->data['DATA']['date']['today_month'] && $day['year'] == $this->data['DATA']['date']['today_year']) {
            		$mark_as_current_day = " mv-calendar-current-day";
            }
            
						$status_count_classes = '';
						
						if(isset($day['status_count_classes'])) {
								$status_count_classes = $day['status_count_classes'];
						}
						
						echo '<div ' . 
										'id="mv-kalender-entry-' . $day['year'] . '-' . $day['month'] . '-' . $day['day'] . '" ' .
										'class="kalender-entry' . $mark_as_current_day . $status_count_classes . '" ' .
										'data-attr-day="' . $day['day'] . '" data-attr-month="' . $day['month'] . '" data-attr-year="' . $day['year'] . '" data-attr-weekday="' . $day['weekday'] . '">' .
                				print_svg_text_box($day['day']) .
            		'</div>';
            
            
            /* {* <!-- Nächsten Wochentag berechnen.. --> *} */
            if($weekday < 7) {
                $weekday = $weekday+1;
						} else {
                $weekday = 0;
            }
            
            if($weekday == 0) {
              echo '</div>';
            }
        }
		?>
</div>