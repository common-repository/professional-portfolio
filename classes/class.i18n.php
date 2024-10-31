<?php

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 */

class PPORT_i18n{

	public function pluginTextdomain() {
		load_plugin_textdomain(PPORT_NAME,false,PPORT_DIR . 'languages/');
	}
	
}