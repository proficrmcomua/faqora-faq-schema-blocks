<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class FSB_Plugin {
	public static function init() {
		FSB_CPT::init();
		FSB_Metabox::init();
		FSB_Shortcode::init();
		FSB_Settings::init();
	}
}
