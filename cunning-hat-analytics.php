<?php
/*
Plugin Name: Cunning Hat Analytics
Author: Cunning Hat
Author URI: https://github.com/CunningHat
Version: 1.0.1
Description: A simple analytics & ecommerce analytics plugin. That's free. Forever.
Text Domain: cunning-hat-analytics
Licence: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Cunning Hat Analytics is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Cunning Hat Analytics is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Cunning Hat Analytics. If not, see https://www.gnu.org/licenses/gpl-2.0.html.

*/

if(!class_exists('CHAnalytics')){
	
	Class CHAnalytics {
		
// 		CONSTRUCT
		
		public function __construct() {
			
			require_once plugin_dir_path( __FILE__ ) . 'public/tracking.php';
			require_once plugin_dir_path( __FILE__ ) . 'admin/set-order-notes.php';
			require_once plugin_dir_path( __FILE__ ) . 'admin/settings.php';
			
			$CHAnalytics_Tracking = new CHAnalytics_Tracking();
			$CHAnalytics_Settings = new CHAnalytics_Settings();
			
		}
		
	}
	
	$CHAnalytics = new CHAnalytics();
	
}