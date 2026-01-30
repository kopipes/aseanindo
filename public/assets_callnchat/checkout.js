(async () => {
    // modal elements
    const modal = document.querySelector(".modal-popup");
    const modalCloseTrigger = document.querySelector(
        ".modal-popup__icon-close"
    );
    const bodyBlackout = document.querySelector(".modal-background");
    const iframe = document.getElementById("iframe-invoice");
    const buttonStartPayment = document.getElementById("button-start-payment");
    const amount = parseInt($("#totalAmount").val());
    const idOrder = $("#idOrder").val();
    const statusInvoice = $("#statusInvoice").val();
    const currency = "IDR";

    let invoiceUrl;

    if (statusInvoice != "Pay") {
        $("#button-start-payment").css({
            "pointer-events": "none",
            background: "#fcd267",
        });
        $("#tableBody").mouseover(function () {
            window.location.replace("/");
        });
    }

    $("#button-start-payment").click(function () {
        startPayment();
    });

    modalCloseTrigger.addEventListener("click", () => {
        modal.classList.remove("modal-popup--visible");
        bodyBlackout.classList.remove("modal-background--blackout");
    });

    const startPayment = async () => {
        if (!invoiceUrl) {
            const invoiceData = {
                currency,
                idOrder,
                amount,
                redirect_url: `${window.location.origin}`,
            };

            try {
                const response = await fetch("/api/payment", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json;charset=utf-8",
                    },
                    body: JSON.stringify(invoiceData),
                });

                const data = await response.json();

                if (
                    response.status >= 200 &&
                    response.status <= 299 &&
                    typeof data.invoice_url !== "undefined"
                ) {
                    invoiceUrl = data.invoice_url;
                    localStorage.setItem("token", data.id);
                    localStorage.setItem("user_id", data.user_id);
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert(error);
            }
        }

        launchModal();
        loadingDemoLaunch();
    };

    const launchModal = () => {
        iframe.src = invoiceUrl;
        modal.classList.add("modal-popup--visible");
        bodyBlackout.classList.add("modal-background--blackout");
    };

    const loadingDemoLaunch = () => {
        buttonStartPayment.disabled = !buttonStartPayment.disabled;
    };
    document.body.classList.remove("preload");
})();
