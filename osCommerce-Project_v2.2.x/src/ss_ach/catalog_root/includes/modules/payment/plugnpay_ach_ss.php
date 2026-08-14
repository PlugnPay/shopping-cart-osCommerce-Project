<?php
/*
  $Id: plugnpay_ach_ss.php

  PlugnPay Smart Screens v2 — ACH / eCheck
  for osCommerce Project v2.2

  Posts to https://pay1.plugnpay.com/pay/
  ACH data is collected on PlugnPay. Authorization is auth-only
  (pb_post_auth=no); capture / void / refund in PlugnPay Admin.
  Requires ACH / eCheck enabled on the gateway account.

  Copyright (c) PlugnPay Technologies
  Released under the GNU General Public License
*/

  class plugnpay_ach_ss {
    var $code, $title, $description, $enabled;

// class constructor
    function plugnpay_ach_ss() {
      global $order;

      $this->signature = 'plugnpay|plugnpay_ach_ss|2.0|2.2';

      $this->code = 'plugnpay_ach_ss';
      $this->title = MODULE_PAYMENT_PLUGNPAY_ACH_SS_TEXT_TITLE;
      $this->public_title = MODULE_PAYMENT_PLUGNPAY_ACH_SS_TEXT_PUBLIC_TITLE;
      $this->description = MODULE_PAYMENT_PLUGNPAY_ACH_SS_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_PAYMENT_PLUGNPAY_ACH_SS_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_PLUGNPAY_ACH_SS_STATUS == 'True') ? true : false);

      if ((int)MODULE_PAYMENT_PLUGNPAY_ACH_SS_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_PLUGNPAY_ACH_SS_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();

      // Smart Screens v2 hosted checkout
      $this->form_action_url = 'https://pay1.plugnpay.com/pay/';
    }

// class methods
    function update_status() {
      global $order;

      if (($this->enabled == true) && ((int)MODULE_PAYMENT_PLUGNPAY_ACH_SS_ZONE > 0)) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_PLUGNPAY_ACH_SS_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
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

      # generate pnp order classifier so that we don't get duplicates
      $pnp_date = date('YmdHis');
      $pnp_orderID = $pnp_date . substr(getmypid(), 0, 3);

      $amount = $this->format_raw($order->info['total']);
      $session_id = tep_session_id();
      $session_name = tep_session_name();
      $success_url = tep_href_link(FILENAME_CHECKOUT_PROCESS, $session_name . '=' . $session_id, 'SSL', false);

      # Smart Screens v2 fields — ACH via pd_transaction_payment_type=ach
      $process_button_string = tep_draw_hidden_field('pt_gateway_account', MODULE_PAYMENT_PLUGNPAY_ACH_SS_LOGIN) .
                               tep_draw_hidden_field('pb_confirmation_sending_email_address', MODULE_PAYMENT_PLUGNPAY_ACH_SS_PUBEMAIL) .
                               tep_draw_hidden_field('pt_client_identifier', 'osCP_ACH_SS2') .
                               tep_draw_hidden_field('pd_transaction_payment_type', 'ach') .
                               tep_draw_hidden_field('pd_display_items', 'yes') .
                               tep_draw_hidden_field('pd_collect_shipping_information', 'yes') .
                               tep_draw_hidden_field('pt_order_classifier', $pnp_orderID) .
                               tep_draw_hidden_field('pt_account_code_1', $pnp_orderID) .
                               tep_draw_hidden_field('pt_currency', strtoupper($currency)) .
                               tep_draw_hidden_field('pt_currency_code', strtoupper($currency)) .
                               tep_draw_hidden_field('pt_transaction_amount', $amount) .
                               tep_draw_hidden_field('pb_post_auth', 'no') .
                               tep_draw_hidden_field('pb_transition_type', 'post') .
                               tep_draw_hidden_field('pb_success_url', $success_url);

      # billing address info
      $process_button_string .= tep_draw_hidden_field('pt_payment_name', $order->billing['firstname'] . ' ' . $order->billing['lastname']) .
                                tep_draw_hidden_field('pt_billing_company', $order->billing['company']) .
                                tep_draw_hidden_field('pt_billing_address_1', $order->billing['street_address']) .
                                tep_draw_hidden_field('pt_billing_city', $order->billing['city']) .
                                tep_draw_hidden_field('pt_billing_province', $order->billing['suburb']) .
                                tep_draw_hidden_field('pt_billing_state', $order->billing['state']) .
                                tep_draw_hidden_field('pt_billing_postal_code', $order->billing['postcode']) .
                                tep_draw_hidden_field('pt_billing_country', $order->billing['country']['iso_code_2']);

      # shipping address info
      if (is_numeric($sendto) && ($sendto > 0)) {
        $process_button_string .= tep_draw_hidden_field('pt_shipping_name', $order->delivery['firstname'] . ' ' . $order->delivery['lastname']) .
                                  tep_draw_hidden_field('pt_shipping_company', $order->delivery['company']) .
                                  tep_draw_hidden_field('pt_shipping_address_1', $order->delivery['street_address']) .
                                  tep_draw_hidden_field('pt_shipping_city', $order->delivery['city']) .
                                  tep_draw_hidden_field('pt_shipping_province', $order->delivery['suburb']) .
                                  tep_draw_hidden_field('pt_shipping_state', $order->delivery['state']) .
                                  tep_draw_hidden_field('pt_shipping_postal_code', $order->delivery['postcode']) .
                                  tep_draw_hidden_field('pt_shipping_country', $order->delivery['country']['iso_code_2']);
      }

      # other customer info
      $process_button_string .= tep_draw_hidden_field('pt_billing_email_address', $order->customer['email_address']) .
                                tep_draw_hidden_field('pt_billing_phone_number', $order->customer['telephone']) .
                                tep_draw_hidden_field('pt_account_code_2', $customer_id) .
                                tep_draw_hidden_field('pt_ip_address', tep_get_ip_address());

      # echo session for return-path checks (also on pb_success_url)
      $process_button_string .= tep_draw_hidden_field('pt_custom_name_1', $session_name) .
                                tep_draw_hidden_field('pt_custom_value_1', $session_id);

      # itemized product details
      for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
        $j = $i + 1;
        $process_button_string .= tep_draw_hidden_field('pt_item_identifier_' . $j, $order->products[$i]['model']);
        $process_button_string .= tep_draw_hidden_field('pt_item_cost_' . $j, $order->products[$i]['final_price']);
        $process_button_string .= tep_draw_hidden_field('pt_item_quantity_' . $j, $order->products[$i]['qty']);
        $process_button_string .= tep_draw_hidden_field('pt_item_description_' . $j, $order->products[$i]['name']);
      }

      # tax & shipping fee settings
      $tax_value = 0;
      if (isset($order->info['tax_groups']) && is_array($order->info['tax_groups'])) {
        foreach ($order->info['tax_groups'] as $value) {
          if ($value > 0) {
            $tax_value += $this->format_raw($value);
          }
        }
      }

      if ($tax_value > 0) {
        $process_button_string .= tep_draw_hidden_field('pt_tax_amount', $this->format_raw($tax_value));
      }

      $process_button_string .= tep_draw_hidden_field('pt_shipping_amount', $this->format_raw($order->info['shipping_cost']));

      return $process_button_string;
    }

    function before_process() {
      global $HTTP_POST_VARS, $order;

      $post = (isset($_POST) && is_array($_POST) && count($_POST)) ? $_POST : $HTTP_POST_VARS;

      $error = false;

      $returned_amount = isset($post['pt_transaction_amount']) ? $post['pt_transaction_amount'] : '';
      if ($returned_amount != $this->format_raw($order->info['total'])) {
        $error = 'verification';
      }

      $status = isset($post['pi_response_status']) ? strtolower(trim($post['pi_response_status'])) : '';
      if ($status != 'success') {
        if ($status == 'badcard') {
          $error = 'declined';
        }
        elseif ($status == 'problem') {
          $error = 'problem';
        }
        elseif ($status == 'fraud') {
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

      $get = (isset($_GET) && is_array($_GET) && count($_GET)) ? $_GET : $HTTP_GET_VARS;

      $error_message = MODULE_PAYMENT_PLUGNPAY_ACH_SS_ERROR_GENERAL;

      switch (isset($get['error']) ? $get['error'] : '') {
        case 'verification':
          $error_message = MODULE_PAYMENT_PLUGNPAY_ACH_SS_ERROR_VERIFICATION;
          break;

        case 'declined':
          $error_message = MODULE_PAYMENT_PLUGNPAY_ACH_SS_ERROR_DECLINED;
          break;

        case 'problem':
          $error_message = MODULE_PAYMENT_PLUGNPAY_ACH_SS_ERROR_PROBLEM;
          break;

        case 'fraud':
          $error_message = MODULE_PAYMENT_PLUGNPAY_ACH_SS_ERROR_FRAUD;
          break;

        default:
          $error_message = MODULE_PAYMENT_PLUGNPAY_ACH_SS_ERROR_GENERAL;
          break;
      }

      $error = array('title' => MODULE_PAYMENT_PLUGNPAY_ACH_SS_ERROR_TITLE,
                     'error' => $error_message);

      return $error;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_PLUGNPAY_ACH_SS_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable PlugnPay ACH SS v2', 'MODULE_PAYMENT_PLUGNPAY_ACH_SS_STATUS', 'False', 'Do you want to accept PlugnPay ACH Smart Screens v2 payments? ACH/eCheck must be enabled on the gateway account.', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Merchant Username', 'MODULE_PAYMENT_PLUGNPAY_ACH_SS_LOGIN', 'pnpdemo', 'Your login username used for the PlugnPay service', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Publisher Email', 'MODULE_PAYMENT_PLUGNPAY_ACH_SS_PUBEMAIL', 'trash@plugnpay.com', 'Merchant confirmation email address used for confirmation emails', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_PLUGNPAY_ACH_SS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Payment Zone', 'MODULE_PAYMENT_PLUGNPAY_ACH_SS_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Set Order Status', 'MODULE_PAYMENT_PLUGNPAY_ACH_SS_ORDER_STATUS_ID', '0', 'Set the status of orders made with this payment module to this value. Orders are authorization-only until captured in PlugnPay Admin.', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_PLUGNPAY_ACH_SS_STATUS', 'MODULE_PAYMENT_PLUGNPAY_ACH_SS_LOGIN', 'MODULE_PAYMENT_PLUGNPAY_ACH_SS_PUBEMAIL', 'MODULE_PAYMENT_PLUGNPAY_ACH_SS_ZONE', 'MODULE_PAYMENT_PLUGNPAY_ACH_SS_ORDER_STATUS_ID', 'MODULE_PAYMENT_PLUGNPAY_ACH_SS_SORT_ORDER');
    }

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
?>
