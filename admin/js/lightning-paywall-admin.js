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
      var stepValue = $(this).val() === "SATS" ? "1" : "0.50";

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
      } else {
        $(".lnpw_price_format").show();
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
    $(".lnpw_fixed_amount_enable").change(function () {
      $(this).is(":checked")
        ? $(this).next().prop("required", true)
        : $(this).next().prop("required", false);
    });
  });

  $(document).ready(function () {
    $(".lnpw_tipping_enter_amount").click(function () {
      if (!$(this).is(":checked")) {
        $(".container_predefined_amount").show();
      } else {
        $(".container_predefined_amount").hide();
      }
    });
  });

  $(document).ready(function () {
    $(
      ".widget-tipping-basic-background_color,.widget-tipping-basic-title_text_color,.widget-tipping-basic-button_text_color,.widget-tipping-basic-button-color,.widget-tipping-basic-description-color,.widget-tipping-basic-title_text_color,.widget-tipping-basic-tipping-color,.widget-tipping-basic-fixed_background,.widget-tipping-basic-background_color_high,.widget-tipping-basic-title_text_color_high,.widget-tipping-basic-button_text_color_high,.widget-tipping-basic-button_color_high,.widget-tipping-basic-description-color_high,.widget-tipping-basic-title_text_color_high,.widget-tipping-basic-tipping-color_high,.widget-tipping-basic-fixed_background_high,.widget-tipping-basic-background_color_wide,.widget-tipping-basic-title_text_color_wide,.widget-tipping-basic-button_text_color_wide,.widget-tipping-basic-button_color_wide,.widget-tipping-basic-description-color_wide,.widget-tipping-basic-title_text_color_wide,.widget-tipping-basic-tipping-color_wide,.widget-tipping-basic-fixed_background_wide,.lnpw_tipping_box_title_color,.lnpw_tipping_box_description_color,.lnpw_tipping_box_tipping_box_color,.lnpw_tipping_box_button_text_color,.lnpw_tipping_box_button_color,.lnpw_tipping_box_background,.lnpw_tipping_hf_background,.lnpw_tipping_banner_title_color,.lnpw_tipping_banner_description_color,.lnpw_tipping_banner_tipping_box_color,.lnpw_tipping_banner_button_text_color,.lnpw_tipping_banner_button_color,.lnpw_tipping_banner_background,.lnpw_tipping_banner_tipping_color,.widget-tipping-basic-hf_color,.widget-tipping-basic-hf_color_high,.widget-tipping-basic-hf_color_wide"
    ).iris({
      defaultColor: true,

      change: function (event, ui) {},

      clear: function () {},

      hide: true,

      palettes: true,
    });
  });
  $(document).ready(function ($) {
    imagePreview(
      $("#lnpw_tipping_banner_button_image"),
      $("#lnpw_tipping_banner_image")
    );
    imageRemove($(".lnpw_tipping_banner_button_remove"));

    imagePreview(
      $("#lnpw_tipping_banner_button_image_background"),
      $("#lnpw_tipping_banner_image_background")
    );
    imageRemove($(".lnpw_tipping_banner_button_remove_background"));

    imagePreview(
      $(".widget-tipping-basic-upload_box_image"),
      $(".widget-tipping-basic-background_id")
    );
    imageRemove($(".widget-tipping-basic-remove_box_image"));

    imagePreview(
      $(".widget-tipping-basic-upload_box_logo"),
      $(".widget-tipping-basic-logo_id")
    );
    imageRemove($(".widget-tipping-basic-remove_box_logo"));

    imagePreview(
      $(".widget-tipping-basic-upload_box_image_high"),
      $(".widget-tipping-basic-background_id_high")
    );
    imageRemove($(".widget-tipping-basic-remove_box_image_high"));

    imagePreview(
      $(".widget-tipping-basic-upload_box_logo_high"),
      $(".widget-tipping-basic-logo_id_high")
    );
    imageRemove($(".widget-tipping-basic-remove_box_logo_high"));

    imagePreview(
      $(".widget-tipping-basic-upload_box_image_wide"),
      $(".widget-tipping-basic-background_id_wide")
    );
    imageRemove($(".widget-tipping-basic-remove_box_image_wide"));

    imagePreview(
      $(".widget-tipping-basic-upload_box_logo_wide"),
      $(".widget-tipping-basic-logo_id_wide")
    );
    imageRemove($(".widget-tipping-basic-remove_box_logo_wide"));

    imagePreview(
      $("#lnpw_tipping_box_button_image"),
      $("#lnpw_tipping_box_image")
    );
    imageRemove($(".lnpw_tipping_box_button_remove"));

    imagePreview(
      $("#lnpw_tipping_box_button_image_background"),
      $("#lnpw_tipping_box_image_background")
    );
    imageRemove($(".lnpw_tipping_box_button_remove_background"));
  });
  $(document).ready(function () {
    showMore(
      $(".lnpw_tipping_banner_collect_name"),
      $(
        '.lnpw_tipping_banner_collect_name_mandatory, label[for="lnpw_tipping_banner_collect[name][mandatory]"]'
      )
    );

    showMore(
      $(".lnpw_tipping_banner_collect_email"),
      $(
        '.lnpw_tipping_banner_collect_email_mandatory, label[for="lnpw_tipping_banner_collect[email][mandatory]"]'
      )
    );

    showMore(
      $(".lnpw_tipping_banner_collect_phone"),
      $(
        '.lnpw_tipping_banner_collect_phone_mandatory, label[for="lnpw_tipping_banner_collect[phone][mandatory]"]'
      )
    );

    showMore(
      $(".lnpw_tipping_banner_collect_address"),
      $(
        '.lnpw_tipping_banner_collect_address_mandatory, label[for="lnpw_tipping_banner_collect[address][mandatory]"]'
      )
    );

    showMore(
      $(".lnpw_tipping_banner_collect_message"),
      $(
        '.lnpw_tipping_banner_collect_message_mandatory, label[for="lnpw_tipping_banner_collect[message][mandatory]"]'
      )
    );
  });
  function imagePreview(click_elem, target) {
    var custom_uploader, click_elem, target;

    click_elem.click(function (e) {
      e.preventDefault();
      if (custom_uploader) {
        custom_uploader.open();
        return;
      }

      custom_uploader = wp.media.frames.file_frame = wp.media({
        title: "Choose Image",
        button: {
          text: "Choose Image",
        },
        multiple: false,
      });

      custom_uploader.on("select", function () {
        var attachment = custom_uploader
          .state()
          .get("selection")
          .first()
          .toJSON();
        target.val(attachment.id);
        click_elem
          .html('<img src="' + attachment.url + '">')
          .next()
          .show();
      });

      custom_uploader.open();
    });
  }
  function imageRemove(remove) {
    $(remove).click(function (e) {
      e.preventDefault();

      var button = $(this);
      button.next().val("");
      button.hide().prev().html("Upload image");
    });
  }
  function showMore(click_elem, target) {
    $(click_elem).click(function () {
      if (!$(this).is(":checked")) {
        $(target).prop("checked", false).css("visibility", "hidden");
      } else {
        $(target).css("visibility", "visible");
      }
    });
  }
})(jQuery);
