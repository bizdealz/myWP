function saveWidget(data)
{
    if (window.isSuper!='true')return false;
    var html = encodeURIComponent(jQuery('#nchat-cover-cover.tosave').html());
    jQuery.ajax({
        type: "POST",
        url: variables.ajaxurl,
        data: 'action=nchat_saveWidget&widget='+jQuery.toJSON(data)+'&html='+html,
        dataType: "json",
        success: function(response)
        {

        },
    });
}

angular.module('nChat', [], function($compileProvider)
{

    /* Compile to HTML */
    $compileProvider.directive('compile', function($compile)
    {
        return function(scope, element, attrs)
        {
            scope.$watch(
                function(scope) {
                    return scope.$eval(attrs.compile);
                },
                function(value) {
                    element.html(value);
                    $compile(element.contents())(scope);
                }
                );
        };
    });

});

function nChatController($scope, $http)
{
    if (window.nChatWidget!='')
    {
        $scope.WidgetData = jQuery.evalJSON(window.nChatWidget);
    }
    else
    {
        $scope.WidgetData = {};
        $scope.WidgetData.form_html = '<h1>Live Chat</h1><p>Our agents are online. Please give us the following details.</p>';  
    }
    $scope.saveWidget = function()
    {
        saveWidget($scope.WidgetData);
    }

    setTimeout(function(){$scope.saveWidget();},3000);

}

Object.size = function(obj)
{
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};

function saveConfig()
{
    var config = jQuery('.nchat-user-data').serialize();
    jQuery.ajax({
        type: "GET",
        url: variables.ajaxurl,
        data: config+'&action=nchat_updateConfig',
        dataType: "json",
        success: function(response)
        {

        },
    });    
}

function sendChat(chats, force)
{
    var status = jQuery('#operator-status').val();
    var focus = '';
    if (jQuery(".chat-box:focus").length!=0)
    {
        var focus = jQuery(".chat-box:focus").parents('.chat-cover').attr('data-key');
    }
    if (window.inProgress==true && force==false){return false;}
    window.inProgress = true;
    jQuery.ajax({
        type: "GET",
        url: variables.ajaxurl,
        data:
        {
            'focus': focus,
            'chats': chats,
            'page': 'admin',
            'status': status,
            'action':'nchat_updateChat'
        },
        dataType: "json",
        success: function(response)
        {
            if (response.chats)
            {
                users_list = '';
                users_tabs = '';
                for (thisChat in response.chats)
                {
                    var length = Object.size(response.chats[thisChat].lines);
                    if (length>0 && !jQuery('.chat-li-'+response.chats[thisChat].key).hasClass('active'))
                    {
                        var nos = jQuery('.chat-li-'+response.chats[thisChat].key+' > span').text();
                        nos = nos=='' ? 0 : parseInt(nos);
                        jQuery('.chat-li-'+response.chats[thisChat].key+' > span').text(nos+length).show();
                    }                    
                    if(jQuery('#chat-'+response.chats[thisChat].key).length==0)
                    {

                        var users_list = users_list + '<li class="chat-li-'+response.chats[thisChat].key+'"><span></span>'+response.chats[thisChat].name+'</li>';
                        var users_tabs = users_tabs + '<div class="tab chat-tab-'+response.chats[thisChat].key+'"><div class="user-profile"><img src="http://www.gravatar.com/avatar/'+response.chats[thisChat].img+'"><div><span>'+response.chats[thisChat].name+'</span><span>'+response.chats[thisChat].email+'</span><span>'+response.chats[thisChat].other.agent+'</span><span>'+response.chats[thisChat].other.ip+'</span></div></div><div id="chat-'+response.chats[thisChat].key+'" class="chat-cover" data-key="'+response.chats[thisChat].key+'" data-position="'+response.chats[thisChat].position+'"><div class="chat-wrap"><div class="chat-area"></div></div><form class="chat-box-cover"><div class="is_typing"><div class="nchat-loader" style="font-size: 10px"><div class="dot dot1"></div><div class="dot dot2"></div><div class="dot dot3"></div></div></div><textarea class="chat-box" maxlength="1000" placeholder="Type here and press Enter"></textarea></form></div></div>';                     
                    }
                    else
                    {
                        jQuery('#chat-'+response.chats[thisChat].key).attr('data-position', response.chats[thisChat].position);
                    }
                }                
                jQuery('.chat-users').append(users_list);
                jQuery('.chat-tabs').append(users_tabs);

                for (thisChat in response.chats)
                {
                    if (response.chats[thisChat].lines)
                    {
                        for (chatLine in response.chats[thisChat].lines)
                        {
                            var newLine = '<div class="chat-line-cover"><p class="chat-'+response.chats[thisChat].lines[chatLine].who+'" id="chat-'+chatLine+'"><span>'+response.chats[thisChat].lines[chatLine].by+'</span>'+response.chats[thisChat].lines[chatLine].message+'</p></div>';
                            if (jQuery('#chat-'+response.chats[thisChat].key+' #chat-'+chatLine).length==0)
                            {
                            jQuery('#chat-'+response.chats[thisChat].key+' .chat-area').append(newLine);
                            }                            
                            var pop = document.getElementById("pop-sound");
                            pop.play();
                        }
                    }                    
                }
            }

            if (response.offline)
            {
                for (thisChat in response.offline)
                {
                    jQuery('.chat-li-'+response.offline[thisChat].id).remove();
                    jQuery('.chat-tab-'+response.offline[thisChat].id).remove();
                }
            }

            jQuery('.chat-cover').removeClass('typing');
            if (response.typing)
            {
                for (key in response.typing)
                {
                    jQuery('#chat-'+response.typing[key]).addClass('typing');
                }
            }
            window.inProgress = false;
        },
    });
}

function updateChat()
{
    chats = [];    
    jQuery('.chat-cover').each(function(){
        var key = jQuery(this).attr('data-key');
        var position = jQuery(this).attr('data-position');
        chats.push({key:key,position:position});
    });
    sendChat(chats, false);
}

function endChat(key)
{
    jQuery('#end-chat').attr('data-temp',jQuery('#end-chat').text()).text('...');  
    jQuery.ajax({
        type: "GET",
        url: variables.ajaxurl,
        data: 'key='+key+'&action=nchat_endChat',
        dataType: "json",
        success: function(response)
        {
            jQuery('#end-chat').text(jQuery('#end-chat').attr('data-temp'));
        },
    });    
}



jQuery(document).ready(function()
{
    updateChat();
    var updateChatInterval = setInterval(function(){
        updateChat();
    }, 2000);
    jQuery('body').on('blur, change','.nchat-user-data',function(){
        saveConfig();
    });

    jQuery('.colorpicker').wpColorPicker({
        change: function(event, ui){
            setTimeout(function(){jQuery('.colorpicker').trigger('input').trigger('change')},100);
        }
    });

    jQuery('body').on('click', '#end-chat', function(){
        var key = jQuery('.chat-tabs .tab.active .chat-cover').attr('data-key');
        endChat(key);
    });
    jQuery('body').on('click', '#go-offline', function(){
        var status = jQuery('#operator-status').val();
        if (status=='online')
        {
            jQuery('#go-offline').text(jQuery('#go-offline').attr('data-offline')).addClass('offline').removeClass('online');            
            jQuery('#operator-status').val('offline');
        }
        else
        {
            jQuery('#go-offline').text(jQuery('#go-offline').attr('data-online')).addClass('online').removeClass('offline');
            jQuery('#operator-status').val('online');
        }        
    });

    jQuery('body').on('click','.nav-jquery > li',function()
    {
        jQuery('#no-chat').fadeOut();
        var tabIndex = jQuery(this).index();
        jQuery(this).find('span').text('').hide();
        jQuery(this).parent().children('li').removeClass('active');
        jQuery(this).addClass('active');
        jQuery(this).parent().parent().children('.tabs').children('.tab').removeClass('active');
        jQuery(this).parent().parent().children('.tabs').children('.tab:eq('+tabIndex+')').addClass('active');
    });
    jQuery('body').on('submit','#nchat-pk',function(event){
        event.preventDefault();
        jQuery('#nchat-pk .response').text('...');        
        jQuery.ajax({
            type: "GET",
            url: variables.ajaxurl,
            data: 'key='+jQuery('#nchat-pk-input').val()+'&action=nchat_verifyLicense',
            dataType: "json",
            success: function(response)
            {
                if (response.message)
                {
                jQuery('#nchat-pk .response').text(response.message);
                }
                else
                {
                jQuery('#nchat-pk .response').text('Unknown error');
                }
            },
        });        
    });
    jQuery('body').on('keyup','.chat-box',function(e)
    {
        if (e.keyCode == 13)
        { 
            var message = jQuery(this).val().substring(0, jQuery(this).attr("maxlength"));
            var key = jQuery(this).parents('.chat-cover').attr('data-key');
            var position = jQuery(this).parents('.chat-cover').attr('data-position');
            chats = [];
            chats.push({message:message,key:key,position:position});
            sendChat(chats, true);
            jQuery(this).val('');
        }
    });   
});