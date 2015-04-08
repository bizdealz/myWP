 /**
 * 
 * ClientEngage VisitorChat for WordPress (http://www.clientengage.com)
 * Copyright 2013, ClientEngage (http://www.clientengage.com)
 *
 * You must have purchased a valid license from CodeCanyon in order to have 
 * the permission to use this file.
 * 
 * You may only use this file according to the respective licensing terms 
 * you agreed to when purchasing this item on CodeCanyon.
 * 
 * All PHP code utilising WordPress features is licensed as GPL version 2 or 
 * later. External libraries placed in the "vendors"-directory are subject to
 * their respective licensing terms (take note of their licenses).
 *  
 * However, other ClientEngage assets such as JavaScript code, 
 * CSS styles and images are not licensed as GPL version 2 or later and you are 
 * required to abide by the CodeCanyon Regular License. 
 * 
 *
 * @author          ClientEngage <contact@clientengage.com>
 * @copyright       Copyright 2013, ClientEngage (http://www.clientengage.com)
 * @link            http://www.clientengage.com ClientEngage
 * @since           ClientEngage VisitorChat for WordPress v 1.0
 * @license         CodeCanyon Regular License
 * 
 */
(function($){var visitorChat={config:{signedUp:false,windowFocused:false,isNewPage:true,processingSignUp:false,processingEnquiry:false,processingSend:false,visitor:{username:"",avatar:""},timerReadMessages:null,timerCheckStatus:null,isOnline:false,decayHistory:0,defaultTimeout:3500,isInitialStatusCheck:true,muteNotificationSoundOnce:false,assetBase:ceVC_JS.baseUrl+"/",restoreOpenState:false,animateHover:ceVC_JS.animateHover},Lang:ceVC_JS.Lang,init:function(){jQuery(".vc_chat_container").css('margin-bottom','-50px');jQuery(".vc_chat_container").show();jQuery(".vc_chat_container").animate({marginBottom:0},{duration:500,queue:true,complete:function(){if(visitorChat.config.restoreOpenState)
{visitorChat.config.isNewPage=true;jQuery(".vc_chat_head").trigger("click");}}});visitorChat.initAnimateHover();jQuery(window).focus(function(){visitorChat.config.windowFocused=true;});jQuery(window).blur(function(){visitorChat.config.windowFocused=false;});jQuery(document).on("click",".vc_chat_head",visitorChat.headerClick);jQuery(document).on("click",".vc_btn_enquiry",visitorChat.enquiryClick);jQuery(document).on("submit",".vc_form_enquiry",visitorChat.enquirySubmit);jQuery(document).on("click",".vc_btn_signup",visitorChat.signUpClick);jQuery(document).on("submit",".vc_form_signup",visitorChat.signUpSubmit);jQuery(document).on("click",".vc_btn_send",visitorChat.sendClick);jQuery(document).on("submit",".vc_form_reply",visitorChat.onSendSubmit);jQuery(document).on("click",".vc_btn_exit_chat",visitorChat.btnExitChatClick);jQuery(document).on("click",".vc_btn_exit_chat_confirm",visitorChat.btnExitChatConfirmClick);jQuery(document).on("click",".vc_btn_exit_chat_cancel",visitorChat.btnExitChatCancelClick);jQuery(document).on("click",".vc_btn_notifications_close",visitorChat.btnNotificationsCloseClick);jQuery(document).on("keyup",".vc_chat_container textarea, .vc_chat_container input",visitorChat.messageKeyUp);jQuery(document).on("keydown",".vc_input_message",visitorChat.messageKeyDown);jQuery('[placeholder]').each(function(){jQuery(this).css('color','#ccc').val(jQuery(this).attr('placeholder'));}).bind('focus',function(){jQuery(this).css('color','inherit');if(jQuery(this).val()===jQuery(this).attr('placeholder')){jQuery(this).val('');}}).bind('blur',function(){jQuery(this).css('color','inherit');if(jQuery(this).val()===''){jQuery(this).val(jQuery(this).attr('placeholder')).css('color','#ccc');}});visitorChat.readMessages();CEVC_Onload_Internal();if(typeof CEVC_Onload!=='undefined'&&typeof CEVC_Onload==='function')
{CEVC_Onload();}},initAnimateHover:function(){if(visitorChat.config.animateHover!==true)
return;jQuery(".vc_chat_container").animate({opacity:0.7},{duration:0,queue:false});jQuery(".vc_chat_container").hover(function(){jQuery(this).animate({opacity:1},{duration:200,queue:false});},function(){if(!jQuery(this).hasClass("vc_box_open"))
jQuery(this).animate({opacity:0.7},{duration:200,queue:false});});jQuery(".vc_chat_container").bind("chatOpen",function(e,isOpen){if(isOpen)
{jQuery(this).animate({opacity:1},{duration:200,queue:false});}
else
{jQuery(this).animate({opacity:0.7},{duration:200,queue:false});}});},enquiryClick:function(e){e.preventDefault();jQuery(".vc_form_enquiry").submit();},btnExitChatClick:function(e){e.preventDefault();jQuery(".vc_exit_chat_confirmation_wrapper").show();jQuery(this).hide();},btnExitChatConfirmClick:function(e){e.preventDefault();clearTimeout(visitorChat.config.timerReadMessages);clearTimeout(visitorChat.config.timerCheckStatus);var formData=jQuery(this).serializeArray();formData.push({name:'data[sign_out]',value:true});jQuery.ajax({url:ceVC_JS.ajaxUrl+"?action=cevc_signout",data:formData,dataType:"json",type:"post",jsonpCallback:visitorChat.getJsonpCallbackName(),success:function(result){if(result.success===true)
{visitorChat.resetDefaultState();}
else
{}},complete:function(){visitorChat.checkStatus(true);}});},btnExitChatCancelClick:function(e){e.preventDefault();jQuery(".vc_exit_chat_confirmation_wrapper").hide();jQuery(".vc_btn_exit_chat").show();},resetDefaultState:function(){visitorChat.config.signedUp=false;visitorChat.config.visitor={username:"",avatar:""};jQuery(".vc_conversation_container").html('<p class="vc_message_intro" data-id="-10">'+visitorChat.Lang.FirstMessageText+'</p>');jQuery(".vc_exit_chat_confirmation_wrapper").hide();jQuery(".vc_btn_exit_chat").show();jQuery(".vc_chat_head").trigger("click");},enquirySubmit:function(e){e.preventDefault();if(visitorChat.config.processingEnquiry)
return false;var validationErrors={};if(jQuery.trim(jQuery(".vc_input_enquiry_username").val())===''||jQuery(".vc_input_enquiry_username").val()===jQuery(".vc_input_enquiry_username").attr("placeholder"))
{validationErrors["username"]=[visitorChat.Lang.ValidationUsernameRequired];}
if(jQuery.trim(jQuery(".vc_input_enquiry_email").val())===''||jQuery(".vc_input_enquiry_email").val()===jQuery(".vc_input_enquiry_email").attr("placeholder"))
{validationErrors["email"]=[visitorChat.Lang.ValidationEmailRequired];}
if(jQuery.trim(jQuery(".vc_input_enquiry_message").val())===''||jQuery(".vc_input_enquiry_message").val()===jQuery(".vc_input_enquiry_message").attr("placeholder"))
{validationErrors["message"]=[visitorChat.Lang.ValidationEnquiryRequired];}
if(validationErrors.hasOwnProperty("username")||validationErrors.hasOwnProperty("email")||validationErrors.hasOwnProperty("message"))
{visitorChat.renderNotifications(validationErrors,true);return;}
visitorChat.config.processingEnquiry=true;var enquiryForm=jQuery(this);enquiryForm.addClass("vc_enquiry_sending");var formData=jQuery(this).serializeArray();formData.push({name:'data[Enquiry][current_page]',value:encodeURIComponent(encodeURIComponent(document.URL))});jQuery.ajax({url:ceVC_JS.ajaxUrl+"?action=cevc_enquiry_submit",data:formData,dataType:"json",type:"post",jsonpCallback:visitorChat.getJsonpCallbackName(),success:function(result){if(result.success===true)
{visitorChat.clearNotifications();jQuery(".vc_form_enquiry")[0].reset();var notifications={success:[visitorChat.Lang.EnquirySubmitSuccess]};visitorChat.renderNotifications(notifications,false);}
else
{visitorChat.renderNotifications(result.errors,true);}},complete:function(){visitorChat.config.processingEnquiry=false;enquiryForm.removeClass("vc_enquiry_sending");jQuery(".vc_chat_container input, .vc_chat_container textarea").trigger("blur");}});return false;},onSendSubmit:function(){var message=jQuery.trim(jQuery(".vc_input_message").val());message=jQuery('<p>'+message+'</p>').text();jQuery(".vc_input_message").val(message);if(message===""||jQuery(".vc_input_message").val()===jQuery(".vc_input_message").attr("placeholder"))
return false;if(visitorChat.config.processingSend)
return false;var messageForm=jQuery(this);messageForm.addClass("vc_message_sending");visitorChat.config.processingSend=true;var formData=jQuery(".vc_form_reply").serializeArray();formData.push({name:'data[Message][current_page]',value:encodeURIComponent(encodeURIComponent(document.URL))});formData.push({name:'data[last_id]',value:visitorChat.getMaxId()});formData.push({name:'data[is_new_page]',value:visitorChat.config.isNewPage});visitorChat.config.isNewPage=false;jQuery(".vc_input_message").val("");var tmpMessage={Message:[],User:{username:visitorChat.config.visitor.username,avatar:visitorChat.config.visitor.avatar,is_admin:false}};tmpMessage.Message.id=visitorChat.getMaxId()+999;tmpMessage.Message.message=message;tmpMessage.Message.created=new Date();visitorChat.addMessageToChat(tmpMessage,{confirmed:false});visitorChat.scrollBottom(false);jQuery.ajax({url:ceVC_JS.ajaxUrl+"?action=cevc_send",data:formData,dataType:"json",type:'post',jsonpCallback:visitorChat.getJsonpCallbackName(),success:function(result){if(result.success===true)
{jQuery("[data-id="+tmpMessage.Message.id+"]").remove();visitorChat.addMessageToChat(result.data);for(var k=0;k<result.messages.length;k++)
{visitorChat.addMessageToChat(result.messages[k]);}
if(result.messages.length>0)
visitorChat.scrollBottom(true);}
else
{jQuery("[data-id="+tmpMessage.Message.id+"]").css('border','1px dashed #f00');}},complete:function(){visitorChat.config.processingSend=false;messageForm.removeClass("vc_message_sending");}});return false;},headerClick:function(e){e.preventDefault();jQuery(".vc_notification_badge_number").fadeOut();jQuery(".vc_notification_badge_number").html('');jQuery(".vc_chat_container").removeClass('vc_has_unread_messages');if(!visitorChat.config.signedUp)
{if(visitorChat.config.isOnline)
{jQuery(".vc_signup_wrapper").show();jQuery(".vc_conversation").hide();jQuery(".vc_enquiry_wrapper").hide();}
else
{jQuery(".vc_enquiry_wrapper").show();jQuery(".vc_signup_wrapper").hide();jQuery(".vc_conversation").hide();}}
else
{jQuery(".vc_enquiry_wrapper").hide();jQuery(".vc_signup_wrapper").hide();jQuery(".vc_conversation").show();}
var wasClosed=jQuery(".vc_chat_toggle_container").is(":visible");jQuery(".vc_chat_toggle_container").slideToggle("fast",function(){visitorChat.scrollBottom(true);if(!wasClosed)
{jQuery(".vc_chat_container").addClass("vc_box_open");jQuery(".vc_chat_container").trigger("chatOpen",true);}});if(wasClosed)
{jQuery(".vc_chat_container").addClass("vc_chat_closing");jQuery(".vc_chat_container").animate({marginBottom:-50},{duration:200,queue:true,complete:function(){jQuery(".vc_chat_container").animate({marginBottom:0},{duration:500,queue:true,complete:function(){}});jQuery(".vc_chat_container").removeClass("vc_box_open");jQuery(".vc_chat_container").removeClass("vc_chat_closing");jQuery(".vc_chat_container").trigger("chatOpen",false);}});}
else
{jQuery(".vc_input_message").focus();}
if(e.originalEvent!==undefined){visitorChat.readMessages();}
visitorChat.checkStatus(true);},signUpClick:function(e){e.preventDefault();jQuery(".vc_form_signup").submit();},signUpSubmit:function(e){e.preventDefault();if(visitorChat.config.processingSignUp)
return false;var validationErrors={};if(jQuery.trim(jQuery(".vc_input_username").val())===''||jQuery(".vc_input_username").val()===jQuery(".vc_input_username").attr("placeholder"))
{validationErrors["username"]=[visitorChat.Lang.ValidationUsernameRequired];}
if(jQuery.trim(jQuery(".vc_input_email").val())===''||jQuery(".vc_input_email").val()===jQuery(".vc_input_email").attr("placeholder"))
{validationErrors["email"]=[visitorChat.Lang.ValidationEmailRequired];}
if(validationErrors.hasOwnProperty("username")||validationErrors.hasOwnProperty("email"))
{visitorChat.renderNotifications(validationErrors,true);return;}
visitorChat.config.processingSignUp=true;var now=new Date();var dateStr=now.getFullYear()+"-"+(now.getMonth()+1)+"-"+now.getDate()+" "+now.getHours()+":"+now.getMinutes()+":"+now.getSeconds();jQuery(".vc_input_visitor_time").val(dateStr);var formData=jQuery(".vc_form_signup").serializeArray();formData.push({name:'callback',value:visitorChat.getJsonpCallbackName()});jQuery.ajax({url:ceVC_JS.ajaxUrl+"?action=cevc_signup",data:formData,dataType:"json",type:"post",success:function(result){if(result.success===true)
{visitorChat.config.visitor.username=jQuery(".vc_input_username").val();visitorChat.config.visitor.avatar=CryptoJS.MD5(jQuery.trim(jQuery(".vc_input_email").val()).toLowerCase());visitorChat.clearNotifications();visitorChat.config.signedUp=true;visitorChat.setHeaderText();visitorChat.readMessages();jQuery(".vc_signup_wrapper").hide();jQuery(".vc_conversation").show();visitorChat.scrollBottom(false);}
else
{visitorChat.renderNotifications(result.errors,true);}},complete:function(){visitorChat.config.processingSignUp=false;}});},setHeaderText:function(){if(visitorChat.config.signedUp===true)
jQuery(".vc_chat_head-title").html(visitorChat.Lang.HeaderCurrentlyChatting);else if(visitorChat.config.isOnline===true)
jQuery(".vc_chat_head-title").html(visitorChat.Lang.HeaderOnline);else
jQuery(".vc_chat_head-title").html(visitorChat.Lang.HeaderOffline);},checkStatus:function(async){var chatHtml='<div class="vc_chat_container" style="display: none;"><div class="vc_chat_head"><i class="vc_header_icon"><span class="vc_notification_badge"><span class="vc_notification_badge_number"></span></span></i><span class="vc_chat_head-title">{HeaderText}</span></div><div class="vc_chat_toggle_container"><div class="vc_sub-head-spacer"></div><div class="vc_notifications_wrapper"><i class="vc_btn_notifications_close">×</i><ul class="vc_errorlist"></ul></div><div class="vc_enquiry_wrapper"><form class="vc_form_enquiry" accept-charset="UTF-8"><p>{OfflineMessage}</p><input maxlength="45" type="text" name="data[Enquiry][username]" placeholder="{UsernamePlaceholder}" class="vc_input_enquiry_username"/><input maxlength="85" type="text" name="data[Enquiry][email]" placeholder="{EmailPlaceholder}" class="vc_input_enquiry_email"/><textarea maxlength="1500" placeholder="{EnquiryMessagePlaceholder}" name="data[Enquiry][message]" class="vc_input_enquiry_message"></textarea><a href="#" class="vc_btn_enquiry vc_btn_style">{EnquiryButtonText}</a></form></div><div class="vc_signup_wrapper"><form class="vc_form_signup" accept-charset="UTF-8"><p>{OnlineMessage}</p><input maxlength="45" type="text" name="data[Discussion][username]" placeholder="{UsernamePlaceholder}" class="vc_input_username"/><input maxlength="85" type="text" name="data[Discussion][email]" placeholder="{EmailPlaceholder}" class="vc_input_email"/><input type="hidden" class="vc_input_visitor_time" name="data[Discussion][visitor_time]"/><a href="#" class="vc_btn_signup vc_btn_style">{StartChatButtonText}</a></form></div><div class="vc_conversation"><div class="vc_conversation_container"><p class="vc_message_intro" data-id="-10">{FirstMessageText}</p></div><div class="vc_composing_container"></div><form class="vc_form_reply" accept-charset="UTF-8"><div class="vc_exit_chat_container"><a href="#" class="vc_btn_exit_chat">{ExitChatButtonText}</a><div class="vc_exit_chat_confirmation_wrapper" style="display: none;"><span>{ExitChatQuestionText} </span><a href="#" class="vc_btn_exit_chat_confirm">{ExitChatButtonConfirmText}</a><span> | </span><a href="#" class="vc_btn_exit_chat_cancel">{ExitChatButtonCancelText}</a></div></div><textarea maxlength="750" placeholder="{MessagePlaceholderText}" name="data[Message][message]" class="vc_input_message"></textarea><a href="#" class="vc_btn_send vc_btn_style">{MessageSendButtonText}</a></form></div></div></div>';if(jQuery(".vc_chat_container").length===0)
{for(i in visitorChat.Lang)
{chatHtml=chatHtml.replace(new RegExp("{"+i+"}","gm"),visitorChat.Lang[i]);}
jQuery('body').append(jQuery(chatHtml));}
clearTimeout(visitorChat.config.timerCheckStatus);var formData=[];if(visitorChat.config.isInitialStatusCheck)
{formData.push({name:'data[referer]',value:encodeURIComponent(encodeURIComponent(document.referrer))});}
else
{formData.push({name:'data[open_state]',value:(jQuery(".vc_chat_toggle_container").is(":visible")&&!jQuery(".vc_chat_container").hasClass("vc_chat_closing"))});}
formData.push({name:'callback',value:visitorChat.getJsonpCallbackName()});jQuery.ajax({url:ceVC_JS.ajaxUrl+"?action=cevc_status",dataType:"json",data:formData,async:async,success:function(result){var wasSignedUp=visitorChat.config.signedUp;if(result.success)
{visitorChat.config.visitor.username=result.username;visitorChat.config.visitor.avatar=result.avatar;visitorChat.config.signedUp=result.signed_up;visitorChat.config.isOnline=result.online;}
if(result.success&&result.signed_up===false&&visitorChat.config.signedUp)
{visitorChat.resetDefaultState();}
if(visitorChat.config.isOnline)
{jQuery(".vc_chat_container").addClass("vc_state_online").removeClass("vc_state_offline");}
else
{jQuery(".vc_chat_container").removeClass("vc_state_online").addClass("vc_state_offline");}
if(!visitorChat.config.signedUp)
{if(visitorChat.config.isOnline)
{jQuery(".vc_signup_wrapper").show();jQuery(".vc_conversation").hide();jQuery(".vc_enquiry_wrapper").hide();}
else
{jQuery(".vc_enquiry_wrapper").show();jQuery(".vc_signup_wrapper").hide();jQuery(".vc_conversation").hide();}}
else
{if(visitorChat.config.isOnline)
{visitorChat.clearNotifications();}
else
{var notifications={offline:[visitorChat.Lang.OperatorOfflineMessage]};visitorChat.renderNotifications(notifications,true);}}
var wasInitialStatusCheck=visitorChat.config.isInitialStatusCheck;if(visitorChat.config.isInitialStatusCheck===true)
{visitorChat.config.isInitialStatusCheck=false;visitorChat.config.muteNotificationSoundOnce=true;visitorChat.setHeaderText();visitorChat.init();if(result.open_state===true)
{visitorChat.config.restoreOpenState=true;}}
if(result.success&&result.signed_up===true&&wasSignedUp===false&&wasInitialStatusCheck===false)
{jQuery(".vc_enquiry_wrapper").hide();jQuery(".vc_signup_wrapper").hide();jQuery(".vc_conversation").show();visitorChat.readMessages();}},complete:function(){visitorChat.setHeaderText();visitorChat.config.timerCheckStatus=setTimeout(function(){visitorChat.checkStatus(true);},25000);},error:function(e){}});},getJsonpCallbackName:function(){var callback='visitorChat_';var cStr=parseInt(new Date().getTime()/1000).toString().substring(5)+(Math.random()+' ').substring(5,10);var mult=parseInt(cStr.charAt(Math.floor(Math.random()*cStr.length)));mult=(mult===0||mult===1)?mult+2:mult;var cStr=mult.toString()+cStr.toString()+(cStr*mult).toString();callback+=cStr.toString().split("").reverse().join("");return callback;},timerNotifications:null,renderNotifications:function(errorMessages,isError){if(isError)
{jQuery(".vc_notifications_wrapper").removeClass("vc_notification_success");jQuery(".vc_notifications_wrapper").addClass("vc_notification_error");}
else
{jQuery(".vc_notifications_wrapper").addClass("vc_notification_success");jQuery(".vc_notifications_wrapper").removeClass("vc_notification_error");}
jQuery(".vc_notifications_wrapper ul.vc_errorlist").html("");for(k in errorMessages)
{for(var l=0;l<errorMessages[k].length;l++)
jQuery(".vc_notifications_wrapper ul.vc_errorlist").append(jQuery("<li>"+errorMessages[k][l]+"</li>"));}
jQuery(".vc_notifications_wrapper").slideDown();clearTimeout(visitorChat.timerNotifications);visitorChat.timerNotifications=setTimeout(function(){jQuery(".vc_notifications_wrapper").slideUp();},10000);},clearNotifications:function(){jQuery(".vc_notifications_wrapper").hide();jQuery(".vc_notifications_wrapper ul.vc_errorlist").html("");},btnNotificationsCloseClick:function(e){e.preventDefault();jQuery(".vc_notifications_wrapper").slideUp();},messageKeyUp:function(e){if(e.shiftKey)
return;if(e.keyCode===13&&!jQuery(this).hasClass("vc_input_enquiry_message")){jQuery(this).parents('form').submit();}},messageKeyDownWait:false,messageKeyDown:function(e){if(visitorChat.messageKeyDownWait===true)
{return;}
else
{visitorChat.messageKeyDownWait=true;setTimeout(function(){visitorChat.messageKeyDownWait=false;},1000);}
if(ceVC_JS.ajaxUrl.isDemo===false)
{jQuery.ajax({url:ceVC_JS.ajaxUrl+"?action=cevc_composing",dataType:"json",type:"post",jsonpCallback:visitorChat.getJsonpCallbackName(),success:function(result){},complete:function(){}});}},sendClick:function(e){e.preventDefault();jQuery('form.vc_form_reply').submit();},addMessageToChat:function(message,options){var isConfirmed="";if((typeof options!=='undefined'&&options.confirmed===false)&&visitorChat.config.visitor.username===message.User.username)
isConfirmed=" vc_submission_pending";else if(typeof options==='undefined'&&visitorChat.config.visitor.username===message.User.username)
isConfirmed=" vc_submission_confirmed";if(jQuery(".vc_conversation_container [data-id="+message.Message.id+"]").length===0)
{jQuery(".vc_conversation_container .vc_message_intro").remove();var mTime=('0'+new Date(message.Message.created).getHours()).slice(-2)+":"+('0'+new Date(message.Message.created).getMinutes()).slice(-2);var newMessage=jQuery("<div style=\"clear: both\" class=\"vc_chatrow"+(message.User.is_admin?" vc_admin_message":"")+"\" data-id=\""+message.Message.id+"\"><div class=\"vc_time"+isConfirmed+"\">"+mTime+"</div><p><i style=\"background-image: url(http://www.gravatar.com/avatar/"+message.User.avatar+"?s=40&d="+encodeURIComponent(ceVC_JS.gravatarDefault)+");\" class=\"vc_avatar\"></i><span class=\"vc_username\">"+message.User.username+"</span> "+visitorChat.prepareMessageText(message.Message.message)+"</p></div>");jQuery(".vc_conversation_container").append(newMessage);jQuery('.vc_conversation_container').children().sort(function(a,b){var aF=parseInt(jQuery(a).attr("data-id"));var bF=parseInt(jQuery(b).attr("data-id"));if(aF>bF)
return 1;else if(aF<bF)
return-1;else
return 0;}).appendTo('.vc_conversation_container');}},prepareMessageText:function(text){var smilies=[{s:[' :-) ',' :) '],r:"smiling"},{s:[' :-( ',' :( '],r:"frowning"},{s:[' :-/ ',' :/ '],r:"unsure"},{s:[' ;-) ',' ;) '],r:"winking"},{s:[' :-D ',' :D '],r:"grinning"},{s:[' B-) ',' B) '],r:"cool"},{s:[' :-P ',' :P '],r:"tongue_out"},{s:[' :-| ',' :| '],r:"speechless"},{s:[' :-O ',' :O '],r:"gasping"},{s:[' X-( ',' X( '],r:"angry"},{s:[' O:-) ',' 0:) '],r:"angel"}];text=" "+text+" ";for(var i=0;i<smilies.length;i++)
{for(var j=0;j<smilies[i].s.length;j++)
{var smilie=' <i style="background-image: url('+('{VC_REP}'+'img/chat/smilies/'+smilies[i].r+'.png')+');" class="vc_smilie" >'+jQuery.trim(smilies[i].s[j].toString())+'</i> ';var regex=new RegExp(visitorChat.escapeRegExp(smilies[i].s[j]),'g');text=text.replace(regex,smilie);}}
var wAddress=/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;var eAddress=/\w+@[a-zA-Z_]+?(?:\.[a-zA-Z]{2,6})+/gim;text=text.replace(wAddress,'<a href="$&">$&</a>').replace(eAddress,'<a href="mailto:$&">$&</a>');var tmp=jQuery("<div>"+text+"<div>");tmp.find("a").each(function(){if(jQuery(this).attr("href").indexOf(document.domain)===-1)
{jQuery(this).attr("target","_blank").addClass("vc_link_external");}
else
{jQuery(this).addClass("vc_link_internal");}});var regex=new RegExp(visitorChat.escapeRegExp("{VC_REP}"),'g');text=jQuery(tmp).html().replace(regex,visitorChat.config.assetBase);return jQuery.trim(text).replace(/\n/g,'<br />');},escapeRegExp:function(str){return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g,"\\$&");},loadMore:function(){visitorChat.readMessages(true);},readMessages:function(loadMore){if(!loadMore)
clearTimeout(visitorChat.config.timerReadMessages);if(!visitorChat.config.signedUp)
{return;}
if(visitorChat.config.isNewPage)
{}
var requestData=[];if(loadMore===true)
{requestData.push({name:'data[first_id]',value:visitorChat.getMinId()});requestData.push({name:'data[load_more]',value:true});}
else
{requestData.push({name:'data[last_id]',value:visitorChat.getMaxId()});requestData.push({name:'data[is_new_page]',value:visitorChat.config.isNewPage});}
jQuery.ajax({data:requestData,url:ceVC_JS.ajaxUrl+"?action=cevc_read",dataType:"json",type:"post",jsonpCallback:visitorChat.getJsonpCallbackName(),success:function(result){if(result.success===true)
{var scrollToElem=jQuery("[data-id="+visitorChat.getMinId()+"]");for(var i=0;i<result.messages.length;i++)
{visitorChat.addMessageToChat(result.messages[i]);}
if(loadMore)
jQuery(".vc_btn_load_more").remove();if(result.messages.length>=20&&jQuery(".vc_btn_load_more").length===0)
{jQuery(".vc_conversation_container").prepend(jQuery("<a href=\"#\" data-id=\"-10\" class=\"vc_btn_load_more vc_btn_style\">Load More</a>"));jQuery(".vc_btn_load_more").on("click",visitorChat.loadMore);}
if(result.composing===true&&result.messages.length===0)
{jQuery(".vc_composing_container").html(visitorChat.Lang.OperatorComposing.replace("{username}",result.composing_username)).slideDown();}
else
{jQuery(".vc_composing_container").slideUp();}
if(result.messages.length>0)
{jQuery(".vc_composing_container").slideUp();}
if(!loadMore&&result.messages.length>0)
{visitorChat.playNotificationSound();visitorChat.scrollBottom(true);if(!visitorChat.config.isNewPage&&!jQuery(".vc_conversation_container").is(":visible"))
{jQuery(".vc_chat_container").animate({opacity:1},{duration:200,queue:false});jQuery(".vc_chat_container").animate({'margin-bottom':-7},100,function(){jQuery(this).animate({'margin-bottom':0},100,function(){jQuery(this).animate({'margin-bottom':-5},100,function(){jQuery(this).animate({'margin-bottom':0},200,function(){});});});});jQuery(".vc_notification_badge_number").fadeIn();var newVal=(isNaN(parseInt(jQuery(".vc_notification_badge_number").html()))?0:parseInt(jQuery(".vc_notification_badge_number").html()))+result.messages.length;jQuery(".vc_notification_badge_number").html(newVal);jQuery(".vc_chat_container").addClass('vc_has_unread_messages');}}
else if(loadMore)
{visitorChat.scrollToElement(scrollToElem);}
if(result.messages.length===0)
visitorChat.config.decayHistory++;else
visitorChat.config.decayHistory=0;var duration=visitorChat.calculateTimeOutDuration();if(!loadMore)
{visitorChat.config.timerReadMessages=setTimeout(function(){visitorChat.readMessages(false);},duration);}}
else if(result.success===false)
{visitorChat.checkStatus(true);}
else
{}
visitorChat.config.isNewPage=false;}});},playNotificationSound:function(){if(visitorChat.config.isNewPage||(visitorChat.config.windowFocused&&jQuery(".vc_conversation_container").is(":visible"))||visitorChat.config.muteNotificationSoundOnce)
{visitorChat.config.muteNotificationSoundOnce=false;return;}
jQuery("#vc_auto_tag").remove();var soundWav=visitorChat.config.assetBase+"files/notification/vc_blubb.wav";var soundMp3=visitorChat.config.assetBase+"files/notification/vc_blubb.mp3";var soundOgg=visitorChat.config.assetBase+"files/notification/vc_blubb.ogg";jQuery('<audio id="vc_auto_tag" autoplay="autoplay">').append(jQuery('<source>').attr('src',soundWav)).append(jQuery('<source>').attr('src',soundMp3)).append(jQuery('<source>').attr('src',soundOgg)).appendTo('body');},calculateTimeOutDuration:function(){if(!jQuery(".vc_conversation_container").is(":visible"))
return 10000;var calcTimeOut=(parseFloat(visitorChat.config.decayHistory/10)*1000);if(calcTimeOut>10000)
return 10000;if(calcTimeOut>visitorChat.config.defaultTimeout)
{return calcTimeOut;}
return visitorChat.config.defaultTimeout;},getMaxId:function(){if(jQuery(".vc_chatrow").length===0)
return 0;var numbers=jQuery(".vc_chatrow").map(function(){return parseFloat(this.getAttribute('data-id'))||-Infinity;}).toArray();return Math.max.apply(Math,numbers);},getMinId:function(){if(jQuery(".vc_chatrow").length===0)
return 0;var numbers=jQuery(".vc_chatrow").map(function(){return parseFloat(this.getAttribute('data-id'))||-Infinity;}).toArray();return Math.min.apply(Math,numbers);},scrollBottom:function(animate){if(!animate)
jQuery(".vc_conversation_container").scrollTop(jQuery(".vc_conversation_container")[0].scrollHeight);else
jQuery(".vc_conversation_container").animate({scrollTop:jQuery(".vc_conversation_container")[0].scrollHeight},800);},scrollToElement:function(elem){var offset=jQuery(elem).offset().top+(jQuery(elem).height()*2)-jQuery(".vc_conversation_container").height();jQuery(".vc_conversation_container").animate({scrollTop:offset},800);},browserTest:function(test){var isOpera=!!(window.opera&&window.opera.version);var isFirefox=visitorChat.testStyle('MozBoxSizing');var isSafari=Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor')>0;var isChrome=!isSafari&&visitorChat.testStyle('WebkitTransform');var isIE=false||visitorChat.testStyle('msTransform');var browserValue={'Opera':isOpera,'Firefox':isFirefox,'Safari':isSafari,'Chrome':isChrome,'IE':isIE};return browserValue[test];},testStyle:function(property){return property in document.documentElement.style;},checkIfMobileDevice:function(){var n=(navigator.userAgent||navigator.vendor||window.opera);return/android.+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge|maemo|midp|mmp|netfront|opera m(ob|in)i|palm(os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows(ce|phone)|xda|xiino/i.test(n)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i.test(n.substr(0,4));}};function vcLoadJS(vcCallback){var script=document.createElement("script");script.type="text/javascript";if(script.readyState){script.onreadystatechange=function(){if(script.readyState==="loaded"||script.readyState==="complete"){script.onreadystatechange=null;vcCallback();}};}else{script.onload=function(){vcCallback();};}
script.src=ceVC_JS.baseUrl+"js/jquery/jquery.min.js?ver="+ceVC_JS.version;var ph=document.getElementsByTagName('script')[0];ph.parentNode.insertBefore(script,ph);}
if(ceVC_JS.hideMobile===false||(ceVC_JS.hideMobile===true&&visitorChat.checkIfMobileDevice()===false))
{if(typeof jQuery==='undefined')
{vcLoadJS(function(){jQuery(document).ready(function(){visitorChat.checkStatus(false);});});}
else
{jQuery(document).ready(function(){visitorChat.checkStatus(false);});}}})(jQuery);