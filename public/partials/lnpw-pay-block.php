<div class="lnpw_pay">
      <div class="lnpw_pay__content">
          <h2><?php echo  Lightning_Paywall_Public::get_payblock_header_string() ?></h2>
          <p>
            <?php echo  Lightning_Paywall_Public::get_post_info_string() ?>
          </p>
       </div>
          <div class="lnpw_pay__footer">
              <div>
                <button type="button" id="lnpw_pay__button" data-post_id="<?php echo  get_the_ID(); ?>"><?php echo  Lightning_Paywall_Public::get_payblock_button_string() ?></button>
              </div>
          <div class="lnpw_pay__loading">
              <p class="loading"></p>
          </div>
          <div class="lnpw_help">
              <a class="lnpw_help__link" href="https://lightning-paywall.coincharge.io/how-to-pay-the-lightning-paywall/" target="_blank">Help</a>
          </div>
       </div>
</div>