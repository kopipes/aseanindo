function getIdJob(ev) {
    let idJob = ev.currentTarget.getAttribute("data-id");
    $("#idJob").val(idJob);
}
var Accordion = function (el, multiple) {
    this.el = el || {};
    this.multiple = multiple || false;
    var links = this.el.find(".job-box");
    links.on("click", { el: this.el, multiple: this.multiple }, this.dropdown);
};

Accordion.prototype.dropdown = function (e) {
    ($this = $(this)), ($next = $this.next());
    let btn = $(this)[0].id;
    let split = btn.split("_")[1];
    $("#idSelected").val(split);
    $("#idJob_" + split).toggleClass("rotate-180");
    $("#desc_" + split).slideToggle();
    $("#req_" + split).slideToggle();
};

var accordion = new Accordion($(".karir-box"), false);
