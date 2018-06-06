<?php

/*
Plugin Name: Gravity Forms Agate Payments
Plugin URI:  https://github.com/
Description: Integrates Gravity Forms with Agate payment gateway.
Version:     2.0.2
Author:      R. Giridhar
Author URI:  https://www.agate.services

/*
useful references:
http://www.gravityhelp.com/forums/topic/credit-card-validating#post-44438
http://www.gravityhelp.com/documentation/page/Gform_creditcard_types
http://www.gravityhelp.com/documentation/page/Gform_enable_credit_card_field
http://www.gravityhelp.com/documentation/page/Form_Object
http://www.gravityhelp.com/documentation/page/Entry_Object
*/

register_activation_hook(__FILE__,'gravityforms_agate_failed_requirements');

function brj_trigger_error($message, $errno)
{
    if (true === isset($_GET['action']) && $_GET['action'] == 'error_scrape') {
        echo '<strong>' . $message . '</strong>';
        exit();
    } else {
        trigger_error($message, $errno);
    }
}

if (false === defined('GFAGATE_PLUGIN_ROOT')) {
    define('GFAGATE_PLUGIN_ROOT', dirname(__FILE__) . '/');
    define('GFAGATE_PLUGIN_NAME', basename(dirname(__FILE__)) . '/' . basename(__FILE__));
    define('GFAGATE_PLUGIN_OPTIONS', 'gfagate_plugin');

    // script/style version
    if (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) {
        define('GFAGATE_PLUGIN_VERSION', time());
    } else {
        define('GFAGATE_PLUGIN_VERSION', '2.0.2');
    }

    // custom fields
    define('GFAGATE_REDIRECT_URL', 'agateRedirectURL');
    define('GFAGATE_APIKEY', 'agateApiKey');
}

/**
 * autoload classes as/when needed
 *
 * @param string $class_name name of class to attempt to load
 */
function gfagate_autoload($class_name)
{
    static $classMap = array (
        'GFAgateAdmin'         => 'class.GFAgateAdmin.php',
        'GFAgateFormData'      => 'class.GFAgateFormData.php',
        'GFAgateOptionsAdmin'  => 'class.GFAgateOptionsAdmin.php',
        'GFAgatePayment'       => 'class.GFAgatePayment.php',
        'GFAgatePlugin'        => 'class.GFAgatePlugin.php',
        'GFAgateStoredPayment' => 'class.GFAgateStoredPayment.php',
    );

    if (true === isset($classMap[$class_name])) {
        require GFAGATE_PLUGIN_ROOT . $classMap[$class_name];
    }

    // require_once __DIR__ . '/autoload.php';
}

spl_autoload_register('gfagate_autoload');

/**
 * Requirements check.
 */
function gravityforms_agate_failed_requirements()
{
    global $wp_version;

    $errors = array();

    // PHP 5.4+ required
    if (true === version_compare(PHP_VERSION, '5.4.0', '<')) {
       $errors[] = 'Your PHP version is too old. The Agate payment plugin requires PHP 5.4 or higher to function. Please contact your web server administrator for assistance.';
    }

    // Wordpress 3.9+ required
    if (true === version_compare($wp_version, '4.0', '<')) {
        $errors[] = 'Your WordPress version is too old. The Agate payment plugin requires Wordpress 3.9 or higher to function. Please contact your web server administrator for assistance.';
    }

    // GMP or BCMath required
    if (false === extension_loaded('gmp') && false === extension_loaded('bcmath')) {
        $errors[] = 'The Agate payment plugin requires the GMP or BC Math extension for PHP in order to function. Please contact your web server administrator for assistance.';
    }

    if (false === empty($errors)) {
        $imploded = implode("<br><br>\n", $errors);
        br_trigger_error($imploded, E_USER_ERROR);
    } else {
        return false;
    }
}

// instantiate the plug-in
GFAgatePlugin::getInstance();
