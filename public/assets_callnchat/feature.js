function getIdFeature(ev) {
    let idFeature = ev.currentTarget.getAttribute("data-id");
    $("#idFeature").val(idFeature);
}

function hideDesc() {
    if ($("#idSelected").val() != "") {
        if ($("#idFeature").val() != $("#idSelected").val()) {
            $(".rotate-img").removeClass("rotate");
            $(".kotak-isi").slideUp();
        }
    }
}

var Accordion = function (el, multiple) {
    this.el = el || {};
    this.multiple = multiple || false;
    var links = this.el.find(".label-title");
    links.on("click", { el: this.el, multiple: this.multiple }, this.dropdown);
};

Accordion.prototype.dropdown = function (e) {
    ($this = $(this)), ($next = $this.next());
    let btn = $(this)[0].id;
    let split = btn.split("_")[1];
    $("#idSelected").val(split);
    $("#idFeature_" + split).toggleClass("rotate");
    $("#kotak_" + split).slideToggle();
};

var accordion = new Accordion($(".feature-box"), false);
