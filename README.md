# Shopping Cart - osCommerce Project Payment Modules

> **Notice: Development discontinued**
>
> This repository contains legacy PlugnPay payment modules for **osCommerce Project**, a third-party fork of osCommerce. **These modules are no longer under active development.**
>
> For new integrations and ongoing updates, we recommend using the current PlugnPay modules for standard **osCommerce** instead:
> [https://github.com/PlugnPay/shopping-cart-osCommerce](https://github.com/PlugnPay/shopping-cart-osCommerce)

---

Easy to install payment modules for the osCommerce Project shopping cart (v2.2).
Packages cover hosted **Smart Screens v2** checkout for credit card and ACH / eCheck (`https://pay1.plugnpay.com/pay/`).

## Downloads by osCommerce Project version

### osCommerce Project v2.2.x (legacy — Smart Screens v2)

* **Smart Screens v2 (Credit Card)** — hosted CC checkout
  - [Download](./osCommerce-Project_v2.2.x/oscproject_2.2_cc_ss2_module.zip)
  - Source: [./osCommerce-Project_v2.2.x/src/ss_cc/](./osCommerce-Project_v2.2.x/src/ss_cc/)
  - Docs: [package README](./osCommerce-Project_v2.2.x/README.md) · [INSTALL_SS_CC.txt](./osCommerce-Project_v2.2.x/INSTALL_SS_CC.txt)
* **Smart Screens v2 (ACH)** — hosted ACH / eCheck checkout
  - [Download](./osCommerce-Project_v2.2.x/oscproject_2.2_ach_ss2_module.zip)
  - Source: [./osCommerce-Project_v2.2.x/src/ss_ach/](./osCommerce-Project_v2.2.x/src/ss_ach/)
  - Docs: [package README](./osCommerce-Project_v2.2.x/README.md) · [INSTALL_SS_ACH.txt](./osCommerce-Project_v2.2.x/INSTALL_SS_ACH.txt)

Package overview: [./osCommerce-Project_v2.2.x/README.md](./osCommerce-Project_v2.2.x/README.md)

## Installation

For complete instructions, open the README inside the package folder (or the linked docs above).

### osCommerce Project 2.2.x — Smart Screens v2 (Credit Card)

1. Download [oscproject_2.2_cc_ss2_module.zip](./osCommerce-Project_v2.2.x/oscproject_2.2_cc_ss2_module.zip).
2. Copy files under `catalog_root/` into the matching paths of your cart root.
3. Admin → Modules → Payment → install/configure **PlugnPay Credit Card SS v2** (`plugnpay_cc_ss`).

- Quick install: [osCommerce-Project_v2.2.x/INSTALL_SS_CC.txt](./osCommerce-Project_v2.2.x/INSTALL_SS_CC.txt)

### osCommerce Project 2.2.x — Smart Screens v2 (ACH)

1. Download [oscproject_2.2_ach_ss2_module.zip](./osCommerce-Project_v2.2.x/oscproject_2.2_ach_ss2_module.zip).
2. Copy files under `catalog_root/` into the matching paths of your cart root.
3. Admin → Modules → Payment → install/configure **PlugnPay ACH SS v2** (`plugnpay_ach_ss`).
4. ACH / eCheck must be enabled on the PlugnPay gateway account.

- Quick install: [osCommerce-Project_v2.2.x/INSTALL_SS_ACH.txt](./osCommerce-Project_v2.2.x/INSTALL_SS_ACH.txt)

## Usage

### Smart Screens v2 (hosted)

* Hosted billing at `https://pay1.plugnpay.com/pay/`; the cart does not collect sensitive payment data.
* Card or ACH details are entered on PlugnPay SSL-secured billing pages.
* Authorization is auth-only (`pb_post_auth=no`); capture / void / refund in PlugnPay Merchant Admin.
* Return checks `pi_response_status=success` and matching `pt_transaction_amount`.
* Separate packages are available for credit card (`pd_transaction_payment_type=credit`) and ACH (`ach`).

## Repository layout

```
shopping-cart-osCommerce-Project/
  README.md
  .gitignore                          # _archive/ only
  osCommerce-Project_v2.2.x/          # legacy (2.2) — Smart Screens v2 CC + ACH
    README.md
    INSTALL_SS_CC.txt
    INSTALL_SS_ACH.txt
    oscproject_2.2_cc_ss2_module.zip
    oscproject_2.2_ach_ss2_module.zip
    src/{ss_cc,ss_ach}/
```

## Support

Provided AS IS. These modules are discontinued; prefer the current [osCommerce modules](https://github.com/PlugnPay/shopping-cart-osCommerce). See [PlugnPay docs](https://docs.plugnpay.com/) for Smart Screens v2 integration details.
