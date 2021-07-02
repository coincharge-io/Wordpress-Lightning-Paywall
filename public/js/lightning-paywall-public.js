(function ($) {
  "use strict";
  $(document).ready(function () {
    var lnpw_invoice_id = null;
    var lnpw_order_id = null;
    var amount;
  
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
            amount = response.data.amount
            lnpwShowInvoice(lnpw_invoice_id, lnpw_order_id, amount);
          } else {
            console.error(response);
          }
        },
      });
    });
  });

  function lnpwShowInvoice(invoice_id, order_id, amount) {
    btcpay.onModalReceiveMessage(function (event) {
      if (event.data.status === "complete") {
        $.ajax({
          url: "/wp-admin/admin-ajax.php",
          method: "POST",
          data: {
            action: "lnpw_paid_invoice",
            invoice_id: invoice_id,
            order_id: order_id,
            amount: amount,
          },
          success: function (response) {
            if (response.success) {
              notifyAdmin(response.data.notify);
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
      if (lnpw_invoice_id) {
        lnpwShowDonationInvoice(lnpw_invoice_id);
        return;
      }
        $.ajax({
          url: "/wp-admin/admin-ajax.php",
          method: "POST",
          data: {
            action: "lnpw_tipping",
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
            if (response.success) {
              lnpw_invoice_id = response.data.invoice_id;
              donor = response.data.donor;
              lnpwShowDonationBoxInvoice(lnpw_invoice_id, donor);
            } else {
               console.error(response);
            }
          },
          error: function (error){
            console.log(error)
        }
      })
    })

    $("#skyscraper_tipping_form").submit(function (e) {
      e.preventDefault();
      if (lnpw_invoice_id) {
        lnpwShowDonationInvoice(lnpw_invoice_id);
        return;
      }
        $.ajax({
          url: "/wp-admin/admin-ajax.php",
          method: "POST",
          data: {
            action: "lnpw_tipping",
            currency: $("#lnpw_skyscraper_tipping_currency").val(),
            amount: $("#lnpw_skyscraper_tipping_amount").val(),
            predefined_amount: $("input[type=radio][name=lnpw_skyscraper_tipping_default_amount]:checked").val(),
            name: $("#lnpw_skyscraper_tipping_donor_name").val(),
            email: $("#lnpw_skyscraper_tipping_donor_email").val(),
            address: $("#lnpw_skyscraper_tipping_donor_address").val(),
            phone: $("#lnpw_skyscraper_tipping_donor_phone").val(),
            message: $("#lnpw_skyscraper_tipping_donor_message").val(),
          },
          success: function (response) {
            if (response.success) {
              lnpw_invoice_id = response.data.invoice_id;
              donor = response.data.donor;
              lnpwShowDonationBannerInvoice(lnpw_invoice_id, donor);
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
  function lnpwShowDonationBoxInvoice(invoice_id, donor) {
    btcpay.onModalReceiveMessage(function (event) {
      if (event.data.status === "complete") {
        notifyAdmin(donor)
        ($("#lnpw_redirect_link").is(":empty")) ? location.reload(true) : location.replace($("#lnpw_redirect_link").val());
          }});

    btcpay.showInvoice(invoice_id);
  }

  function lnpwShowDonationBannerInvoice(invoice_id, donor) {
    btcpay.onModalReceiveMessage(function (event) {
      if (event.data.status === "complete") {
        notifyAdmin(donor)
        ($("#lnpw_skyscraper_redirect_link").is(":empty")) ? location.reload(true) : location.replace($("#lnpw_redirect_link").val());
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
    $("#lnpw_tipping_amount").on('input', function(){
      var currency = $("#lnpw_tipping_currency").val();
      var amount = $(this).val();
      var converted = fiat_to_crypto(currency, amount, usd, eur, sats);
      //var converted_amount = '~' + fiat_to_crypto(currency, amount, usd, eur, sats)+ ' '+get_currency(currency)
      $("#lnpw_converted_amount").attr('readonly', false).val('~' + fiat_to_crypto(currency, amount, usd, eur, sats)).attr('readonly', true);
      $("#lnpw_converted_currency").attr('readonly', false).val(get_currency(currency)).attr('readonly', true);
    });
     $("#lnpw_skyscraper_tipping_amount").on('input', function(){
      var currency = $("#lnpw_skyscraper_tipping_currency").val();
      var amount = $(this).val();
      var converted = fiat_to_crypto(currency, amount, usd, eur, sats);
      $("#lnpw_skyscraper_converted_amount").attr('readonly', false).val('~' + fiat_to_crypto(currency, amount, usd, eur, sats)).attr('readonly', true);
      $("#lnpw_skyscraper_converted_currency").attr('readonly', false).val(get_currency(currency)).attr('readonly', true);
    });

    $("#lnpw_widget_lnpw_skyscraper_tipping_amount_high").on('input', function(){
      var currency = $("#lnpw_widget_lnpw_skyscraper_tipping_currency_high").val();
      var amount = $(this).val();
      var converted = fiat_to_crypto(currency, amount, usd, eur, sats);
      $("#lnpw_widget_lnpw_skyscraper_converted_amount_high").attr('readonly', false).val('~' + fiat_to_crypto(currency, amount, usd, eur, sats)).attr('readonly', true);
      $("#lnpw_widget_lnpw_skyscraper_converted_currency_high").attr('readonly', false).val(get_currency(currency)).attr('readonly', true);
    });

    $("#lnpw_widget_lnpw_skyscraper_tipping_amount_wide").on('input', function(){
      var currency = $("#lnpw_widget_lnpw_skyscraper_tipping_currency_wide").val();
      var amount = $(this).val();
      var converted = fiat_to_crypto(currency, amount, usd, eur, sats);
      $("#lnpw_widget_lnpw_skyscraper_converted_amount_wide").attr('readonly', false).val('~' + fiat_to_crypto(currency, amount, usd, eur, sats)).attr('readonly', true);
      $("#lnpw_widget_lnpw_skyscraper_converted_currency_wide").attr('readonly', false).val(get_currency(currency)).attr('readonly', true);
    });
    /*$("#value1, #value2, #value3").hover(function(){
      var predefined = $(this).val().split(' ');
      var converted_icon = fiat_to_crypto(predefined[1], predefined[0], usd, eur, sats);
      var converted_icon_amount = '~' + fiat_to_crypto(predefined[1], predefined[0], usd, eur, sats)+ ' '+get_currency(predefined[1]);
      $("#lnpw_converted_amount").attr('readonly', false).val('~' + fiat_to_crypto(predefined[1], predefined[0], usd, eur, sats)).attr('readonly', true);
      $("#lnpw_converted_currency").attr('readonly', false).val(get_currency(predefined[1])).attr('readonly', true);
      if($(this).parent().find('i')[0].className) {
        $("label[for='"+this.id+"']").show();
      }
    }, function(){
      $("#lnpw_converted_amount").attr('readonly', false).val('').attr('readonly', true);
      if($(this).parent().find('i')[0].className) {
        $("label[for='"+this.id+"']").hide();
      }
    });*/
    $("#value_1, #value_2, #value_3").change(function(){
      if ($(this).is(':checked')){
        var predefined = $(this).val().split(' ');
        var converted_icon = fiat_to_crypto(predefined[1], predefined[0], usd, eur, sats);
        var converted_icon_amount = '~' + fiat_to_crypto(predefined[1], predefined[0], usd, eur, sats)+ ' '+get_currency(predefined[1]);
        $("#lnpw_skyscraper_converted_amount").attr('readonly', false).val('~' + fiat_to_crypto(predefined[1], predefined[0], usd, eur, sats)).attr('readonly', true);
        $("#lnpw_skyscraper_converted_currency").attr('readonly', false).val(get_currency(predefined[1])).attr('readonly', true);
      }
    });

    $("#lnpw_widget-value_1_high, #lnpw_widget-value_2_high, #lnpw_widget-value_3_high").change(function(){
      if ($(this).is(':checked')){
        var predefined = $(this).val().split(' ');
        var converted_icon = fiat_to_crypto(predefined[1], predefined[0], usd, eur, sats);
        var converted_icon_amount = '~' + fiat_to_crypto(predefined[1], predefined[0], usd, eur, sats)+ ' '+get_currency(predefined[1]);
        $("#lnpw_widget_lnpw_skyscraper_converted_amount_high").attr('readonly', false).val('~' + fiat_to_crypto(predefined[1], predefined[0], usd, eur, sats)).attr('readonly', true);
        $("#lnpw_widget_lnpw_skyscraper_converted_currency_high").attr('readonly', false).val(get_currency(predefined[1])).attr('readonly', true);
      }
    });

    $("#lnpw_widget-value_1_wide, #lnpw_widget-value_2_wide, #lnpw_widget-value_3_wide").change(function(){
      if ($(this).is(':checked')){
        var predefined = $(this).val().split(' ');
        var converted_icon = fiat_to_crypto(predefined[1], predefined[0], usd, eur, sats);
        var converted_icon_amount = '~' + fiat_to_crypto(predefined[1], predefined[0], usd, eur, sats)+ ' '+get_currency(predefined[1]);
        $("#lnpw_widget_lnpw_skyscraper_converted_amount_wide").attr('readonly', false).val('~' + fiat_to_crypto(predefined[1], predefined[0], usd, eur, sats)).attr('readonly', true);
        $("#lnpw_widget_lnpw_skyscraper_converted_currency_wide").attr('readonly', false).val(get_currency(predefined[1])).attr('readonly', true);
      }
    });
    
    $("#value1, #value2, #value3").change(function(){
      if ($(this).is(':checked')){
        var predefined = $(this).val().split(' ');
        var converted_icon = fiat_to_crypto(predefined[1], predefined[0], usd, eur, sats);
        var converted_icon_amount = '~' + fiat_to_crypto(predefined[1], predefined[0], usd, eur, sats)+ ' '+get_currency(predefined[1]);
        $("#lnpw_converted_amount").attr('readonly', false).val('~' + fiat_to_crypto(predefined[1], predefined[0], usd, eur, sats)).attr('readonly', true);
        $("#lnpw_converted_currency").attr('readonly', false).val(get_currency(predefined[1])).attr('readonly', true);
      }
    });
    
})
function fiat_to_crypto(currency, val, usd, eur, sats){
  var value = Number(val);
  switch (currency){
    case 'BTC':
       return (value*usd).toFixed(2);
    case 'USD':
        return ((sats/usd)*value).toFixed(0);
    case 'EUR':
        return ((sats/eur)*value).toFixed(0);
    default:
        return ((usd/sats)*value).toFixed(2);
  }
}
function get_currency(currency){
  switch (currency){
    case 'BTC':
       return 'USD';
    case 'USD':
        return 'SATS';
    case 'EUR':
        return 'SATS';
    default:
        return 'USD';
  }
}
$(document).ready(function(){ 
  var form_count = 1, previous_form, next_form, total_forms;
  total_forms = $("fieldset").length;  
  var freeInput = $("#lnpw_tipping_amount");
  var fixedAmount = $('.lnpw_tipping_default_amount');
  var validationField = ((fixedAmount.length !== 0 && freeInput.length === 0) ? fixedAmount : freeInput);
  
  $("input.next-form").click(function(){
    if (validationField[0].checkValidity()){
      previous_form = $(this).parent().parent();
      next_form = $(this).parent().parent().next();
      next_form.show();
      previous_form.hide();
    }else{
      validationField[0].reportValidity()
    }
  });  
  $("input.previous-form").click(function(){
    previous_form = $(this).parent().parent();
    next_form = $(this).parent().parent().prev();
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

   $("input[type=radio][name=lnpw_skyscraper_tipping_default_amount]").change(function () {
      $("input[type=radio][name=lnpw_skyscraper_tipping_default_amount]").attr('required', true);
      $("#lnpw_skyscraper_tipping_amount").removeAttr('required');
      $("#lnpw_skyscraper_tipping_amount").val('');
  })
  $("#lnpw_skyscraper_tipping_amount").click(function () {
    $("#lnpw_skyscraper_tipping_amount").attr('required', true);
    $("input[type=radio][name=lnpw_skyscraper_tipping_default_amount]").removeAttr('required');
    $("input[type=radio][name=lnpw_skyscraper_tipping_default_amount]").prop('checked', false);   
  })
})
$(document).ready(function(){

  $("input[type=radio][name=lnpw_widget_lnpw_skyscraper_tipping_default_amount_high]").change(function () {
      $("input[type=radio][name=lnpw_widget_lnpw_skyscraper_tipping_default_amount_high]").attr('required', true);
      $("#lnpw_widget_lnpw_skyscraper_tipping_amount_high").removeAttr('required');
      $("#lnpw_widget_lnpw_skyscraper_tipping_amount_high").val('');
  })
  $("#lnpw_widget_lnpw_skyscraper_tipping_amount_high").click(function () {
    $("#lnpw_widget_lnpw_skyscraper_tipping_amount_high").attr('required', true);
    $("input[type=radio][name=lnpw_widget_lnpw_skyscraper_tipping_default_amount_high]").removeAttr('required');
    $("input[type=radio][name=lnpw_widget_lnpw_skyscraper_tipping_default_amount_high]").prop('checked', false);   
  })

  
  })
$(document).ready(function(){ 
    $("input[name=lnpw_widget_ lnpw_skyscraper_tipping_default_amount_wide]").change(function () {
      $("input[type=radio][name=lnpw_widget_lnpw_skyscraper_tipping_default_amount_wide]").attr('required', true);
      $("#lnpw_widget_lnpw_skyscraper_tipping_amount_wide").removeAttr('required');
      $("#lnpw_widget_lnpw_skyscraper_tipping_amount_wide").val('');
  })
  $("#lnpw_widget_lnpw_skyscraper_tipping_amount_wide").click(function () {
    $("#lnpw_widget_lnpw_skyscraper_tipping_amount_wide").attr('required', true);
    $("input[type=radio][name=lnpw_widget_lnpw_skyscraper_tipping_default_amount_wide]").removeAttr('required');
    $("input[type=radio][name=lnpw_widget_lnpw_skyscraper_tipping_default_amount_wide]").prop('checked', false);   
  })
})
$(document).ready(function(){ 
  var form_count = 1, previous_form, next_form, total_forms;
  total_forms = $(".lnpw_skyscraper_tipping_container fieldset").length;  
  var freeInput = $("#lnpw_skyscraper_tipping_amount");
  var fixedAmount = $('.lnpw_skyscraper_tipping_default_amount');
  var validationField = ((fixedAmount.length !== 0 && freeInput.length === 0) ? fixedAmount : freeInput);
  
  
  $("input.skyscraper-next-form").click(function(){
    if (validationField[0].checkValidity()){
      previous_form = $(this).parent().parent();
      next_form = $(this).parent().parent().next();
      next_form.show();
      previous_form.hide();
    }else{
      validationField[0].reportValidity()
    }
  });  
  $("input.skyscraper-previous-form").click(function(){
    previous_form = $(this).parent().parent();
    next_form = $(this).parent().parent().prev();
    next_form.show();
    previous_form.hide();
  }); 
});

$(document).ready(function(){ 
  var form_count = 1, previous_form, next_form, total_forms;
  total_forms = $(".lnpw_widget.lnpw_skyscraper_tipping_container_high fieldset").length;  
  var freeInput = $("#lnpw_widget_lnpw_skyscraper_tipping_amount_high");
  var fixedAmount = $('.lnpw_widget.lnpw_skyscraper_tipping_default_amount_high');
  var validationField = ((fixedAmount.length !== 0 && freeInput.length === 0) ? fixedAmount : freeInput);
  
  
  $(".lnpw_widget_skyscraper-next-form.high").click(function(){
    if (validationField[0].checkValidity()){
      previous_form = $(this).parent().parent();
      next_form = $(this).parent().parent().next();
      next_form.show();
      previous_form.hide();
    }else{
      validationField[0].reportValidity()
    }
  });  
  $(".lnpw_widget_skyscraper-previous-form.high").click(function(){
    previous_form = $(this).parent().parent();
    next_form = $(this).parent().parent().prev();
    next_form.show();
    previous_form.hide();
  }); 
});

$(document).ready(function(){ 
  var form_count = 1, previous_form, next_form, total_forms;
  total_forms = $(".lnpw_widget.lnpw_skyscraper_tipping_container_wide fieldset").length;  
  var freeInput = $("#lnpw_widget_lnpw_skyscraper_tipping_amount_wide");
  var fixedAmount = $('.lnpw_widget.lnpw_skyscraper_tipping_default_amount_wide');
  var validationField = ((fixedAmount.length !== 0 && freeInput.length === 0) ? fixedAmount : freeInput);
  
  
  $(".lnpw_widget_skyscraper-next-form.wide").click(function(){
    if (validationField[0].checkValidity()){
      previous_form = $(this).parent().parent();
      next_form = $(this).parent().parent().next();
      next_form.show();
      previous_form.hide();
    }else{
      validationField[0].reportValidity()
    }
  });  
  $(".lnpw_widget_skyscraper-previous-form.wide").click(function(){
    previous_form = $(this).parent().parent();
    next_form = $(this).parent().parent().prev();
    next_form.show();
    previous_form.hide();
  }); 
});
})(jQuery);
