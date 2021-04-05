<?php
$lnpw_enabled        = get_post_meta($post->ID, 'lnpw_enabled', true);
$lnpw_price          = get_post_meta($post->ID, 'lnpw_price', true);
$lnpw_duration       = get_post_meta($post->ID, 'lnpw_duration', true);
$lnpw_duration_type  = get_post_meta($post->ID, 'lnpw_duration_type', true);
$supported_durations = Lightning_Paywall_Admin::DURATIONS;
$used_currency       = get_option('lnpw_currency');
$used_price          = get_option('lnpw_default_price');
$used_duration_type  = get_option('lnpw_default_duration_type');
$used_duration       = get_option('lnpw_default_duration');

?>

<div>

    <div style="margin-top: 25px;">
        <input type="checkbox" id="lnpw_enabled" name="lnpw_enabled" value="true" <?php echo $lnpw_enabled ? 'checked' : ''; ?>>
        <label for="lnpw_enabled">Enable Lightning Paywall</label>
    </div>

    <div style="margin-top: 25px;">
        <input type="number" min=0 placeholder="Price" step=<?php echo $used_currency === 'BTC' ? '0.00000001' : ($used_currency === 'SATS' ? '1' : '0.50'); ?> name="lnpw_price" value="<?php echo $lnpw_price ? $lnpw_price : '' ?>">
        <p>If field empty, used default value - <?php echo "{$used_price} {$used_currency}"; ?></p>
    </div>

    <div style="margin-top: 25px;">
        <input type="number" min=0 placeholder="Duration" name="lnpw_duration" value="<?php echo $lnpw_duration ? $lnpw_duration : '' ?>">
        <p>If field empty, used default value - <?= $used_duration; ?></p>

        <select name="lnpw_duration_type">
            <option disabled value="">Select duration type</option>
            <option value="">Default</option>
            <?php foreach ($supported_durations as $duration) : ?>
                <option <?php selected($lnpw_duration, $duration); ?> value="<?php echo $duration ?>"><?php echo $duration ?></option>
            <?php endforeach; ?>

        </select>
        <p>If field 'default', used default value - <?php echo $used_duration_type; ?></p>
    </div>

    <div>
        <div>
            <label for="checkout_view_title">View title</label>
        </div>
        <div>
            <textarea id="checkout_view_title" name=""><?php   ?></textarea>
        </div>
    </div>
    <div>
        <div>
            <label for="checkout_view_description">View description</label>
        </div>
        <div>
            <textarea id="checkout_view_description" name=""><?php   ?></textarea>
        </div>
    </div>

</div>