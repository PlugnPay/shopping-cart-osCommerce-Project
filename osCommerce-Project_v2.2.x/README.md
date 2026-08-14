# osCommerce Project v2.2.x — PlugnPay Payment Modules

> **Discontinued:** These packages target the legacy **osCommerce Project** fork and are no longer maintained. For current PlugnPay modules, use [shopping-cart-osCommerce](https://github.com/PlugnPay/shopping-cart-osCommerce).

Packages for **osCommerce Project 2.2**. Choose Smart Screens (Credit Card) or Smart Screens (ACH) for hosted checkout.

## Choose a module

| | Smart Screens CC | Smart Screens ACH |
|---|---|---|
| Download | [oscproject_2.2_cc_ss_module.zip](./oscproject_2.2_cc_ss_module.zip) | [oscproject_2.2_ach_ss_module.zip](./oscproject_2.2_ach_ss_module.zip) |
| Source | [src/ss_cc/](./src/ss_cc/) | [src/ss_ach/](./src/ss_ach/) |
| Checkout | Hosted CC | Hosted ACH / eCheck |
| Card/ACH on your server | No | No |
| Storefront SSL | Prefer off | Prefer off |
| Status | Discontinued (legacy for Project 2.2) | Discontinued (legacy for Project 2.2) |

## Smart Screens — Credit Card

- Source: [src/ss_cc/](./src/ss_cc/)
- Download: [oscproject_2.2_cc_ss_module.zip](./oscproject_2.2_cc_ss_module.zip)
- Quick install: [INSTALL_SS_CC.txt](./INSTALL_SS_CC.txt)
- Vendor notes: [src/ss_cc/readme_install.txt](./src/ss_cc/readme_install.txt)

Copy `catalog_root/` paths into the cart. Payment module: `plugnpay_cc_ss`. Also overlays `admin/modules.php`.

### Install steps

1. Download [oscproject_2.2_cc_ss_module.zip](./oscproject_2.2_cc_ss_module.zip) (or use `src/ss_cc/`).
2. Copy files under `catalog_root/` into the matching paths of your shopping cart root.
3. Admin → Modules → Payment → install/configure **PlugnPay CC Smart Screens**.
4. In PlugnPay Account Settings, enable the transition page and set Transition Page Type to `post`.

## Smart Screens — ACH / eCheck

- Source: [src/ss_ach/](./src/ss_ach/)
- Download: [oscproject_2.2_ach_ss_module.zip](./oscproject_2.2_ach_ss_module.zip)
- Quick install: [INSTALL_SS_ACH.txt](./INSTALL_SS_ACH.txt)
- Vendor notes: [src/ss_ach/readme_install.txt](./src/ss_ach/readme_install.txt)

Copy `catalog_root/` paths into the cart. Payment module: `plugnpay_ach_ss`. ACH must be enabled on the gateway account.

### Install steps

1. Download [oscproject_2.2_ach_ss_module.zip](./oscproject_2.2_ach_ss_module.zip) (or use `src/ss_ach/`).
2. Copy files under `catalog_root/` into the matching paths of your shopping cart root.
3. Admin → Modules → Payment → install/configure **PlugnPay ACH Smart Screens**.
4. In PlugnPay Account Settings, enable the transition page and set Transition Page Type to `post`.
5. Ensure ACH / eCheck is enabled on the PlugnPay gateway account.

## Development layout

```
osCommerce-Project_v2.2.x/
  README.md
  INSTALL_SS_CC.txt
  INSTALL_SS_ACH.txt
  oscproject_2.2_cc_ss_module.zip
  oscproject_2.2_ach_ss_module.zip
  src/
    ss_cc/     # catalog_root/ overlay + readme_install.txt
    ss_ach/
```
