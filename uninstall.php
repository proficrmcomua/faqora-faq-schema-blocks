<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// We intentionally keep FAQ blocks and settings by default to avoid accidental data loss.
// Site owners can remove FAQ blocks manually before deleting the plugin.
