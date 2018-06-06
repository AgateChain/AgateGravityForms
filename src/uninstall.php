<?php
/**
 * Agate for Gravity Forms Uninstall
 *
 * @author 		agate
 */
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
    exit();
delete_option('agateRedirectURL');
delete_option('agateApiKey');
