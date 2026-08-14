# osCommerce Project v2.2.x — PlugnPay Payment Modules

> **Discontinued:** These packages target the legacy **osCommerce Project** fork and are no longer maintained. For current PlugnPay modules, use [shopping-cart-osCommerce](https://github.com/PlugnPay/shopping-cart-osCommerce).

Packages for **osCommerce Project 2.2**. Choose Smart Screens v2 (Credit Card) or Smart Screens v2 (ACH) for hosted checkout at `https://pay1.plugnpay.com/pay/`.

## Choose a module

| | Smart Screens v2 CC | Smart Screens v2 ACH |
|---|---|---|
| Download | [oscproject_2.2_cc_ss2_module.zip](./oscproject_2.2_cc_ss2_module.zip) | [oscproject_2.2_ach_ss2_module.zip](./oscproject_2.2_ach_ss2_module.zip) |
| Source | [src/ss_cc/](./src/ss_cc/) | [src/ss_ach/](./src/ss_ach/) |
| Checkout | Hosted CC (`/pay/`) | Hosted ACH / eCheck (`/pay/`) |
| Payment type | `pd_transaction_payment_type=credit` | `pd_transaction_payment_type=ach` |
| Card/ACH on your server | No | No |
| Auth mode | Auth-only (`pb_post_auth=no`); settle in PlugnPay Admin | Auth-only (`pb_post_auth=no`); settle in PlugnPay Admin |
| Status | Discontinued (legacy for Project 2.2) | Discontinued (legacy for Project 2.2) |

## Smart Screens v2 — Credit Card

- Source: [src/ss_cc/](./src/ss_cc/)
- Download: [oscproject_2.2_cc_ss2_module.zip](./oscproject_2.2_cc_ss2_module.zip)
- Quick install: [INSTALL_SS_CC.txt](./INSTALL_SS_CC.txt)
- Vendor notes: [src/ss_cc/readme_install.txt](./src/ss_cc/readme_install.txt)

Copy `catalog_root/` paths into the cart. Payment module: `plugnpay_cc_ss`. Also overlays `admin/modules.php`.

### Install steps

1. Download [oscproject_2.2_cc_ss2_module.zip](./oscproject_2.2_cc_ss2_module.zip) (or use `src/ss_cc/`).
2. Copy files under `catalog_root/` into the matching paths of your shopping cart root.
3. Admin → Modules → Payment → install/configure **PlugnPay Credit Card SS v2**.
4. Set Merchant Username, Publisher Email, and Accepted Cards.

## Smart Screens v2 — ACH / eCheck

- Source: [src/ss_ach/](./src/ss_ach/)
- Download: [oscproject_2.2_ach_ss2_module.zip](./oscproject_2.2_ach_ss2_module.zip)
- Quick install: [INSTALL_SS_ACH.txt](./INSTALL_SS_ACH.txt)
- Vendor notes: [src/ss_ach/readme_install.txt](./src/ss_ach/readme_install.txt)

Copy `catalog_root/` paths into the cart. Payment module: `plugnpay_ach_ss`. ACH must be enabled on the gateway account.

### Install steps

1. Download [oscproject_2.2_ach_ss2_module.zip](./oscproject_2.2_ach_ss2_module.zip) (or use `src/ss_ach/`).
2. Copy files under `catalog_root/` into the matching paths of your shopping cart root.
3. Admin → Modules → Payment → install/configure **PlugnPay ACH SS v2**.
4. Ensure ACH / eCheck is enabled on the PlugnPay gateway account.

## Field mapping (Smart Screens v1 → v2)

| Smart Screens v1 | Smart Screens v2 |
|---|---|
| `https://pay1.plugnpay.com/payment/pay.cgi` | `https://pay1.plugnpay.com/pay/` |
| `publisher-name` | `pt_gateway_account` |
| `publisher_email` | `pb_confirmation_sending_email_address` |
| `card_amount` | `pt_transaction_amount` |
| `success_link` | `pb_success_url` |
| `FinalStatus` | `pi_response_status` |
| `item#` / `cost#` / `quantity#` / `description#` | `pt_item_identifier_#` / `pt_item_cost_#` / `pt_item_quantity_#` / `pt_item_description_#` |
| `paymethod=credit` | `pd_transaction_payment_type=credit` |
| `paymethod=onlinecheck` | `pd_transaction_payment_type=ach` |
| `authtype` | `pb_post_auth=no` (auth-only) |

## Development layout

```
osCommerce-Project_v2.2.x/
  README.md
  INSTALL_SS_CC.txt
  INSTALL_SS_ACH.txt
  oscproject_2.2_cc_ss2_module.zip
  oscproject_2.2_ach_ss2_module.zip
  src/
    ss_cc/     # catalog_root/ overlay + readme_install.txt
    ss_ach/
```
