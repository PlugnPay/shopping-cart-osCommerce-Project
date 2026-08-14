=================================================
PlugnPay Smart Screens v2 — ACH / eCheck
Payment Module for osCommerce Project v2.2
=================================================

***** IMPORTANT NOTES *****
This module is being provided "AS IS". Limited technical support assistance will
be given to help diagnose/address problems with this module. The amount of support
provided is up to PlugnPay's staff.

These modules for osCommerce Project are no longer under active development.
Prefer the current osCommerce packages:
https://github.com/PlugnPay/shopping-cart-osCommerce

This module posts customers to PlugnPay hosted Smart Screens v2
(https://pay1.plugnpay.com/pay/) with pd_transaction_payment_type=ach.
ACH/eCheck data is collected on PlugnPay SSL pages. Authorization is auth-only
(pb_post_auth=no). Capture, void, and refund are performed in PlugnPay Merchant
Admin. ACH / eCheck must be enabled on the gateway account.

If you want to change the behavior of this module, please feel free to make changes
to the files yourself. However, customized payment modules will not be provided
support assistance.
***************************

Installation:

1. Copy catalog_root/includes/languages/english/modules/payment/plugnpay_ach_ss.php
   into the corresponding path in your shopping cart.

2. Do the same with catalog_root/includes/modules/payment/plugnpay_ach_ss.php

3. Go into your admin panel and activate the PlugnPay ACH SS v2 payment module
   and fill in Merchant Username and Publisher Email.

4. Ensure ACH / eCheck is enabled on the PlugnPay gateway account.

5. No PlugnPay "transition page" account setting is required for SS v2 —
   the module sets pb_transition_type=post and pb_success_url automatically.


Troubleshooting:

Check to be sure you actually uploaded the files in the correct folders;
ensuring you overwrite the existing file, if one exists.

Check the uploaded file's permissions:
-- .php files should be chmod 755
   (read/write/execute by owner, read/execute by all others)

These are the common issues found by our support staff.

-----------------------------------------------------------------------------
Updates:

08/14/26
- Upgraded from Smart Screens v1 (pay.cgi) to Smart Screens v2 (/pay/).
- Uses pd_transaction_payment_type=ach for hosted ACH/eCheck.
- Field mapping: publisher-name → pt_gateway_account, card_amount →
  pt_transaction_amount, FinalStatus → pi_response_status, item# →
  pt_item_identifier_#, success_link → pb_success_url.
- Auth-only (pb_post_auth=no); settle in PlugnPay Admin.
- Removed legacy Transaction Method / AVS Level admin options.

01/03/09
- created basic PlugnPay ACH Smart Screens payment module.
