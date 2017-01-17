<?php
	Class CHAnalytics_Settings {
		
		public function __construct() {
			add_action('admin_menu', array($this, 'CHAnalytics_AddAdminMenuLink'));
			add_action('admin_init', array($this, 'CHAnalytics_RegisterSettings'));
		}
		
		public function CHAnalytics_AddAdminMenuLink() {
			add_menu_page(
				'Cunning Hat Analytics',
				'Cunning Hat', 
				'manage_options',
				'cunning_hat_analytics',
				array($this,'CHAnalytics_DisplaySettings')
			);
		}
		
		public function CHAnalytics_DisplaySettings() {
			require_once 'templates/cunning-hat-analytics-display.php';
		}
		
		public function CHAnalytics_RegisterSettings() {
			
			add_settings_section('ch_analytics_general_settings', 'Settings', null, 'cunning_hat_analytics');
			
			register_setting('ch_analytics_general_settings_form', 'ch_tracking_code');
			
			add_settings_field(
				'ch_tracking_code',
				'Tracking Code', 
				array( $this, 'ch_render_fields_text' ), 
				'cunning_hat_analytics', 
				'ch_analytics_general_settings',
				array(
					'field' => 'ch_tracking_code',
					'label_for' => 'ch_tracking_code'
				)
			);
		}
		
		public function ch_render_fields_text( $args ) {
			$field = $args[ 'field' ];
			$value = get_option( $field );
			echo sprintf( '<input type="text" name="%s" id="%s" value="%s" />', $field, $field, $value );
		}
	}