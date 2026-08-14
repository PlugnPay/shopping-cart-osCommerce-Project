=================================================
PlugnPay Smart Screens Method
Payment Module for osCommerce Project v2.2
=================================================

***** IMPORTANT NOTES *****
This module is being provided "AS IS".  Limited technical support assistance will
be given to help diagnose/address problems with this module.  The amount of support
provided is up to PlugnPay's staff.

If you experience a problem with this module, first seek assistance through this
module's readme file, then check with the community at 'www.oscommerceproject.com'.
If you are still unable to resolve the issue, contact us via PlugnPay's Online Helpdesk.

This module is used without having your own server's SSL abilities. We will ask the
customer for all of their credit card info on our SSL secured billing page on our
secured servers.  The authorization will be done via PlugnPay's Smart Screens payment
method.  The module is intended to itemize the order in the PlugnPay system using
available information from the cart.

If you want to change the behavior of this module, please feel free to make changes
to the files yourself.  However, customized payment modules will not be provided
support assistance.
***************************

Installation:

1. Copy the catalog_root/includes/languages/english/modules/payment/plugnpay_cc_ss.php file to the
corresponding place within your shopping cart folder on your web server.

2. Do the same with catalog_root/includes/modules/payment/plugnpay_cc_ss.php

3. Do the same with catalog_root/admin/modules.php

4. Go into your admin panel and activate the PlugnPay payment module on the payment modules
page and fill in the appropriate data.

5. Go into the Account Settings section of your PlugnPay administration area.
Enable the Use Transition Page option & set the Transition Page Type to
'post'.


Troubleshooting:

Check to be sure you actually uploaded the files in the correct folders;
ensuring you overwrite the existing file, if one exists.

Check the uploaded file's permissions:
-- .php files should be chmod 755
   (read/write/execute by owner, read/execute by all others)

Make sure you have an active merchant account for a given card type, prior to accepting
online payments for it.  This is especilaly important for Amex & Discover cards. 

These are the common issues found by our support staff.

-----------------------------------------------------------------------------
Updates:

12/27/08
- created basic PlugnPay Smart Screens payment module.

12/30/08
- consolidated code base, to make it more user friendly.
- applied minor bug fixes.

01/03/09
- applied minor bug fixes.
- adjusted module installation instructions
- corrected card-type option display in admin area & during checkout process.
- changed module send 2-char country ISO code, instead of country name.

