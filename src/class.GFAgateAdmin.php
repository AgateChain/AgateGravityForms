<?php

/**
 * Class for admin screens
 */
class GFAgateAdmin
{
    public $settingsURL;
    private $plugin;

    /**
     * @param GFAgatePlugin $plugin
     */
    public function __construct($plugin)
    {
        $this->plugin = $plugin;

        // handle change in settings pages
        if (true === class_exists('GFCommon')) {
            if (version_compare(GFCommon::$version, '1.6.99999', '<')) {
                // pre-v1.7 settings
                $this->settingsURL = admin_url('admin.php?page=gf_settings&addon=Agate+Payments');
            } else {
                // post-v1.7 settings
                $this->settingsURL = admin_url('admin.php?page=gf_settings&subview=Agate+Payments');
            }
        }

        // handle admin init action
        add_action('admin_init', array($this, 'adminInit'));

        // add GravityForms hooks
        add_action("gform_entry_info", array($this, 'gformEntryInfo'), 10, 2);

        // hook for showing admin messages
        add_action('admin_notices', array($this, 'actionAdminNotices'));

        // add action hook for adding plugin action links
        add_action('plugin_action_links_' . GFAGATE_PLUGIN_NAME, array($this, 'addPluginActionLinks'));

        // hook for adding links to plugin info
        add_filter('plugin_row_meta', array($this, 'addPluginDetailsLinks'), 10, 2);

        // hook for enqueuing admin styles
        add_filter('admin_enqueue_scripts', array($this, 'enqueueScripts'));

    }

    /**
     * test whether GravityForms plugin is installed and active
     * @return boolean
     */
    public static function isGfActive()
    {
        return class_exists('RGForms');
    }

    /**
     * handle admin init action
     */
    public function adminInit()
    {
        if (true === isset($_GET['page'])) {
            switch ($_GET['page']) {
                case 'gf_settings':
                    // add our settings page to the Gravity Forms settings menu
                    RGForms::add_settings_page('Agate Payments', array($this, 'optionsAdmin'));
                    break;
                default:
                    // not used
            }
        }
    }

    /**
     * only output our stylesheet if this is our admin page
     */
    public function enqueueScripts()
    {
        wp_enqueue_style('gfagate-admin', $this->plugin->urlBase . 'style-admin.css', false, GFAGATE_PLUGIN_VERSION);
    }

    /**
     * show admin messages
     */
    public function actionAdminNotices()
    {
        if (self::isGfActive() == false) {
            $this->plugin->showError('Gravity Forms Agate Payments requires <a href="http://www.gravityforms.com/">Gravity Forms</a> to be installed and activated.');
        }
    }

    /**
     * action hook for adding plugin action links
     */
    public function addPluginActionLinks($links)
    {
        // add settings link, but only if GravityForms plugin is active
        if (self::isGfActive() == true) {
            $settings_link = sprintf('<a href="%s">%s</a>', $this->settingsURL, __('Settings'));
            array_unshift($links, $settings_link);
        }

        return $links;
    }

    /**
    * action hook for adding plugin details links
    */
    public static function addPluginDetailsLinks($links, $file)
    {
        if (true === isset($file) && $file == GFAGATE_PLUGIN_NAME) {
            $links[] = '<a href="https://agate.services">' . __('Get help') . '</a>';
            $links[] = '<a href="https://www.agate.services">' . __('agate.services') . '</a>';
        }

        return $links;
    }

    /**
     * action hook for building the entry details view
     * @param int $form_id
     * @param array $lead
     */
    public function gformEntryInfo($form_id, $lead)
    {
        if (true === isset($lead) && false === empty($lead)) {
            $payment_gateway = gform_get_meta($lead['id'], 'payment_gateway');

            if ($payment_gateway == 'gfagate') {
                $authcode = gform_get_meta($lead['id'], 'authcode');

                if (true === isset($authcode)) {
                    echo 'Auth Code: ', esc_html($authcode), "<br /><br />\n";
                }
            }
        } else {
            error_log('[ERROR] In GFAgateAdmin::gformEntryInfo(): Missing or invalid $lead parameter.');
            throw new \Exception('An error occurred in the Agate Payment plugin: Missing or invalid $lead parameter in the gformEntryInfo() function.');
        }
    }

    /**
     * action hook for processing admin menu item
     */
    public function optionsAdmin()
    {
        $admin = new GFAgateOptionsAdmin($this->plugin, 'gfagate-options', $this->settingsURL);

        if (true === isset($admin) && false === empty($admin)) {
            $admin->process();
        } else {
            error_log('[ERROR] In GFAgateAdmin::optionsAdmin(): Could not create a new GFAgateOptionsAdmin object.');
            throw new \Exception('An error occurred in the Agate Payment plugin: Could not create a new GFAgateOptionsAdmin object.');
        }
    }

}
