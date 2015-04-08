function setCookie(cname,cvalue,exdays)
{
    var d = new Date();
    d.setTime(d.getTime()+(exdays*24*60*60*1000));
    var expires = "expires="+d.toGMTString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function sendChat(chats, noise, force)
{
    if (window.inProgress==true && force==false){return false;}
    window.inProgress = true;
    if (window.chatEnded==true){return false;}
    var focus = '';
    if (jQuery("#chat-box:focus").length!=0)
    {
        var focus = jQuery("#chat-box:focus").parents('#nchat-cover-cover').attr('data-key');
    }    
    var needOperator = jQuery('#operator-profile').html()=='' ? true : false;
    jQuery.ajax({
        type: "GET",
        url: variables.ajaxurl,
        data:
        {  
            'focus': focus,
            'chats': chats,
            'needOperator': needOperator,
            'action':'nchat_updateChat'
        },
        dataType: "json",
        success: function(response)
        {
            window.inProgress = false;            
            now = new Date();
            time = now.getHours()+':'+now.getMinutes();
            jQuery('#nchat-cover-cover').removeClass('is_typing');
            if (response.ty)
            {
                for (key in response.ty)
                {
                    jQuery('#nchat-cover-cover').addClass('is_typing');
                }
            }            
            if (response.ch)
            {
                for (thisChat in response.ch)
                {
                    if (response.ch[thisChat].p)
                    {
                        jQuery('#nchat-cover-cover').attr('data-position', response.ch[thisChat].p);
                    }
                    if (response.ch[thisChat].lines)
                    {
                        if (jQuery('.chat-online').length && response.ch[thisChat].lines.length!=0)
                        {
                            if (noise!=false)
                            {
                                var pop = document.getElementById("pop-sound");
                                pop.play();                                
                            }
                        }
                        for (chatLine in response.ch[thisChat].lines)
                        {
                            var lastLine = chatLine;
                        }
                        for (chatLine in response.ch[thisChat].lines)
                        {
                            if (response.ch[thisChat].lines[chatLine].message==undefined) { continue; }                            
                            var newLine = '<div class="chat-line-cover chat-line-cover-'+response.ch[thisChat].lines[chatLine].who+'"><p class="chat-'+response.ch[thisChat].lines[chatLine].who+'" id="chat-'+chatLine+'"><span>'+response.ch[thisChat].lines[chatLine].by+'</span><span class="txt">'+response.ch[thisChat].lines[chatLine].message+'</span></p><span class="chat-time">'+time+'</span></div>';

                            if (jQuery('#chat-'+chatLine).length==0)
                            {
                                jQuery('#chat-area').append(newLine);                                
                            }
                            if (lastLine==chatLine)
                            {
                                var objDiv = document.getElementById("chat-wrap");
                                objDiv.scrollTop = objDiv.scrollHeight;                                
                            }
                        }
                    }
                }
            }
            if (response.op)
            {
                if (jQuery('#operator-profile').html()=='')
                {
                    html = '<img src="'+response.op.image+'"><div id="operator-name">'+response.op.name+'<div id="operator-bio">'+response.op.bio+'</div></div>';
                    jQuery('#operator-profile').html(html);
                }
            }
            if (response.cs)
            {
                var newLine = '<div class="chat-ended">'+response.cs+'</div><div id="send-transcript">'+response.cs2+'</div>';
                jQuery('#chat-area').append(newLine);                
                window.chatEnded = true;
            }

        },
    });
}



jQuery(document).ready(function()
{

    jQuery('#chat-area').html('');
    jQuery('#operator-profile').html('');
    var height = jQuery(window).height() < 600 && jQuery(window).height() > 200 ? (parseInt(jQuery(window).height())/2)-50 : false;
    if (height)
    {
        jQuery('#chat-wrap').css('max-height',height+'px');
        jQuery('#chat-wrap').css('overflow','auto');
    }

    var key = jQuery('#nchat-cover-cover').attr('data-key');
    var position = jQuery('#nchat-cover-cover').attr('data-position');
    chats = [];
    chats.push({key:key,position:position});
    if (key!=''){sendChat(chats, false, false);}

    jQuery('#nchat-cover-cover').appendTo(document.body);

    /* Auto Initiate Chat */
    var time = parseInt(jQuery('#nchat-cover-cover').attr('data-auto'));
    if (!isNaN(time))
    {
        time = time * 1000;
        setTimeout(function()
        {
            if (jQuery('#nchat-cover-cover.chat-hide.operator-online').length)
            {
                jQuery('#nchat-trigger').trigger('click');
                jQuery('#nchat-cover-cover').attr('data-auto','');
            }
        }, time);
    }

    jQuery('.nchat-min').click(function(event)
    {
        setCookie('nchatWidget', 'off', 7);            
        jQuery('#nchat-cover-cover').addClass('chat-hide').removeClass('chat-show');
    });
    jQuery('.nchat-x').click(function(event)
    {
        jQuery('#chat-area').html('');        
        jQuery.ajax(
        {
         type: 'GET',
         url: variables.ajaxurl,
         data: 'key='+jQuery('#nchat-cover-cover').attr('data-key')+'&action=nchat_endChat',
         dataType: "json",
         success: function(response)
         {
            setCookie('nchatWidget', 'off', 7);
            if(jQuery('.chat-offline').length==1){return false;}
            var key = jQuery('#nchat-cover-cover').attr('data-key');
            var position = jQuery('#nchat-cover-cover').attr('data-position');
            chats = [];
            chats.push({key:key,position:position});
            if (key==''){return false;}
            sendChat(chats,true,false);
            window.chatEnded = true;
        },
    });        
    });

    jQuery('#nchat-trigger').click(function(event){
        setCookie('nchatWidget', 'on', 7);
        jQuery('#nchat-cover-cover').addClass('chat-show').removeClass('chat-hide');    
    });

    jQuery('body').on('click','#send-transcript',function(event){
        jQuery(this).text('...');
        var key = jQuery('#nchat-cover-cover').attr('data-key');
        jQuery.ajax({
           type: 'GET',
           url: variables.ajaxurl,
           data: 'action=nchat_sendTranscript&key='+key,
           dataType: "json",
           success: function(response)
           {
            if (response.success)
            {
                jQuery('#send-transcript').text(response.success);
                jQuery('#send-transcript').attr('id','send-transcript-done');
            }
            else
            {
                jQuery('#send-transcript').text('retry');
            }
        },
    });
    });


    jQuery('body').on('submit','#chat-contact',function(event){
        event.preventDefault();
        var data = jQuery(this).serialize();
        jQuery('#chat-contact-submit').text('...');
        jQuery.ajax({
           type: 'GET',
           url: variables.ajaxurl,
           data: 'action=nchat_sendContact&'+data,
           dataType: "json",
           success: function(response)
           {
            if (response.success)
            {                
                jQuery('#hide-if-success').slideUp();
                jQuery('#chat-contact .success-response').html(response.success);
                jQuery('#chat-contact .success-response').slideDown();
            }
            if (response.failed=='true' && response.name)
            {
                jQuery('#chat-contact .field-name').parent('.ip-cover').addClass('error');
            }
            if (response.failed=='true' && response.email)
            {
                jQuery('#chat-contact .field-email').parent('.ip-cover').addClass('error');
            }
            if (response.failed=='true' && response.comments)
            {
                jQuery('#chat-contact .field-comments').parent('.ip-cover').addClass('error');
                jQuery('#chat-contact-submit').text(jQuery('#chat-contact-submit').attr('data-temp'));
            }
        },
    });
}); 

var sendChatInterval = setInterval(function(event){
    if(jQuery('.chat-offline').length==1){return false;}
    var key = jQuery('#nchat-cover-cover').attr('data-key');
    var position = jQuery('#nchat-cover-cover').attr('data-position');
    chats = [];
    chats.push({key:key,position:position});
    if (key==''){return false;}
    sendChat(chats,true,false);
}, 1500);

jQuery("#chat-signup").submit(function(event)
{
    event.preventDefault();
    var data = jQuery('#chat-signup').serialize()+'&action=nchat_startChat';
    jQuery('#chat-signup-submit').attr('data-temp',jQuery('#chat-signup-submit').text()).text('...');
    jQuery.ajax({
     type: 'GET',
     url: variables.ajaxurl,
     data: data,
     dataType: "json",
     success: function(response)
     {
        window.chatEnded = false;
        jQuery('#chat-area').html('');
        jQuery('#chat-signup-submit').text(jQuery('#chat-signup-submit').attr('data-temp'));
        if (response.success=='true')
        {
            jQuery('#nchat-cover-cover').addClass('chat-online').removeClass('chat-offline');            
            jQuery('#nchat-cover-cover').addClass('chat-show').removeClass('chat-hide');

            jQuery('#nchat-cover-cover').attr('data-key', response.key);
            jQuery('#nchat-cover-cover').attr('data-position', 0);
        }
        if (response.failed=='true' && response.name)
        {
            jQuery('.field-name').parent('.ip-cover').addClass('error');
        }
        if (response.failed=='true' && response.email)
        {
            jQuery('.field-email').parent('.ip-cover').addClass('error');
        }
    },
});        
});

jQuery('#chat-box').keyup(function(e)
{
    if (e.keyCode == 13)
    { 
        var message = jQuery(this).val().substring(0, jQuery(this).attr("maxlength"));
        var key = jQuery('#nchat-cover-cover').attr('data-key');
        var position = jQuery('#nchat-cover-cover').attr('data-position');
        chats = [];
        chats.push({message:message,key:key,position:position});
        if (key==''){return false;}            
        sendChat(chats,true,true);
        jQuery(this).val('');
    }
});
});