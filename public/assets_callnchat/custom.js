const token = $('meta[name="csrf-token"]').attr("content");
var className = "inverted";
var btn = "btn-color";
var btnOutline = "btn-outline";
var btnOutlineLang = "btn-outline-lang";
var scrollTrigger = 100;

window.onscroll = function () {
    if (
        window.scrollY >= scrollTrigger ||
        window.pageYOffset >= scrollTrigger
    ) {
        document.getElementsByClassName("navbar")[0].classList.add(className);
        document.getElementsByClassName("btn-daftar")[0].classList.add(btn);
        document
            .getElementsByClassName("btn-masuk")[0]
            .classList.add(btnOutline);
        // document
        //     .getElementsByClassName("btn-lang")[0]
        //     .classList.add(btnOutlineLang);
        document.querySelectorAll(".menu-bar > a").forEach((el) => {
            el.style.color = "black";
        });
    } else {
        document
            .getElementsByClassName("navbar")[0]
            .classList.remove(className);
        document.getElementsByClassName("btn-daftar")[0].classList.remove(btn);
        document
            .getElementsByClassName("btn-masuk")[0]
            .classList.remove(btnOutline);
        // document
        //     .getElementsByClassName("btn-lang")[0]
        //     .classList.remove(btnOutlineLang);
        document.querySelectorAll(".menu-bar > a").forEach((el) => {
            el.style.color = "white";
        });
    }
};

var Accordion = function (el, multiple) {
    this.el = el || {};
    this.multiple = multiple || false;
    var links = this.el.find(".title-content-fitur-user");
    links.on("click", { el: this.el, multiple: this.multiple }, this.dropdown);
};

Accordion.prototype.dropdown = function (e) {
    var $el = e.data.el;
    ($this = $(this)), ($next = $this.next());

    $next.slideToggle();
    $this.parent().toggleClass("open");
    let btn = $(this)[0].id;
    let split = btn.split("_")[1];

    $.ajax({
        headers: { "X-CSRF-TOKEN": token },
        type: "POST",
        url: "/get-feature",
        data: { id: split },
        success: function (response) {
            $("#imageFeature-user").attr("src", response.image);
        },
    });

    if (!e.data.multiple) {
        $el.find(".desc-fitur-user")
            .not($next)
            .slideUp()
            .parent()
            .removeClass("open");
    }
};

var accordion = new Accordion($(".content-fitur-user"), false);

function myFunction() {
    var x = document.getElementById("myMenu");
    if (x.className === "menu-bar") {
        x.className += " responsive";
    } else {
        x.className = "menu-bar";
    }
}

function getIdMenu(ev) {
    let id = ev.currentTarget.getAttribute("data-id");
    $(".menu_" + id).toggle("munculgaes");
}
