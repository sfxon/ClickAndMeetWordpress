<div class="kalender">
    <input type="hidden" id="mv-kalender-current-selected-day" value="<?php echo $this->data['DATA']['date']['today_day']; ?>" />
    <input type="hidden" id="mv-kalender-current-selected-month" value="<?php echo $this->data['DATA']['date']['today_month']; ?>" />
    <input type="hidden" id="mv-kalender-current-selected-year" value="<?php echo $this->data['DATA']['date']['today_year']; ?>" />
    
    <div class="kalender-top">
        <div class="kalender-month">
            <label for="current_month" class="kalender-top-label"><?php echo $this->data['MV_CALENDAR_TEXTS']['calendar_month_title']; ?></label>
            <input type="text" value="<?php echo $this->data['DATA']['date']['current_month']; ?>" id="current_month" class="kalender-top-input" style="width: 80px;" />
        </div>
        <div class="kalender-year">
            <label for="current_month" class="kalender-top-label"><?php echo $this->data['MV_CALENDAR_TEXTS']['calendar_year_title']; ?></label>
            <input type="text" value="<?php echo $this->data['DATA']['date']['current_year']; ?>" id="current_year" class="kalender-top-input" style="width: 80px;" />
        </div>
        <div class="kalender-top-nav pull-right"><div class="kalender-top-nav-prev"><img src="<?php echo $this->data['TEMPLATE_URL'] . '/admin/templates/images/arrow-left.png'; ?>" /></div><div class="kalender-top-nav-next"><img src="<?php echo $this->data['TEMPLATE_URL'] . '/admin/templates/images/arrow_right.png'; ?>" /></div></div>
        </div>
        <div class="mv-clearfix"></div>
    </div>
    
    <div class="kalender-header">
    		<?php
    		echo '<div class="kalender-header-weeknumber">' .  print_svg_text_box($this->data['MV_CALENDAR_TEXTS']['cal_kw_short']) . '</div>';
        echo '<div class="kalender-header-day">' . print_svg_text_box($this->data['MV_CALENDAR_TEXTS']['cal_monday_short']) . '</div>';
				echo '<div class="kalender-header-day">' . print_svg_text_box($this->data['MV_CALENDAR_TEXTS']['cal_tuesday_short']) . '</div>';
				echo '<div class="kalender-header-day">' . print_svg_text_box($this->data['MV_CALENDAR_TEXTS']['cal_wednesday_short']) . '</div>';
				echo '<div class="kalender-header-day">' . print_svg_text_box($this->data['MV_CALENDAR_TEXTS']['cal_thursday_short']) . '</div>';
				echo '<div class="kalender-header-day">' . print_svg_text_box($this->data['MV_CALENDAR_TEXTS']['cal_friday_short']) . '</div>';
				echo '<div class="kalender-header-day">' . print_svg_text_box($this->data['MV_CALENDAR_TEXTS']['cal_sat_short']) . '</div>';
				echo '<div class="kalender-header-day">' . print_svg_text_box($this->data['MV_CALENDAR_TEXTS']['cal_sun_short']) . '</div>';
				?>
    </div>                            
    
    <div class="calendar-content-loading"><?php echo $this->data['MV_CALENDAR_TEXTS']['loading_data_title']; ?></div>
    
    <div class="calendar-content" style="display: none;"></div>
</div>

<?php
		function print_svg_text_box($text) {
				return '<svg viewBox="0 0 47 47"><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" style="font-familiy: arial; font-size: 24px;" fill="#FFF">' . $text . '</text></svg>';
		}
?>