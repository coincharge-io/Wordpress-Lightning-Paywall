(function ($) {
  "use strict";

  $(document).ready(function () {
    $("#lnpw_btcpay_check_status").click(function () {
      $(".lnpw_btcpay_status").hide(500);
      $.ajax({
        url: "/wp-admin/admin-ajax.php",
        method: "POST",
        data: {
          action: "lnpw_check_greenfield_api_work",
          auth_key_view: $("#lnpw_btcpay_auth_key_view").val(),
          auth_key_create: $("#lnpw_btcpay_auth_key_create").val(),
          server_url: $("#lnpw_btcpay_server_url").val(),
        },
        success: function (response) {
          if (response.success) {
            $("#lnpw_btcpay_store_id").val(response.data.store_id);
            $("#lnpw_btcpay_status_success").fadeIn(500);
          } else {
            $("#lnpw_btcpay_status_error")
              .html(response.data.message)
              .fadeIn(500);
          }
        },
        error: function (error) {
          console.log(error);
        },
      });
    });
  });
  $(document).ready(function () {
    $("#lnpw_default_currency").change(function () {
      var stepValue =
        $(this).val() === "SATS" ? "1"
          : "0.50";

      $("#lnpw_default_price").attr({
        step: stepValue,
        value: parseInt($("#lnpw_default_price").val()),
      });
    });
  });
  $(document).ready(function () {
    $("#lnpw_default_currency").change(function () {
      if ($("#lnpw_default_currency").val() !== "SATS") {
        $(".lnpw_price_format").hide();
      }else{
        $(".lnpw_price_format").show()
      }
    });
  });
  $(document).ready(function () {
    $("#lnpw_default_duration_type").change(function () {
      var dtype = $(this).val();

      if (dtype === "unlimited" || dtype === "onetime") {
        $("#lnpw_default_duration").prop({ required: false, disabled: true });
      } else {
        $("#lnpw_default_duration").prop({ required: true, disabled: false });
      }
    });
  });
  function isValidUrl(url) {
    if (
      typeof url === "string" &&
      (url.indexOf("http://") == 0 || url.indexOf("https://") == 0)
    ) {
      return true;
    }

    return false;
  }

  function formatUrl(url) {
    if (typeof url === "string") {
      return url.replace(/^(.+?)\/*?$/, "$1");
    }

    return null;
  }
  $(document).ready(function () {
    $("#lnpw_btcpay_server_url").change(function () {
      var url = formatUrl($(this).val());

      var redirectLink = $(".lnpw_auth_key");

      if (url.length == 0) {
        return;
      }

      if (isValidUrl(url)) {
        url = url + "/Manage/APIKeys";
        redirectLink.attr("href", url).html(url).show();
      }
    });
  });
  function convertDate(d) {
    var year = d.getFullYear();
    var month = pad(d.getMonth() + 1);
    var day = pad(d.getDate());
    var hour = pad(d.getHours());
    var minutes = pad(d.getMinutes());

    return year + "-" + month + "-" + day + " " + hour + ":" + minutes;
  }

  function pad(num) {
    var num = "0" + num;
    return num.slice(-2);
  }

  $(document).ready(function () {
    $.ajax({
      url: "/wp-admin/admin-ajax.php",
      method: "GET",
      data: {
        action: "lnpw_get_greenfield_invoices",
      },
      success: function (response) {
        if (response.success) {
          var data = JSON.parse(JSON.stringify(response["data"]));

          var body = $(".section.invoices");

          $.each(data, function (i, value) {
            var parseUrl = value["checkoutLink"].split("/");

            var base = parseUrl[0] + "//" + parseUrl[2];

            var redirectLink = base + "/invoices/" + value["id"];

            var creationDate = new Date(parseInt(value["createdTime"]) * 1000);

            var formatted = convertDate(creationDate);

            body.append(
              "<tr><td>" +
                formatted +
                "</td><td>" +
                value["metadata"]["itemDesc"] +
                "</td><td class=" +
                value["status"] +
                ">" +
                value["status"] +
                "</td><td>" +
                value["amount"] +
                "</td><td>" +
                value["currency"] +
                "</td><td><a target=_blank href=" +
                redirectLink +
                ">" +
                value["id"] +
                "</a></td></tr>"
            );
          });
        } else {
          console.log(response);
        }
      },
    });
  });
  $(document).ready(function () {
    $('.lnpw_tipping_collect').click(function () {
      if (!$(this).is(':checked')) {
        $(".container_donor_information").hide();
      }else{
        $(".container_donor_information").show()
      }
    });
  });
  $(document).ready(function () {
    $('.lnpw_tipping_predefined').click(function () {
      if (!$(this).is(':checked')) {
        $(".container_predefined_amount").hide();
      }else{
        $(".container_predefined_amount").show()
      }
    });
  });
  $(document).ready(function (){
    $('#lnpw_tipping_button_text_color, #lnpw_tipping_background, #lnpw_tipping_button_color').iris({
      
      defaultColor: true,
      
      change: function(event, ui){},
      
      clear: function() {},
      
      hide: true,
      
      palettes: true
      });
  });
$(document).ready(function($){
            imagePreview($('#lnpw_tipping_button_image'), $('#lnpw_tipping_image'));
            imageRemove($('.lnpw_tipping_button_remove'));
           
        });
$(document).ready(function () {
    showMore($('.lnpw_tipping_collect_name'), $('.lnpw_tipping_collect_name_mandatory, label[for="lnpw_tipping_collect_name_mandatory"]'));

    showMore($('.lnpw_tipping_collect_email'), $('.lnpw_tipping_collect_email_mandatory, label[for="lnpw_tipping_collect_email_mandatory"]'));

    showMore($('.lnpw_tipping_collect_phone'), $('.lnpw_tipping_collect_phone_mandatory, label[for="lnpw_tipping_collect_phone_mandatory"]'));

    showMore($('.lnpw_tipping_collect_address'), $('.lnpw_tipping_collect_address_mandatory, label[for="lnpw_tipping_collect_address_mandatory"]'));

    showMore($('.lnpw_tipping_collect_message'), $('.lnpw_tipping_collect_message_mandatory, label[for="lnpw_tipping_collect_message_mandatory"]'));
  });
function imagePreview(click_elem, target){
  var custom_uploader
              , click_elem
              , target 

            click_elem.click(function(e) {
                e.preventDefault();

                if (custom_uploader) {
                    custom_uploader.open();
                    return;
                }
                
                custom_uploader = wp.media.frames.file_frame = wp.media({
                    title: 'Choose Image',
                    button: {
                        text: 'Choose Image'
                    },
                    multiple: false
                });
                
                custom_uploader.on('select', function() {
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    target.val(attachment.id);
                    click_elem.html('<img src="' + attachment.url + '">').next().show();
                });
                
                custom_uploader.open();
            });
}
function imageRemove(remove){
  $(remove).click(function(e){
 
  e.preventDefault();

  var button = $(this);
  button.next().val(''); 
  button.hide().prev().html('Upload image');
      });      
}
function showMore(click_elem, target){
  $(click_elem).click(function () {
      if (!$(this).is(':checked')) {
        $(target).css('visibility', 'hidden');
      }else{
        $(target).css('visibility', 'visible');
      }
    });
}
})(jQuery);
