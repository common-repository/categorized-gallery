
jQuery(document).ready(function ($) {
    jQuery(".agal_category_shortcode").attr('title', 'Click to copy');
    jQuery(".agal_category_shortcode").css('cursor', 'pointer');

    jQuery(".agal_category_shortcode").on("click", function () {
        click_to_copy(jQuery(this));
    });

    function click_to_copy($this) {
        var $temp = jQuery("<input>");
        jQuery("body").append($temp);
        $temp.val($this.text()).select();
        document.execCommand("copy");
        $temp.remove();
    };

    // $("#example").sortable({
    //     items: 'tr',
    //     dropOnEmpty: false,
    //     start: function (G, ui) {
    //         ui.item.addClass("select");
    //     },
    //     stop: function (G, ui) {
    //         ui.item.removeClass("select");
    //         $(this).find("tr").each(function (GFG) {
    //             if (GFG > 0) {
    //                 $(this).find("td").eq(1).html(GFG);
    //             }
    //         });
    //     }
    // });


});