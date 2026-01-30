const token = $('meta[name="csrf-token"]').attr("content");
const routeReview = $("#routeReview").val();

$("#star-1").click(function () {
    $("#ratingPoint").val(1);
    $("#star-1").attr(
        "src",
        "https://haloyelow-app-storage.oss-ap-southeast-5.aliyuncs.com/star-on.png"
    );
    $("#star-2").attr(
        "src",
        "https://haloyelow-app-storage.oss-ap-southeast-5.aliyuncs.com/star-off.png"
    );
    $("#star-3").attr(
        "src",
        "https://haloyelow-app-storage.oss-ap-southeast-5.aliyuncs.com/star-off.png"
    );
    $("#star-4").attr(
        "src",
        "https://haloyelow-app-storage.oss-ap-southeast-5.aliyuncs.com/star-off.png"
    );
    $("#star-5").attr(
        "src",
        "https://haloyelow-app-storage.oss-ap-southeast-5.aliyuncs.com/star-off.png"
    );
});
$("#star-2").click(function () {
    $("#ratingPoint").val(2);
    $("#star-1").attr(
        "src",
        "https://haloyelow-app-storage.oss-ap-southeast-5.aliyuncs.com/star-on.png"
    );
    $("#star-2").attr(
        "src",
        "https://haloyelow-app-storage.oss-ap-southeast-5.aliyuncs.com/star-on.png"
    );
    $("#star-3").attr(
        "src",
        "https://haloyelow-app-storage.oss-ap-southeast-5.aliyuncs.com/star-off.png"
    );
    $("#star-4").attr(
        "src",
        "https://haloyelow-app-storage.oss-ap-southeast-5.aliyuncs.com/star-off.png"
    );
    $("#star-5").attr(
        "src",
        "https://haloyelow-app-storage.oss-ap-southeast-5.aliyuncs.com/star-off.png"
    );
});
$("#star-3").click(function () {
    $("#ratingPoint").val(3);
    $("#star-1").attr(
        "src",
        "https://haloyelow-app-storage.oss-ap-southeast-5.aliyuncs.com/star-on.png"
    );
    $("#star-2").attr(
        "src",
        "https://haloyelow-app-storage.oss-ap-southeast-5.aliyuncs.com/star-on.png"
    );
    $("#star-3").attr(
        "src",
        "https://haloyelow-app-storage.oss-ap-southeast-5.aliyuncs.com/star-on.png"
    );
    $("#star-4").attr(
        "src",
        "https://haloyelow-app-storage.oss-ap-southeast-5.aliyuncs.com/star-off.png"
    );
    $("#star-5").attr(
        "src",
        "https://haloyelow-app-storage.oss-ap-southeast-5.aliyuncs.com/star-off.png"
    );
});
$("#star-4").click(function () {
    $("#ratingPoint").val(4);
    $("#star-1").attr(
        "src",
        "https://haloyelow-app-storage.oss-ap-southeast-5.aliyuncs.com/star-on.png"
    );
    $("#star-2").attr(
        "src",
        "https://haloyelow-app-storage.oss-ap-southeast-5.aliyuncs.com/star-on.png"
    );
    $("#star-3").attr(
        "src",
        "https://haloyelow-app-storage.oss-ap-southeast-5.aliyuncs.com/star-on.png"
    );
    $("#star-4").attr(
        "src",
        "https://haloyelow-app-storage.oss-ap-southeast-5.aliyuncs.com/star-on.png"
    );
    $("#star-5").attr(
        "src",
        "https://haloyelow-app-storage.oss-ap-southeast-5.aliyuncs.com/star-off.png"
    );
});
$("#star-5").click(function () {
    $("#ratingPoint").val(5);
    $("#star-1").attr(
        "src",
        "https://haloyelow-app-storage.oss-ap-southeast-5.aliyuncs.com/star-on.png"
    );
    $("#star-2").attr(
        "src",
        "https://haloyelow-app-storage.oss-ap-southeast-5.aliyuncs.com/star-on.png"
    );
    $("#star-3").attr(
        "src",
        "https://haloyelow-app-storage.oss-ap-southeast-5.aliyuncs.com/star-on.png"
    );
    $("#star-4").attr(
        "src",
        "https://haloyelow-app-storage.oss-ap-southeast-5.aliyuncs.com/star-on.png"
    );
    $("#star-5").attr(
        "src",
        "https://haloyelow-app-storage.oss-ap-southeast-5.aliyuncs.com/star-on.png"
    );
});

$("#reviewText").change(function () {
    $("#btnSubmit").css({ "pointer-events": "auto", background: "#3943B7" });
});

$("#btnSubmit").click(function () {
    let ticketId = this.href.substring(this.href.lastIndexOf("/") + 1);
    let fixTicketId = ticketId.replace("#", "");
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        type: "POST",
        url: routeReview,
        data: {
            id: fixTicketId,
            rating: $("#ratingPoint").val(),
            review: $("#reviewText").val(),
        },
        success: function (response) {
            swal({
                title: "Successfully posted review for ticket",
                type: "success",
                showCancelButton: false,
                confirmButtonText: "Ok",
                closeOnConfirm: false,
                closeOnCancel: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
            }).then((next) => {
                if (next) {
                    setTimeout(function () {
                        window.location.replace = "/";
                    }, 1000);
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
        },
    });
});
