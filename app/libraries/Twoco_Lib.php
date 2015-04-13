<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    /**
     * 2CheckOut Class
     *
     * Integrate the 2CheckOut payment gateway in your site using this easy
     * to use library. Just see the example code to know how you should
     * proceed. Btw, this library does not support the recurring payment
     * system. If you need that, drop me a note and I will send to you.
     *
     * @package     Payment Gateway
     * @category    Library
     * @author      Md Emran Hasan <phpfour@gmail.com>
     * @link        http://www.phpfour.com
     */
    abstract class PaymentGateway
    {

        /**
         * Holds the last error encountered
         *
         * @var string
         */
        public $lastError;

        /**
         * Do we need to log IPN results ?
         *
         * @var boolean
         */
        public $logIpn;

        /**
         * File to log IPN results
         *
         * @var string
         */
        public $ipnLogFile;

        /**
         * Payment gateway IPN response
         *
         * @var string
         */
        public $ipnResponse;

        /**
         * Are we in test mode ?
         *
         * @var boolean
         */
        public $testMode;

        /**
         * Field array to submit to gateway
         *
         * @var array
         */
        public $fields = array();

        /**
         * IPN post values as array
         *
         * @var array
         */
        public $ipnData = array();

        /**
         * Payment gateway URL
         *
         * @var string
         */
        public $gatewayUrl;

        /**
         * Initialization constructor
         *
         * @param none
         * @return void
         */
        public function __construct()
        {
            // Some default values of the class
            $this->lastError = '';
            $this->logIpn = TRUE;
            $this->ipnResponse = '';
            $this->testMode = FALSE;
        }

        /**
         * Adds a key=>value pair to the fields array
         *
         * @param string key of field
         * @param string value of field
         * @return
         */
        public function addField($field, $value)
        {
            $this->fields["$field"] = $value;
        }

        /**
         * Submit Payment Request
         *
         * Generates a form with hidden elements from the fields array
         * and submits it to the payment gateway URL. The user is presented
         * a redirecting message along with a button to click.
         *
         * @param none
         * @return void
         */
        public function submitPayment()
        {
            $this->prepareSubmit();

            echo "<html>";
            echo "<head><title>Processing Payment...</title></head>";
            echo "<body onLoad=\"document.forms['gateway_form'].submit();\">\n";
            echo "<p style=\"text-align:center;\"><h2>Please wait, your order is being processed and you";
            echo " will be redirected to the payment website.</h2></p>\n";
            echo "<form method=\"POST\" name=\"gateway_form\" ";
            echo "action=\"" . $this->gatewayUrl . "\">\n";

            foreach ($this->fields as $name => $value)
            {
                echo "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
            }


            echo "<p style=\"text-align:center;\"><br/><br/>If you are not automatically redirected to ";
            echo "payment website within 5 seconds...<br/><br/>\n";
            echo "<input type=\"submit\" value=\"Click Here\"></p>";

            echo "</form>";
            echo "</body></html>";
            exit;
        }

        /**
         * Perform any pre-posting actions
         *
         * @param none
         * @return none
         */
        protected function prepareSubmit()
        {
            // Fill if needed
        }

        /**
         * Enables the test mode
         *
         * @param none
         * @return none
         */
        abstract protected function enableTestMode();

        /**
         * Validate the IPN notification
         *
         * @param none
         * @return boolean
         */
        abstract protected function validateIpn();

        /**
         * Logs the IPN results
         *
         * @param boolean IPN result
         * @return void
         */
        public function logResults($success)
        {

            if (!$this->logIpn)
                return;

            // Timestamp
            $text = '[' . date('m/d/Y g:i A') . '] - ';

            // Success or failure being logged?
            $text .= ($success) ? "SUCCESS!\n" : 'FAIL: ' . $this->lastError . "\n";

            // Log the POST variables
            $text .= "IPN POST Vars from gateway:\n";
            foreach ($this->ipnData as $key => $value)
            {
                $text .= "$key=$value, ";
            }

            // Log the response from the paypal server
            $text .= "\nIPN Response from gateway Server:\n " . $this->ipnResponse;

            // Write to log
            $fp = fopen($this->ipnLogFile, 'a');
            fwrite($fp, $text . "\n\n");
            fclose($fp);
        }

    }

    class Twoco_Lib extends PaymentGateway
    {

        /**
         * Secret word to be used for IPN verification
         *
         * @var string
         */
        public $secret;

        /**
         * Initialize the 2CheckOut gateway
         *
         * @param none
         * @return void
         */
        public function Twoco_Lib()
        {
            parent::__construct();

            // Some default values of the class
            $this->gatewayUrl = 'https://www.2checkout.com/checkout/purchase';
            $this->ipnLogFile = '2co.ipn_results.log';
        }

        /**
         * Enables the test mode
         *
         * @param none
         * @return none
         */
        public function enableTestMode()
        {
            $this->testMode = TRUE;
            $this->addField('demo', 'Y');
        }

        /**
         * Set the secret word
         *
         * @param string the scret word
         * @return void
         */
        public function setSecret($word)
        {
            if (!empty($word))
            {
                $this->secret = $word;
            }
        }

        /**
         * Validate the IPN notification
         *
         * @param none
         * @return boolean
         */
        public function validateIpn()
        {
            foreach ($_POST as $field => $value)
            {
                $this->ipnData["$field"] = $value;
            }

            $vendorNumber = ($this->ipnData["vendor_number"] != '') ? $this->ipnData["vendor_number"] : $this->ipnData["sid"];
            $orderNumber = $this->ipnData["order_number"];
            $orderTotal = $this->ipnData["total"];

            // If demo mode, the order number must be forced to 1
            if ($this->demo == "Y" || $this->ipnData['demo'] == 'Y')
            {
                $orderNumber = "1";
            }

            // Calculate md5 hash as 2co formula: md5(secret_word + vendor_number + order_number + total)
            $key = strtoupper(md5($this->secret . $vendorNumber . $orderNumber . $orderTotal));

            // verify if the key is accurate
            if ($this->ipnData["key"] == $key || $this->ipnData["x_MD5_Hash"] == $key)
            {
                $this->logResults(true);
                return true;
            }
            else
            {
                $this->lastError = "Verification failed: MD5 does not match!";
                $this->logResults(false);
                return false;
            }
        }

    }
    