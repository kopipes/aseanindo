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
