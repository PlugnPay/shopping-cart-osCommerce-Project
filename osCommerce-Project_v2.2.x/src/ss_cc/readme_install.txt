=================================================
PlugnPay Smart Screens v2 — Credit Card
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
(https://pay1.plugnpay.com/pay/). Card data is collected on PlugnPay SSL pages.
Authorization is auth-only (pb_post_auth=no). Capture, void, and refund are
performed in PlugnPay Merchant Admin.

If you want to change the behavior of this module, please feel free to make changes
to the files yourself. However, customized payment modules will not be provided
support assistance.
***************************

Installation:

1. Copy catalog_root/includes/languages/english/modules/payment/plugnpay_cc_ss.php
   into the corresponding path in your shopping cart.

2. Do the same with catalog_root/includes/modules/payment/plugnpay_cc_ss.php

3. Do the same with catalog_root/admin/modules.php

4. Go into your admin panel and activate the PlugnPay Credit Card SS v2 payment
   module and fill in Merchant Username, Publisher Email, and Accepted Cards.

5. No PlugnPay "transition page" account setting is required for SS v2 —
   the module sets pb_transition_type=post and pb_success_url automatically.


Troubleshooting:

Check to be sure you actually uploaded the files in the correct folders;
ensuring you overwrite the existing file, if one exists.

Check the uploaded file's permissions:
-- .php files should be chmod 755
   (read/write/execute by owner, read/execute by all others)

Make sure you have an active merchant account for a given card type, prior to accepting
online payments for it. This is especially important for Amex & Discover cards.

These are the common issues found by our support staff.

-----------------------------------------------------------------------------
Updates:

08/14/26
- Upgraded from Smart Screens v1 (pay.cgi) to Smart Screens v2 (/pay/).
- Field mapping: publisher-name → pt_gateway_account, card_amount →
  pt_transaction_amount, FinalStatus → pi_response_status, item# →
  pt_item_identifier_#, success_link → pb_success_url.
- Auth-only (pb_post_auth=no); settle in PlugnPay Admin.
- Removed legacy Transaction Method / AVS Level admin options.

01/03/09
- applied minor bug fixes.
- adjusted module installation instructions
- corrected card-type option display in admin area & during checkout process.
- changed module send 2-char country ISO code, instead of country name.

12/30/08
- consolidated code base, to make it more user friendly.
- applied minor bug fixes.

12/27/08
- created basic PlugnPay Smart Screens payment module.
