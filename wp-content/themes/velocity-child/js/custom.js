jQuery(document).ready(function($) {
    $(".wp-block-gallery a").each(function() {
        $(this).attr("data-lightbox", "gallery");
    });
    
    jQuery(".velocity-menu").find("li.menu-item-has-children").append('<i class="fa fa-chevron-right" aria-hidden="true"></i>');
    jQuery(".velocity-menu li.menu-item-has-children i").on("click", function() {
        jQuery(this).hasClass("fa-chevron-down") ? jQuery(this).removeClass("fa-chevron-down").parent("li.menu-item-has-children").find("> ul").slideToggle() : jQuery(this).addClass("fa-chevron-down").parent("li.menu-item-has-children").find("> ul").slideToggle()
    })
});