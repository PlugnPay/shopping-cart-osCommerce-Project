<?php
/*
  $Id: plugnpay_cc_ss.php 1803 2008-12-27 18:16:37Z hpdl $

  osCommerce (Open Source Commerce)
  http://www.oscommerceproject.org

  Copyright (c) 2008 osCommerce

  Released under the GNU General Public License
*/

  class plugnpay_cc_ss {
    var $code, $title, $description, $enabled;

// class constructor
    function plugnpay_cc_ss() {
      global $order;

      $this->signature = 'plugnpay|plugnpay_cc_ss|1.0|2.2';

      $this->code = 'plugnpay_cc_ss';
      $this->title = MODULE_PAYMENT_PLUGNPAY_CC_SS_TEXT_TITLE;
      $this->public_title = MODULE_PAYMENT_PLUGNPAY_CC_SS_TEXT_PUBLIC_TITLE;
      $this->description = MODULE_PAYMENT_PLUGNPAY_CC_SS_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_PLUGNPAY_CC_SS_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_PLUGNPAY_CC_SS_STATUS == 'True') ? true : false);

      if ((int)MODULE_PAYMENT_PLUGNPAY_CC_SS_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_PLUGNPAY_CC_SS_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();
      $this->form_action_url = 'https://pay1.plugnpay.com/payment/pay.cgi';

      $this->accepted_cc = MODULE_PAYMENT_PLUGNPAY_CC_SS_ACCEPTED;

      # array for credit card selection
      $this->card_types = array('Amex' => 'amex',
                                'Mastercard' => 'mastercard',
                                'Discover' => 'discover',
                                'Visa' => 'visa');

      $this->allowed_types = array();

      # produce credit card allowed list
      $cc_array = explode(', ', MODULE_PAYMENT_PLUGNPAY_CC_SS_ACCEPTED);
      while (list($key, $value) = each($cc_array)) {
        $this->allowed_types[$value] = $this->card_types[$value];
      }
    }

// class methods
    function update_status() {
      global $order;

      if (($this->enabled == true) && ((int)MODULE_PAYMENT_PLUGNPAY_CC_SS_ZONE > 0)) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_PLUGNPAY_CC_SS_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          }
          elseif ($check['zone_id'] == $order->billing['zone_id']) {
            $check_flag = true;
            break;
          }
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }
    }

    function javascript_validation() {
      return false;
    }

    function selection() {
      return array('id' => $this->code,
                   'module' => $this->public_title);
    }

    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
      return false;
    }

    function process_button() {
      global $customer_id, $order, $sendto, $currency;

      $cc_allowed_types = '';
      reset($this->allowed_types);
      while (list($key, $value) = each($this->allowed_types)) {
        $cc_allowed_types .= $value . ",";
      }

      # generate pnp orderid so that we don't get duplicates
      $pnp_date = date("YmdHis");
      $pnp_orderID = $pnp_date . substr(getmypid(),0,3);

      # basic settings
      $process_button_string .= tep_draw_hidden_field('publisher-name', MODULE_PAYMENT_PLUGNPAY_CC_SS_LOGIN) .
                                tep_draw_hidden_field('publisher_email', MODULE_PAYMENT_PLUGNPAY_CC_SS_PUBEMAIL) .
                                tep_draw_hidden_field('client', 'osCP_CC_SS') .
                                tep_draw_hidden_field('paymethod', 'credit') .
                                tep_draw_hidden_field('convert', 'underscores') .
                                tep_draw_hidden_field('card_allowed', $cc_allowed_types) .
                                tep_draw_hidden_field('easycart', '1') .
                                tep_draw_hidden_field('shipinfo', '1') .
                                tep_draw_hidden_field('nostatelist', 'yes') .
                                tep_draw_hidden_field('orderID', $pnp_orderID) .
                                tep_draw_hidden_field('currency', $currency) .
                                tep_draw_hidden_field('card_amount', $this->format_raw($order->info['total'])) .
                                tep_draw_hidden_field('authtype', ((MODULE_PAYMENT_PLUGNPAY_CC_SS_AUTHTYPE == 'authpostauth') ? 'authpostauth' : 'authonly')) .
                                tep_draw_hidden_field('success_link', tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL', false)) .
                                tep_draw_hidden_field('badcard_link', tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL', false)) .
                                tep_draw_hidden_field('problem_link', tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL', false));

      # billing address info
      $process_button_string .= tep_draw_hidden_field('card_name', $order->billing['firstname'] . ' ' . $order->billing['lastname']) .
                                tep_draw_hidden_field('card_company', $order->billing['company']) .
                                tep_draw_hidden_field('card_address1', $order->billing['street_address']) .
                                tep_draw_hidden_field('card_city', $order->billing['city']) .
                                tep_draw_hidden_field('card-prov', $order->billing['suburb']) .
                                tep_draw_hidden_field('card_state', $order->billing['state']) .
                                tep_draw_hidden_field('card_zip', $order->billing['postcode']) .
                                tep_draw_hidden_field('card_country', $order->billing['country']['iso_code_2']);

      # shipping address info
      if (is_numeric($sendto) && ($sendto > 0)) {
        $process_button_string .= tep_draw_hidden_field('shipname', $order->delivery['firstname'] . ' ' . $order->delivery['lastname']) .
                                  tep_draw_hidden_field('company', $order->delivery['company']) .
                                  tep_draw_hidden_field('address1', $order->delivery['street_address']) .
                                  tep_draw_hidden_field('city', $order->delivery['city']) .
                                  tep_draw_hidden_field('province', $order->delivery['suburb']) .
                                  tep_draw_hidden_field('state', $order->delivery['state']) .
                                  tep_draw_hidden_field('zip', $order->delivery['postcode']) .
                                  tep_draw_hidden_field('country', $order->delivery['country']['iso_code_2']);
      }

      # other customer info
      $process_button_string .= tep_draw_hidden_field('email', $order->customer['email_address']) .
                                tep_draw_hidden_field('phone', $order->customer['telephone']) .
                                tep_draw_hidden_field('order_id', $customer_id) .
                                tep_draw_hidden_field('ipaddress', tep_get_ip_address());


      # AVS setting
      if (MODULE_PAYMENT_PLUGNPAY_CC_SS_AVS_LEVEL != 0){
        $process_button_string .= tep_draw_hidden_field('app_level', MODULE_PAYMENT_PLUGNPAY_CC_SS_AVS_LEVEL);
      }

      # itemized product details
      for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
        $j = $i + 1;
        $process_button_string .= tep_draw_hidden_field("item$j", $order->products[$i]['model']);
        $process_button_string .= tep_draw_hidden_field("cost$j", $order->products[$i]['final_price']);
        $process_button_string .= tep_draw_hidden_field("quantity$j", $order->products[$i]['qty']);
        $process_button_string .= tep_draw_hidden_field("description$j", $order->products[$i]['name']);
        $process_button_string .= tep_draw_hidden_field("taxable$i", $order->products[$i]['taxable'] > 0 ? 'Y' : 'N');
      }

      # tax & shipping fee settings
      $tax_value = 0;

      reset($order->info['tax_groups']);
      while (list($key, $value) = each($order->info['tax_groups'])) {
        if ($value > 0) {
          $tax_value += $this->format_raw($value);
        }
      }

      if ($tax_value > 0) {
        $process_button_string .= tep_draw_hidden_field('tax', $this->format_raw($tax_value));
      }

      $process_button_string .= tep_draw_hidden_field('shipping', $this->format_raw($order->info['shipping_cost'])) .
                                tep_draw_hidden_field(tep_session_name(), tep_session_id());

      return $process_button_string;
    }

    function before_process() {
      global $HTTP_POST_VARS, $order;

      $error = false;

      if ($HTTP_POST_VARS['card_amount'] != $this->format_raw($order->info['total'])) {
        $error = 'verification';
      }

      if ($HTTP_POST_VARS['FinalStatus'] != 'success') {
        if ($HTTP_POST_VARS['FinalStatus'] == 'badcard') {
          $error = 'declined';
        }
        elseif ($HTTP_POST_VARS['FinalStatus'] == 'problem') {
          $error = 'problem';
        }
        elseif ($HTTP_POST_VARS['FinalStatus'] == 'fraud') {
          $error = 'fraud';
        }
        else {
          $error = 'general';
        }
      }

      if ($error != false) {
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code . '&error=' . $error, 'SSL', true, false));
      }
    }

    function after_process() {
      return false;
    }

    function get_error() {
      global $HTTP_GET_VARS;

      $error_message = MODULE_PAYMENT_PLUGNPAY_CC_SS_ERROR_GENERAL;

      switch ($HTTP_GET_VARS['error']) {
        case 'verification':
          $error_message = MODULE_PAYMENT_PLUGNPAY_CC_SS_ERROR_VERIFICATION;
          break;

        case 'declined':
          $error_message = MODULE_PAYMENT_PLUGNPAY_CC_SS_ERROR_DECLINED;
          break;

        case 'problem':
          $error_message = MODULE_PAYMENT_PLUGNPAY_CC_SS_ERROR_PROBLEM;
          break;

        case 'fraud':
          $error_message = MODULE_PAYMENT_PLUGNPAY_CC_SS_ERROR_FRAUD;
          break;

        default:
          $error_message = MODULE_PAYMENT_PLUGNPAY_CC_SS_ERROR_GENERAL;
          break;
      }

      $error = array('title' => MODULE_PAYMENT_PLUGNPAY_CC_SS_ERROR_TITLE,
                     'error' => $error_message);

      return $error;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PLUGNPAY_CC_SS_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable PlugnPay Credit Card SS', 'MODULE_PAYMENT_PLUGNPAY_CC_SS_STATUS', 'False', 'Do you want to accept PlugnPay Credit Card SS payments?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Merchant Username', 'MODULE_PAYMENT_PLUGNPAY_CC_SS_LOGIN', 'pnpdemo', 'Your login username used for the PlugnPay service', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Publisher Email', 'MODULE_PAYMENT_PLUGNPAY_CC_SS_PUBEMAIL', 'trash@plugnpay.com', 'Merchant Confirmation email address used for confirmation emails', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Transaction Method', 'MODULE_PAYMENT_PLUGNPAY_CC_SS_AUTHTYPE', 'authonly', 'The processing method to use for each transaction.', '6', '0', 'tep_cfg_select_option(array(\'authonly\', \'authpostauth\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('AVS Level', 'MODULE_PAYMENT_PLUGNPAY_CC_SS_AVS_LEVEL', '0', 'The level of address verification you wish to have. Levels 1-6 0=Off.', '6', '4', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_PLUGNPAY_CC_SS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_PLUGNPAY_CC_SS_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_PLUGNPAY_CC_SS_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Accepted Credit Cards', 'MODULE_PAYMENT_PLUGNPAY_CC_SS_ACCEPTED', 'Mastercard, Visa', 'The credit cards you currently accept', '6', '0', '_selectOptions(array(\'Amex\', \'Discover\', \'Mastercard\', \'Visa\'), ', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_PLUGNPAY_CC_SS_STATUS', 'MODULE_PAYMENT_PLUGNPAY_CC_SS_LOGIN', 'MODULE_PAYMENT_PLUGNPAY_CC_SS_PUBEMAIL', 'MODULE_PAYMENT_PLUGNPAY_CC_SS_AUTHTYPE', 'MODULE_PAYMENT_PLUGNPAY_CC_SS_AVS_LEVEL', 'MODULE_PAYMENT_PLUGNPAY_CC_SS_ZONE', 'MODULE_PAYMENT_PLUGNPAY_CC_SS_ORDER_STATUS_ID', 'MODULE_PAYMENT_PLUGNPAY_CC_SS_SORT_ORDER', 'MODULE_PAYMENT_PLUGNPAY_CC_SS_ACCEPTED');
    }

/*
    function _hmac($key, $data) {
      if (function_exists('mhash') && defined('MHASH_MD5')) {
        return bin2hex(mhash(MHASH_MD5, $data, $key));
      }

// RFC 2104 HMAC implementation for php.
// Creates an md5 HMAC.
// Eliminates the need to install mhash to compute a HMAC
// Hacked by Lance Rushing

      $b = 64; // byte length for md5
      if (strlen($key) > $b) {
        $key = pack("H*",md5($key));
      }

      $key = str_pad($key, $b, chr(0x00));
      $ipad = str_pad('', $b, chr(0x36));
      $opad = str_pad('', $b, chr(0x5c));
      $k_ipad = $key ^ $ipad ;
      $k_opad = $key ^ $opad;

      return md5($k_opad . pack("H*",md5($k_ipad . $data)));
    }

    function _InsertFP($loginid, $x_tran_key, $amount, $sequence, $currency = '') {
      $tstamp = time();

      $fingerprint = $this->_hmac($x_tran_key, $loginid . '^' . $sequence . '^' . $tstamp . '^' . $amount . '^' . $currency);

      return tep_draw_hidden_field('x_fp_sequence', $sequence) .
             tep_draw_hidden_field('x_fp_timestamp', $tstamp) .
             tep_draw_hidden_field('x_fp_hash', $fingerprint);
    }
*/

// format prices without currency formatting
    function format_raw($number, $currency_code = '', $currency_value = '') {
      global $currencies, $currency;

      if (empty($currency_code) || !$this->is_set($currency_code)) {
        $currency_code = $currency;
      }

      if (empty($currency_value) || !is_numeric($currency_value)) {
        $currency_value = $currencies->currencies[$currency_code]['value'];
      }

      return number_format(tep_round($number * $currency_value, $currencies->currencies[$currency_code]['decimal_places']), $currencies->currencies[$currency_code]['decimal_places'], '.', '');
    }
  }

  // PlugnPay Consolidated Credit Card Checkbox Implementation
  // Code from UPS Choice v1.7
  function _selectOptions($select_array, $key_value, $key = '') {
    for ($i=0; $i<(sizeof($select_array)); $i++) {
      $name = (($key) ? 'configuration[' . $key . '][]' : 'configuration_value');
      $string .= '<br><input type="checkbox" name="' . $name . '" value="' . $select_array[$i] . '"';
      $key_values = explode(", ", $key_value);
      if (in_array($select_array[$i], $key_values)) $string .= ' checked="checked"';
      $string .= '> ' . $select_array[$i];
    } 
    return $string;
  }
?>
