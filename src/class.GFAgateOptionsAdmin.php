<?php


/**
 * Options form input fields
 */
class GFAgateOptionsForm
{

    public $agateApiKey    = '';
    public $agateRedirectURL  = '';

    /**
     * initialise from form post, if posted
     */
    public function __construct()
    {
        if (self::isFormPost()) {
            $this->agateApiKey    = self::getPostValue('agateApiKey');
            $this->agateRedirectURL  = self::getPostValue('agateRedirectURL');
        }
    }

    /**
     * Is this web request a form post?
     *
     * Checks to see whether the HTML input form was posted.
     *
     * @return boolean
     */
    public static function isFormPost()
    {
        return (bool)($_SERVER['REQUEST_METHOD'] == 'POST');
    }

    /**
     * Read a field from form post input.
     *
     * Guaranteed to return a string, trimmed of leading and trailing spaces, slashes stripped out.
     *
     * @return string
     * @param string $fieldname name of the field in the form post
     */
    public static function getPostValue($fieldname)
    {
        return isset($_POST[$fieldname]) ? stripslashes(trim($_POST[$fieldname])) : '';
    }

    /**
     * Validate the form input, and return error messages.
     *
     * Return a string detailing error messages for validation errors discovered,
     * or an empty string if no errors found.
     * The string should be HTML-clean, ready for putting inside a paragraph tag.
     *
     * @return string
     */
    public function validate()
    {
        $errmsg = '';

        if (false === isset($this->agateRedirectURL) || strlen($this->agateRedirectURL) <= 0) {
            $errmsg .= "# Please enter a Redirect URL.<br/>\n";
        }

        if (false === isset($this->agateApiKey) || strlen($this->agateApiKey) <= 0) {
            $errmsg .= "# Please enter your ApiKey.<br/>\n";
        }

        return $errmsg;
    }
}

/**
 * Options admin
 */
class GFAgateOptionsAdmin
{

    private $plugin;           // handle to the plugin object
    private $menuPage;         // slug for admin menu page
    private $scriptURL = '';
    private $frm;              // handle for the form validator

    /**
     * @param GFAgatePlugin $plugin handle to the plugin object
     * @param string $menuPage URL slug for this admin menu page
     */
    public function __construct($plugin, $menuPage, $scriptURL)
    {
        $this->plugin    = $plugin;
        $this->menuPage  = $menuPage;
        $this->scriptURL = $scriptURL;

        wp_enqueue_script('jquery');
    }

    /**
     * process the admin request
     */
    public function process()
    {
        $this->frm = new GFAgateOptionsForm();

        if (false === isset($this->frm) || true === empty($this->frm)) {
            error_log('[ERROR] In GFAgateOptionsAdmin::process(): Could not create a new GFAgateOptionsForm object.');
            throw new \Exception('An error occurred in the Agate Payment plugin: Could not create a new GFAgateOptionsForm object.');
        }

        if ($this->frm->isFormPost()) {
            check_admin_referer('save', $this->menuPage . '_wpnonce');

            $errmsg = $this->frm->validate();

            if (true === empty($errmsg)) {
                update_option('agateApiKey', $this->frm->agateApiKey);
                update_option('agateRedirectURL', $this->frm->agateRedirectURL);


                $this->plugin->showMessage(__('Options saved.'));
            } else {
                $this->plugin->showError($errmsg);
            }
        } else {
            // initialise form from stored options
            $this->frm->agateRedirectURL = get_option('agateRedirectURL');
            $this->frm->agateApiKey   = get_option('agateApiKey');
        }

        require GFAGATE_PLUGIN_ROOT . 'views/admin-settings.php';
    }
}
