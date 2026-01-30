$.ajax({
    headers: { "X-CSRF-TOKEN": token },
    type: "POST",
    url: "api/callback",
    data: {
        token: localStorage.getItem("token"),
        user_id: localStorage.getItem("user_id"),
    },
    success: function (response) {
        setTimeout(function () {
            window.location.replace("/");
        }, 1500);
    },
});
