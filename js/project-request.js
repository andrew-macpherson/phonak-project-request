jQuery(document).on('click','.redirect',function(e) {
    e.preventDefault();
    var destinationUrl = jQuery('select[name="destination"]').val();
    window.location.href = destinationUrl;
});