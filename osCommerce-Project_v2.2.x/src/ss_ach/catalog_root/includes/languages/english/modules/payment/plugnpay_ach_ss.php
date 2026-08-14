<?php
/*
  $Id: plugnpay_ach_ss.php

  PlugnPay Smart Screens v2 — ACH / eCheck
  for osCommerce Project v2.2

  Copyright (c) PlugnPay Technologies
  Released under the GNU General Public License
*/

  define('MODULE_PAYMENT_PLUGNPAY_ACH_SS_TEXT_TITLE', 'PlugnPay ACH SS v2');
  define('MODULE_PAYMENT_PLUGNPAY_ACH_SS_TEXT_PUBLIC_TITLE', 'ACH/eCheck (Processed by PlugnPay)');
  define('MODULE_PAYMENT_PLUGNPAY_ACH_SS_TEXT_DESCRIPTION', '<img src="images/icon_popup.gif" border="0">&nbsp;<a href="https://www.plugnpay.com" target="_blank" style="text-decoration: underline; font-weight: bold;">Visit PlugnPay.com Website</a><br><br>Smart Screens v2 hosted ACH/eCheck checkout at https://pay1.plugnpay.com/pay/. ACH data is collected on PlugnPay. Authorization is auth-only; capture in PlugnPay Admin. ACH must be enabled on the gateway account.');
  define('MODULE_PAYMENT_PLUGNPAY_ACH_SS_ERROR_TITLE', 'There has been an error processing your ACH payment');
  define('MODULE_PAYMENT_PLUGNPAY_ACH_SS_ERROR_VERIFICATION', 'The ACH transaction could not be verified with this order. Please try again and if problems persist, please try another payment method.');
  define('MODULE_PAYMENT_PLUGNPAY_ACH_SS_ERROR_DECLINED', 'This ACH transaction has been declined. Please try again and if problems persist, please try another payment method.');
  define('MODULE_PAYMENT_PLUGNPAY_ACH_SS_ERROR_PROBLEM', 'There was a problem processing your ACH transaction. Please try again and if problems persist, please try another payment method.');
  define('MODULE_PAYMENT_PLUGNPAY_ACH_SS_ERROR_FRAUD', 'This ACH transaction has been rejected. Please try again and if problems persist, please try another payment method.');
  define('MODULE_PAYMENT_PLUGNPAY_ACH_SS_ERROR_GENERAL', 'Please try again and if problems persist, please try another payment method.');
?>
