(function ($) {
  "use strict";
  $(document).ready(function () {
    var lnpw_invoice_id = null;
    var lnpw_order_id = null;
  
    $("#lnpw_pay__button").click(function () {
      $(".lnpw_pay__loading p.loading").addClass("spinner");
      var post_id = $(this).data("post_id");
      if (lnpw_invoice_id && lnpw_order_id) {
        lnpwShowInvoice(lnpw_invoice_id, lnpw_order_id);
        return;
      }
      
      $.ajax({
        url: "/wp-admin/admin-ajax.php",
        method: "GET",
        data: {
          action: "lnpw_get_invoice_id",
          post_id: post_id,
        },
        success: function (response) {
          $(".lnpw_pay__loading p.loading").removeClass("spinner");
          if (response.success) {
            lnpw_invoice_id = response.data.invoice_id;
            lnpw_order_id = response.data.order_id;
            lnpwShowInvoice(lnpw_invoice_id, lnpw_order_id);
          } else {
            console.error(response);
          }
        },
      });
    });
  });

  function lnpwShowInvoice(invoice_id, order_id) {
    btcpay.onModalReceiveMessage(function (event) {
      if (event.data.status === "complete") {
        $.ajax({
          url: "/wp-admin/admin-ajax.php",
          method: "POST",
          data: {
            action: "lnpw_paid_invoice",
            invoice_id: invoice_id,
            order_id: order_id,
          },
          success: function (response) {
            if (response.success) {
              location.reload(true);
            } else {
              console.error(response);
            }
          },
        });
      }
    });

    btcpay.showInvoice(invoice_id);
  }

  $(document).ready(function () {
    var lnpw_invoice_id = null;
    var donor;
    $("#tipping_form").submit(function (e) {
      e.preventDefault();
      $(".lnpw_pay__loading p.loading").addClass("spinner");
      if (lnpw_invoice_id) {
        lnpwShowDonationInvoice(lnpw_invoice_id);
        return;
      }
        $.ajax({
          url: "/wp-admin/admin-ajax.php",
          method: "POST",
          data: {
            action: "lnpw_donate",
            currency: $("#lnpw_tipping_currency").val(),
            amount: $("#lnpw_tipping_amount").val(),
            predefined_amount: $("input[type=radio][name=lnpw_tipping_default_amount]:checked").val(),
            name: $("#lnpw_tipping_donor_name").val(),
            email: $("#lnpw_tipping_donor_email").val(),
            address: $("#lnpw_tipping_donor_address").val(),
            phone: $("#lnpw_tipping_donor_phone").val(),
            message: $("#lnpw_tipping_donor_message").val(),
          },
          success: function (response) {
            $(".lnpw_pay__loading p.loading").removeClass("spinner");
            if (response.success) {
              lnpw_invoice_id = response.data.invoice_id;
              donor = response.data.donor;
              lnpwShowDonationInvoice(lnpw_invoice_id, donor);
            } else {
               console.error(response);
            }
          },
          error: function (error){
            console.log(error)
        }
      })
    })
  })
  function lnpwShowDonationInvoice(invoice_id, donor) {
    btcpay.onModalReceiveMessage(function (event) {
      if (event.data.status === "complete") {
        notifyAdmin(donor)
        ($("#lnpw_redirect_link").is(":empty")) ? location.reload(true) : location.replace($("#lnpw_redirect_link").val());
          }});

    btcpay.showInvoice(invoice_id);
  }
  function notifyAdmin(donor_info){
    $.ajax({
          url: "/wp-admin/admin-ajax.php",
          method: "POST",
          data: {
            action: "lnpw_notify_admin",
            donor_info: donor_info
          }})
  }
  $(document).ready(function () {
    $("#lnpw_tipping_currency").change(function () {
      var stepValue =
        $(this).val() === "BTC"
          ? "0.00000001"
          : $(this).val() === "SATS"
          ? "1"
          : "0.50";
      $("#lnpw_tipping_amount").attr({
        step: stepValue,
        value: parseInt($("#lnpw_tipping_amount").val()),
      });
    });
  });
$(document).ready(function(){
  var eur, usd, sats;
  $.ajax({
        url: "/wp-admin/admin-ajax.php",
        method: "GET",
        data: {
          action: "lnpw_convert_currencies",
        },
        success: function (response) {
          if (response.success) {
            eur = response['data']['eur']['value'];
            usd = response['data']['usd']['value'];
            sats = response['data']['sats']['value'];
          } else {
            console.error(response);
          }
        },
      });
    $("#lnpw_tipping_amount").change(function(){
      var currency = $("#lnpw_tipping_currency").val();
      var amount = $(this).val();
      var converted = fiat_to_crypto(currency, amount, usd, eur, sats);
    
      $("#lnpw_converted_amount").attr('readonly', false).val(converted).attr('readonly', true);
    })
})
function fiat_to_crypto(currency, val, usd, eur, sats){
  var value = Number(val);
  switch (currency){
    case 'BTC':
       return value*usd;
    case 'USD':
        return ((sats/usd)*value).toFixed(0);
    case 'EUR':
        return ((sats/eur)*value).toFixed(0);
    default:
        return (usd/sats)*value;
  }
}
$(document).ready(function(){ 
  var form_count = 1, previous_form, next_form, total_forms;
  total_forms = $("fieldset").length;  
  $(".next-form").click(function(){
    if ($("#lnpw_tipping_amount")[0].checkValidity()){
      previous_form = $(this).parent();
      next_form = $(this).parent().next();
      next_form.show();
      previous_form.hide();
    }else{
      $("#lnpw_tipping_amount")[0].reportValidity()
    }
  });  
  $(".previous-form").click(function(){
    previous_form = $(this).parent();
    next_form = $(this).parent().prev();
    next_form.show();
    previous_form.hide();
  }); 
});

$(document).ready(function(){
    $("input[type=radio][name=lnpw_tipping_default_amount]").change(function () {
      $("input[type=radio][name=lnpw_tipping_default_amount]").attr('required', true);
      $("#lnpw_tipping_amount").removeAttr('required');
      $("#lnpw_tipping_amount").val('');
  })
  $("#lnpw_tipping_amount").click(function () {
    $("#lnpw_tipping_amount").attr('required', true);
    $("input[type=radio][name=lnpw_tipping_default_amount]").removeAttr('required');
    $("input[type=radio][name=lnpw_tipping_default_amount]").prop('checked', false);
      
  })
  })

  
})(jQuery);
