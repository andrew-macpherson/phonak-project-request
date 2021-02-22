var fileCount = 1;
jQuery(document).ready(function(){

    //jQuery(".datepicker").datepicker();

    jQuery('input[name="due_date"], input[name="project_latest_date"]').change(function(){
        var siteId = jQuery('#siteID').val();

        // IF UK SITE ONLY
        if(siteId == 2){
            var due_date = jQuery('input[name="due_date"], input[name="project_latest_date"]').val();

            var now = moment(new Date()); //todays date
            var end = moment(due_date); // another date
            var duration = moment.duration(end.diff(now));
            var days = duration.asDays();
            console.log(days)

            if (days < 5) {
                alert('You are submitting a request with less than the minimum required days to complete a project, please provide a reason why this is needed urgently in the product description box');
                return false;
            }
        }
    });

    if (jQuery("input[name='project_approved']").length >= 1){
        var val = jQuery("input[name='project_approved']:checked").val();
        if (val == "Yes"){
            enable_option('approved-option','Approved by');
        }
    jQuery("input[name='project_approved']").change(function(){
        var val = jQuery(this).val();
        if (val == "Yes"){
            enable_option('approved-option','Approved by');
        }
        else {
            if (jQuery('#approved-option').is(":visible")){
                jQuery('#approved-option').hide();
                jQuery('#approved-option input').prop('disabled', true);
            }
        }
    });
}

    if (jQuery("#project_distribution").length >= 1){
            var val = jQuery("#project_distribution").val();
            if (val == "Eblast"){
                enable_option('distribution-option','Insert SF Criteria');
            }
            else if (val == "Printer mailer"){
                enable_option('distribution-option','Insert Target Group');
            }
            else if (val == "OTHER"){
                enable_option('distribution-option','Other');
            }
        jQuery("#project_distribution").change(function(){
            var val = jQuery(this).val();
            if (val == "Eblast"){
                enable_option('distribution-option','Insert SF Criteria');

            }
            else if (val == "Printer mailer"){
                enable_option('distribution-option','Insert Target Group');
            }
            else if (val == "OTHER"){
                enable_option('distribution-option','Other');
            }
            else {
                if (jQuery('#distribution-option').is(":visible")){
                    jQuery('#distribution-option').hide();
                    jQuery('#distribution-option input').prop('disabled', true);
                }
            }
        });
    }

    function enable_option(divID, labelText){
        //show distribution-option div
        jQuery('#'+divID+' label').text(labelText);
        jQuery('#'+divID+' input').prop('disabled', false);
        jQuery('#'+divID).show();
    }

    jQuery('.project_request_form').submit(function(event){

        var errors = [];


        jQuery('.required').map(function(){
            if(jQuery(this).val() == ''){
                errors.push('There was an error');
            }
        });

        if(errors.length > 0){
            alert('Please make sure all fields are filled out');
            event.preventDefault();
            return;
        }else{

            jQuery('.projectSubmitBtn').hide();
            jQuery('.loadingText').show();

        }

    })


    jQuery('.addProjectFileContainer').click(function(e){
        e.preventDefault();
        fileCount ++;
        jQuery('.projectFileContainer').append('<div><input type="file" class="attachmentsFileBtn" name="attachments'+fileCount+'" /></div>');
    });

})