<?php

$used_currency          = get_option('lnpw_default_currency');
$supported_currencies   = Lightning_Paywall_Admin::CURRENCIES;
$default_price          = get_option('lnpw_default_price');
$default_duration       = get_option('lnpw_default_duration');
$default_duration_type  = get_option('lnpw_default_duration_type');
$default_text           = get_option('lnpw_default_payblock_text');
$default_button         = get_option('lnpw_default_payblock_button');
$default_info           = get_option('lnpw_default_payblock_info');
$supported_durations    = Lightning_Paywall_Admin::DURATIONS;
$used_format            = get_option("lnpw_default_btc_format");
$supported_btc_format   = Lightning_Paywall_Admin::BTC_FORMAT;
$btcpay_server_url      = get_option('lnpw_btcpay_server_url');
$btcpay_auth_key_view   = get_option('lnpw_btcpay_auth_key_view');
$btcpay_auth_key_create = get_option('lnpw_btcpay_auth_key_create');
?>

<div class="container">
    <h1>Lightning Paywall Settings</h1>

    <div style="margin-top: 25px;">

        <form method="POST" action="options.php">
            <?php settings_fields('lnpw_general_settings'); ?>

            <div>
                <h2>Payment Block</h2>
                <div class="row">
                    <div class="col-20">
                        <label for="checkout_title">Checkout title</label>
                    </div>
                    <div class="col-80">
                        <textarea id="checkout_title" name="lnpw_default_payblock_text"><?php echo $default_text; ?></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-20">
                        <label for="checkout_button">Checkout button text</label>
                    </div>
                    <div class="col-80">
                        <textarea id="checkout_button" name="lnpw_default_payblock_button"><?php echo $default_button; ?></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-20">
                        <label for="checkout_info">Checkout price info</label>
                    </div>
                    <div class="col-80">
                        <textarea id="checkout_info" name="lnpw_default_payblock_info"><?php echo $default_info; ?></textarea>
                    </div>
                </div>
            </div>
            <div>
                <h2>Price & Duration</h2>
                <div class="row">
                    <div class="col-20">
                        <label for="lnpw_default_price">Default price</label>
                    </div>
                    <div class="col-80">
                        <input required type="number" min=0 placeholder="Default Price" step=1 name="lnpw_default_price" id="lnpw_default_price" value="<?php echo $default_price ?>">

                        <select required name="lnpw_default_currency" id="lnpw_default_currency">
                            <option disabled value="">Select currency</option>
                            <?php foreach ($supported_currencies as $currency) : ?>
                                <option <?php echo $used_currency === $currency ? 'selected' : ''; ?> value="<?php echo $currency; ?>">
                                    <?php echo $currency; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($used_currency === 'SATS') : ?>
                            <div class="lnpw_price_format">
                                <p>Select Bitcoin price display:</p>
                                <?php foreach ($supported_btc_format as $format) : ?>
                                    <div>
                                        <input type="radio" id="lnpw_default_btc_format" name="lnpw_default_btc_format" value="<?php echo $format ?>" <?php echo $used_format === $format ? 'checked' : '' ?>>
                                        <label for="lnpw_default_btc_format"><?php echo $format ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <div style="display:none;" class="lnpw_price_format">
                                <p>Select Bitcoin price display:</p>
                                <?php foreach ($supported_btc_format as $format) : ?>
                                    <div>
                                        <input type="radio" id="lnpw_default_btc_format" name="lnpw_default_btc_format" value="<?php echo $format ?>" <?php echo $used_format === $format ? 'checked' : '' ?>>
                                        <label for="lnpw_default_btc_format"><?php echo $format ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-20">
                        <label for="lnpw_default_duration">Default duration</label>
                    </div>
                    <div class="col-80">
                        <input type="number" min="1" placeholder="Default Access Duration" name="lnpw_default_duration" id="lnpw_default_duration" disabled value="<?php echo $default_duration ?>">

                        <select required name="lnpw_default_duration_type" id="lnpw_default_duration_type">
                            <option disabled value="">Select duration type</option>
                            <?php foreach ($supported_durations as $duration) : ?>
                                <option <?php echo $default_duration_type === $duration ? 'selected' : ''; ?> value="<?php echo $duration; ?>">
                                    <?php echo $duration; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div>
                <h2>BTCPay Server</h2>

                <div class="row">
                    <div class="col-20">
                        <label>BTCPay Server Url</label>
                    </div>
                    <div class="col-80">
                        <input type="url" placeholder="BTCPay Server Url" name="lnpw_btcpay_server_url" id="lnpw_btcpay_server_url" value="<?php echo $btcpay_server_url ?>" style="min-width: 335px;">
                        <div class="lnpw_generate_api">Generate API keys:<a href="" class="lnpw_auth_key" target="_blank"></a></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-20">
                        <label for="lnpw_btcpay_auth_key_view">BTCPay Server API Key View</label>
                    </div>
                    <div class="col-80">
                        <input required type="text" placeholder="Auth Key View" name="lnpw_btcpay_auth_key_view" id="lnpw_btcpay_auth_key_view" value="<?php echo $btcpay_auth_key_view ?>" style="min-width: 500px;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-20">
                        <label for="lnpw_btcpay_auth_key_create">BTCPay Server API Key Create</label>
                    </div>
                    <div class="col-80">
                        <input required type="text" placeholder="Auth Key Create" name="lnpw_btcpay_auth_key_create" id="lnpw_btcpay_auth_key_create" value="<?php echo $btcpay_auth_key_create ?>" style="min-width: 500px;">
                    </div>
                </div>
            </div>
    </div>
</div>
<div style="margin-top: 10px;">
    <a href="https://lightning-paywall.coincharge.io/set-up-wordpress-lightning-paywall/" target="_blank">Help</a>
</div>
<p id="lnpw_btcpay_status_success" class="lnpw_btcpay_status" style="color: green;">
    BTCPAY SERVER CONNECTED
</p>
<p id="lnpw_btcpay_status_error" class="lnpw_btcpay_status" style="color: red;"></p>
<div style="display: inline-block; margin-top: 25px;">
    <button class="button button-primary" type="submit">Save</button>
    <button id="lnpw_btcpay_check_status" class="button button-secondary" type="button">Check BTCPay Server Status</button>
</div>
</form>

</div>

</div>