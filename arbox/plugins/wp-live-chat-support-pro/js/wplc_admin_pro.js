 

jQuery( document ).ready(function() {
    if(jQuery("input[type=radio][name='wplc_mail_type']:checked").val() === "php_mailer"){
        jQuery("#wplc_smtp_details").show();
    } else {
        jQuery("#wplc_smtp_details").hide();
    }
    
    jQuery('.wplc_mail_type_radio').click(
    function(e){
        if (jQuery(this).is(':checked') && jQuery(this).val() === "php_mailer"){
            jQuery("#wplc_smtp_details").show();
        } else {
            jQuery("#wplc_smtp_details").hide();
        }
    });
    
    jQuery("#wplc_localization_warning").hide()
    if(jQuery("#wplc_using_localization_plugin").is(":checked")){
        jQuery(".wplc_localization_strings").hide();
        jQuery("#wplc_localization_warning").show()
    }
    jQuery('#wplc_using_localization_plugin').click(
    function(e){
        if (jQuery(this).is(':checked')){
            jQuery(".wplc_localization_strings").hide();
            jQuery("#wplc_localization_warning").show();
        } else {
            jQuery(".wplc_localization_strings").show();
            jQuery("#wplc_localization_warning").hide();
        }
    });
        
    
    jQuery( "#wplc_visitor_accordion" ).accordion({heightStyle: "content"});
        
    jQuery(".wplc_hide_input").hide();
        
    jQuery("#wplc_animation_1").click(function() {
        jQuery("#wplc_rb_animation_1").attr('checked', true);
        jQuery("#wplc_rb_animation_2").attr('checked', false);
        jQuery("#wplc_rb_animation_3").attr('checked', false);
        jQuery("#wplc_rb_animation_4").attr('checked', false);
        jQuery("#wplc_animation_1").addClass("wplc_animation_active");
        jQuery("#wplc_animation_2").removeClass("wplc_animation_active");
        jQuery("#wplc_animation_3").removeClass("wplc_animation_active");
        jQuery("#wplc_animation_4").removeClass("wplc_animation_active");
    });
    
    jQuery("#wplc_animation_2").click(function() {
        jQuery("#wplc_rb_animation_1").attr('checked', false);
        jQuery("#wplc_rb_animation_2").attr('checked', true);
        jQuery("#wplc_rb_animation_3").attr('checked', false);
        jQuery("#wplc_rb_animation_4").attr('checked', false);
        jQuery("#wplc_animation_1").removeClass("wplc_animation_active");
        jQuery("#wplc_animation_2").addClass("wplc_animation_active");
        jQuery("#wplc_animation_3").removeClass("wplc_animation_active");
        jQuery("#wplc_animation_4").removeClass("wplc_animation_active");
    });
    
    jQuery("#wplc_animation_3").click(function() {
        jQuery("#wplc_rb_animation_1").attr('checked', false);
        jQuery("#wplc_rb_animation_2").attr('checked', false);
        jQuery("#wplc_rb_animation_3").attr('checked', true);
        jQuery("#wplc_rb_animation_4").attr('checked', false);
        jQuery("#wplc_animation_1").removeClass("wplc_animation_active");
        jQuery("#wplc_animation_2").removeClass("wplc_animation_active");
        jQuery("#wplc_animation_3").addClass("wplc_animation_active");
        jQuery("#wplc_animation_4").removeClass("wplc_animation_active");
    });
    
    jQuery("#wplc_animation_4").click(function() {
        jQuery("#wplc_rb_animation_1").attr('checked', false);
        jQuery("#wplc_rb_animation_2").attr('checked', false);
        jQuery("#wplc_rb_animation_3").attr('checked', false);
        jQuery("#wplc_rb_animation_4").attr('checked', true);
        jQuery("#wplc_animation_1").removeClass("wplc_animation_active");
        jQuery("#wplc_animation_2").removeClass("wplc_animation_active");
        jQuery("#wplc_animation_3").removeClass("wplc_animation_active");
        jQuery("#wplc_animation_4").addClass("wplc_animation_active");
    });
    
    
    /* Themes */
    
    jQuery("#wplc_theme_1").click(function() {
        console.log('clicked');
        jQuery("#wplc_rb_theme_1").attr('checked', true);
        jQuery("#wplc_rb_theme_2").attr('checked', false);
        jQuery("#wplc_rb_theme_3").attr('checked', false);
        jQuery("#wplc_rb_theme_4").attr('checked', false);
        jQuery("#wplc_rb_theme_5").attr('checked', false);
        jQuery("#wplc_rb_theme_6").attr('checked', false);
        jQuery("#wplc_theme_1").addClass("wplc_theme_active");
        jQuery("#wplc_theme_2").removeClass("wplc_theme_active");
        jQuery("#wplc_theme_3").removeClass("wplc_theme_active");
        jQuery("#wplc_theme_4").removeClass("wplc_theme_active");
        jQuery("#wplc_theme_5").removeClass("wplc_theme_active");
        jQuery("#wplc_theme_6").removeClass("wplc_theme_active");
    });
    
    jQuery("#wplc_theme_2").click(function() {
        jQuery("#wplc_rb_theme_1").attr('checked', false);
        jQuery("#wplc_rb_theme_2").attr('checked', true);
        jQuery("#wplc_rb_theme_3").attr('checked', false);
        jQuery("#wplc_rb_theme_4").attr('checked', false);
        jQuery("#wplc_rb_theme_5").attr('checked', false);
        jQuery("#wplc_rb_theme_6").attr('checked', false);
        jQuery("#wplc_theme_1").removeClass("wplc_theme_active");
        jQuery("#wplc_theme_2").addClass("wplc_theme_active");
        jQuery("#wplc_theme_3").removeClass("wplc_theme_active");
        jQuery("#wplc_theme_4").removeClass("wplc_theme_active");
        jQuery("#wplc_theme_5").removeClass("wplc_theme_active");
        jQuery("#wplc_theme_6").removeClass("wplc_theme_active");
    });
    
    jQuery("#wplc_theme_3").click(function() {
        jQuery("#wplc_rb_theme_1").attr('checked', false);
        jQuery("#wplc_rb_theme_2").attr('checked', false);
        jQuery("#wplc_rb_theme_3").attr('checked', true);
        jQuery("#wplc_rb_theme_4").attr('checked', false);
        jQuery("#wplc_rb_theme_5").attr('checked', false);
        jQuery("#wplc_rb_theme_6").attr('checked', false);
        jQuery("#wplc_theme_1").removeClass("wplc_theme_active");
        jQuery("#wplc_theme_2").removeClass("wplc_theme_active");
        jQuery("#wplc_theme_3").addClass("wplc_theme_active");
        jQuery("#wplc_theme_4").removeClass("wplc_theme_active");
        jQuery("#wplc_theme_5").removeClass("wplc_theme_active");
        jQuery("#wplc_theme_6").removeClass("wplc_theme_active");
    });
    
    jQuery("#wplc_theme_4").click(function() {
        jQuery("#wplc_rb_theme_1").attr('checked', false);
        jQuery("#wplc_rb_theme_2").attr('checked', false);
        jQuery("#wplc_rb_theme_3").attr('checked', false);
        jQuery("#wplc_rb_theme_4").attr('checked', true);
        jQuery("#wplc_rb_theme_5").attr('checked', false);
        jQuery("#wplc_rb_theme_6").attr('checked', false);
        jQuery("#wplc_theme_1").removeClass("wplc_theme_active");
        jQuery("#wplc_theme_2").removeClass("wplc_theme_active");
        jQuery("#wplc_theme_3").removeClass("wplc_theme_active");
        jQuery("#wplc_theme_4").addClass("wplc_theme_active");
        jQuery("#wplc_theme_5").removeClass("wplc_theme_active");
        jQuery("#wplc_theme_6").removeClass("wplc_theme_active");
    });
    
    jQuery("#wplc_theme_5").click(function() {
        jQuery("#wplc_rb_theme_1").attr('checked', false);
        jQuery("#wplc_rb_theme_2").attr('checked', false);
        jQuery("#wplc_rb_theme_3").attr('checked', false);
        jQuery("#wplc_rb_theme_4").attr('checked', false);
        jQuery("#wplc_rb_theme_5").attr('checked', true);
        jQuery("#wplc_rb_theme_6").attr('checked', false);
        jQuery("#wplc_theme_1").removeClass("wplc_theme_active");
        jQuery("#wplc_theme_2").removeClass("wplc_theme_active");
        jQuery("#wplc_theme_3").removeClass("wplc_theme_active");
        jQuery("#wplc_theme_4").removeClass("wplc_theme_active");
        jQuery("#wplc_theme_5").addClass("wplc_theme_active");
        jQuery("#wplc_theme_6").removeClass("wplc_theme_active");
    });
    
    jQuery("#wplc_theme_6").click(function() {
        jQuery("#wplc_rb_theme_1").attr('checked', false);
        jQuery("#wplc_rb_theme_2").attr('checked', false);
        jQuery("#wplc_rb_theme_3").attr('checked', false);
        jQuery("#wplc_rb_theme_4").attr('checked', false);
        jQuery("#wplc_rb_theme_5").attr('checked', false);
        jQuery("#wplc_rb_theme_6").attr('checked', true);
        jQuery("#wplc_theme_1").removeClass("wplc_theme_active");
        jQuery("#wplc_theme_2").removeClass("wplc_theme_active");
        jQuery("#wplc_theme_3").removeClass("wplc_theme_active");
        jQuery("#wplc_theme_4").removeClass("wplc_theme_active");
        jQuery("#wplc_theme_5").removeClass("wplc_theme_active");
        jQuery("#wplc_theme_6").addClass("wplc_theme_active");
    });
    
    var wplc_agent_status = jQuery("#wplc_agent_status").attr('checked');

    if(wplc_agent_status === 'checked'){
        jQuery("#wplc_agent_status_text").html(wplc_admin_strings.accepting_chats);
    } else {
        jQuery("#wplc_agent_status_text").html(wplc_admin_strings.not_accepting_chats);
    }
      
   /* Make sure switchery has been loaded on this page */
    if(typeof Switchery !== 'undefined'){
        var wplc_switchery_element = document.querySelector('.wplc_switchery');
        /* Make sure that the switch is being displayed */
        if(wplc_switchery_element !== null){
        
            var wplc_switchery_init = new Switchery(wplc_switchery_element, { color: '#00B344', secondaryColor: '#D91600' });

            var changeCheckbox = document.querySelector('#wplc_agent_status');

            changeCheckbox.onchange = function () {
                var wplc_accepting_chats = jQuery(this).attr('checked');

                if(wplc_accepting_chats === 'checked'){
                    jQuery("#wplc_agent_status_text").html(wplc_admin_strings.accepting_chats);
                    var wplc_ma_set_transient = null;

                    wplc_ma_set_transient = setInterval(function (){wplc_ma_update_agent_transient();}, 60000);                    

                    function wplc_ma_update_agent_transient() {
                        var data = {
                            action: 'wplc_ma_set_transient',
                            security: wplc_admin_strings.nonce,
                            user_id:  wplc_admin_strings.user_id
                        };
                        jQuery.post(ajaxurl, data, function(response) {
                            location.reload(true);
                        });
                    }
                    
                    wplc_ma_update_agent_transient();
                    
                } else {
                    jQuery("#wplc_agent_status_text").html(wplc_admin_strings.not_accepting_chats);

                    var wplc_ma_remove_transient = null;

                    wplc_ma_remove_transient = setInterval(function (){wplc_ma_remove_agent_transient();}, 60000);
                    

                    function wplc_ma_remove_agent_transient() {
                        var data = {
                            action: 'wplc_ma_remove_transient',
                            security: wplc_admin_strings.nonce,
                            user_id:  wplc_admin_strings.user_id
                        };
                        jQuery.post(ajaxurl, data, function(response) {
                            location.reload(true);
                        });
                    }     
                    
                    wplc_ma_remove_agent_transient();
                    
                }
            };
        }             
    }
    
});