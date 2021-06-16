<?php
$supported_currencies   = Lightning_Paywall_Admin::TIPPING_CURRENCIES;
$predefined_enabled = get_option('lnpw_tipping_enter_amount');
$dimensions = $predefined_enabled === 'true' ? ['250x250', '300x300'] : ['400x400', '500x500', '600x600'];
$used_currency  = get_option('lnpw_tipping_currency');
$used_dimension      = get_option('lnpw_tipping_dimension');
$redirect = get_option('lnpw_tipping_redirect');
$collect = get_option('lnpw_tipping_collect');
$fixed_amount = get_option('lnpw_tipping_fixed_amount');
$text = get_option('lnpw_tipping_text');
$color = get_option('lnpw_tipping_color');
$image = get_option('lnpw_tipping_image');
$logo = wp_get_attachment_image_src($image['logo']);
$background = wp_get_attachment_image_src($image['background']);
?>

<style>

    .lnpw_tipping_collect_name_mandatory,
    label[for="lnpw_tipping_collect[name][mandatory]"] {
        visibility: <?php echo $collect['name']['collect'] === 'true' ? '' : 'hidden'; ?>;
    }

    .lnpw_tipping_collect_email_mandatory,
    label[for="lnpw_tipping_collect[email][mandatory]"] {
        visibility: <?php echo $collect['email']['collect'] === 'true' ? '' : 'hidden'; ?>;
    }

    .lnpw_tipping_collect_address_mandatory,
    label[for="lnpw_tipping_collect[address][mandatory]"] {
        visibility: <?php echo $collect['address']['collect'] === 'true' ? '' : 'hidden'; ?>;
    }

    .lnpw_tipping_collect_phone_mandatory,
    label[for="lnpw_tipping_collect[phone][mandatory]"] {
        visibility: <?php echo $collect['phone']['collect'] === 'true' ? '' : 'hidden'; ?>;
    }

    .lnpw_tipping_collect_message_mandatory,
    label[for="lnpw_tipping_collect[message][mandatory]"] {
        visibility: <?php echo $collect['message']['collect'] === 'true' ? '' : 'hidden'; ?>;
    }
</style>
<div class="tipping_settings">
    <h1>Tipping</h1>
    <form method="POST" action="options.php">
        <?php settings_fields('lnpw_tipping_settings'); ?>
        <div class="row">
            <div class="col-50">
                <label for="lnpw_tipping_dimension">Dimension</label>
            </div>
            <div class="col-50">
                <select required name="lnpw_tipping_dimension" id="lnpw_tipping_dimension">
                    <option disabled value="">Select dimension</option>
                    <?php foreach ($dimensions as $dimension) : ?>
                        <option <?php echo $used_dimension === $dimension ? 'selected' : ''; ?> value="<?php echo $dimension; ?>">
                            <?php echo $dimension; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-50">
                <label for="lnpw_tipping_image_background">Background image</label>
            </div>
            <div class="col-50">
            <?php if ($background) : ?>
                    <button id="lnpw_tipping_button_image_background" class="lnpw_tipping_button_image_background" name="lnpw_tipping_button_image_background"><img src="<?php echo $background[0]; ?>" /></a></button>
                    <button class="lnpw_tipping_button_remove_background">Remove image</button>
                    <input type="hidden" id="lnpw_tipping_image_background" class="lnpw_tipping_image_background" name="lnpw_tipping_image[background]" value=<?php echo $image['background']; ?> />
                <?php else : ?>
                    <button id="lnpw_tipping_button_image_background" class="lnpw_tipping_button_image_background" name="lnpw_tipping_button_image_background">Upload </button>
                    <button class="lnpw_tipping_button_remove_background" style="display:none">Remove image</button>
                    <input type="hidden" id="lnpw_tipping_image_background" class="lnpw_tipping_image_background" name="lnpw_tipping_image[background]" value=<?php echo $image['background']; ?> />
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-50">
                <label for="lnpw_tipping_background">Background color</label>
            </div>
            <div class="col-50">
            <input id="lnpw_tipping_background" class="lnpw_tipping_background" name="lnpw_tipping_color[background]" type="text" value=<?php echo $color['background']; ?> />
            </div>
        </div>
        <h3>Description</h3>
        
        <div class="row">
            <div class="col-50">
                <label for="lnpw_tipping_image">Tipping Logo</label>
            </div>
            <div class="col-50">
                <?php if ($logo) : ?>
                    <button id="lnpw_tipping_button_image" class="lnpw_tipping_button_image" name="lnpw_tipping_button_image"><img src="<?php echo $logo[0]; ?>" /></a></button>
                    <button class="lnpw_tipping_button_remove">Remove image</button>
                    <input type="hidden" id="lnpw_tipping_image" class="lnpw_tipping_image" name="lnpw_tipping_image[logo]" value=<?php echo $image['logo']; ?> />
                <?php else : ?>
                    <button id="lnpw_tipping_button_image" class="lnpw_tipping_button_image" name="lnpw_tipping_button_image">Upload</button>
                    <button class="lnpw_tipping_button_remove" style="display:none">Remove image</button>
                    <input type="hidden" id="lnpw_tipping_image" class="lnpw_tipping_image" name="lnpw_tipping_image[logo]" value=<?php echo $image['logo']; ?> />
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-50">
                <label for="lnpw_tipping_title">Title</label>
                <textarea id="lnpw_tipping_title" name="lnpw_tipping_text[title]"><?php echo $text['title']; ?></textarea>
            </div>
            <div class="col-50">
                <label for="lnpw_tipping_title_color">Title text color</label>
                <input id="lnpw_tipping_title_color" class="lnpw_tipping_title_color" name="lnpw_tipping_color[title]" type="text" value=<?php echo $color['title']; ?> />
            </div>
        </div>
        <div class="row">
            <div class="col-50">
                <label for="lnpw_tipping_description">Description</label>
                <textarea id="lnpw_tipping_description" name="lnpw_tipping_text[description]"><?php echo $text['description']; ?></textarea>
            </div>
            <div class="col-50">
            <label for="lnpw_tipping_description_color">Description text color</label>
                <input id="lnpw_tipping_description_color" class="lnpw_tipping_description_color" name="lnpw_tipping_color[description]" type="text" value=<?php echo $color['description']; ?> />
            </div>
        </div>
        <div class="row">
            <div class="col-50">
                <label for="lnpw_tipping_text">Tipping text</label>
                <textarea id="lnpw_tipping_text" name="lnpw_tipping_text[info]"><?php echo $text['info']; ?></textarea>
            </div>
            <div class="col-50">
            <label for="lnpw_tipping_tipping_color">Tipping text color</label>
                <input id="lnpw_tipping_tipping_color" class="lnpw_tipping_tipping_color" name="lnpw_tipping_color[tipping]" type="text" value=<?php echo $color['tipping']; ?> />
            </div>
        </div>
        <div class="row">
            <div class="col-50">
                <label for="lnpw_tipping_redirect">Link to Thank you Page</label>
            </div>
            <div class="col-50">
                <input id="lnpw_tipping_redirect" name="lnpw_tipping_redirect" value=<?php echo $redirect; ?> />
            </div>
        </div>
        <h3>Amount</h3>
        <div class="row">
            <div class="col-50">
                <label for="lnpw_tipping_enter_amount">Free input of amount</label>
                <input type="checkbox" id="lnpw_tipping_enter_amount" class="lnpw_tipping_enter_amount" name="lnpw_tipping_enter_amount" <?php echo $predefined_enabled === 'true' ? 'checked' : ''; ?> value="true" />
            </div>
            <div class="col-50">
                <label for="lnpw_tipping_currency">Currency</label>
                <select required name="lnpw_tipping_currency" id="lnpw_tipping_currency">
                    <option disabled value="">Select currency</option>
                    <?php foreach ($supported_currencies as $currency) : ?>
                        <option <?php echo $used_currency === $currency ? 'selected' : ''; ?> value="<?php echo $currency; ?>">
                            <?php echo $currency; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="container_predefined_amount">
            <div class="row">
                <div class="col-50">
                    <label for="lnpw_default_price1">Default price1</label>
                </div>
                <div class="col-50">
                    <input type="checkbox" class="lnpw_fixed_amount_enable" name="lnpw_tipping_fixed_amount[value1][enabled]" <?php echo $fixed_amount['value1']['enabled'] === 'true' ? 'checked' : ''; ?> value="true" />
                    <input type="number" min=0 placeholder="Default Price1" step=1 name="lnpw_tipping_fixed_amount[value1][amount]" id="lnpw_default_price1" value="<?php echo $fixed_amount['value1']['amount']; ?>">

                    <select required name="lnpw_tipping_fixed_amount[value1][currency]" id="lnpw_default_currency1">
                        <option disabled value="">Select currency</option>
                        <?php foreach ($supported_currencies as $currency) : ?>
                            <option <?php echo $fixed_amount['value1']['currency'] === $currency ? 'selected' : ''; ?> value="<?php echo $currency; ?>">
                                <?php echo $currency; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <input type="text" id="lnpw_tipping_icon1" class="lnpw_tipping_icon1" name="lnpw_tipping_fixed_amount[value1][icon]" placeholder="Font Awesome Icon" title="Font Awesome Icon class" value="<?php echo $fixed_amount['value1']['icon']; ?>" />
                </div>
            </div>
            <div class="row">
                <div class="col-50">
                    <label for="lnpw_default_price2">Default price2</label>
                </div>
                <div class="col-50">
                    <input type="checkbox" class="lnpw_fixed_amount_enable" name="lnpw_tipping_fixed_amount[value2][enabled]" <?php echo $fixed_amount['value2']['enabled'] === 'true' ? 'checked' : ''; ?> value="true" />
                    <input type="number" min=0 placeholder="Default Price2" step=1 name="lnpw_tipping_fixed_amount[value2][amount]" id="lnpw_default_price2" value="<?php echo $fixed_amount['value2']['amount']; ?>">

                    <select required name="lnpw_tipping_fixed_amount[value2][currency]" id="lnpw_default_currency2">
                        <option disabled value="">Select currency</option>
                        <?php foreach ($supported_currencies as $currency) : ?>
                            <option <?php echo $fixed_amount['value2']['currency'] === $currency ? 'selected' : ''; ?> value="<?php echo $currency; ?>">
                                <?php echo $currency; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <input type="text" id="lnpw_tipping_icon2" class="lnpw_tipping_icon2" name="lnpw_tipping_fixed_amount[value2][icon]" placeholder="Font Awesome Icon" title="Font Awesome Icon class" value="<?php echo $fixed_amount['value2']['icon']; ?>" />
                </div>
            </div>
            <div class="row">
                <div class="col-50">
                    <label for="lnpw_default_price3">Default price3</label>
                </div>
                <div class="col-50">
                    <input type="checkbox" class="lnpw_fixed_amount_enable" name="lnpw_tipping_fixed_amount[value3][enabled]" <?php echo $fixed_amount['value3']['enabled'] === 'true' ? 'checked' : ''; ?> value="true" />
                    <input type="number" min=0 placeholder="Default Price3" step=1 name="lnpw_tipping_fixed_amount[value3][amount]" id="lnpw_default_price3" value="<?php echo $fixed_amount['value3']['amount']; ?>">

                    <select required name="lnpw_tipping_fixed_amount[value3][currency]" id="lnpw_default_currency3">
                        <option disabled value="">Select currency</option>
                        <?php foreach ($supported_currencies as $currency) : ?>
                            <option <?php echo $fixed_amount['value3']['currency'] === $currency ? 'selected' : ''; ?> value="<?php echo $currency; ?>">
                                <?php echo $currency; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <input type="text" id="lnpw_tipping_icon3" class="lnpw_tipping_icon3" name="lnpw_tipping_fixed_amount[value3][icon]" placeholder="Font Awesome Icon" title="Font Awesome Icon class" value="<?php echo $fixed_amount['value3']['icon']; ?>" />
                </div>
            </div>
        </div>
        <h3>Button</h3>
        <div class="row">
            <div class="col-50">
                <label for="lnpw_tipping_button_text">Button text</label>
            </div>
            <div class="col-50">
                <textarea id="lnpw_tipping_button_text" name="lnpw_tipping_text[button]"><?php echo $text['button']; ?></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-50">
                <label for="lnpw_tipping_button_text_color">Button text color</label>
            </div>
            <div class="col-50">
                <input id="lnpw_tipping_button_text_color" class="lnpw_tipping_button_text_color" name="lnpw_tipping_color[button_text]" type="text" value=<?php echo $color['button_text']; ?> />

            </div>
        </div>
        
        
        <div class="row">
            <div class="col-50">
                <label for="lnpw_tipping_button_color">Button color</label>
            </div>
            <div class="col-50">
                <input id="lnpw_tipping_button_color" class="lnpw_tipping_button_color" name="lnpw_tipping_color[button]" type="text" value=<?php echo $color['button']; ?> />

            </div>
        </div>
        <h4>Collect further information</h4>
        <div class="row">
            <div class="col-50">
                <label for="lnpw_tipping_collect[name][collect]">Full name</label>
            </div>
            <div class="col-50">
                <label for="lnpw_tipping_collect[name][collect]">Display</label>

                <input type="checkbox" class="lnpw_tipping_collect_name" name="lnpw_tipping_collect[name][collect]" <?php echo $collect['name']['collect'] === 'true' ? 'checked' : ''; ?> value="true" />


                <input type="checkbox" class="lnpw_tipping_collect_name_mandatory" name="lnpw_tipping_collect[name][mandatory]" <?php echo $collect['name']['mandatory'] === 'true' ? 'checked' : ''; ?> value="true" />
                <label for="lnpw_tipping_collect[name][mandatory]">Mandatory</label>
            </div>
        </div>
        <div class="row">
            <div class="col-50">
                <label for="lnpw_tipping_collect[email][collect]">Email</label>
            </div>
            <div class="col-50">
                <label for="lnpw_tipping_collect[email][collect]">Display</label>

                <input type="checkbox" class="lnpw_tipping_collect_email" name="lnpw_tipping_collect[email][collect]" <?php echo $collect['email']['collect'] === 'true' ? 'checked' : ''; ?> value="true" />

                <input type="checkbox" class="lnpw_tipping_collect_email_mandatory" name="lnpw_tipping_collect[email][mandatory]" <?php echo $collect['email']['mandatory'] === 'true' ? 'checked' : ''; ?> value="true" />
                <label for="lnpw_tipping_collect[email][mandatory]">Mandatory</label>
            </div>
        </div>
        <div class="row">
            <div class="col-50">
                <label for="lnpw_tipping_collect[address][collect]">Address</label>
            </div>
            <div class="col-50">
                <label for="lnpw_tipping_collect[address][collect]">Display</label>

                <input type="checkbox" class="lnpw_tipping_collect_address" name="lnpw_tipping_collect[address][collect]" <?php echo $collect['address']['collect'] === 'true' ? 'checked' : ''; ?> value="true" />

                <input type="checkbox" class="lnpw_tipping_collect_address_mandatory" name="lnpw_tipping_collect[address][mandatory]" <?php echo $collect['address']['mandatory'] === 'true' ? 'checked' : ''; ?> value="true" />
                <label for="lnpw_tipping_collect[address][mandatory]">Mandatory</label>
            </div>
        </div>
        <div class="row">
            <div class="col-50">
                <label for="lnpw_tipping_collect[phone][collect]">Phone number</label>
            </div>
            <div class="col-50">
                <label for="lnpw_tipping_collect[phone][collect]">Display</label>

                <input type="checkbox" class="lnpw_tipping_collect_phone" name="lnpw_tipping_collect[phone][collect]" <?php echo $collect['phone']['collect'] === 'true' ? 'checked' : ''; ?> value="true" />

                <input type="checkbox" class="lnpw_tipping_collect_phone_mandatory" name="lnpw_tipping_collect[phone][mandatory]" <?php echo $collect['name']['mandatory'] === 'true' ? 'checked' : ''; ?> value="true" />
                <label for="lnpw_tipping_collect[phone][mandatory]">Mandatory</label>
            </div>
        </div>
        <div class="row">
            <div class="col-50">
                <label for="lnpw_tipping_collect[message][collect]">Message</label>
            </div>
            <div class="col-50">
                <label for="lnpw_tipping_collect[message][collect]">Display</label>
                <input type="checkbox" class="lnpw_tipping_collect_message" name="lnpw_tipping_collect[message][collect]" <?php echo $collect['message']['collect'] === 'true' ? 'checked' : ''; ?> value="true" />

                <input type="checkbox" class="lnpw_tipping_collect_message_mandatory" name="lnpw_tipping_collect[message][mandatory]" <?php echo $collect['message']['mandatory'] === 'true' ? 'checked' : ''; ?> value="true" />
                <label for="lnpw_tipping_collect[message][mandatory]">Mandatory</label>
            </div>
        </div>

        <div style="display: inline-block; margin-top: 25px;">
            <button class="button button-primary" type="submit">Save</button>
        </div>
    </form>
</div>