<?php


/**
 * Class for handling Agate payment
 *
 * @link https://agate.services
 */
class GFAgatePayment
{
    public $uid;            // Displays unique id
    public $total;          // Displays Total
    public $buyer_email;    // Displays Customer's EmaiL

    /**
     * Writes $contents to system error logger.
     *
     * @param mixed $contents
     * @throws Exception $e
     */
    public function error_log($contents)
    {
        if (false === isset($contents) || true === empty($contents)) {
            return;
        }

        if (true === is_array($contents)) {
            $contents = var_export($contents, true);
        } else if (true === is_object($contents)) {
            $contents = json_encode($contents);
        }

        error_log($contents);
    }
    
    // Convert currency to iUSD
    public function convertCurToIUSD($url, $amount, $api_key, $currencySymbol) {
      error_log("Entered into Convert Amount");
        $ch = curl_init($url.'?api_key='.$api_key.'&currency='.$currencySymbol.'&amount='. $amount);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json')
      );

      $result = curl_exec($ch);
      $data = json_decode( $result , true);
      error_log('Response =>'. var_export($data, TRUE));
      // Return the equivalent amount value acquired from Agate server.
      return (float) $data["result"];

      }


      public function redirectPayment($baseUri, $amount_iUSD, $amount, $currencySymbol, $api_key, $redirect_url) {
        error_log("Entered into auto submit-form");
        // Using Auto-submit form to redirect user
        echo "<form id='form' method='post' action='". $baseUri . "?api_key=" . $api_key . "'>".
                "<input type='hidden' autocomplete='off' name='amount' value='".$amount."'/>".
                "<input type='hidden' autocomplete='off' name='amount_iUSD' value='".$amount_iUSD."'/>".
                "<input type='hidden' autocomplete='off' name='callBackUrl' value='".$redirect_url."'/>".
                "<input type='hidden' autocomplete='off' name='api_key' value='".$api_key."'/>".
                "<input type='hidden' autocomplete='off' name='cur' value='".$currencySymbol."'/>".
               "</form>".
               "<script type='text/javascript'>".
                    "document.getElementById('form').submit();".
               "</script>";
      }

    /**
     * Process a payment
     */
    public function processPayment()
    {
      global $wpdb;
            if (true === empty(get_option('agateRedirectURL'))) {
                update_option('agateRedirectURL', get_site_url());
            }

            // price
            $price = number_format($this->total, 2, '.', '');

            $baseUri        = "http://gateway.agate.services/" ;
            $convertUrl     = "http://gateway.agate.services/convert/";
            $api_key        = get_option('agateApiKey');
            $redirect_url   = get_option('agateRedirectURL');
            $currencySymbol = GFCommon::get_currency();
            $order_total    = $price;

            error_log($this->uid." ".$baseUri." ".$api_key." ".$callBack." ".$notification." ". $baseCur);
            error_log("target cur = ". $target_cur);

            $amount_iUSD = $this->convertCurToIUSD($convertUrl, $order_total, $api_key, $currencySymbol);

            $this->redirectPayment($baseUri, $amount_iUSD, $order_total, $currencySymbol, $api_key, $redirect_url);

    }

}
