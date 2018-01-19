var siteUrl = 'http://phonakmarketing.ca/site';
//var siteUrl = 'http://localhost:81/phonakmarketing';

var tab = 1;
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
        if(jQuery('.tab').length != 2){
            jQuery('#submitProjectForm').show();
        }
    }else {
        jQuery('.next').removeClass('inactive');
        jQuery('#submitProjectForm').hide();
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



jQuery(document).on('change','.projectTypeSelector',function(e) {
   console.log('change');
   console.log(jQuery(this).val());
   var val = jQuery(this).val();

   var additionalTabs = 0;

   if(val == 'Print Advertisement'){
        additionalTabs = 2;

        jQuery('.additionalTabContent').html(`
            <div class="tab tab-3">
                <div>
                    test3 
                </div>
            </div>
            <div class="tab tab-4">
                <div>
                test 4
                </div>
            </div>
        `);

        jQuery('.tab-3').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Advertisements-&-Direct-Mail/type-of-ad.php');
        jQuery('.tab-4').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Advertisements-&-Direct-Mail/brief.php');

   }
   if(val == 'Direct Mail'){
        additionalTabs = 4;
        jQuery('.additionalTabContent').html(`
            <div class="tab tab-3">
                <div>

                </div>
            </div>
            <div class="tab tab-4">
                <div>

                </div>
            </div>
             <div class="tab tab-5">
                <div>

                </div>
            </div>
             <div class="tab tab-6">
                <div>

                </div>
            </div>
        `);

        jQuery('.tab-3').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Advertisements-&-Direct-Mail/type-of-project.php');
        jQuery('.tab-4').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Advertisements-&-Direct-Mail/postage-type.php');
        jQuery('.tab-5').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Advertisements-&-Direct-Mail/postage-type-demographics.php');
        jQuery('.tab-6').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Advertisements-&-Direct-Mail/brief.php');
   }
   if(val == 'Logo'){
        additionalTabs = 1;
        jQuery('.additionalTabContent').html(`
            <div class="tab tab-3">
                <div>

                </div>
            </div>
        `);

        jQuery('.tab-3').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Logo-Brochure-Stationary/logo-design/brief.php');
   }
   if(val == 'Brochure'){
        additionalTabs = 2;

        jQuery('.additionalTabContent').html(`
            <div class="tab tab-3">
                <div>

                </div>
            </div>
            <div class="tab tab-4">
                <div>

                </div>
            </div>
        `);

        jQuery('.tab-3').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Logo-Brochure-Stationary/template.php');
        jQuery('.tab-4').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Logo-Brochure-Stationary/brochures/brief.php');
   }
   if(val == 'Stationary'){
        additionalTabs = 2;

        jQuery('.additionalTabContent').html(`
            <div class="tab tab-3">
                <div>

                </div>
            </div>
            <div class="tab tab-4">
                <div>

                </div>
            </div>
        `);

        jQuery('.tab-3').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Logo-Brochure-Stationary/type.php');
        jQuery('.tab-4').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Logo-Brochure-Stationary/stationary/brief.php');

   }
   if(val == 'Newsletter'){
        additionalTabs = 5;

        jQuery('.additionalTabContent').html(`
            <div class="tab tab-3">
                <div>

                </div>
            </div>
            <div class="tab tab-4">
                <div>

                </div>
            </div>
            <div class="tab tab-5">
                <div>

                </div>
            </div>
            <div class="tab tab-6">
                <div>

                </div>
            </div>
            <div class="tab tab-7">
                <div>

                </div>
            </div>
        `);

        jQuery('.tab-3').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Database-Marketing/type.php');
        jQuery('.tab-4').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Database-Marketing/newsletter/template.php');
        jQuery('.tab-5').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Database-Marketing/newsletter/brief.php');
        jQuery('.tab-6').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Database-Marketing/delivery-options.php');
        jQuery('.tab-7').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Database-Marketing/contact-list.php');
   }
   if(val == 'Appointment Anniversary'){
        additionalTabs = 5;

        jQuery('.additionalTabContent').html(`
            <div class="tab tab-3">
                <div>

                </div>
            </div>
            <div class="tab tab-4">
                <div>

                </div>
            </div>
            <div class="tab tab-5">
                <div>

                </div>
            </div>
            <div class="tab tab-6">
                <div>

                </div>
            </div>
            <div class="tab tab-7">
                <div>

                </div>
            </div>
        `);

        jQuery('.tab-3').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Database-Marketing/type.php');
        jQuery('.tab-4').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Database-Marketing/appointment-anniversary/template.php');
        jQuery('.tab-5').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Database-Marketing/appointment-anniversary/brief.php');
        jQuery('.tab-6').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Database-Marketing/delivery-options.php');
        jQuery('.tab-7').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Database-Marketing/contact-list.php');
   }
   if(val == 'Greeting Cards'){
        additionalTabs = 5;

        jQuery('.additionalTabContent').html(`
            <div class="tab tab-3">
                <div>

                </div>
            </div>
            <div class="tab tab-4">
                <div>

                </div>
            </div>
            <div class="tab tab-5">
                <div>

                </div>
            </div>
            <div class="tab tab-6">
                <div>

                </div>
            </div>
            <div class="tab tab-7">
                <div>

                </div>
            </div>
        `);

        jQuery('.tab-3').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Database-Marketing/type.php');
        jQuery('.tab-4').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Database-Marketing/occasion.php');
        jQuery('.tab-5').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Database-Marketing/greeting-cards/template.php');
        jQuery('.tab-6').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Database-Marketing/greeting-cards/brief.php');
        jQuery('.tab-7').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Database-Marketing/contact-list.php');
   }
   if(val == 'Letter'){
        additionalTabs = 4;

         jQuery('.additionalTabContent').html(`
            <div class="tab tab-3">
                <div>

                </div>
            </div>
            <div class="tab tab-4">
                <div>

                </div>
            </div>
            <div class="tab tab-5">
                <div>

                </div>
            </div>
            <div class="tab tab-6">
                <div>

                </div>
            </div>
        `);

        jQuery('.tab-3').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Database-Marketing/type.php');
        jQuery('.tab-4').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Database-Marketing/letter/brief.php');
        jQuery('.tab-5').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Database-Marketing/delivery-options.php');
        jQuery('.tab-6').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/Database-Marketing/contact-list.php');
   }
   if(val == 'Facebook Ad Campaign'){
        additionalTabs = 2;

        jQuery('.additionalTabContent').html(`
            <div class="tab tab-3">
                <div>

                </div>
            </div>
            <div class="tab tab-4">
                <div>

                </div>
            </div>
        `);

        jQuery('.tab-3').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/digital/objective.php');
        jQuery('.tab-4').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/digital/facebook-ad-campaign/brief.php');
   }
   if(val == 'Email Marketing'){
        additionalTabs = 4;

        jQuery('.additionalTabContent').html(`
            <div class="tab tab-3">
                <div>

                </div>
            </div>
            <div class="tab tab-4">
                <div>

                </div>
            </div>
            <div class="tab tab-5">
                <div>

                </div>
            </div>
            <div class="tab tab-6">
                <div>

                </div>
            </div>
        `);

        jQuery('.tab-3').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/digital/email-marketing/type-of-email.php');
        jQuery('.tab-4').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/digital/email-marketing/brief.php');
        jQuery('.tab-5').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/digital/email-marketing/contacts.php');
        jQuery('.tab-6').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/digital/email-marketing/email-software.php');
   }
   if(val == 'Google Ad Words'){
        additionalTabs = 1;

        jQuery('.additionalTabContent').html(`
            <div class="tab tab-3">
                <div>

                </div>
            </div>
        `);

        jQuery('.tab-3').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/digital/google-ad-words/brief.php');
   }
   if(val == 'Lead Nurturing Campaign'){
        additionalTabs = 1;

        jQuery('.additionalTabContent').html(`
            <div class="tab tab-3">
                <div>

                </div>
            </div>
        `);

        jQuery('.tab-3').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/digital/lead-nurturing-campaign/contacts.php');
   }
   if(val == 'GP One Pagers'){
        additionalTabs = 2;

         jQuery('.additionalTabContent').html(`
            <div class="tab tab-3">
                <div>

                </div>
            </div>
            <div class="tab tab-4">
                <div>

                </div>
            </div>
        `);

        jQuery('.tab-3').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/GP-One-Pagers/topic.php');
        jQuery('.tab-4').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/GP-One-Pagers/brief.php');

   }
   if(val == 'Describe'){
        additionalTabs = 1;

        jQuery('.additionalTabContent').html(`
            <div class="tab tab-3">
                <div>

                </div>
            </div>
        `);

        jQuery('.tab-3').load(siteUrl+'/wp-content/plugins/phonak-project-request/forms/other/describe.php');

   }

   jQuery('.additionalTabs').html('');
   for(var i = 0; i< additionalTabs; i++){
        var step = i + 3;
        jQuery('.additionalTabs').append('<span class="step step-'+step+'"></span>');
   }



   updateTab(0);

});