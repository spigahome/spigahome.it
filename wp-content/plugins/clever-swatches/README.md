# CleverSwatches

CleverSwatches - WooCommerce Color or Image Variation Swatches. Convert your normal variable attribute dropdown select to nicely looking color or image select. You can display images or color in all common size. Display them in round or square mode. CleverSwatches allows you to add multiple gallery images for each variation and when visitor selects the variation those gallery images will be shown as gallery images instead of main product gallery images.

## Key Features

- Support multiple gallery images for each variation
- Support color/image/text variation swatches for attributes
- Support color/image/text/dropdown select swatches for each variation custom attributes
- Square as well as circus swatch support
- Different sizes of swatch from small to large
- Enable / Disable tooltips for swatch item feature
- Swatches can be set per attribute globally, or overridden on an individual product level
- Shows color or image swatches on shop/category/tag archive pages
- Shows color or image swatches on[product_page ] shortcode.
- Enable / Disable pre-select for variable product
- Improve SEO on URLs with filtering and multiple select for variations __(pending)__
- Swatches gallery: This feature allow replace original product gallery of theme by CW gallery. It helpful for theme not support CW, and make CW work immediately no need use integrated files.(New)

## UpComing Features

- Reset button for single page, allow use reset variable selected.
- Enable Variation Update On Cart Page:Using this admin can allow there buyers to change the selected variation on cart page.

## ChangeLogs

### Version 2.1.9 - Jan 30, 2020

- Fix issue of Cart button with attributes have special characters.
- Fix issue of Cart and CW with custom attributes set default selected.

### Version 2.1.8 - Dec 19, 2019
- Improve shop page function.
- Fix issue translate plugin.
- Fix render custom attribute at shop page.
- Fix admin menu disappears because of WCFM - WooCommerce Multivendor Marketplace plugin.

### Version 2.1.7 - Oct 17, 2019
- Fix issue Swatches not working with custom attribute have white space and special characters.
- Fix issue can't get default attributes follow product URL.
- Improve loading gallery effect.
- Improve default zoom follow default zoom feature of WooCommerce.
- Update Swatches template for Porto theme. Make it work better.

### Version 2.1.6 - July 08, 2019
- Add integrated files for Aurum theme.
- Fix issue don't change after product redirect from cart to single product.
- Improve mobile and touch device UX.
- Improve attribute global settings. Allow apply attribute type for products added before active Clever Swatches.

### Version 2.1.5 - Jun 04, 2019
- Fix issue of CW with draft product.
- Fix issue CW not working with attribute have non-lating encoding.
- Fix issue product can't add to cart with default display type select.
- Fix issue plugin can't translate.
- Fix minor issue with js of plugin.
- Fix issue build-in gallery of CW not working with Elementor product template.
- Update integrated js for Salient theme.
- Improve CW style.

### Version 2.1.4 - Jan 31, 2019
- Fix issue built-in gallery of CW broken when user using custom layout Elementor.
- Fix issue of CW if attribute deleted by user.
- Fix collapse CW metaboxes in single product.
- Update integrated js for Flatsome theme.
- Improve CW function and shop page template.

### Version 2.1.3 - Nov 26, 2018
- Add js trigger `woocommerce_variation_select_change` for trigger default variation WooCommerce.
- Add integrated js for bridge theme.
- Change icon by using svg.
- Fix issue duplicate button.
- Fix issue load js vendor.
- Fix issue of custom attribute have white space can't set color/image.
- Improve load template add-to-cart-button.

### Version 2.1.2 - Aug 15, 2018
- Fix issue redirect url when click to image of product shop loop.
- Fix issue of variable products when set attribute is Any in shop loop.
- Fix issue change gallery when attribute set type is select and default selected option.
- Fix issue conflict js with js of thumbnail.
- Fix issue js `wp.template not is function`.
- Improve js function add to cart of shop page. Auto trigger add to cart effect of theme if theme using js trigger `added_to_cart`.
- Improve js function for increase performance.
- Update default image template follow WooCommerce 3.4 structure.

### Version 2.1.1 - May 22, 2018
- Add language file for Czech language (Thank Fany VanDaal).
- Add Vertical layout for Swatches gallery.
- Improve default template gallery.
- Tested with themes: GeneratePress, Astra, OceanWP.

### Version 2.1.0 - May 03, 2018
- Add option enable/disable tooltip.
- Add option enable/disable CW gallery.
- Add new feature: CW gallery. Allow replace original product gallery of theme by CW gallery.
- Fix issue of swatches selector when variation product set variable is Any.
- Fix issue override CW template from child theme.
- Improve js, css.
- Improve template file.
- Replace jQuery Tooltip by Tippy js.

### Version 2.0.8 - Apr 18, 2018
- Add js trigger for shop page `cleverswatch_shop_update_image`.
- Add integrated files for themes wowmall, basel.
- Fix issue with vendor product.

### Version 2.0.7 - Mar 23, 2018
- Fix issue with out of stock product on shop page.
- Improve style, js functions.
- Improve mobile UX.

### Version 2.0.6 - Mar 19, 2018
- Add integrated files for unero theme.
- Add integrated files for bronx theme.
- Fix issue with default variable product without cw data.
- Fix issue with default variation out of stock.
- Fix issue with variable product have type is select when hover.
- Fix issue with default variations is Any options.
- Fix issue of default variable product with check available.
- Improve style, js functions.


### Version 2.0.5 - Mar 02, 2018

- Fix issue CW not working with WMPL.
- Fix issue swatches can't display on cart/checkout page.
- Improve shop page template.

### Version 2.0.4 - Feb 03, 2018

- Fix issue with swatches gallery template.
- Improve integrated files.

### Version 2.0.3 - Jan 09, 2018

- Fix issue with loading default template file.
- Fix issue with variable select if product already create before.
- Improve swatches functions.
- Update integrated files for themes Xstore, Shopkeeper.

### Version 2.0.2 - Dec 14, 2017

- Add integrated files for woodmart and vitrine theme.
- Fix issue change SKU not change after select attributes.
- Fix issue with image attribute small than shop thumbnail size.
- Fix issue with hook woocommerce_template_loop_product_link_close when user edit template.
- Improve js file, keep button icon when select variables.
- Improve template file and style.

### Version 2.0.1 - Dec 07, 2017

- Add integrated files for Xstore theme.
- Change size image of CW image label.
- Fix issue of product edit page.
- Fix issue of Attributes admin page (Products -> Attributes).
- Fix issue redirect to single product when click to CW image label on shop page.
- Update integrated files.
- Update language file.

### Version 2.0.0 - Nov 10, 2017

- Add new visual effect for swatches at product page and shop page
- Add new function allow to add to cart at shop page
- Fix issue swatch don't work with product attribute type select/text at shop page
- Fix issue can't add to cart at product page in some case

### Version 1.0.3 - Sep 21, 2017

- Fix issue that not able to disable to show the swatches on cart page
- Porto theme supported

### Version 1.0.2 - Sep 20, 2017

- Fix issue duplicate attribute
- Fix issue Variable not show if variations select "Any"
- Fix issue redirect to single when click to variation on shop page
- Flatsome theme supported
- Electro theme supported

### Version 1.0.1 - Sep 11, 2017

- Fix issue with any value in variation
- Add custom Jquery Selector class option for single page
- Minor improvements

### Version 1.0.0 - Sep 05, 2017

- Initial release

## Js trigger: Use for custom js functions
### Both of shop and single product page
- `cleverswatch_button_select_option` Trigger when button add to cart switch to default (No variations selected).
- `cleverswatch_button_add_cart` Trigger when variable is selected and allow add to cart.
- `cleverswatch_button_out_stock` Trigger when variable is selected but not allow add to cart (variable product is out of stock, or not visible...).
### Shop page js trigger
- `cleverswatch_before_add_to_cart` Trigger when user click to add to cart button after swatches selected. It's useful on case user want do some action when user click to add to cart button.
- `cleverswatch_after_add_to_cart` Trigger when variable product on list added to cart.
### Single product js trigger
- `cleverswatch_before_update_gallery` Trigger before product gallery is changed (When swatches selected).
- `cleverswatch_update_gallery` Trigger after product gallery is changed (After swatches selected, and data return).
