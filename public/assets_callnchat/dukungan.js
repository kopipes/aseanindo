$("#btnFaq").on("click", function (e) {
    e.preventDefault();
    $(this).addClass("btn-active-hover");
    $("#btnTutor").removeClass("btn-active-hover");
    $(".faq-section").removeClass("non-active-tab").addClass("active-tab");
    $(".tutorial-section").removeClass("active-tab").addClass("non-active-tab");
    $(".rotate-img-tutor").removeClass("rotate-180");
    $(".description-tutor").slideUp();
});

$("#btnTutor").on("click", function (e) {
    e.preventDefault();
    $(this).addClass("btn-active-hover");
    $("#btnFaq").removeClass("btn-active-hover");
    $(".tutorial-section").removeClass("non-active-tab").addClass("active-tab");
    $(".faq-section").removeClass("active-tab").addClass("non-active-tab");
    $(".rotate-img-faq").removeClass("rotate-180");
    $(".description-faq").slideUp();
});

function getIdFaq(ev) {
    let idFaq = ev.currentTarget.getAttribute("data-id");
    $("#idFaq").val(idFaq);
}

function getIdTutorial(ev) {
    let idTutorial = ev.currentTarget.getAttribute("data-id");
    $("#idTutorial").val(idTutorial);
}

function hideDescFaq() {
    if ($("#idSelected").val() != "") {
        if ($("#idFaq").val() != $("#idSelected").val()) {
            $(".rotate-img-faq").removeClass("rotate-180");
            $(".description-faq").slideUp();
        }
    }
}

function hideDescTutorial() {
    if ($("#idSelected").val() != "") {
        if ($("#idTutorial").val() != $("#idSelected").val()) {
            $(".rotate-img-tutor").removeClass("rotate-180");
            $(".description-tutor").slideUp();
        }
    }
}

var Accordion = function (el, multiple) {
    this.el = el || {};
    this.multiple = multiple || false;
    var links = this.el.find(".faq-box");
    links.on("click", { el: this.el, multiple: this.multiple }, this.dropdown);
};

Accordion.prototype.dropdown = function (e) {
    ($this = $(this)), ($next = $this.next());
    let btn = $(this)[0].id;
    let split = btn.split("_")[1];
    $("#idSelected").val(split);
    $("#idFaq_" + split).toggleClass("rotate-180");
    $("#desc_" + split).slideToggle();
    $("#req_" + split).slideToggle();
};

var Accordion2 = function (el, multiple) {
    this.el = el || {};
    this.multiple = multiple || false;
    var links = this.el.find(".tutorial-box");
    links.on("click", { el: this.el, multiple: this.multiple }, this.dropdown);
};

Accordion2.prototype.dropdown = function (e) {
    ($this = $(this)), ($next = $this.next());
    let btn = $(this)[0].id;
    let split = btn.split("_")[1];
    $("#idSelected").val(split);
    $("#idTutorial_" + split).toggleClass("rotate-180");
    $("#desc_tutorial_" + split).slideToggle();
    $("#req_" + split).slideToggle();
};

var accordion = new Accordion($(".dukungan-box"), false);
var accordion2 = new Accordion2($(".dukungan-box"), false);
