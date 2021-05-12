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
})(jQuery);
