var lebar = window.screen.width;

if (lebar <= 640) {
    var splide = new Splide(".splide", {
        perPage: 1,
        rewind: true,
        arrows: true,
        pagination: false,
    });
} else {
    var splide = new Splide(".splide", {
        perPage: 3,
        rewind: true,
        arrows: true,
        pagination: true,
    });
}
splide.mount();

function getActiveTrial(ev) {
    let idList = ev.currentTarget.getAttribute("data-id");
    $(document).mousemove(function(){
        if($("#list-paket-"+idList+":hover").length != 0){
            $(".list-paket").addClass("mengecil");
        } else{
            $(".list-paket").removeClass("mengecil");
        }
    });
}

function getActive(ev) {
    let idList = ev.currentTarget.getAttribute("data-id");
    $(document).mousemove(function(){
        if($("#list-paket-"+idList+":hover").length != 0){
            $(".list-paket-trial").addClass("mengecil");
            $(".list-paket").addClass("mengecil");
        } else{
            $(".list-paket-trial").removeClass("mengecil");
            $(".list-paket").removeClass("mengecil");
        }
    });
}
