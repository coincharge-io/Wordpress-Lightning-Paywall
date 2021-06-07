<?php
$supported_currencies   = Lightning_Paywall_Admin::TIPPING_CURRENCIES;
$dimensions = ['250x250', '300x250', '240x400', '400x400', '500x500', '600x600'];
$used_currency  = get_option('lnpw_tipping_currency');
$used_dimension      = get_option('lnpw_tipping_dimension');
$title = get_option('lnpw_tipping_title');
$description = get_option('lnpw_tipping_description');
$btn_text = get_option('lnpw_tipping_button_text');
$btn_text_color = get_option('lnpw_tipping_button_text_color');
$btn_color = get_option('lnpw_tipping_button_color');
$background_color = get_option('lnpw_tipping_background');
$image_id = get_option('lnpw_tipping_image');
$image = wp_get_attachment_image_src($image_id);
$icon1_id = get_option('lnpw_tipping_icon1');
$icon1 = wp_get_attachment_image_src($icon1_id, array('20', '20'), false);
$icon2_id = get_option('lnpw_tipping_icon2');
$icon2 = wp_get_attachment_image_src($icon2_id, array('20', '20'), false);
$icon3_id = get_option('lnpw_tipping_icon3');
$icon3 = wp_get_attachment_image_src($icon3_id, array('20', '20'), false);
$collect = get_option('lnpw_tipping_collect');
$redirect = get_option('lnpw_tipping_redirect');
$name = get_option('lnpw_tipping_collect_name');
$phone = get_option('lnpw_tipping_collect_phone');
$email = get_option('lnpw_tipping_collect_email');
$address = get_option('lnpw_tipping_collect_address');
$message = get_option('lnpw_tipping_collect_message');
$mandatory_name = get_option('lnpw_tipping_collect_name_mandatory');
$mandatory_phone = get_option('lnpw_tipping_collect_phone_mandatory');
$mandatory_email = get_option('lnpw_tipping_collect_email_mandatory');
$mandatory_address = get_option('lnpw_tipping_collect_address_mandatory');
$mandatory_message = get_option('lnpw_tipping_collect_message_mandatory');
$default_price1 = get_option('lnpw_default_price1');
$default_currency1 = get_option('lnpw_default_currency1');
$default_price2 = get_option('lnpw_default_price2');
$default_currency2 = get_option('lnpw_default_currency2');
$default_price3 = get_option('lnpw_default_price3');
$default_currency3 = get_option('lnpw_default_currency3');
?>

<style>
    .container_donor_information {
        display: <?php echo $collect === 'true' ? 'block' : 'none'; ?>;
    }
</style>
<div>
    <h1>Tipping</h1>
    <form method="POST" action="options.php">
        <?php settings_fields('lnpw_tipping_settings'); ?>
        <div class="row">
            <div class="col-20">
                <label for="lnpw_tipping_dimension">Dimension</label>
            </div>
            <div class="col-80">
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
            <div class="col-20">
                <label for="lnpw_tipping_image">Tipping Logo</label>
            </div>
            <div class="col-80">
                <?php if ($image) : ?>
                    <button id="lnpw_tipping_button_image" class="lnpw_tipping_button_image" name="lnpw_tipping_button_image"><img src="<?php echo $image[0]; ?>" /></a></button>
                    <button class="lnpw_tipping_button_remove">Remove image</button>
                    <input type="hidden" id="lnpw_tipping_image" class="lnpw_tipping_image" name="lnpw_tipping_image" value=<?php echo $image_id; ?> />
                <?php else : ?>
                    <button id="lnpw_tipping_button_image" class="lnpw_tipping_button_image" name="lnpw_tipping_button_image">Upload</button>
                    <button class="lnpw_tipping_button_remove" style="display:none">Remove image</button>
                    <input type="hidden" id="lnpw_tipping_image" class="lnpw_tipping_image" name="lnpw_tipping_image" value=<?php echo $image_id; ?> />
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-20">
                <label for="lnpw_tipping_title">Title</label>
            </div>
            <div class="col-80">
                <textarea id="lnpw_tipping_title" name="lnpw_tipping_title"><?php echo $title; ?></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-20">
                <label for="lnpw_tipping_description">Description</label>
            </div>
            <div class="col-80">
                <textarea id="lnpw_tipping_description" name="lnpw_tipping_description"><?php echo $description; ?></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-20">
                <label for="lnpw_tipping_redirect">Link to Thank you Page</label>
            </div>
            <div class="col-80">
                <input id="lnpw_tipping_redirect" name="lnpw_tipping_redirect" value=<?php echo $redirect; ?> />
            </div>
        </div>
        <div class="row">
            <div class="col-20">
                <label for="lnpw_tipping_button_text">Button text</label>
            </div>
            <div class="col-80">
                <textarea id="lnpw_tipping_button_text" name="lnpw_tipping_button_text"><?php echo $btn_text; ?></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-20">
                <label for="lnpw_default_price1">Default price1</label>
            </div>
            <div class="col-80">
                <input required type="number" min=0 placeholder="Default Price1" step=1 name="lnpw_default_price1" id="lnpw_default_price1" value="<?php echo $default_price1 ?>">

                <select required name="lnpw_default_currency1" id="lnpw_default_currency1">
                    <option disabled value="">Select currency</option>
                    <?php foreach ($supported_currencies as $currency) : ?>
                        <option <?php echo $default_currency1 === $currency ? 'selected' : ''; ?> value="<?php echo $currency; ?>">
                            <?php echo $currency; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <?php if ($icon1) : ?>
                    <button id="lnpw_tipping_button_icon1" class="lnpw_tipping_button_icon1" name="lnpw_tipping_button_icon1"><img src="<?php echo $icon1[0]; ?>" /></a></button>
                    <button class="lnpw_tipping_button_remove_icon1">Remove icon1</button>
                    <input type="hidden" id="lnpw_tipping_icon1" class="lnpw_tipping_icon1" name="lnpw_tipping_icon1" value=<?php echo $icon1_id; ?> />
                <?php else : ?>
                    <button id="lnpw_tipping_button_icon1" class="lnpw_tipping_button_icon1" name="lnpw_tipping_button_icon1">Upload</button>
                    <button class="lnpw_tipping_button_remove_icon1" style="display:none">Remove image</button>
                    <input type="hidden" id="lnpw_tipping_icon1" class="lnpw_tipping_icon1" name="lnpw_tipping_icon1" value=<?php echo $icon1_id; ?> />
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-20">
                <label for="lnpw_default_price2">Default price2</label>
            </div>
            <div class="col-80">
                <input required type="number" min=0 placeholder="Default Price2" step=1 name="lnpw_default_price2" id="lnpw_default_price2" value="<?php echo $default_price2 ?>">

                <select required name="lnpw_default_currency2" id="lnpw_default_currency2">
                    <option disabled value="">Select currency</option>
                    <?php foreach ($supported_currencies as $currency) : ?>
                        <option <?php echo $default_currency2 === $currency ? 'selected' : ''; ?> value="<?php echo $currency; ?>">
                            <?php echo $currency; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <?php if ($icon2) : ?>
                    <button id="lnpw_tipping_button_icon2" class="lnpw_tipping_button_icon2" name="lnpw_tipping_button_icon2"><img src="<?php echo $icon2[0]; ?>" /></a></button>
                    <button class="lnpw_tipping_button_remove_icon2">Remove icon2</button>
                    <input type="hidden" id="lnpw_tipping_icon2" class="lnpw_tipping_icon2" name="lnpw_tipping_icon2" value=<?php echo $icon2_id; ?> />
                <?php else : ?>
                    <button id="lnpw_tipping_button_icon2" class="lnpw_tipping_button_icon2" name="lnpw_tipping_button_icon2">Upload</button>
                    <button class="lnpw_tipping_button_remove_icon2" style="display:none">Remove image</button>
                    <input type="hidden" id="lnpw_tipping_icon2" class="lnpw_tipping_icon2" name="lnpw_tipping_icon2" value=<?php echo $icon2_id; ?> />
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-20">
                <label for="lnpw_default_price3">Default price3</label>
            </div>
            <div class="col-80">
                <input required type="number" min=0 placeholder="Default Price3" step=1 name="lnpw_default_price3" id="lnpw_default_price3" value="<?php echo $default_price3 ?>">

                <select required name="lnpw_default_currency3" id="lnpw_default_currency3">
                    <option disabled value="">Select currency</option>
                    <?php foreach ($supported_currencies as $currency) : ?>
                        <option <?php echo $default_currency3 === $currency ? 'selected' : ''; ?> value="<?php echo $currency; ?>">
                            <?php echo $currency; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <?php if ($icon3) : ?>
                    <button id="lnpw_tipping_button_icon3" class="lnpw_tipping_button_icon3" name="lnpw_tipping_button_icon3"><img src="<?php echo $icon3[0]; ?>" /></a></button>
                    <button class="lnpw_tipping_button_remove_icon3">Remove icon1</button>
                    <input type="hidden" id="lnpw_tipping_icon3" class="lnpw_tipping_icon3" name="lnpw_tipping_icon3" value=<?php echo $icon3_id; ?> />
                <?php else : ?>
                    <button id="lnpw_tipping_button_icon3" class="lnpw_tipping_button_icon3" name="lnpw_tipping_button_icon3">Upload</button>
                    <button class="lnpw_tipping_button_remove_icon3" style="display:none">Remove image</button>
                    <input type="hidden" id="lnpw_tipping_icon3" class="lnpw_tipping_icon3" name="lnpw_tipping_icon3" value=<?php echo $icon3_id; ?> />
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-20">
                <label for="lnpw_tipping_button_text_color">Button text color</label>
            </div>
            <div class="col-80">
                <input id="lnpw_tipping_button_text_color" class="lnpw_tipping_button_text_color" name="lnpw_tipping_button_text_color" type="text" value=<?php echo $btn_text_color; ?> />

            </div>
        </div>
        <div class="row">
            <div class="col-20">
                <label for="lnpw_tipping_currency">Currency</label>
            </div>
            <div class="col-80">
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
        <div class="row">
            <div class="col-20">
                <label for="lnpw_tipping_background">Background color</label>
            </div>
            <div class="col-80">
                <input id="lnpw_tipping_background" class="lnpw_tipping_background" name="lnpw_tipping_background" type="text" value=<?php echo $background_color; ?> />

            </div>
        </div>
        <div class="row">
            <div class="col-20">
                <label for="lnpw_tipping_button_color">Button color</label>
            </div>
            <div class="col-80">
                <input id="lnpw_tipping_button_color" class="lnpw_tipping_button_color" name="lnpw_tipping_button_color" type="text" value=<?php echo $btn_color; ?> />

            </div>
        </div>

        <div class="row">
            <div class="col-20">
                <label for="lnpw_tipping_collect">Collect further information?</label>
            </div>
            <div class="col-80">
                <input type="hidden" class="lnpw_tipping_collect" name="lnpw_tipping_collect" value='false' />
                <input type="checkbox" class="lnpw_tipping_collect" name="lnpw_tipping_collect" <?php echo $collect === 'true' ? 'checked' : ''; ?> value="true" />

            </div>
        </div>
        <div class="container_donor_information">
            <div class="row">
                <div class="col-20">
                    <label for="lnpw_tipping_collect_name">Full name</label>
                </div>
                <div class="col-80">
                    <input type="hidden" class="lnpw_tipping_collect_name" name="lnpw_tipping_collect_name" value='false' />
                    <input type="checkbox" class="lnpw_tipping_collect_name" name="lnpw_tipping_collect_name" <?php echo $name === 'true' ? 'checked' : ''; ?> value="true" />

                    <input type="hidden" class="lnpw_tipping_collect_name_mandatory" name="lnpw_tipping_collect_name_mandatory" value='false' />
                    <input type="checkbox" class="lnpw_tipping_collect_name_mandatory" name="lnpw_tipping_collect_name_mandatory" <?php echo $mandatory_name === 'true' ? 'checked' : ''; ?> value="true" />

                </div>
            </div>
            <div class="row">
                <div class="col-20">
                    <label for="lnpw_tipping_collect_email">Email</label>
                </div>
                <div class="col-80">
                    <input type="hidden" class="lnpw_tipping_collect_email" name="lnpw_tipping_collect_email" value='false' />
                    <input type="checkbox" class="lnpw_tipping_collect_email" name="lnpw_tipping_collect_email" <?php echo $email === 'true' ? 'checked' : ''; ?> value="true" />

                    <input type="hidden" class="lnpw_tipping_collect_email_mandatory" name="lnpw_tipping_collect_email_mandatory" value='false' />
                    <input type="checkbox" class="lnpw_tipping_collect_email_mandatory" name="lnpw_tipping_collect_email_mandatory" <?php echo $mandatory_email === 'true' ? 'checked' : ''; ?> value="true" />
                </div>
            </div>
            <div class="row">
                <div class="col-20">
                    <label for="lnpw_tipping_collect_address">Address</label>
                </div>
                <div class="col-80">
                    <input type="hidden" class="lnpw_tipping_collect_address" name="lnpw_tipping_collect_address" value='false' />
                    <input type="checkbox" class="lnpw_tipping_collect_address" name="lnpw_tipping_collect_address" <?php echo $address === 'true' ? 'checked' : ''; ?> value="true" />

                    <input type="hidden" class="lnpw_tipping_collect_address_mandatory" name="lnpw_tipping_collect_address_mandatory" value='false' />
                    <input type="checkbox" class="lnpw_tipping_collect_address_mandatory" name="lnpw_tipping_collect_address_mandatory" <?php echo $mandatory_address === 'true' ? 'checked' : ''; ?> value="true" />
                </div>
            </div>
            <div class="row">
                <div class="col-20">
                    <label for="lnpw_tipping_collect_phone">Phone number</label>
                </div>
                <div class="col-80">
                    <input type="hidden" class="lnpw_tipping_collect_phone" name="lnpw_tipping_collect_phone" value='false' />
                    <input type="checkbox" class="lnpw_tipping_collect_phone" name="lnpw_tipping_collect_phone" <?php echo $phone === 'true' ? 'checked' : ''; ?> value="true" />

                    <input type="hidden" class="lnpw_tipping_collect_phone_mandatory" name="lnpw_tipping_collect_phone_mandatory" value='false' />
                    <input type="checkbox" class="lnpw_tipping_collect_phone_mandatory" name="lnpw_tipping_collect_phone_mandatory" <?php echo $mandatory_phone === 'true' ? 'checked' : ''; ?> value="true" />
                </div>
            </div>
            <div class="row">
                <div class="col-20">
                    <label for="lnpw_tipping_collect_message">Message</label>
                </div>
                <div class="col-80">
                    <input type="hidden" class="lnpw_tipping_collect_message" name="lnpw_tipping_collect_message" value='false' />
                    <input type="checkbox" class="lnpw_tipping_collect_message" name="lnpw_tipping_collect_message" <?php echo $message === 'true' ? 'checked' : ''; ?> value="true" />

                    <input type="hidden" class="lnpw_tipping_collect_message_mandatory" name="lnpw_tipping_collect_message_mandatory" value='false' />
                    <input type="checkbox" class="lnpw_tipping_collect_message_mandatory" name="lnpw_tipping_collect_message_mandatory" <?php echo $mandatory_message === 'true' ? 'checked' : ''; ?> value="true" />
                </div>
            </div>
        </div>

        <div style="display: inline-block; margin-top: 25px;">
            <button class="button button-primary" type="submit">Save</button>
        </div>
    </form>
</div>