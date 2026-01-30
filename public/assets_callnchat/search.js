// const token = $('meta[name="csrf-token"]').attr("content");
const routeSearch = $("#routeSearch").val();

$("#btnSearch").on("click", function (e) {
    e.preventDefault();

    $.ajax({
        headers: { "X-CSRF-TOKEN": token },
        type: "GET",
        url: routeSearch,
        data: { search: $("#search").val() },
        success: function (response) {
            window.location.href = "/support/faq?search=" + $("#search").val();
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
