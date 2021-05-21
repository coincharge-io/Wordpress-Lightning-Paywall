<?php
$supported_currencies   = Lightning_Paywall_Admin::CURRENCIES;
array_push($supported_currencies, 'BTC');
$dimensions = ['250x250', '300x250', '240x400'];
$used_currency  = get_option('lnpw_tipping_currency');
$used_dimension      = get_option('lnpw_tipping_dimension');
$title = get_option('lnpw_tipping_title');
$description = get_option('lnpw_tipping_description');
$btn_text = get_option('lnpw_tipping_button_text');
$btn_text_color = get_option('lnpw_tipping_button_text_color');
$btn_color = get_option('lnpw_tipping_button_color');
$background_color = get_option('lnpw_tipping_background');
$banner = get_option('lnpw_tipping_banner');
$collect = get_option('lnpw_tipping_collect');
?>


<div>
    <h1>Tipping</h1>
    <form method="POST" action="options.php">
        <?php settings_fields('lnpw_general_settings'); ?>
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
                <label for="lnpw_tipping_button_text">Button text</label>
            </div>
            <div class="col-80">
                <textarea id="lnpw_tipping_button_text" name="lnpw_tipping_button_text"><?php echo $btn_text; ?></textarea>
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
                <label for="lnpw_tipping_banner">Banner</label>
            </div>
            <div class="col-80">
                <input type="text" id="lnpw_tipping_banner" class="lnpw_tipping_banner" name="lnpw_tipping_banner" value=<?php echo $banner; ?> />
                <button id="lnpw_tipping_button_banner" class="lnpw_tipping_button_banner" name="lnpw_tipping_button_banner">Upload</button>

            </div>
        </div>
        <div class="row">
            <div class="col-20">
                <label for="lnpw_tipping_collect">Do you want to collect donor information?</label>
            </div>
            <div class="col-80">
                <input type="hidden" id="lnpw_tipping_collect" class="lnpw_tipping_collect" name="lnpw_tipping_collect" value='false' />
                <input type="checkbox" id="lnpw_tipping_collect" class="lnpw_tipping_collect" name="lnpw_tipping_collect" <?php echo $collect === 'true' ? 'checked' : ''; ?> value="true" />

            </div>
        </div>
        <?php if ($collect === 'true') : ?>
            <div class="container-donor-information">
                <div class="row">
                    <div class="col-20">
                        <label for="lnpw_tipping_collect_name">Full name</label>
                    </div>
                    <div class="col-80">
                        <input type="hidden" id="lnpw_tipping_collect_name" class="lnpw_tipping_collect_name" name="lnpw_tipping_collect_name" value='false' />
                        <input type="checkbox" id="lnpw_tipping_collect_name" class="lnpw_tipping_collect_name" name="lnpw_tipping_collect_name" <?php echo $collect_name === 'true' ? 'checked' : ''; ?> value="true" />

                    </div>
                </div>
                <div class="row">
                    <div class="col-20">
                        <label for="lnpw_tipping_collect_email">Email</label>
                    </div>
                    <div class="col-80">
                        <input type="hidden" id="lnpw_tipping_collect_email" class="lnpw_tipping_collect_email" name="lnpw_tipping_collect_email" value='false' />
                        <input type="checkbox" id="lnpw_tipping_collect_email" class="lnpw_tipping_collect_email" name="lnpw_tipping_collect_email" <?php echo $collect_name === 'true' ? 'checked' : ''; ?> value="true" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-20">
                        <label for="lnpw_tipping_collect_address">Address</label>
                    </div>
                    <div class="col-80">
                        <input type="hidden" id="lnpw_tipping_collect_address" class="lnpw_tipping_collect_address" name="lnpw_tipping_collect_address" value='false' />
                        <input type="checkbox" id="lnpw_tipping_collect_address" class="lnpw_tipping_collect_address" name="lnpw_tipping_collect_address" <?php echo $collect_name === 'true' ? 'checked' : ''; ?> value="true" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-20">
                        <label for="lnpw_tipping_collect_phone">Phone number</label>
                    </div>
                    <div class="col-80">
                        <input type="hidden" id="lnpw_tipping_collect_phone" class="lnpw_tipping_collect_phone" name="lnpw_tipping_collect_phone" value='false' />
                        <input type="checkbox" id="lnpw_tipping_collect_phone" class="lnpw_tipping_collect_phone" name="lnpw_tipping_collect_phone" <?php echo $collect_name === 'true' ? 'checked' : ''; ?> value="true" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-20">
                        <label for="lnpw_tipping_collect_message">Message</label>
                    </div>
                    <div class="col-80">
                        <input type="hidden" id="lnpw_tipping_collect_message" class="lnpw_tipping_collect_message" name="lnpw_tipping_collect_message" value='false' />
                        <input type="checkbox" id="lnpw_tipping_collect_message" class="lnpw_tipping_collect_message" name="lnpw_tipping_collect_message" <?php echo $collect_name === 'true' ? 'checked' : ''; ?> value="true" />
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div style="display: inline-block; margin-top: 25px;">
            <button class="button button-primary" type="submit">Save</button>
        </div>
    </form>
</div>