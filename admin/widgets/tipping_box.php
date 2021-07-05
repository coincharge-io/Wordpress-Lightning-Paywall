<?php

class Tipping_Box extends WP_Widget
{

    public function __construct()
    {

        parent::__construct(
            'tipping-box',  // Base ID
            'LNPW Tipping Box'   // Name
        );
    }



    public function widget($args, $instance)
    {

        echo do_shortcode("[lnpw_tipping_basic dimension='{$instance['dimension']}' title = '{$instance['title']}' description	= '{$instance['description']}'
        currency = '{$instance['currency']}'
        background_color = '{$instance['background_color']}'
        title_text_color = '{$instance['title_text_color']}'
        tipping_text = '{$instance['tipping_text']}'
        tipping_text_color = '{$instance['tipping_text_color']}'
        redirect = '{$instance['redirect']}'
        amount = '{$instance['redirect']}'
        description_color = '{$instance['description_color']}'
        button_text = '{$instance['button_text']}'
        button_text_color = '{$instance['button_text_color']}'
        button_color = '{$instance['button_color']}'
        logo_id = '{$instance['logo_id']}'
        background_id = '{$instance['background_id']}'
        display_name = '{$instance['display_name']}'
        mandatory_name = '{$instance['mandatory_name']}'
        display_email = '{$instance['display_email']}'
        mandatory_email = '{$instance['mandatory_email']}'
        display_address = '{$instance['display_address']}'
        mandatory_address = '{$instance['mandatory_address']}'
        display_phone = '{$instance['display_phone']}'
        mandatory_phone = '{$instance['mandatory_phone']}'
        display_message = '{$instance['display_message']}'
        mandatory_message = '{$instance['mandatory_message']}'
        widget = 'true']");
    }


    public function form($instance)
    {

        $dimensions = array('250x300', '300x300');
        $supported_currencies = Lightning_Paywall_Admin::TIPPING_CURRENCIES;
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Support my work', 'text_domain');

        $description = !empty($instance['description']) ? $instance['description'] : esc_html__('', 'text_domain');

        $dimension = !empty($instance['dimension']) ? $instance['dimension'] : esc_html__('250x300', 'text_domain');
        $currency = !empty($instance['currency']) ? $instance['currency'] : esc_html__('SATS', 'text_domain');

        $background_color = !empty($instance['background_color']) ? $instance['background_color'] : esc_html__('#E6E6E6', 'text_domain');
        $title_text_color = !empty($instance['title_text_color']) ? $instance['title_text_color'] : esc_html__('#000000', 'text_domain');

        $tipping_text = !empty($instance['tipping_text']) ? $instance['tipping_text'] : esc_html__('Enter Tipping Amount', 'text_domain');
        $tipping_text_color = !empty($instance['tipping_text_color']) ? $instance['tipping_text_color'] : esc_html__('#000000', 'text_domain');
        $redirect = !empty($instance['redirect']) ? $instance['redirect'] : esc_html__('', 'text_domain');
        $amount = !empty($instance['amount']) ? $instance['amount'] : esc_html__('', 'text_domain');
        $description_color = !empty($instance['description_color']) ? $instance['description_color'] : esc_html__('#000000', 'text_domain');

        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : esc_html__('Tipping now', 'text_domain');
        $button_text_color = !empty($instance['button_text_color']) ? $instance['button_text_color'] : esc_html__('#FFFFFF', 'text_domain');

        $button_color = !empty($instance['button_color']) ? $instance['button_color'] : esc_html__('#FE642E', 'text_domain');


        $logo_id = !empty($instance['logo_id']) ? $instance['logo_id'] : esc_html__('', 'text_domain');
        $background_id = !empty($instance['background_id']) ? $instance['background_id'] : esc_html__('', 'text_domain');

        $logo = wp_get_attachment_image_src($logo_id);
        $background = wp_get_attachment_image_src($background_id);

        $display_name = !empty($instance['display_name']) ? $instance['display_name'] : esc_html__('', 'text_domain');
        $mandatory_name = !empty($instance['mandatory_name']) ? $instance['mandatory_name'] : esc_html__('', 'text_domain');

        $display_email = !empty($instance['display_email']) ? $instance['display_email'] : esc_html__('', 'text_domain');
        $mandatory_email = !empty($instance['mandatory_email']) ? $instance['mandatory_email'] : esc_html__('', 'text_domain');

        $display_address = !empty($instance['display_address']) ? $instance['display_address'] : esc_html__('', 'text_domain');
        $mandatory_address = !empty($instance['mandatory_address']) ? $instance['mandatory_address'] : esc_html__('', 'text_domain');

        $display_phone = !empty($instance['display_phone']) ? $instance['display_phone'] : esc_html__('', 'text_domain');
        $mandatory_phone = !empty($instance['mandatory_phone']) ? $instance['mandatory_phone'] : esc_html__('', 'text_domain');

        $display_message = !empty($instance['display_message']) ? $instance['display_message'] : esc_html__('', 'text_domain');
        $mandatory_message = !empty($instance['mandatory_message']) ? $instance['mandatory_message'] : esc_html__('', 'text_domain');

?>
        <div class="tipping_box">
            <h1>Tipping</h1>
            <div class="row">
                <label for="<?php echo esc_attr($this->get_field_id('dimension')); ?>"><?php echo esc_html__('Dimension', 'text_domain'); ?></label>

                <select required id="<?php echo esc_attr($this->get_field_id('dimension')); ?>" name="<?php echo esc_attr($this->get_field_name('dimension')); ?>" type="text" value="<?php echo esc_attr($dimension); ?>">
                    <option disabled value=""><?php echo esc_html__('Select dimension:', 'text_domain'); ?></option>
                    <?php foreach ($dimensions as $dim) : ?>
                        <option <?php echo $dimension === $dim ? 'selected' : ''; ?> value="<?php echo $dim; ?>">
                            <?php echo $dim; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="row">
                <div class="col-50">
                    <label for="<?php echo esc_attr($this->get_field_id('background_image')); ?>"><?php echo esc_html__('Background image', 'text_domain'); ?></label>
                </div>
                <div class="col-50">
                    <?php if ($background) : ?>
                        <button id="lnpw_tipping_button_image_background" class="widget-tipping-basic-upload_box_image" name="lnpw_tipping_button_image_background"><img width="100" height="100" alt="Tipping box background" src="<?php echo $background[0]; ?>" /></a></button>
                        <button class="widget-tipping-basic-remove_box_image"> <?php echo esc_html__('Remove', 'text_domain'); ?></button>
                        <input type="hidden" class="widget-tipping-basic-background_id" id="<?php echo esc_attr($this->get_field_id('background_id')); ?>" name="<?php echo esc_attr($this->get_field_name('background_id')); ?>" type="text" value="<?php echo esc_attr($background_id); ?>" />
                    <?php else : ?>
                        <button id="lnpw_tipping_button_image_background" class="widget-tipping-basic-upload_box_image" name="lnpw_tipping_button_image_background"><?php echo esc_html__('Upload', 'text_domain'); ?></button>
                        <button class="widget-tipping-basic-remove_box_image" style="display:none"><?php echo esc_html__('Remove', 'text_domain'); ?></button>
                        <input type="hidden" class="widget-tipping-basic-background_id" id="<?php echo esc_attr($this->get_field_id('background_id')); ?>" name="<?php echo esc_attr($this->get_field_name('background_id')); ?>" type="text" value="<?php echo esc_attr($background_id); ?>" />
                    <?php endif; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-50">
                    <label for="<?php echo esc_attr($this->get_field_id('background_color')); ?>"><?php echo esc_html__('Background color', 'text_domain'); ?></label>
                </div>
                <div class="col-50">
                    <input id="<?php echo esc_attr($this->get_field_id('background_color')); ?>" class="widget-tipping-basic-background_color" name="<?php echo esc_attr($this->get_field_name('background_color')); ?>" type="text" value="<?php echo esc_attr($background_color); ?>" type="text" />
                </div>
            </div>
            <h3><?php echo esc_html__('Description', 'text_domain'); ?></h3>

            <div class="row">
                <div class="col-50">
                    <label for="<?php echo esc_attr($this->get_field_id('logo')); ?>"><?php echo esc_html__('Tipping logo', 'text_domain'); ?></label>
                </div>
                <div class="col-50">
                    <?php if ($logo_id) : ?>
                        <button id="lnpw_tipping_button_image" class="widget-tipping-basic-upload_box_logo" name="lnpw_tipping_button_image"><img width="100" height="100" alt="Tipping box logo" src="<?php echo $logo[0]; ?>" /></a></button>
                        <button class="widget-tipping-basic-remove_box_image"><?php echo esc_html__('Remove', 'text_domain'); ?></button>
                        <input type="hidden" class="widget-tipping-basic-logo_id" id="<?php echo esc_attr($this->get_field_id('logo_id')); ?>" name="<?php echo esc_attr($this->get_field_name('logo_id')); ?>" type="text" value="<?php echo esc_attr($logo_id); ?>" />
                    <?php else : ?>
                        <button id="lnpw_tipping_button_image" class="widget-tipping-basic-upload_box_logo" name="lnpw_tipping_button_image"><?php echo esc_html__('Upload', 'text_domain'); ?></button>
                        <button class="widget-tipping-basic-remove_box_image" style="display:none"><?php echo esc_html__('Remove', 'text_domain'); ?></button>
                        <input type="hidden" class="widget-tipping-basic-logo_id" id="<?php echo esc_attr($this->get_field_id('logo_id')); ?>" name="<?php echo esc_attr($this->get_field_name('logo_id')); ?>" type="text" value="<?php echo esc_attr($logo_id); ?>" />
                    <?php endif; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-50">
                    <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo esc_html__('Title:', 'text_domain'); ?></label>
                    <textarea id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"><?php echo esc_attr($title); ?></textarea>
                </div>
                <div class="col-50">
                    <label for="<?php echo esc_attr($this->get_field_id('title_text_color')); ?>"><?php echo esc_html__('Title text color', 'text_domain'); ?></label>
                    <input id="<?php echo esc_attr($this->get_field_id('title_text_color')); ?>" name="<?php echo esc_attr($this->get_field_name('title_text_color')); ?>" class="widget-tipping-basic-title_text_color" type="text" value="<?php echo esc_attr($title_text_color); ?>" />
                </div>
            </div>
            <div class="row">
                <div class="col-50">
                    <label for="<?php echo esc_attr($this->get_field_id('description')); ?>"><?php echo esc_html__('Description:', 'text_domain'); ?></label>
                    <textarea id="<?php echo esc_attr($this->get_field_id('description')); ?>" name="<?php echo esc_attr($this->get_field_name('description')); ?>" type="text"><?php echo esc_attr($description); ?></textarea>
                </div>
                <div class="col-50">
                    <label for="<?php echo esc_attr($this->get_field_id('description_color')); ?>"><?php echo esc_html__('Description text color:', 'text_domain'); ?></label>
                    <input id="<?php echo esc_attr($this->get_field_id('description_color')); ?>" name="<?php echo esc_attr($this->get_field_name('description_color')); ?>" class="widget-tipping-basic-description-color" type="text" value="<?php echo esc_attr($description_color); ?>" />
                </div>
            </div>
            <div class="row">
                <div class="col-50">
                    <label for="<?php echo esc_attr($this->get_field_id('tipping_text')); ?>"><?php echo esc_html__('Tipping text', 'text_domain'); ?></label>
                    <textarea id="<?php echo esc_attr($this->get_field_id('tipping_text')); ?>" name="<?php echo esc_attr($this->get_field_name('tipping_text')); ?>" type="text" value="<?php echo esc_attr($tipping_text); ?>"></textarea>
                </div>
                <div class="col-50">
                    <label for="<?php echo esc_attr($this->get_field_id('tipping_text_color')); ?>"><?php echo esc_html__('Tipping text color', 'text_domain'); ?></label>
                    <input id="<?php echo esc_attr($this->get_field_id('tipping_text_color')); ?>" name="<?php echo esc_attr($this->get_field_name('tipping_text_color')); ?>" type="text" class="widget-tipping-basic-tipping-color" value="<?php echo esc_attr($tipping_text_color); ?>" />
                </div>
            </div>
            <div class="row">
                <div class="col-50">
                    <label for="<?php echo esc_attr($this->get_field_id('redirect')); ?>"><?php echo esc_html__('Link to Thank you page', 'text_domain'); ?></label>

                    <input id="<?php echo esc_attr($this->get_field_id('redirect')); ?>" name="<?php echo esc_attr($this->get_field_name('redirect')); ?>" class="widget-tipping-basic_redirect" type="text" value="<?php echo esc_attr($redirect); ?>" />
                </div>
            </div>

            <div class="row">
                <div class="col-50">
                    <label for="<?php echo esc_attr($this->get_field_id('currency')); ?>"><?php echo esc_html__('Currency', 'text_domain'); ?></label>
                    <select required id="<?php echo esc_attr($this->get_field_id('currency')); ?>" name="<?php echo esc_attr($this->get_field_name('currency')); ?>" value="<?php echo esc_attr($title); ?>">>
                        <option disabled value=""><?php echo esc_html__('Select currency', 'text_domain'); ?></option>
                        <?php foreach ($supported_currencies as $curr) : ?>
                            <option <?php echo $currency === $curr ? 'selected' : ''; ?> value="<?php echo $curr; ?>">
                                <?php echo $curr; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <h3><?php echo esc_html__('Button', 'text_domain'); ?></h3>
            <div class="row">
                <div class="col-50">
                    <label for="<?php echo esc_attr($this->get_field_id('button_text')); ?>"><?php echo esc_html__('Button text', 'text_domain'); ?></label>

                    <textarea id="<?php echo esc_attr($this->get_field_id('button_text')); ?>" name="<?php echo esc_attr($this->get_field_name('button_text')); ?>" type="text" value="<?php echo esc_attr($button_text); ?>"><?php echo esc_attr($button_text); ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-50">
                    <label for="<?php echo esc_attr($this->get_field_id('button_text_color')); ?>"><?php echo esc_html__('Button text color', 'text_domain'); ?></label>
                </div>
                <div class="col-50">
                    <input id="<?php echo esc_attr($this->get_field_id('button_text_color')); ?>" class="widget-tipping-basic-button_text_color" name="<?php echo esc_attr($this->get_field_name('button_text_color')); ?>" type="text" value="<?php echo esc_attr($button_text_color); ?>" />

                </div>
            </div>


            <div class="row">
                <label for="<?php echo esc_attr($this->get_field_id('button_color')); ?>"><?php echo esc_html__('Button color', 'text_domain'); ?></label>

                <input id="<?php echo esc_attr($this->get_field_id('button_color')); ?>" class="widget-tipping-basic-button_color" name="<?php echo esc_attr($this->get_field_name('button_color')); ?>" type="text" value="<?php echo esc_attr($button_color); ?>" />

            </div>
            <h4><?php echo esc_html__('Collect further information', 'text_domain'); ?></h4>
            <div class="row">
                <div class="col-50">
                    <p><?php echo esc_html__('Full name', 'text_domain'); ?></p>
                </div>
                <div class="col-50 col-2">
                    <div>
                        <label for="<?php echo esc_attr($this->get_field_id('display_name')); ?>"><?php echo esc_html__('Display', 'text_domain'); ?></label>

                        <input id="<?php echo esc_attr($this->get_field_id('display_name')); ?>" name="<?php echo esc_attr($this->get_field_name('display_name')); ?>" type="checkbox" <?php echo $display_name === 'true' ? 'checked' : ''; ?> value="true" />
                    </div>
                    <div>
                        <label for="<?php echo esc_attr($this->get_field_id('mandatory_name')); ?>"><?php echo esc_html__('Mandatory', 'text_domain'); ?></label>
                        <input id="<?php echo esc_attr($this->get_field_id('mandatory_name')); ?>" name="<?php echo esc_attr($this->get_field_name('mandatory_name')); ?>" type="checkbox" <?php echo $mandatory_name === 'true' ? 'checked' : ''; ?> value="true" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-50">
                    <p><?php echo esc_html__('Email', 'text_domain'); ?></p>
                </div>
                <div class="col-50 col-2">
                    <div>
                        <label for="<?php echo esc_attr($this->get_field_id('display_email')); ?>"><?php echo esc_html__('Display', 'text_domain'); ?></label>

                        <input id="<?php echo esc_attr($this->get_field_id('display_email')); ?>" name="<?php echo esc_attr($this->get_field_name('display_email')); ?>" type="checkbox" <?php echo $display_email === 'true' ? 'checked' : ''; ?> value="true" />
                    </div>
                    <div>
                        <label for="<?php echo esc_attr($this->get_field_id('mandatory_email')); ?>"><?php echo esc_html__('Mandatory', 'text_domain'); ?></label>
                        <input id="<?php echo esc_attr($this->get_field_id('mandatory_email')); ?>" name="<?php echo esc_attr($this->get_field_name('mandatory_email')); ?>" type="checkbox" <?php echo $mandatory_email === 'true' ? 'checked' : ''; ?> value="true" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-50">
                    <p><?php echo esc_html__('Address', 'text_domain'); ?></p>
                </div>
                <div class="col-50 col-2">
                    <div>
                        <label for="<?php echo esc_attr($this->get_field_id('display_address')); ?>"><?php echo esc_html__('Display', 'text_domain'); ?></label>

                        <input id="<?php echo esc_attr($this->get_field_id('display_address')); ?>" name="<?php echo esc_attr($this->get_field_name('display_address')); ?>" type="checkbox" <?php echo $display_address === 'true' ? 'checked' : ''; ?> value="true" />
                    </div>
                    <div>
                        <label for="<?php echo esc_attr($this->get_field_id('mandatory_address')); ?>"><?php echo esc_html__('Mandatory', 'text_domain'); ?></label>
                        <input id="<?php echo esc_attr($this->get_field_id('mandatory_address')); ?>" name="<?php echo esc_attr($this->get_field_name('mandatory_address')); ?>" type="checkbox" <?php echo $mandatory_address === 'true' ? 'checked' : ''; ?> value="true" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-50">
                    <p><?php echo esc_html__('Phone number', 'text_domain'); ?></p>
                </div>
                <div class="col-50 col-2">
                    <div>
                        <label for="<?php echo esc_attr($this->get_field_id('display_phone')); ?>"><?php echo esc_html__('Display', 'text_domain'); ?></label>

                        <input id="<?php echo esc_attr($this->get_field_id('display_phone')); ?>" name="<?php echo esc_attr($this->get_field_name('display_phone')); ?>" type="checkbox" <?php echo $display_phone === 'true' ? 'checked' : ''; ?> value="true" />
                    </div>
                    <div>
                        <label for="<?php echo esc_attr($this->get_field_id('mandatory_phone')); ?>"><?php echo esc_html__('Mandatory', 'text_domain'); ?></label>
                        <input id="<?php echo esc_attr($this->get_field_id('mandatory_phone')); ?>" name="<?php echo esc_attr($this->get_field_name('mandatory_phone')); ?>" type="checkbox" <?php echo $mandatory_phone === 'true' ? 'checked' : ''; ?> value="true" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-50">
                    <p><?php echo esc_html__('Message', 'text_domain'); ?></p>
                </div>
                <div class="col-50 col-2">
                    <div>
                        <label for="<?php echo esc_attr($this->get_field_id('display_message')); ?>"><?php echo esc_html__('Display', 'text_domain'); ?></label>

                        <input id="<?php echo esc_attr($this->get_field_id('display_message')); ?>" name="<?php echo esc_attr($this->get_field_name('display_message')); ?>" type="checkbox" <?php echo $display_message === 'true' ? 'checked' : ''; ?> value="true" />
                    </div>
                    <div>
                        <label for="<?php echo esc_attr($this->get_field_id('mandatory_message')); ?>"><?php echo esc_html__('Mandatory', 'text_domain'); ?></label>
                        <input id="<?php echo esc_attr($this->get_field_id('mandatory_message')); ?>" name="<?php echo esc_attr($this->get_field_name('mandatory_message')); ?>" type="checkbox" value="true" <?php echo $mandatory_message === 'true' ? 'checked' : ''; ?> />
                    </div>
                </div>
            </div>
        </div>
<?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();

        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['dimension'] = (!empty($new_instance['dimension'])) ? $new_instance['dimension'] : '';

        $instance['description'] = !empty($new_instance['description']) ? wp_strip_all_tags($new_instance['description']) : '';

        $instance['currency'] = !empty($new_instance['currency']) ? $new_instance['currency'] : '';

        $instance['background_color'] = !empty($new_instance['background_color']) ? wp_strip_all_tags($new_instance['background_color']) : '';
        $instance['title_text_color'] = !empty($new_instance['title_text_color']) ? wp_strip_all_tags($new_instance['title_text_color']) : '';
        $instance['tipping_text'] = !empty($new_instance['tipping_text']) ? wp_strip_all_tags($new_instance['tipping_text']) : '';
        $instance['tipping_text_color'] = !empty($new_instance['tipping_text_color']) ? wp_strip_all_tags($new_instance['tipping_text_color']) : '';
        $instance['tipping_color'] = !empty($new_instance['tipping_color']) ? wp_strip_all_tags($new_instance['tipping_color']) : '';
        $instance['redirect'] = !empty($new_instance['redirect']) ? wp_strip_all_tags($new_instance['redirect']) : '';
        $instance['amount'] = !empty($new_instance['amount']) ? wp_strip_all_tags($new_instance['amount']) : '';
        $instance['description_color'] = !empty($new_instance['description_color']) ? wp_strip_all_tags($new_instance['description_color']) : '';

        $instance['button_text'] = !empty($new_instance['button_text']) ? wp_strip_all_tags($new_instance['button_text']) : '';
        $instance['button_text_color'] = !empty($new_instance['button_text_color']) ? wp_strip_all_tags($new_instance['button_text_color']) : '';

        $instance['button_color'] = !empty($new_instance['button_color']) ? wp_strip_all_tags($new_instance['button_color']) : '';

        $instance['logo_id'] = !empty($new_instance['logo_id']) ? $new_instance['logo_id'] : '';
        $instance['background_id'] = !empty($new_instance['background_id']) ? $new_instance['background_id'] : '';

        $instance['display_name'] = !empty($new_instance['display_name']) ? $new_instance['display_name'] : 'false';
        $instance['mandatory_name'] = !empty($new_instance['mandatory_name']) ? $new_instance['mandatory_name'] : 'false';

        $instance['display_email'] = !empty($new_instance['display_email']) ? $new_instance['display_email'] : 'false';
        $instance['mandatory_email'] = !empty($new_instance['mandatory_email']) ? $new_instance['mandatory_email'] : 'false';

        $instance['display_address'] = !empty($new_instance['display_address']) ? $new_instance['display_address'] : 'false';
        $instance['mandatory_address'] = !empty($new_instance['mandatory_address']) ? $new_instance['mandatory_address'] : 'false';

        $instance['display_phone'] = !empty($new_instance['display_phone']) ? $new_instance['display_phone'] : 'false';
        $instance['mandatory_phone'] = !empty($new_instance['mandatory_phone']) ? $new_instance['mandatory_phone'] : 'false';

        $instance['display_message'] = !empty($new_instance['display_message']) ? $new_instance['display_message'] : 'false';
        $instance['mandatory_message'] = !empty($new_instance['mandatory_message']) ? $new_instance['mandatory_message'] : 'false';

        return $instance;
    }
}
$my_widget = new Tipping_Box();
?>