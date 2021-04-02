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
    $("#lnpw_currency").change(function () {
      var stepValue =
        $(this).val() === "BTC"
          ? "0.00000001"
          : $(this).val() === "SATS"
          ? "1"
          : "0.50";

      $("#lnpw_default_price").attr({
        step: stepValue,
        value: parseInt($("#lnpw_default_price").val()),
      });
    });
  });
  $(document).ready(function () {
    $("form").submit(function () {
      if ($("#lnpw_currency").val() === "BTC") {
        $("#lnpw_default_price").val(
          parseFloat($("#lnpw_default_price").val()).toFixed(8)
        );
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
})(jQuery);
