jQuery(function ($) {
  var addToAll = false;
  var gallery = true;
  var titlePosition = "inside";
  jQuery(addToAll ? "img" : "img.fancybox").each(function () {
    var $this = $(this);
    var title = $this.attr("title");
    var src = $this.attr("src");
    //alert(src);
    var a = $('<a href="' + src + '" class="fancybox"></a>')
      .attr("href", src)
      .attr("title", title);
    $this.wrap(a);
  });
  if (gallery) jQuery("a.fancybox").attr("rel", "fancyboxgallery");
  jQuery("a.fancybox").fancybox({
    titlePosition: titlePosition,
  });
  $("[data-fancybox]").fancybox({
    // Options will go here
    buttons: ["close"],
    wheel: false,
    transitionEffect: "slide",
    // thumbs          : false,
    // hash            : false,
    loop: true,
    // keyboard        : true,
    toolbar: false,
    // animationEffect : false,
    // arrows          : true,
    clickContent: false,
  });
});
$ = jQuery.noConflict();
