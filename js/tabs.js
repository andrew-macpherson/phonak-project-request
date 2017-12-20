var tab = 0;
jQuery(window).load(function() {
    updateTab(1);
});
jQuery(document).on('click','.tabs-wrapper .next',function(e) {
    e.preventDefault();
    updateTab(1);
});
jQuery(document).on('click','.tabs-wrapper .prev',function(e) {
    e.preventDefault();
    updateTab(-1);
});
function updateTab(val) {
    tab += val;
    if(tab == 0) {
        tab = 1;
    }
    else if(tab > jQuery('.tab').length) {
        tab = jQuery('.tab').length;
    }
    if(tab == 1) {
        jQuery('.prev').addClass('inactive');
    }
    else {
        jQuery('.prev').removeClass('inactive');
    }
    if(tab == jQuery('.tab').length) {
        jQuery('.next').addClass('inactive');
    }
    else {
        jQuery('.next').removeClass('inactive');
    }
    if(tab < jQuery('.tab').length) {
        jQuery('input[type="submit"]').addClass('inactive');
    }
    else {
        jQuery('input[type="submit"]').removeClass('inactive');
    }
    jQuery('.tab.active').removeClass('active');
    jQuery('.tab-'+tab).addClass('active');
    jQuery('.step.active').removeClass('active');
    jQuery('.step-'+tab).addClass('active');
}