<?php
$supported_currencies   = Lightning_Paywall_Admin::TIPPING_CURRENCIES;
$dimensions =  ['250x300', '300x300'];
$used_currency  = get_option('lnpw_tipping_box_currency');
$used_dimension      = get_option('lnpw_tipping_box_dimension');
$redirect = get_option('lnpw_tipping_box_redirect');
$text = get_option('lnpw_tipping_box_text');
$color = get_option('lnpw_tipping_box_color');
$image = get_option('lnpw_tipping_box_image');
$logo = wp_get_attachment_image_src($image['logo']);
$background = wp_get_attachment_image_src($image['background']);
?>


<div class="tipping_box_settings">
    <form method="POST" action="options.php">
        <?php settings_fields('lnpw_tipping_box_settings'); ?>
        <div class="row">
            <div class="col-50">
                <label for="lnpw_tipping_box_dimension">Dimension</label>
            </div>
            <div class="col-50">
                <select required name="lnpw_tipping_box_dimension" id="lnpw_tipping_box_dimension">
                    <option disabled value="">Select dimension</option>
                    <?php foreach ($dimensions as $dimension) : ?>
                    <option <?php echo $used_dimension === $dimension ? 'selected' : ''; ?>
                        value="<?php echo $dimension; ?>">
                        <?php echo $dimension; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-50">
                <label for="lnpw_tipping_box_image_background">Background image</label>
            </div>
            <div class="col-50">
                <?php if ($background) : ?>
                <button id="lnpw_tipping_box_button_image_background" class="lnpw_tipping_box_button_image_background"
                    name="lnpw_tipping_box_button_image_background"><img
                        src="<?php echo $background[0]; ?>" /></a></button>
                <button class="lnpw_tipping_box_button_remove_background">Remove image</button>
                <input type="hidden" id="lnpw_tipping_box_image_background" class="lnpw_tipping_box_image_background"
                    name="lnpw_tipping_box_image[background]" value=<?php echo $image['background']; ?> />
                <?php else : ?>
                <button id="lnpw_tipping_box_button_image_background" class="lnpw_tipping_box_button_image_background"
                    name="lnpw_tipping_box_button_image_background">Upload </button>
                <button class="lnpw_tipping_box_button_remove_background" style="display:none">Remove image</button>
                <input type="hidden" id="lnpw_tipping_box_image_background" class="lnpw_tipping_box_image_background"
                    name="lnpw_tipping_box_image[background]" value=<?php echo $image['background']; ?> />
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-50">
                <label for="lnpw_tipping_box_background">Background color</label>
            </div>
            <div class="col-50">
                <input id="lnpw_tipping_box_background" class="lnpw_tipping_box_background"
                    name="lnpw_tipping_box_color[background]" type="text" value=<?php echo $color['background']; ?> />
            </div>
        </div>
        <h3>Description</h3>

        <div class="row">
            <div class="col-50">
                <label for="lnpw_tipping_box_image">Tipping Logo</label>
            </div>
            <div class="col-50">
                <?php if ($logo) : ?>
                <button id="lnpw_tipping_box_button_image" class="lnpw_tipping_box_button_image"
                    name="lnpw_tipping_box_button_image"><img src="<?php echo $logo[0]; ?>" /></a></button>
                <button class="lnpw_tipping_box_button_remove">Remove image</button>
                <input type="hidden" id="lnpw_tipping_box_image" class="lnpw_tipping_box_image"
                    name="lnpw_tipping_box_image[logo]" value=<?php echo $image['logo']; ?> />
                <?php else : ?>
                <button id="lnpw_tipping_box_button_image" class="lnpw_tipping_box_button_image"
                    name="lnpw_tipping_box_button_image">Upload</button>
                <button class="lnpw_tipping_box_button_remove" style="display:none">Remove image</button>
                <input type="hidden" id="lnpw_tipping_box_image" class="lnpw_tipping_box_image"
                    name="lnpw_tipping_box_image[logo]" value=<?php echo $image['logo']; ?> />
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-50">
                <label for="lnpw_tipping_box_title">Title</label>
                <textarea id="lnpw_tipping_box_title"
                    name="lnpw_tipping_box_text[title]"><?php echo $text['title']; ?></textarea>
            </div>
            <div class="col-50">
                <label for="lnpw_tipping_box_title_color">Title text color</label>
                <input id="lnpw_tipping_box_title_color" class="lnpw_tipping_box_title_color"
                    name="lnpw_tipping_box_color[title]" type="text" value=<?php echo $color['title']; ?> />
            </div>
        </div>
        <div class="row">
            <div class="col-50">
                <label for="lnpw_tipping_box_description">Description</label>
                <textarea id="lnpw_tipping_box_description"
                    name="lnpw_tipping_box_text[description]"><?php echo $text['description']; ?></textarea>
            </div>
            <div class="col-50">
                <label for="lnpw_tipping_box_description_color">Description text color</label>
                <input id="lnpw_tipping_box_description_color" class="lnpw_tipping_box_description_color"
                    name="lnpw_tipping_box_color[description]" type="text" value=<?php echo $color['description']; ?> />
            </div>
        </div>
        <div class="row">
            <div class="col-50">
                <label for="lnpw_tipping_box_text">Tipping text</label>
                <textarea id="lnpw_tipping_box_text"
                    name="lnpw_tipping_box_text[info]"><?php echo $text['info']; ?></textarea>
            </div>
            <div class="col-50">
                <label for="lnpw_tipping_box_tipping_box_color">Tipping text color</label>
                <input id="lnpw_tipping_box_tipping_box_color" class="lnpw_tipping_box_tipping_box_color"
                    name="lnpw_tipping_box_color[tipping]" type="text" value=<?php echo $color['tipping']; ?> />
            </div>
        </div>
        <div class="row">
            <div class="col-50">
                <label for="lnpw_tipping_box_redirect">Link to Thank you Page</label>
            </div>
            <div class="col-50">
                <input id="lnpw_tipping_box_redirect" name="lnpw_tipping_box_redirect" value=<?php echo $redirect; ?> />
            </div>
        </div>
        <h3>Button</h3>
        <div class="row">
            <div class="col-50">
                <label for="lnpw_tipping_box_button_text">Button text</label>
            </div>
            <div class="col-50">
                <textarea id="lnpw_tipping_box_button_text"
                    name="lnpw_tipping_box_text[button]"><?php echo $text['button']; ?></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-50">
                <label for="lnpw_tipping_box_button_text_color">Button text color</label>
            </div>
            <div class="col-50">
                <input id="lnpw_tipping_box_button_text_color" class="lnpw_tipping_box_button_text_color"
                    name="lnpw_tipping_box_color[button_text]" type="text" value=<?php echo $color['button_text']; ?> />

            </div>
        </div>


        <div class="row">
            <div class="col-50">
                <label for="lnpw_tipping_box_button_color">Button color</label>
            </div>
            <div class="col-50">
                <input id="lnpw_tipping_box_button_color" class="lnpw_tipping_box_button_color"
                    name="lnpw_tipping_box_color[button]" type="text" value=<?php echo $color['button']; ?> />

            </div>
        </div>

        <div style="display: inline-block; margin-top: 25px;">
            <button class="button button-primary" type="submit">Save</button>
        </div>
    </form>
</div>