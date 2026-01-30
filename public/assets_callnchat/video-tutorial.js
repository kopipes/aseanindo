// const token = $('meta[name="csrf-token"]').attr("content");
const routeGetVideo = $("#routeGetVideo").val();
const routeGetVideoPrev = $("#routeGetVideoPrev").val();
const routeGetVideoNext = $("#routeGetVideoNext").val();
const base_url = window.location.origin;

var sortingNumber = $("#sortingNumber").val();
var minSorting = $("#minSorting").val();
var maxSorting = $("#maxSorting").val();

cekMinSorting(sortingNumber, minSorting);
cekMaxSorting(sortingNumber, maxSorting);

var tag = document.createElement("script");
tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName("script")[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

var videoPlayer;

function onYouTubeIframeAPIReady() {
    videoPlayer = new YT.Player("frameYoutube", {
        videoId: $("#videoKey").val(),
        playerVars: {
            rel: 0,
            rs: 0,
        },
        events: {
            onStateChange: onPlayerStateChange,
        },
    });
}

function onPlayerStateChange(event) {
    var key = event.target.playerInfo.videoData.video_id;
    switch (event.data) {
        case 0:
            $("#stateVideo").val(0);
            return "Start";
            break;
        case 1:
            $("#stateVideo").val(1);
            cekValue(key);
            break;
        case 2:
            $("#stateVideo").val(2);
            cekValue(key);
    }
}

function cekValue(key) {
    if ($("#stateVideo").val() == 1) {
        $("#playBtn_" + key).hide();
        $("#playBtnMobile_" + key).hide();
        $("#pauseBtn_" + key).show();
        $("#pauseBtnMobile_" + key).show();
    } else if ($("#stateVideo").val() == 2) {
        $("#playBtn_" + key).show();
        $("#playBtnMobile_" + key).show();
        $("#pauseBtn_" + key).hide();
        $("#pauseBtnMobile_" + key).hide();
    }
}

function checkYoutubePaused() {
    let state = videoPlayer.getPlayerState();
    $("#videoKey").val(state);
    if (state <= 0 || state == 2 || state == 5) {
        return true;
    } else {
        return false;
    }
}

function cekMinSorting(sorting, min) {
    if (sorting == min) {
        $(".btn-prev").css("background-color", "#DCDAD4");
        $(".btn-prev").css("pointer-events", "none");
    } else {
        $(".btn-prev").css("background-color", "#feb500");
        $(".btn-prev").css("pointer-events", "auto");
    }
}

function cekMaxSorting(sorting, max) {
    if (sorting == max) {
        $(".btn-next").css("background-color", "#DCDAD4");
        $(".btn-next").css("pointer-events", "none");
    } else {
        $(".btn-next").css("background-color", "#feb500");
        $(".btn-next").css("pointer-events", "auto");
    }
}

function getVideo(ev) {
    ev.preventDefault();
    videoPlayer.destroy();
    let idVideo = ev.currentTarget.getAttribute("data-id");
    let videoKey = ev.currentTarget.getAttribute("id-yutub");
    $("#videoKey").val(videoKey);
    $.ajax({
        headers: { "X-CSRF-TOKEN": token },
        type: "POST",
        url: routeGetVideo,
        data: {
            id: idVideo,
        },
        success: function (response) {
            $(".play-btn").css("pointer-events", "none");
            $(".play-btn-mobile").css("pointer-events", "none");
            $("#playBtn_" + response.key).css("pointer-events", "auto");
            $("#playBtnMobile_" + response.key).css("pointer-events", "auto");
            $(".selected-title").css("color", "black");
            $(".selected-title-mobile").css("color", "black");
            $(".judul-" + idVideo).css("color", "#feb500");
            $(".judul-mobile-" + idVideo).css("color", "#feb500");
            $(".btn-prev").css("background-color", "#DCDAD4");
            $("#sortingNumber").val(response.data.sorting_number);
            $("#minSorting").val(response.minSorting);
            $("#maxSorting").val(response.maxSorting);
            cekMinSorting(response.data.sorting_number, response.minSorting);
            cekMaxSorting(response.data.sorting_number, response.maxSorting);

            $("#idVideo").val(response.data.id);
            $("#subkatId").val(response.data.subkat_id);
            onYouTubeIframeAPIReady();
            $(".play-btn").show();
            $(".play-btn-mobile").show();
            $(".pause-btn").hide();
            $(".pause-btn-mobile").hide();
            $("#frameYoutube").attr(
                "src",
                "https://www.youtube.com/embed/" +
                    response.key +
                    "?rel=0&rs=0&enablejsapi=1&origin=" +
                    base_url +
                    "&widgetid=1"
            );
            $(".label-title").text(response.data.title);
        },
    });
}

function play(ev) {
    ev.preventDefault();
    videoPlayer.playVideo();
    let idVideo = ev.currentTarget.getAttribute("data-id");
    let idVideoMobile = ev.currentTarget.getAttribute("data-id-mobile");
    $("#playBtn_" + idVideo).hide();
    $("#playBtnMobile_" + idVideoMobile).hide();
    $("#pauseBtn_" + idVideo).show();
    $("#pauseBtnMobile_" + idVideoMobile).show();
}

function pause(ev) {
    ev.preventDefault();
    videoPlayer.pauseVideo();
    let idVideo = ev.currentTarget.getAttribute("data-id");
    let idVideoMobile = ev.currentTarget.getAttribute("data-id-mobile");
    $("#playBtn_" + idVideo).show();
    $("#playBtnMobile_" + idVideoMobile).show();
    $("#pauseBtn_" + idVideo).hide();
    $("#pauseBtnMobile_" + idVideoMobile).hide();
}

function getVideoPrev(ev) {
    ev.preventDefault();
    videoPlayer.destroy();
    $.ajax({
        headers: { "X-CSRF-TOKEN": token },
        type: "POST",
        url: routeGetVideoPrev,
        data: {
            id: $("#idVideo").val(),
            subkat_id: $("#subkatId").val(),
        },
        success: function (response) {
            $(".play-btn").css("pointer-events", "none");
            $(".play-btn-mobile").css("pointer-events", "none");
            $("#playBtn_" + response.key).css("pointer-events", "auto");
            $("#playBtnMobile_" + response.key).css("pointer-events", "auto");
            $(".selected-title").css("color", "black");
            $(".selected-title-mobile").css("color", "black");
            $(".judul-" + response.data.id).css("color", "#feb500");
            $(".judul-mobile-" + response.data.id).css("color", "#feb500");
            $("#sortingNumber").val(response.data.sorting_number);
            $("#minSorting").val(response.minSorting);
            $("#maxSorting").val(response.maxSorting);
            cekMinSorting(response.data.sorting_number, response.minSorting);
            cekMaxSorting(response.data.sorting_number, response.maxSorting);
            $("#idVideo").val(response.data.id);
            $("#subkatId").val(response.data.subkat_id);
            $(".label-title").text(response.data.title);
            onYouTubeIframeAPIReady();
            $(".play-btn").show();
            $(".play-btn-mobile").show();
            $(".pause-btn").hide();
            $(".pause-btn-mobile").hide();
            $("#frameYoutube").attr(
                "src",
                "https://www.youtube.com/embed/" +
                    response.key +
                    "?rel=0&rs=0&enablejsapi=1&origin=" +
                    base_url +
                    "&widgetid=1"
            );
        },
    });
}

function getVideoNext(ev) {
    ev.preventDefault();
    videoPlayer.destroy();
    $.ajax({
        headers: { "X-CSRF-TOKEN": token },
        type: "POST",
        url: routeGetVideoNext,
        data: {
            id: $("#idVideo").val(),
            subkat_id: $("#subkatId").val(),
        },
        success: function (response) {
            $(".play-btn").css("pointer-events", "none");
            $(".play-btn-mobile").css("pointer-events", "none");
            $("#playBtn_" + response.key).css("pointer-events", "auto");
            $("#playBtnMobile_" + response.key).css("pointer-events", "auto");
            $(".selected-title").css("color", "black");
            $(".selected-title-mobile").css("color", "black");
            $(".judul-" + response.data.id).css("color", "#feb500");
            $(".judul-mobile-" + response.data.id).css("color", "#feb500");
            $("#sortingNumber").val(response.data.sorting_number);
            $("#minSorting").val(response.minSorting);
            $("#maxSorting").val(response.maxSorting);
            cekMinSorting(response.data.sorting_number, response.minSorting);
            cekMaxSorting(response.data.sorting_number, response.maxSorting);
            $("#idVideo").val(response.data.id);
            $("#subkatId").val(response.data.subkat_id);
            $(".label-title").text(response.data.title);
            onYouTubeIframeAPIReady();
            $(".play-btn").show();
            $(".play-btn-mobile").show();
            $(".pause-btn").hide();
            $(".pause-btn-mobile").hide();
            $("#frameYoutube").attr(
                "src",
                "https://www.youtube.com/embed/" +
                    response.key +
                    "?rel=0&rs=0&enablejsapi=1&origin=" +
                    base_url +
                    "&widgetid=1"
            );
        },
    });
}
