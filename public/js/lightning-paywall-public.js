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
            amount = response.data.amount;
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
    $("#tipping_form_box").submit(function (e) {
      var text = $("#lnpw_tipping__button").text();
      $("#lnpw_tipping__button").html(
        `<span class="tipping-border" role="status" aria-hidden="true"></span>`
      );
      e.preventDefault();
      if (lnpw_invoice_id) {
        lnpwShowDonationBoxInvoice(lnpw_invoice_id);
        return;
      }
      $.ajax({
        url: "/wp-admin/admin-ajax.php",
        method: "POST",
        data: {
          action: "lnpw_tipping",
          currency: $("#lnpw_tipping_currency").val(),
          amount: $("#lnpw_tipping_amount").val(),
          predefined_amount: $(
            "input[type=radio][name=lnpw_tipping_default_amount]:checked"
          ).val(),
        },
        success: function (response) {
          $("#lnpw_tipping__button").html(text);
          if (response.success) {
            lnpw_invoice_id = response.data.invoice_id;
            donor = response.data.donor;
            lnpwShowDonationBoxInvoice(
              lnpw_invoice_id,
              donor,
              $("#lnpw_redirect_link")
            );
          } else {
            console.error(response);
          }
        },
        error: function (error) {
          console.log(error);
        },
      });
    });

    $("#tipping_form_box_widget").submit(function (e) {
      e.preventDefault();
      var text = $("#lnpw_tipping__button_lnpw_widget").text();
      $("#lnpw_tipping__button_lnpw_widget").html(
        `<span class="tipping-border" role="status" aria-hidden="true"></span>`
      );
      if (lnpw_invoice_id) {
        lnpwShowDonationBoxInvoice(lnpw_invoice_id);
        return;
      }
      $.ajax({
        url: "/wp-admin/admin-ajax.php",
        method: "POST",
        data: {
          action: "lnpw_tipping",
          currency: $("#lnpw_tipping_currency_lnpw_widget").val(),
          amount: $("#lnpw_tipping_amount_lnpw_widget").val(),
        },
        success: function (response) {
          $("#lnpw_tipping__button_lnpw_widget").html(text);
          if (response.success) {
            lnpw_invoice_id = response.data.invoice_id;
            donor = response.data.donor;
            lnpwShowDonationBoxInvoice(
              lnpw_invoice_id,
              donor,
              $("#lnpw_redirect_link_lnpw_widget")
            );
          } else {
            console.error(response);
          }
        },
        error: function (error) {
          console.log(error);
        },
      });
    });

    $("#skyscraper_tipping_form").submit(function (e) {
      e.preventDefault();
      var text = $("#lnpw_skyscraper_tipping__button").text();
      $("#lnpw_skyscraper_tipping__button").html(
        `<span class="tipping-border" role="status" aria-hidden="true"></span>`
      );
      if (lnpw_invoice_id) {
        lnpwShowDonationBannerInvoice(lnpw_invoice_id);
        return;
      }
      $.ajax({
        url: "/wp-admin/admin-ajax.php",
        method: "POST",
        data: {
          action: "lnpw_tipping",
          currency: $("#lnpw_skyscraper_tipping_currency").val(),
          amount: $("#lnpw_skyscraper_tipping_amount").val(),
          predefined_amount: $(
            "input[type=radio][name=lnpw_skyscraper_tipping_default_amount_wide]:checked, input[type=radio][name=lnpw_skyscraper_tipping_default_amount_high]:checked"
          ).val(),
          name: $("#lnpw_skyscraper_tipping_donor_name").val(),
          email: $("#lnpw_skyscraper_tipping_donor_email").val(),
          address: $("#lnpw_skyscraper_tipping_donor_address").val(),
          phone: $("#lnpw_skyscraper_tipping_donor_phone").val(),
          message: $("#lnpw_skyscraper_tipping_donor_message").val(),
        },
        success: function (response) {
          $("#lnpw_skyscraper_tipping__button").html(text);

          if (response.success) {
            lnpw_invoice_id = response.data.invoice_id;
            donor = response.data.donor;
            lnpwShowDonationBannerInvoice(
              lnpw_invoice_id,
              donor,
              $("#lnpw_skyscraper_redirect_link")
            );
          } else {
            console.error(response);
          }
        },
        error: function (error) {
          console.log(error);
        },
      });
    });

    $("#page_tipping_form").submit(function (e) {
      e.preventDefault();
      var text = $("#lnpw_page_tipping__button").text();
      $("#lnpw_page_tipping__button").html(
        `<span class="tipping-border" role="status" aria-hidden="true"></span>`
      );
      if (lnpw_invoice_id) {
        lnpwShowDonationBannerInvoice(lnpw_invoice_id);
        return;
      }
      $.ajax({
        url: "/wp-admin/admin-ajax.php",
        method: "POST",
        data: {
          action: "lnpw_tipping",
          currency: $("#lnpw_page_tipping_currency").val(),
          amount: $("#lnpw_page_tipping_amount").val(),
          predefined_amount: $(
            "input[type=radio][name=lnpw_page_tipping_default_amount]:checked"
          ).val(),
          name: $("#lnpw_page_tipping_donor_name").val(),
          email: $("#lnpw_page_tipping_donor_email").val(),
          address: $("#lnpw_page_tipping_donor_address").val(),
          phone: $("#lnpw_page_tipping_donor_phone").val(),
          message: $("#lnpw_page_tipping_donor_message").val(),
        },
        success: function (response) {
          $("#lnpw_page_tipping__button").html(text);
          if (response.success) {
            lnpw_invoice_id = response.data.invoice_id;
            donor = response.data.donor;
            lnpwShowDonationBannerInvoice(
              lnpw_invoice_id,
              donor,
              $("#lnpw_page_redirect_link")
            );
          } else {
            console.error(response);
          }
        },
        error: function (error) {
          console.log(error);
        },
      });
    });

    $("#lnpw_widget_skyscraper_tipping_form_high").submit(function (e) {
      e.preventDefault();
      var text = $("#lnpw_widget_lnpw_skyscraper_tipping__button_high").text();
      $("#lnpw_widget_lnpw_skyscraper_tipping__button_high").html(
        `<span class="tipping-border" role="status" aria-hidden="true"></span>`
      );

      if (lnpw_invoice_id) {
        lnpwShowDonationBannerInvoice(lnpw_invoice_id);
        return;
      }
      $.ajax({
        url: "/wp-admin/admin-ajax.php",
        method: "POST",
        data: {
          action: "lnpw_tipping",
          currency: $(
            "#lnpw_widget_lnpw_skyscraper_tipping_currency_high"
          ).val(),
          amount: $("#lnpw_widget_lnpw_skyscraper_tipping_amount_high").val(),
          predefined_amount: $(
            "input[type=radio][name=lnpw_widget_lnpw_skyscraper_tipping_default_amount_high]:checked"
          ).val(),
          name: $(
            "#lnpw_widget_lnpw_skyscraper_tipping_donor_Full_name_high"
          ).val(),
          email: $(
            "#lnpw_widget_lnpw_skyscraper_tipping_donor_Email_high"
          ).val(),
          address: $(
            "#lnpw_widget_lnpw_skyscraper_tipping_donor_Address_high"
          ).val(),
          phone: $(
            "#lnpw_widget_lnpw_skyscraper_tipping_donor_Phone_high"
          ).val(),
          message: $(
            "#lnpw_widget_lnpw_skyscraper_tipping_donor_Message_high"
          ).val(),
        },
        success: function (response) {
          $("#lnpw_widget_lnpw_skyscraper_tipping__button_high").html(text);
          if (response.success) {
            lnpw_invoice_id = response.data.invoice_id;
            donor = response.data.donor;
            lnpwShowDonationBannerInvoice(
              lnpw_invoice_id,
              donor,
              $("#lnpw_widget_lnpw_skyscraper_redirect_link_high")
            );
          } else {
            console.error(response);
          }
        },
        error: function (error) {
          console.log(error);
        },
      });
    });

    $("#lnpw_widget_skyscraper_tipping_form_wide").submit(function (e) {
      e.preventDefault();
      var text = $("#lnpw_widget_lnpw_skyscraper_tipping__button_wide").text();
      $("#lnpw_widget_lnpw_skyscraper_tipping__button_wide").html(
        `<span class="tipping-border" role="status" aria-hidden="true"></span>`
      );
      if (lnpw_invoice_id) {
        lnpwShowDonationBannerInvoice(lnpw_invoice_id);
        return;
      }
      $.ajax({
        url: "/wp-admin/admin-ajax.php",
        method: "POST",
        data: {
          action: "lnpw_tipping",
          currency: $(
            "#lnpw_widget_lnpw_skyscraper_tipping_currency_wide"
          ).val(),
          amount: $("#lnpw_widget_lnpw_skyscraper_tipping_amount_wide").val(),
          predefined_amount: $(
            "input[type=radio][name=lnpw_widget_lnpw_skyscraper_tipping_default_amount_wide]:checked"
          ).val(),
          name: $(
            "#lnpw_widget_lnpw_skyscraper_tipping_donor_Full_name_wide"
          ).val(),
          email: $(
            "#lnpw_widget_lnpw_skyscraper_tipping_donor_Email_wide"
          ).val(),
          address: $(
            "#lnpw_widget_lnpw_skyscraper_tipping_donor_Address_wide"
          ).val(),
          phone: $(
            "#lnpw_widget_lnpw_skyscraper_tipping_donor_Phone_wide"
          ).val(),
          message: $(
            "#lnpw_widget_lnpw_skyscraper_tipping_donor_Message_wide"
          ).val(),
        },
        success: function (response) {
          $("#lnpw_widget_lnpw_skyscraper_tipping__button_wide").html(text);
          if (response.success) {
            lnpw_invoice_id = response.data.invoice_id;
            donor = response.data.donor;
            lnpwShowDonationBannerInvoice(
              lnpw_invoice_id,
              donor,
              $("#lnpw_widget_lnpw_skyscraper_redirect_link_wide")
            );
          } else {
            console.error(response);
          }
        },
        error: function (error) {
          console.log(error);
        },
      });
    });
  });
  function lnpwShowDonationBoxInvoice(invoice_id, donor, redirect) {
    btcpay.onModalReceiveMessage(function (event) {
      if (event.data.status === "complete") {
        notifyAdmin(donor)(redirect.is(":empty"))
          ? location.reload(true)
          : location.replace($("#lnpw_redirect_link").val());
      }
    });

    btcpay.showInvoice(invoice_id);
  }

  function lnpwShowDonationBannerInvoice(invoice_id, donor, redirect) {
    btcpay.onModalReceiveMessage(function (event) {
      if (event.data.status === "complete") {
        notifyAdmin(donor)(
          /*($("#lnpw_skyscraper_redirect_link").is(":empty")) ? location.reload(true) : location.replace($("#lnpw_redirect_link").val());
          }});*/
          redirect.is(":empty")
        )
          ? location.reload(true)
          : location.replace($("#lnpw_redirect_link").val());
      }
    });

    btcpay.showInvoice(invoice_id);
  }
  function notifyAdmin(donor_info) {
    $.ajax({
      url: "/wp-admin/admin-ajax.php",
      method: "POST",
      data: {
        action: "lnpw_notify_admin",
        donor_info: donor_info,
      },
    });
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
  $(document).ready(function () {
    var eur, usd, sats;
    $.ajax({
      url: "/wp-admin/admin-ajax.php",
      method: "GET",
      data: {
        action: "lnpw_convert_currencies",
      },
      success: function (response) {
        if (response.success) {
          eur = response["data"]["eur"]["value"];
          usd = response["data"]["usd"]["value"];
          sats = response["data"]["sats"]["value"];
        } else {
          console.error(response);
        }
      },
    });
    $("#lnpw_tipping_amount").on("input", function () {
      var currency = $("#lnpw_tipping_currency").val();
      var amount = $(this).val();
      var converted = fiat_to_crypto(currency, amount, usd, eur, sats);
      //var converted_amount = '~' + fiat_to_crypto(currency, amount, usd, eur, sats)+ ' '+get_currency(currency)
      $("#lnpw_converted_amount")
        .attr("readonly", false)
        .val(fiat_to_crypto(currency, amount, usd, eur, sats))
        .attr("readonly", true);
      $("#lnpw_converted_currency")
        .attr("readonly", false)
        .val(get_currency(currency))
        .attr("readonly", true);
    });

    $("#lnpw_tipping_amount_lnpw_widget").on("input", function () {
      var currency = $("#lnpw_tipping_currency_lnpw_widget").val();
      var amount = $(this).val();
      var converted = fiat_to_crypto(currency, amount, usd, eur, sats);
      //var converted_amount = '~' + fiat_to_crypto(currency, amount, usd, eur, sats)+ ' '+get_currency(currency)
      $("#lnpw_converted_amount_lnpw_widget")
        .attr("readonly", false)
        .val(fiat_to_crypto(currency, amount, usd, eur, sats))
        .attr("readonly", true);
      $("#lnpw_converted_currency_lnpw_widget")
        .attr("readonly", false)
        .val(get_currency(currency))
        .attr("readonly", true);
    });

    $("#lnpw_page_tipping_amount").on("input", function () {
      var currency = $("#lnpw_page_tipping_currency").val();
      var amount = $(this).val();
      var converted = fiat_to_crypto(currency, amount, usd, eur, sats);
      $("#lnpw_page_converted_amount")
        .attr("readonly", false)
        .val(fiat_to_crypto(currency, amount, usd, eur, sats))
        .attr("readonly", true);
      $("#lnpw_page_converted_currency")
        .attr("readonly", false)
        .val(get_currency(currency))
        .attr("readonly", true);
    });

    $("#lnpw_widget_lnpw_skyscraper_tipping_amount_high").on(
      "input",
      function () {
        var currency = $(
          "#lnpw_widget_lnpw_skyscraper_tipping_currency_high"
        ).val();
        var amount = $(this).val();
        var converted = fiat_to_crypto(currency, amount, usd, eur, sats);
        $("#lnpw_widget_lnpw_skyscraper_converted_amount_high")
          .attr("readonly", false)
          .val(fiat_to_crypto(currency, amount, usd, eur, sats))
          .attr("readonly", true);
        $("#lnpw_widget_lnpw_skyscraper_converted_currency_high")
          .attr("readonly", false)
          .val(get_currency(currency))
          .attr("readonly", true);
      }
    );

    $("#lnpw_widget_lnpw_skyscraper_tipping_amount_wide").on(
      "input",
      function () {
        var currency = $(
          "#lnpw_widget_lnpw_skyscraper_tipping_currency_wide"
        ).val();
        var amount = $(this).val();
        var converted = fiat_to_crypto(currency, amount, usd, eur, sats);
        $("#lnpw_widget_lnpw_skyscraper_converted_amount_wide")
          .attr("readonly", false)
          .val(fiat_to_crypto(currency, amount, usd, eur, sats))
          .attr("readonly", true);
        $("#lnpw_widget_lnpw_skyscraper_converted_currency_wide")
          .attr("readonly", false)
          .val(get_currency(currency))
          .attr("readonly", true);
      }
    );
    $("#lnpw_skyscraper_tipping_amount").on("input", function () {
      var currency = $("#lnpw_skyscraper_tipping_currency").val();
      var amount = $(this).val();
      var converted = fiat_to_crypto(currency, amount, usd, eur, sats);
      $("#lnpw_skyscraper_converted_amount")
        .attr("readonly", false)
        .val(fiat_to_crypto(currency, amount, usd, eur, sats))
        .attr("readonly", true);
      $("#lnpw_skyscraper_converted_currency")
        .attr("readonly", false)
        .val(get_currency(currency))
        .attr("readonly", true);
    });
    $("#value_1, #value_2, #value_3").change(function () {
      if ($(this).is(":checked")) {
        var predefined = $(this).val().split(" ");
        var converted_icon = fiat_to_crypto(
          predefined[1],
          predefined[0],
          usd,
          eur,
          sats
        );
        var converted_icon_amount =
          fiat_to_crypto(predefined[1], predefined[0], usd, eur, sats) +
          " " +
          get_currency(predefined[1]);
        $("#lnpw_skyscraper_converted_amount")
          .attr("readonly", false)
          .val(fiat_to_crypto(predefined[1], predefined[0], usd, eur, sats))
          .attr("readonly", true);
        $("#lnpw_skyscraper_converted_currency")
          .attr("readonly", false)
          .val(get_currency(predefined[1]))
          .attr("readonly", true);
      }
    });
    $(
      ".lnpw_page_amount_value_1, .lnpw_page_amount_value_2, .lnpw_page_amount_value_3"
    ).click(function () {
      switch ($(this)[0].className) {
        case "lnpw_page_amount_value_1":
          $(".lnpw_page_amount_value_2").removeClass("selected");
          $(".lnpw_page_amount_value_3").removeClass("selected");
          break;
        case "lnpw_page_amount_value_2":
          $(".lnpw_page_amount_value_1").removeClass("selected");
          $(".lnpw_page_amount_value_3").removeClass("selected");
          break;
        case "lnpw_page_amount_value_3":
          $(".lnpw_page_amount_value_1").removeClass("selected");
          $(".lnpw_page_amount_value_2").removeClass("selected");
          break;
        default:
          $(".lnpw_page_amount_value_1").removeClass("selected");
          $(".lnpw_page_amount_value_2").removeClass("selected");
          $(".lnpw_page_amount_value_3").removeClass("selected");
      }
      $(this).children().find("input:radio").prop("checked", true).change();
      if ($(this).children().find("input:radio").is(":checked")) {
        $(this).toggleClass("selected");
      }
      var predefined = $(this).children().find("input:radio").val().split(" ");
      var converted_icon = fiat_to_crypto(
        predefined[1],
        predefined[0],
        usd,
        eur,
        sats
      );
      var converted_icon_amount =
        fiat_to_crypto(predefined[1], predefined[0], usd, eur, sats) +
        " " +
        get_currency(predefined[1]);
      $("#lnpw_page_converted_amount")
        .attr("readonly", false)
        .val(fiat_to_crypto(predefined[1], predefined[0], usd, eur, sats))
        .attr("readonly", true);
      $("#lnpw_page_converted_currency")
        .attr("readonly", false)
        .val(get_currency(predefined[1]))
        .attr("readonly", true);
    });

    $(
      ".lnpw_skyscraper_amount_value_1.high, .lnpw_skyscraper_amount_value_2.high, .lnpw_skyscraper_amount_value_3.high"
    ).click(function () {
      switch ($(this)[0].className) {
        case "lnpw_skyscraper_amount_value_1.high":
          $(".lnpw_skyscraper_amount_value_2.high").removeClass("selected");
          $(".lnpw_skyscraper_amount_value_3.high").removeClass("selected");
          break;
        case "lnpw_skyscraper_amount_value_2.high":
          $(".lnpw_skyscraper_amount_value_1.high").removeClass("selected");
          $(".lnpw_skyscraper_amount_value_3.high").removeClass("selected");
          break;
        case "lnpw_skyscraper_amount_value_3.high":
          $(".lnpw_skyscraper_amount_value_1.high").removeClass("selected");
          $(".lnpw_skyscraper_amount_value_2.high").removeClass("selected");
          break;
        default:
          $(".lnpw_skyscraper_amount_value_1.high").removeClass("selected");
          $(".lnpw_skyscraper_amount_value_2.high").removeClass("selected");
          $(".lnpw_skyscraper_amount_value_3.high").removeClass("selected");
      }
      $(this).children().find("input:radio").prop("checked", true).change();
      if ($(this).children().find("input:radio").is(":checked")) {
        $(this).toggleClass("selected");
      }
      var predefined = $(this).children().find("input:radio").val().split(" ");
      var converted_icon = fiat_to_crypto(
        predefined[1],
        predefined[0],
        usd,
        eur,
        sats
      );
      var converted_icon_amount =
        fiat_to_crypto(predefined[1], predefined[0], usd, eur, sats) +
        " " +
        get_currency(predefined[1]);
      $("#lnpw_skyscraper_converted_amount")
        .attr("readonly", false)
        .val(fiat_to_crypto(predefined[1], predefined[0], usd, eur, sats))
        .attr("readonly", true);
      $("#lnpw_skyscraper_converted_currency")
        .attr("readonly", false)
        .val(get_currency(predefined[1]))
        .attr("readonly", true);
    });

    $(
      ".lnpw_skyscraper_amount_value_1.wide, .lnpw_skyscraper_amount_value_2.wide, .lnpw_skyscraper_amount_value_3.wide"
    ).click(function () {
      switch ($(this)[0].className) {
        case "lnpw_skyscraper_amount_value_1.wide":
          $(".lnpw_skyscraper_amount_value_2.wide").removeClass("selected");
          $(".lnpw_skyscraper_amount_value_3.wide").removeClass("selected");
          break;
        case "lnpw_skyscraper_amount_value_2.wide":
          $(".lnpw_skyscraper_amount_value_1.wide").removeClass("selected");
          $(".lnpw_skyscraper_amount_value_3.wide").removeClass("selected");
          break;
        case "lnpw_skyscraper_amount_value_3.wide":
          $(".lnpw_skyscraper_amount_value_1.wide").removeClass("selected");
          $(".lnpw_skyscraper_amount_value_2.wide").removeClass("selected");
          break;
        default:
          $(".lnpw_skyscraper_amount_value_1.wide").removeClass("selected");
          $(".lnpw_skyscraper_amount_value_2.wide").removeClass("selected");
          $(".lnpw_skyscraper_amount_value_3.wide").removeClass("selected");
      }
      $(this).children().find("input:radio").prop("checked", true).change();
      if ($(this).children().find("input:radio").is(":checked")) {
        $(this).toggleClass("selected");
      }
      var predefined = $(this).children().find("input:radio").val().split(" ");
      var converted_icon = fiat_to_crypto(
        predefined[1],
        predefined[0],
        usd,
        eur,
        sats
      );
      var converted_icon_amount =
        fiat_to_crypto(predefined[1], predefined[0], usd, eur, sats) +
        " " +
        get_currency(predefined[1]);
      $("#lnpw_skyscraper_converted_amount")
        .attr("readonly", false)
        .val(fiat_to_crypto(predefined[1], predefined[0], usd, eur, sats))
        .attr("readonly", true);
      $("#lnpw_skyscraper_converted_currency")
        .attr("readonly", false)
        .val(get_currency(predefined[1]))
        .attr("readonly", true);
    });

    $(
      ".lnpw_widget.lnpw_skyscraper_amount_value_1.high, .lnpw_widget.lnpw_skyscraper_amount_value_2.high, .lnpw_widget.lnpw_skyscraper_amount_value_3.high"
    ).click(function () {
      switch ($(this)[0].className) {
        case "lnpw_widget.lnpw_skyscraper_amount_value_1.high":
          $(".lnpw_widget.lnpw_skyscraper_amount_value_2.high").removeClass(
            "selected"
          );
          $(".lnpw_widget.lnpw_skyscraper_amount_value_3.high").removeClass(
            "selected"
          );
          break;
        case "lnpw_widget.lnpw_skyscraper_amount_value_2.high":
          $(".lnpw_widget.lnpw_skyscraper_amount_value_1.high").removeClass(
            "selected"
          );
          $(".lnpw_widget.lnpw_skyscraper_amount_value_3.high").removeClass(
            "selected"
          );
          break;
        case "lnpw_widget.lnpw_skyscraper_amount_value_3.high":
          $(".lnpw_widget.lnpw_skyscraper_amount_value_1.high").removeClass(
            "selected"
          );
          $(".lnpw_widget.lnpw_skyscraper_amount_value_2.high").removeClass(
            "selected"
          );
          break;
        default:
          $(".lnpw_widget.lnpw_skyscraper_amount_value_1.high").removeClass(
            "selected"
          );
          $(".lnpw_widget.lnpw_skyscraper_amount_value_2.high").removeClass(
            "selected"
          );
          $(".lnpw_widget.lnpw_skyscraper_amount_value_3.high").removeClass(
            "selected"
          );
      }
      $(this).children().find("input:radio").prop("checked", true).change();
      if ($(this).children().find("input:radio").is(":checked")) {
        $(this).toggleClass("selected");
      }
      var predefined = $(this).children().find("input:radio").val().split(" ");
      var converted_icon = fiat_to_crypto(
        predefined[1],
        predefined[0],
        usd,
        eur,
        sats
      );
      var converted_icon_amount =
        fiat_to_crypto(predefined[1], predefined[0], usd, eur, sats) +
        " " +
        get_currency(predefined[1]);
      $("#lnpw_widget_lnpw_skyscraper_converted_amount_high")
        .attr("readonly", false)
        .val(fiat_to_crypto(predefined[1], predefined[0], usd, eur, sats))
        .attr("readonly", true);
      $("#lnpw_widget_lnpw_skyscraper_converted_currency_high")
        .attr("readonly", false)
        .val(get_currency(predefined[1]))
        .attr("readonly", true);
    });

    $(
      ".lnpw_widget.lnpw_skyscraper_amount_value_1.wide, .lnpw_widget.lnpw_skyscraper_amount_value_2.wide, .lnpw_widget.lnpw_skyscraper_amount_value_3.wide"
    ).click(function () {
      switch ($(this)[0].className) {
        case "lnpw_widget.lnpw_skyscraper_amount_value_1.wide":
          $(".lnpw_widget.lnpw_skyscraper_amount_value_2.wide").removeClass(
            "selected"
          );
          $(".lnpw_widget.lnpw_skyscraper_amount_value_3.wide").removeClass(
            "selected"
          );
          break;
        case "lnpw_widget.lnpw_skyscraper_amount_value_2.wide":
          $(".lnpw_widget.lnpw_skyscraper_amount_value_1.wide").removeClass(
            "selected"
          );
          $(".lnpw_widget.lnpw_skyscraper_amount_value_3.wide").removeClass(
            "selected"
          );
          break;
        case "lnpw_widget.lnpw_skyscraper_amount_value_3.wide":
          $(".lnpw_widget.lnpw_skyscraper_amount_value_1.wide").removeClass(
            "selected"
          );
          $(".lnpw_widget.lnpw_skyscraper_amount_value_2.wide").removeClass(
            "selected"
          );
          break;
        default:
          $(".lnpw_widget.lnpw_skyscraper_amount_value_1.wide").removeClass(
            "selected"
          );
          $(".lnpw_widget.lnpw_skyscraper_amount_value_2.wide").removeClass(
            "selected"
          );
          $(".lnpw_widget.lnpw_skyscraper_amount_value_3.wide").removeClass(
            "selected"
          );
      }
      $(this).children().find("input:radio").prop("checked", true).change();
      if ($(this).children().find("input:radio").is(":checked")) {
        $(this).toggleClass("selected");
      }
      var predefined = $(this).children().find("input:radio").val().split(" ");
      var converted_icon = fiat_to_crypto(
        predefined[1],
        predefined[0],
        usd,
        eur,
        sats
      );
      var converted_icon_amount =
        fiat_to_crypto(predefined[1], predefined[0], usd, eur, sats) +
        " " +
        get_currency(predefined[1]);
      $("#lnpw_widget_lnpw_skyscraper_converted_amount_wide")
        .attr("readonly", false)
        .val(fiat_to_crypto(predefined[1], predefined[0], usd, eur, sats))
        .attr("readonly", true);
      $("#lnpw_widget_lnpw_skyscraper_converted_currency_wide")
        .attr("readonly", false)
        .val(get_currency(predefined[1]))
        .attr("readonly", true);
    });
  });
  function fiat_to_crypto(currency, val, usd, eur, sats) {
    var value = Number(val);
    switch (currency) {
      case "BTC":
        return (value * usd).toFixed(2);
      case "USD":
        return ((sats / usd) * value).toFixed(0);
      case "EUR":
        return ((sats / eur) * value).toFixed(0);
      default:
        return ((usd / sats) * value).toFixed(2);
    }
  }
  function get_currency(currency) {
    switch (currency) {
      case "BTC":
        return "USD";
      case "USD":
        return "SATS";
      case "EUR":
        return "SATS";
      default:
        return "USD";
    }
  }

  $(document).ready(function () {
    $("input[type=radio][name=lnpw_tipping_default_amount]").change(
      function () {
        $("input[type=radio][name=lnpw_tipping_default_amount]").attr(
          "required",
          true
        );
        $("#lnpw_tipping_amount").removeAttr("required");
        $("#lnpw_tipping_amount").val("");
      }
    );
    $("#lnpw_tipping_amount").click(function () {
      $("#lnpw_tipping_amount").attr("required", true);
      $("input[type=radio][name=lnpw_tipping_default_amount]").removeAttr(
        "required"
      );
      $("input[type=radio][name=lnpw_tipping_default_amount]").prop(
        "checked",
        false
      );
    });

    $(
      "input[type=radio][name=lnpw_skyscraper_tipping_default_amount_wide]"
    ).change(function () {
      $(
        "input[type=radio][name=lnpw_skyscraper_tipping_default_amount_wide]"
      ).attr("required", true);
      $("input[name=lnpw_skyscraper_tipping_amount_wide]").removeAttr(
        "required"
      );
      $("input[name=lnpw_skyscraper_tipping_amount_wide]").val("");
    });
    $("input[name=lnpw_skyscraper_tipping_amount_wide]").click(function () {
      $("input[name=lnpw_skyscraper_tipping_amount_wide]").attr(
        "required",
        true
      );
      $(
        "input[type=radio][name=lnpw_skyscraper_tipping_default_amount_wide]"
      ).removeAttr("required");
      $(
        "input[type=radio][name=lnpw_skyscraper_tipping_default_amount_wide]"
      ).prop("checked", false);
      $(".lnpw_skyscraper_amount_value_1.wide").removeClass("selected");
      $(".lnpw_skyscraper_amount_value_2.wide").removeClass("selected");
      $(".lnpw_skyscraper_amount_value_3.wide").removeClass("selected");
    });

    $(
      "input[type=radio][name=lnpw_skyscraper_tipping_default_amount_high]"
    ).change(function () {
      $(
        "input[type=radio][name=lnpw_skyscraper_tipping_default_amount_high]"
      ).attr("required", true);
      $("input[name=lnpw_skyscraper_tipping_amount_high]").removeAttr(
        "required"
      );
      $("input[name=lnpw_skyscraper_tipping_amount_high]").val("");
    });
    $("input[name=lnpw_skyscraper_tipping_amount_high]").click(function () {
      $("input[name=lnpw_skyscraper_tipping_amount_high]").attr(
        "required",
        true
      );
      $(
        "input[type=radio][name=lnpw_skyscraper_tipping_default_amount_high]"
      ).removeAttr("required");
      $(
        "input[type=radio][name=lnpw_skyscraper_tipping_default_amount_high]"
      ).prop("checked", false);
      $(".lnpw_skyscraper_amount_value_1.high").removeClass("selected");
      $(".lnpw_skyscraper_amount_value_2.high").removeClass("selected");
      $(".lnpw_skyscraper_amount_value_3.high").removeClass("selected");
    });

    $(
      "input[type=radio][name=lnpw_widget_lnpw_skyscraper_tipping_default_amount_wide]"
    ).change(function () {
      $(
        "input[type=radio][name=lnpw_widget_lnpw_skyscraper_tipping_default_amount_wide]"
      ).attr("required", true);
      $(
        "input[name=lnpw_widget_lnpw_skyscraper_tipping_amount_wide]"
      ).removeAttr("required");
      $("input[name=lnpw_widget_lnpw_skyscraper_tipping_amount_wide]").val("");
    });
    $("input[name=lnpw_widget_lnpw_skyscraper_tipping_amount_wide]").click(
      function () {
        $("input[name=lnpw_widget_lnpw_skyscraper_tipping_amount_wide]").attr(
          "required",
          true
        );
        $(
          "input[type=radio][name=lnpw_widget_lnpw_skyscraper_tipping_default_amount_wide]"
        ).removeAttr("required");
        $(
          "input[type=radio][name=lnpw_widget_lnpw_skyscraper_tipping_default_amount_wide]"
        ).prop("checked", false);
        $(".lnpw_widget.lnpw_skyscraper_amount_value_1.wide").removeClass(
          "selected"
        );
        $(".lnpw_widget.lnpw_skyscraper_amount_value_2.wide").removeClass(
          "selected"
        );
        $(".lnpw_widget.lnpw_skyscraper_amount_value_3.wide").removeClass(
          "selected"
        );
      }
    );

    $(
      "input[type=radio][name=lnpw_widget_lnpw_skyscraper_tipping_default_amount_high]"
    ).change(function () {
      $(
        "input[type=radio][name=lnpw_widget_lnpw_skyscraper_tipping_default_amount_high]"
      ).attr("required", true);
      $(
        "input[name=lnpw_widget_lnpw_skyscraper_tipping_amount_high]"
      ).removeAttr("required");
      $("input[name=lnpw_widget_lnpw_skyscraper_tipping_amount_high]").val("");
    });
    $("input[name=lnpw_widget_lnpw_skyscraper_tipping_amount_high]").click(
      function () {
        $("input[name=lnpw_widget_lnpw_skyscraper_tipping_amount_high]").attr(
          "required",
          true
        );
        $(
          "input[type=radio][name=lnpw_widget_lnpw_skyscraper_tipping_default_amount_high]"
        ).removeAttr("required");
        $(
          "input[type=radio][name=lnpw_widget_lnpw_skyscraper_tipping_default_amount_high]"
        ).prop("checked", false);
        $("lnpw_widget.lnpw_skyscraper_amount_value_1.high").removeClass(
          "selected"
        );
        $(".lnpw_widget.lnpw_skyscraper_amount_value_2.high").removeClass(
          "selected"
        );
        $(".lnpw_widget.lnpw_skyscraper_amount_value_3.high").removeClass(
          "selected"
        );
      }
    );
  });
  $(document).ready(function () {
    $(
      "input[type=radio][name=lnpw_widget_lnpw_skyscraper_tipping_default_amount_high]"
    ).change(function () {
      $(
        "input[type=radio][name=lnpw_widget_lnpw_skyscraper_tipping_default_amount_high]"
      ).attr("required", true);
      $("#lnpw_widget_lnpw_skyscraper_tipping_amount_high").removeAttr(
        "required"
      );
      $("#lnpw_widget_lnpw_skyscraper_tipping_amount_high").val("");
    });
    $("#lnpw_widget_lnpw_skyscraper_tipping_amount_high").click(function () {
      $("#lnpw_widget_lnpw_skyscraper_tipping_amount_high").attr(
        "required",
        true
      );
      $(
        "input[type=radio][name=lnpw_widget_lnpw_skyscraper_tipping_default_amount_high]"
      ).removeAttr("required");
      $(
        "input[type=radio][name=lnpw_widget_lnpw_skyscraper_tipping_default_amount_high]"
      ).prop("checked", false);
    });
  });
  $(document).ready(function () {
    $(
      "input[name=lnpw_widget_lnpw_skyscraper_tipping_default_amount_wide]"
    ).change(function () {
      $(
        "input[type=radio][name=lnpw_widget_lnpw_skyscraper_tipping_default_amount_wide]"
      ).attr("required", true);
      $("#lnpw_widget_lnpw_skyscraper_tipping_amount_wide").removeAttr(
        "required"
      );
      $("#lnpw_widget_lnpw_skyscraper_tipping_amount_wide").val("");
    });
    $("#lnpw_widget_lnpw_skyscraper_tipping_amount_wide").click(function () {
      $("#lnpw_widget_lnpw_skyscraper_tipping_amount_wide").attr(
        "required",
        true
      );
      $(
        "input[type=radio][name=lnpw_widget_lnpw_skyscraper_tipping_default_amount_wide]"
      ).removeAttr("required");
      $(
        "input[type=radio][name=lnpw_widget_lnpw_skyscraper_tipping_default_amount_wide]"
      ).prop("checked", false);
    });

    $("input[type=radio][name=lnpw_page_tipping_default_amount]").change(
      function () {
        $("input[type=radio][name=lnpw_page_tipping_default_amount]").attr(
          "required",
          true
        );
        $("#lnpw_page_tipping_amount").removeAttr("required");
        $("#lnpw_page_tipping_amount").val("");
      }
    );
    $("#lnpw_page_tipping_amount").click(function () {
      $("#lnpw_page_tipping_amount").attr("required", true);
      $("input[type=radio][name=lnpw_page_tipping_default_amount]").removeAttr(
        "required"
      );
      $("input[type=radio][name=lnpw_page_tipping_default_amount]").prop(
        "checked",
        false
      );
      $(".lnpw_page_amount_value_1").removeClass("selected");
      $(".lnpw_page_amount_value_2").removeClass("selected");
      $(".lnpw_page_amount_value_3").removeClass("selected");
    });
  });

  $(document).ready(function () {
    var form_count = 1,
      previous_form,
      next_form,
      total_forms;
    total_forms = $(".lnpw_skyscraper_tipping_container fieldset").length;
    var freeInput = $(
      "input[name=lnpw_widget_lnpw_skyscraper_tipping_amount_high]"
    );
    var fixedAmount = $(
      "input[type=radio][name=lnpw_widget_lnpw_skyscraper_tipping_default_amount_high]"
    );
    var validationField =
      fixedAmount.length !== 0 && freeInput.length === 0
        ? fixedAmount
        : freeInput;

    $("input.lnpw_widget.skyscraper-next-form.high").click(function () {
      if (validationField[0].checkValidity()) {
        previous_form = $(this).parent().parent().parent();
        next_form = $(this).parent().parent().parent().next();
        next_form.show();
        previous_form.hide();
      } else {
        validationField[0].reportValidity();
      }
    });
    $("input.lnpw_widget.skyscraper-previous-form.high").click(function () {
      previous_form = $(this).parent().parent().parent();
      next_form = $(this).parent().parent().parent().prev();
      next_form.show();
      previous_form.hide();
    });
  });

  $(document).ready(function () {
    var form_count = 1,
      previous_form,
      next_form,
      total_forms;
    total_forms = $(".lnpw_skyscraper_tipping_container fieldset").length;
    var freeInput = $(
      "input[name=lnpw_widget_lnpw_skyscraper_tipping_amount_wide]"
    );
    var fixedAmount = $(
      "input[type=radio][name=lnpw_widget_lnpw_skyscraper_tipping_default_amount_wide]"
    );

    var validationField =
      fixedAmount.length !== 0 && freeInput.length === 0
        ? fixedAmount
        : freeInput;

    $("input.lnpw_widget.skyscraper-next-form.wide").click(function () {
      if (validationField[0].checkValidity()) {
        previous_form = $(this).parent().parent().parent();
        next_form = $(this).parent().parent().parent().next();
        next_form.show();
        previous_form.hide();
      } else {
        validationField[0].reportValidity();
      }
    });
    $("input.lnpw_widget.skyscraper-previous-form.wide").click(function () {
      previous_form = $(this).parent().parent().parent();
      next_form = $(this).parent().parent().parent().prev();
      next_form.show();
      previous_form.hide();
    });
  });
  $(document).ready(function () {
    var form_count = 1,
      previous_form,
      next_form,
      total_forms;
    total_forms = $(".lnpw_page_tipping_container fieldset").length;
    var freeInput = $("#lnpw_page_tipping_amount");
    var fixedAmount = $(".lnpw_page_tipping_default_amount");
    var validationField =
      fixedAmount.length !== 0 && freeInput.length === 0
        ? fixedAmount
        : freeInput;

    $("input.page-next-form").click(function () {
      if (validationField[0].checkValidity()) {
        $(".lnpw_page_bar_container.bar-1").removeClass("active");
        $(".lnpw_page_bar_container.bar-2").addClass("active");
        previous_form = $(this).parent().parent();
        next_form = $(this).parent().parent().next();
        next_form.show();
        previous_form.hide();
      } else {
        validationField[0].reportValidity();
      }
    });
    $("input.page-previous-form").click(function () {
      $(".lnpw_page_bar_container.bar-2").removeClass("active");
      $(".lnpw_page_bar_container.bar-1").addClass("active");
      previous_form = $(this).parent().parent().parent();
      next_form = $(this).parent().parent().parent().prev();
      next_form.show();
      previous_form.hide();
    });
  });
  $(document).ready(function () {
    var form_count = 1,
      previous_form,
      next_form,
      total_forms;
    total_forms = $(".lnpw_skyscraper_tipping_container fieldset").length;
    var freeInput = $("input[name=lnpw_skyscraper_tipping_amount_high]");
    var fixedAmount = $(
      "input[type=radio][name=lnpw_skyscraper_tipping_default_amount_high]"
    );
    var validationField =
      fixedAmount.length !== 0 && freeInput.length === 0
        ? fixedAmount
        : freeInput;

    $("input.skyscraper-next-form.high").click(function () {
      if (validationField[0].checkValidity()) {
        previous_form = $(this).parent().parent().parent();
        next_form = $(this).parent().parent().parent().next();
        next_form.show();
        previous_form.hide();
      } else {
        validationField[0].reportValidity();
      }
    });
    $("input.skyscraper-previous-form.high").click(function () {
      previous_form = $(this).parent().parent().parent();
      next_form = $(this).parent().parent().parent().prev();
      next_form.show();
      previous_form.hide();
    });
  });

  $(document).ready(function () {
    var form_count = 1,
      previous_form,
      next_form,
      total_forms;
    total_forms = $(".lnpw_skyscraper_tipping_container fieldset").length;
    var freeInput = $("input[name=lnpw_skyscraper_tipping_amount_wide]");
    var fixedAmount = $(
      "input[type=radio][name=lnpw_skyscraper_tipping_default_amount_wide]"
    );
    var validationField =
      fixedAmount.length !== 0 && freeInput.length === 0
        ? fixedAmount
        : freeInput;

    $("input.skyscraper-next-form.wide").click(function () {
      if (validationField[0].checkValidity()) {
        previous_form = $(this).parent().parent().parent();
        next_form = $(this).parent().parent().parent().next();
        next_form.show();
        previous_form.hide();
      } else {
        validationField[0].reportValidity();
      }
    });
    $("input.skyscraper-previous-form.wide").click(function () {
      previous_form = $(this).parent().parent().parent();
      next_form = $(this).parent().parent().parent().prev();
      next_form.show();
      previous_form.hide();
    });
  });
})(jQuery);
