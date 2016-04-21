jQuery(document).ready(function() {
    //SHOW SHOWCASE ITEM WIDGET SLIDESHOW
    jQuery(".rslides").responsiveSlides({
        auto: false,
        pager: true,
        nav: true,
        speed: 500,
        namespace: "centered-btns"
    });
    //CATEGORY SORT FUNCTION
    jQuery('.spr_cat_sort').click(function() {
        var sort = jQuery(this).attr('sort');
        var dir = jQuery(this).attr('dir');
        jQuery('#spr_sort').val(sort);
        jQuery('#spr_dir').val(dir);
        jQuery('#modernaSearchForm').submit();
    });
    //CATEGORY PAGE FUNCTION
    jQuery('.spr_category_sort_page').click(function() {
        var rel = jQuery(this).attr('rel');
        jQuery('#spr_page').val(rel);
        jQuery('#modernaSearchForm').submit();
    });
});

