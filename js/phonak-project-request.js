var fileCount = 1;
jQuery(document).ready(function(){

    //jQuery(".datepicker").datepicker();

    jQuery('input[name="due_date"]').change(function(){
        var siteId = jQuery('#siteID').val();

        // IF UK SITE ONLY
        if(siteId == 2){
            var due_date = jQuery('input[name="due_date"]').val();

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