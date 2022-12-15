<?php

namespace mvclickandmeet_namespace;

class mvRenderer {
		var $data = array();
		
		public function assign($var, $val) {
				$this->data[$var] = $val;
		}
		
		public function render($template) {
				$mv_plugin_path = dirname(plugin_dir_path( __FILE__ ));
				
				require_once($mv_plugin_path . '/' . $template);
		}
		
		public function fetch($template) {
				ob_start(); // start capturing output.
				$this->render($template);
				$output = ob_get_contents(); // the actions output will now be stored in the variable as a string!
				ob_end_clean(); // never forget this or you will keep capturing output.
				
				return $output;
		}
}