const routeSendEmail = $("#routeSendEmail").val();
const routeReloadCaptcha = $("#routeReloadCaptcha").val();

$("#btnSubmit").prop("disabled", true);

$("#captcha").on("change", function (e) {
    e.preventDefault();
    $("#btnSubmit").focus();
    $("#btnSubmit").prop("disabled", false);
});

$("#btnSubmit").on("click", function (e) {
    e.preventDefault();
    var request = {
        name: $("#name").val(),
        email: $("#email").val(),
        phone_number: $("#phone_number").val(),
        know_us: $("#know_us").val(),
        question: $("#question").val(),
        captcha: $("#captcha").val(),
    };

    $.ajax({
        headers: { "X-CSRF-TOKEN": token },
        type: "POST",
        url: routeSendEmail,
        data: request,
        beforeSend: () => {
            $("#btnSubmit").html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
            );
        },

        success: function (response) {
            console.log(response);
            swal({
                type: response.alert,
                title: response.message,
                showCancelButton: false,
                confirmButtonText: "Ok",
                allowOutsideClick: false,
                allowEscapeKey: false,
            }).then((nextStep) => {
                if (nextStep) {
                    $.ajax({
                        type: "GET",
                        url: routeReloadCaptcha,
                        success: function (data) {
                            $(".captcha span").html(data.captcha);
                        },
                    });
                    $("#formQuestion").trigger("reset");
                    $("#btnSubmit").html("Submit");
                }
            });
        },
        error: function (xhr) {
            var err = JSON.parse(xhr.responseText);
            var errorString = "<ul>";
            $.each(err.errors, function (key, value) {
                errorString += "<p>" + value + "</p>";
            });
            errorString += "</ul>";

            swal({
                type: "warning",
                title: errorString,
                showCancelButton: false,
                confirmButtonColor: "#e3342f",
                confirmButtonText: "Ok",
            });
            $("#btnSubmit").html("Submit");
        },
    });
});
