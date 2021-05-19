<?php
$supported_currencies   = Lightning_Paywall_Admin::CURRENCIES;
array_push($supported_currencies, 'BTC');
$used_currency          = get_option('lnpw_tipping_currency');

//var_dump($supported_currencies);
?>


<div>
    <h1>tipping</h1>
    <div class="row">
        <div class="col-20">
            <label for="lnpw_tipping_title">Title</label>
        </div>
        <div class="col-80">
            <textarea id="lnpw_tipping_title" name="lnpw_tipping_title"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="col-20">
            <label for="lnpw_tipping_description">Description</label>
        </div>
        <div class="col-80">
            <textarea id="lnpw_tipping_description" name="lnpw_tipping_description"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="col-20">
            <label for="lnpw_tipping_button_text">Button text</label>
        </div>
        <div class="col-80">
            <textarea id="lnpw_tipping_button_text" name="lnpw_tipping_button_text"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="col-20">
            <label for="lnpw_tipping_currency">Currency</label>
        </div>
        <select required name="lnpw_tipping_currency" id="lnpw_tipping_currency">
            <option disabled value="">Select currency</option>
            <?php foreach ($supported_currencies as $currency) : ?>
                <option <?php echo $used_currency === $currency ? 'selected' : ''; ?> value="<?php echo $currency; ?>">
                    <?php echo $currency; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="row">
        <div class="col-20">
            <label for="lnpw_tipping_background">Background color</label>
        </div>
        <div class="col-80">
            <input id="lnpw_tipping_background" class="lnpw_tipping_background" name="lnpw_tipping_background" type="text" value="" />

        </div>
    </div>
    <div class="row">
        <div class="col-20">
            <label for="lnpw_tipping_button_color">Button color</label>
        </div>
        <div class="col-80">
            <input id="lnpw_tipping_button_color" class="lnpw_tipping_button_color" name="lnpw_tipping_button_color" type="text" value="" />

        </div>
    </div>
</div>