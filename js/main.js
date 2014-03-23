/*http://pingvisha.ru*/
$(document).ready(function() {
    $(".radiobtn").on("click", function () {
        var input_radio = $(this).parent().prev().children("input");
        input_radio.prop("checked", false);
        input_radio.eq($(this).index()).prop("checked", true);
        $(this).parent().children().removeClass("active");
        $(this).addClass("active");
    });

    $("input:checked").each(function () {
       $(this).parent().next().children().eq($(this).index()).addClass("active");
    });
});