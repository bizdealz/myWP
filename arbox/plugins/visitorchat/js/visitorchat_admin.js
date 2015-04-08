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
(function($) {

    $(document).ready(function() {
        visitorChat.init();
    });

    var visitorChat = {
        config: {
            windowFocused: false, // indicates whether the window was fous
            isNewPage: true, // indicates whether the is a new page without any loading to have happened
            user: ceVC_JS.user, // holds the user-object
            timerReadMessages: null, // the timer for readMessages
            assetBase: ceVC_JS.baseUrl + "/" // baseUrl for system-assets
        },
        init: function() {
            var processing = false;

            $(window).focus(function() {
                visitorChat.config.windowFocused = true;
                console.log('Window focus');
            });
            $(window).blur(function() {
                visitorChat.config.windowFocused = false;
                console.log('Window blur');
            });

            visitorChat.readMessages();
            $(document).on("keyup", ".input_message", visitorChat.messageKeyUp);
            $(document).on("keydown", ".input_message", visitorChat.messageKeyDown);
            $(document).on("click", ".btn_close_tab", visitorChat.clickCloseTab);
            $(document).on("click", ".chat_tab-nav a", visitorChat.clickTab);
            $(document).on("click", ".btn_load-more", visitorChat.clickLoadMore);

            $(document).on("submit", ".form_discussion", function() {
                console.log('Form submit');
                var message = jQuery.trim($(this).find(".input_message").val());
                message = $('<p>' + message + '</p>').text();
                $(this).find(".input_message").val(message);

                if (message === "")
                    return false;

                if (processing)
                    return false;

                processing = true;
                // Process Post
                var formData = $(this).serializeArray();
                $(this).find(".input_message").val("");
                var discussionId = $(this).find(".input_discussion_id").val();
                console.log("Discussion ID: " + discussionId);
                var tmpMessage = {Message: [], User: {username: visitorChat.config.user.username, avatar: visitorChat.config.user.avatar}};

                tmpMessage.Message.id = visitorChat.getMaxId($(this).parents(".discussion_wrapper").find(".discussion_container")) + 999;
                tmpMessage.Message.message = message;
                tmpMessage.Message.created = new Date();

                visitorChat.readMessages();
                visitorChat.addMessage(discussionId, tmpMessage, {confirmed: false});
                visitorChat.scrollBottom(discussionId, false);

                $.ajax({
                    type: "post",
                    url: ceVC_JS.ajaxUrl + "?action=cevc_admin_send",
                    data: formData,
                    dataType: "json",
                    success: function(result) {
                        if (result.success === true)
                        {
                            $("[data-id=" + tmpMessage.Message.id + "]").remove();
                            visitorChat.addMessage(discussionId, result.data);
                        }
                        else
                        {
                            $("[data-id=" + tmpMessage.Message.id + "]").css('border', '1px dashed #f00');
                        }
                    },
                    complete: function() {
                        processing = false;
                    },
                    error: function(xhr, status) {
                        if (xhr.status === 403)
                        {
                            location.reload();
                        }
                    }
                });

                return false;
            });


        },
        clickCloseTab: function(e) {
            e.preventDefault();
            clearTimeout(visitorChat.config.timerReadMessages);

            var discussionId = $($(this).parents("a").attr('href')).find("[data-discussion_id]").attr("data-discussion_id");
            $(this).remove();

            var requestData = [];
            requestData.push({name: 'data[discussion_id]', value: discussionId});
            $.ajax({
                async: true,
                cache: false,
                type: 'post',
                data: requestData,
                url: ceVC_JS.ajaxUrl + "?action=cevc_admin_discussion_close",
                dataType: 'json',
                beforeSend: function() {

                },
                success: function(data) {
                    if (data.success)
                    {
                        visitorChat.removeDiscussion(discussionId);
                    }
                },
                complete: function() {
                    visitorChat.readMessages();
                },
                error: function(xhr, status) {
                    if (xhr.status === 403)
                    {
                        location.reload();
                    }
                }
            });

            console.log("Clicked close tab: " + discussionId);
        },
        removeDiscussion: function(discussionId) {
            $("[href=#tab_" + discussionId + "]").parents("li").fadeOut(300, function() {
                $(this).remove();
                $("#tab_" + discussionId + "").remove();
                $("[href=#tab_default]").tab("show");
            });
        },
        clickTab: function(e) {
            e.preventDefault();
            $(this).find(".notification_badge").hide("fast", function() {
                $(this).remove();
            });

            if ($(this).attr('href') === "#tab_default")
                return;

            var discussionId = $($(this).attr('href')).find("[data-discussion_id]").attr("data-discussion_id");
            visitorChat.scrollBottom(discussionId, false);
        },
        clickLoadMore: function(e) {
            e.preventDefault();

            var discussion_id = $(this).parents(".discussion_wrapper").find(".input_discussion_id").val();
            var first_id = visitorChat.getMinId($(this).parents(".discussion_wrapper"));
            console.log("dId: " + discussion_id + " | fId: " + first_id);

            visitorChat.loadMoreMessages(discussion_id, first_id);

            $(this).remove();
        },
        loadMoreMessages: function(discussion_id, first_id) {
            var requestData = [];
            requestData.push({name: 'data[discussion_id]', value: discussion_id});
            requestData.push({name: 'data[first_id]', value: first_id});

            $.ajax({
                async: true,
                cache: false,
                type: 'post',
                data: requestData,
                url: ceVC_JS.ajaxUrl + "?action=cevc_admin_read_more",
                dataType: 'json',
                beforeSend: function() {

                },
                success: function(data) {
                    if (data.success)
                    {
                        for (i in data.messages)
                        {
                            visitorChat.addMessage(discussion_id, data.messages[i]);
                        }
                        if (data.messages.length === 20)
                        {
                            $("#tab_" + discussion_id + " .discussion_container").prepend($('<a href="#" class="btn_load-more">Load More</a>'));
                        }
                    }
                },
                error: function(xhr, status) {
                    if (xhr.status === 403)
                    {
                        location.reload();
                    }
                }
            });

        },
        messageKeyUp: function(e) {
            if (e.shiftKey)
                return;
            if (e.keyCode === 13) {
                console.log('Message pressed ENTER');
                console.log($(this).parents(".discussion_wrapper").attr('id'));
                $(".form_discussion").submit();
            }
        },
        messageKeyDownWait: false,
        messageKeyDown: function(e) {

            // if IsWaiting return else start action
            // write 500ms timeout set isWaiting true, afterwards false

            if (visitorChat.messageKeyDownWait === true)
            {
                console.log("Composing: 500ms have not yet passed");
                return;
            }
            else
            {
                visitorChat.messageKeyDownWait = true;

                setTimeout(function() {
                    visitorChat.messageKeyDownWait = false;
                }, 1000);

            }
            var disc_id = $(this).parents(".form_discussion").attr('data-discussion_id');

            var requestData = [];
            requestData.push({name: 'data[discussion_id]', value: disc_id});

            if (ceVC_JS.ajaxUrl.isDemo === false)
            {
                $.ajax({
                    async: true,
                    cache: false,
                    type: 'post',
                    data: requestData,
                    dataType: 'json',
                    url: ceVC_JS.ajaxUrl + "?action=cevc_admin_composing",
                    success: function(result) {
                        console.log("Sent Composing-State...");
                    },
                    complete: function() {
                    }
                });
            }
        },
        readMessages: function() {
            var requestData = [];

            var i = 0;
            $(".discussion_wrapper").each(function() {
                requestData.push({name: 'data[Discussion][' + i + '][discussion_id]', value: $(this).find(".input_discussion_id").val()});
                requestData.push({name: 'data[Discussion][' + i + '][last_id]', value: visitorChat.getMaxId($(this).find(".discussion_container"))});
                i++;
            });
            requestData.push({name: 'data[is_new_page]', value: visitorChat.config.isNewPage});

            $.ajax({
                async: true,
                cache: false,
                type: 'post',
                data: requestData,
                url: ceVC_JS.ajaxUrl + "?action=cevc_admin_read",
                dataType: 'json',
                beforeSend: function() {
                    clearTimeout(visitorChat.config.timerReadMessages);
                },
                success: function(data) {
                    for (i in data.discussions)
                    {
                        var tmplFormContainer = '<span class="vc_composing_container"></span><div class="form_container row-fluid">' +
                                '<form class="form_discussion" data-discussion_id="' + data.discussions[i].Discussion.id + '">' +
                                '<textarea maxlength="750" class="input_message span12" name="data[Message][message]"></textarea>' +
                                '<input class="input_discussion_id" type="hidden" name="data[Message][discussion_id]" value="' + data.discussions[i].Discussion.id + '" />' +
                                '</form>' +
                                '</div>';
                        var tmplMetaData = '<table class="table table-condensed table-striped"><tbody>' +
                                '<tr><th colspan="2">' + ceVC_JS.Lang.MetaVisitorData + '</th></tr>' +
                                '<tr><th>' + ceVC_JS.Lang.MetaUsername + '</th><td class="meta_username"></td></tr>' +
                                '<tr><th>' + ceVC_JS.Lang.MetaEmail + '</th><td class="meta_email"></td></tr>' +
                                '<tr><th>' + ceVC_JS.Lang.MetaStarted + '</th><td class="meta_created"></td></tr>' +
                                '<tr><th>' + ceVC_JS.Lang.MetaLastActivity + '</th><td class="meta_modified"></td></tr>' +
                                '<tr><th>' + ceVC_JS.Lang.MetaVisitorExited + '</th><td class="meta_visitor_exited"></td></tr>' +
                                '<tr><th>' + ceVC_JS.Lang.MetaReferer + '</th><td class="meta_referer"></td></tr>' +
                                '<tr><th>' + ceVC_JS.Lang.MetaVisitorLanguages + '</th><td class="meta_visitor_languages"></td></tr>' +
                                '<tr><th>' + ceVC_JS.Lang.MetaVisitorTime + '</th><td class="meta_visitor_time"></td></tr>' +
                                '<tr><th>' + ceVC_JS.Lang.MetaBrowser + '</th><td class="meta_browser_name"></td></tr>' +
                                '<tr><th>' + ceVC_JS.Lang.MetaOS + '</th><td class="meta_operating_system"></td></tr>' +
                                '<tr><th>' + ceVC_JS.Lang.MetaCurrentPage + '</th><td class="meta_current_page"></td></tr>' +
                                '</tbody></table>';

                        if ($("#tab_" + data.discussions[i].Discussion.id).length === 0)
                        {
                            // Discussion not yet exist, so add it
                            $('.chat_tab-nav').append('<li class=""><a href="#tab_' + data.discussions[i].Discussion.id + '" data-toggle="tab">' + data.discussions[i].Discussion.username + '</a>');
                            $('.tab-content').append('<div class="tab-pane discussion_wrapper" id="tab_' + data.discussions[i].Discussion.id + '"><div class="row-fluid"><div class="span8"><div class=\"discussion_container"></div>' + tmplFormContainer + '</div>   <div class="span4"><div class="well container_metadata">' + tmplMetaData + '</div></div>   </div></div>');
                        }
                        var disc = data.discussions[i];

                        var addedTab = $("#tab_" + disc.Discussion.id);
                        addedTab.find(".meta_username").html(disc.Discussion.username);
                        addedTab.find(".meta_email").html(disc.Discussion.email);

                        var cTime = ('0' + new Date(disc.Discussion.created).getHours()).slice(-2) + ":" + ('0' + new Date(disc.Discussion.created).getMinutes()).slice(-2) + ":" + ('0' + new Date(disc.Discussion.created).getSeconds()).slice(-2);
                        addedTab.find(".meta_created").html(cTime);
                        var mTime = ('0' + new Date(disc.Discussion.modified).getHours()).slice(-2) + ":" + ('0' + new Date(disc.Discussion.modified).getMinutes()).slice(-2) + ":" + ('0' + new Date(disc.Discussion.modified).getSeconds()).slice(-2);
                        addedTab.find(".meta_modified").html(mTime);
                        addedTab.find(".meta_visitor_exited").html((disc.Discussion.visitor_exited ? ceVC_JS.Lang.Yes : ceVC_JS.Lang.No));
                        addedTab.find(".meta_referer").html(disc.Discussion.referer);

                        var visitorLangs = '';
                        for (li in disc.Discussion.visitor_languages)
                            visitorLangs += disc.Discussion.visitor_languages[li] + ', ';
                        addedTab.find(".meta_visitor_languages").html(visitorLangs.substring(0, visitorLangs.length - 2));

                        addedTab.find(".meta_visitor_time").html(disc.Discussion.visitor_time);
                        addedTab.find(".meta_browser_name").html(disc.Discussion.user_agent.browser_name);
                        addedTab.find(".meta_operating_system").html(disc.Discussion.user_agent.operating_system);

                        if (disc.Discussion.has_expired)
                        {
                            if ($("[href=#tab_" + disc.Discussion.id + "]").parents("li").find(".btn_close_tab").length === 0)
                            {
                                var btn = $('<button type="button" class="close btn_close_tab">&times;</button>');
                                $("[href=#tab_" + disc.Discussion.id + "]").parents("li").find("a").append(btn);
                            }
                        }
                        else
                        {
                            $("[href=#tab_" + disc.Discussion.id + "]").parents("li").find(".btn_close_tab").remove();
                        }

                        if (disc.Discussion.composing && disc.Message.length === 0)
                        {
                            var composingMsg = ceVC_JS.Lang.OperatorComposing;
                            addedTab.find(".vc_composing_container").html(composingMsg.replace("{username}", disc.Discussion.username)).slideDown();
                        }
                        else
                        {
                            addedTab.find(".vc_composing_container").slideUp();
                        }
                        if (disc.Message.length !== 0)
                        {
                            addedTab.find(".vc_composing_container").slideUp();
                        }


                        // Already existing discussion | add notification
                        visitorChat.renderNotificationBadges(disc);

                        for (j in disc.Message)
                        {
                            visitorChat.addMessage(disc.Discussion.id, disc.Message[j]);
                            if (disc.Message[j].Message.current_page !== "")
                            {
                                var hLink = $("<a href=\"" + disc.Message[j].Message.current_page + "\" target=\"_blank\">" + disc.Message[j].Message.current_page + "</a>");
                                addedTab.find(".meta_current_page").html(hLink);
                            }
                        }

                        if (disc.Message.length === 20)
                        {
                            $("#tab_" + disc.Discussion.id + " .discussion_container").prepend($('<a href="#" class="btn_load-more">Load More</a>'));
                        }

                        if (disc.Message.length > 0)
                        {
                            visitorChat.playNotificationSound();
                            visitorChat.scrollBottom(data.discussions[i].Discussion.id, true);
                        }

                    }

                    visitorChat.config.isNewPage = false;

                },
                complete: function() {
                    visitorChat.config.timerReadMessages = setTimeout(visitorChat.readMessages, 3500);
                },
                error: function(xhr, status) {
                    if (xhr.status === 403)
                    {
                        location.reload();
                    }
                }
            });
        },
        renderNotificationBadges: function(discussion) {
            if (discussion.Message.length > 0)
            {
                var tab = $("[href=#tab_" + discussion.Discussion.id + "]");
                if (!tab.parent().hasClass("active"))
                {
                    if (tab.find(".notification_badge").length === 0)
                    {
                        tab.append('<span style="display: none" class="badge badge-success notification_badge">' + discussion.Message.length + '</span>');
                        tab.find(".notification_badge").show("fast");
                    }
                    else
                    {
                        var curVal = parseInt(tab.find(".notification_badge").html()) + discussion.Message.length;
                        tab.find(".notification_badge").html(curVal);
                    }
                }
            }
        },
        addMessage: function(discussionId, message, options) {

            if ($("[data-id=" + message.Message.id + "]").length !== 0)
                return;

            var isConfirmed = ""; // render pending/confirmed message
            if ((typeof options !== 'undefined' && options.confirmed === false) && visitorChat.config.user.username === message.User.username)
                isConfirmed = " vc_submission_pending";
            else if (typeof options === 'undefined' && visitorChat.config.user.username === message.User.username)
                isConfirmed = " vc_submission_confirmed";
            var mTime = ('0' + new Date(message.Message.created).getHours()).slice(-2) + ":" + ('0' + new Date(message.Message.created).getMinutes()).slice(-2);

            $("#tab_" + discussionId + " .discussion_container").append("<div class=\"chatmessage\" data-id=\"" + message.Message.id + "\"><div class=\"message_time" + isConfirmed + "\">" + mTime + "</div><p><i style=\"background-image: url(http://www.gravatar.com/avatar/" + message.User.avatar + "?s=40&d=" + encodeURIComponent(ceVC_JS.gravatarDefault) + ");\" class=\"vc_avatar\"></i><span class=\"username\">" + message.User.username + "</span> " + visitorChat.prepareMessageText(message.Message.message) + "</p></div>");

            $("#tab_" + discussionId + " .discussion_container").children().sort(function(a, b) {
                var aF = parseInt($(a).attr("data-id"));
                var bF = parseInt($(b).attr("data-id"));
                if (aF > bF)
                    return 1;
                else if (aF < bF)
                    return -1;
                else
                    return 0;
            }).appendTo("#tab_" + discussionId + " .discussion_container");

        },
        prepareMessageText: function(text) {
            var smilies = [
                {s: [' :-) ', ' :) '], r: "smiling"},
                {s: [' :-( ', ' :( '], r: "frowning"},
                {s: [' :-/ ', ' :/ '], r: "unsure"},
                {s: [' ;-) ', ' ;) '], r: "winking"},
                {s: [' :-D ', ' :D '], r: "grinning"},
                {s: [' B-) ', ' B) '], r: "cool"},
                {s: [' :-P ', ' :P '], r: "tongue_out"},
                {s: [' :-| ', ' :| '], r: "speechless"},
                {s: [' :-O ', ' :O '], r: "gasping"},
                {s: [' X-( ', ' X( '], r: "angry"},
                {s: [' O:-) ', ' 0:) '], r: "angel"}
            ];

            text = " " + text + " ";
            for (var i = 0; i < smilies.length; i++)
            {
                for (var j = 0; j < smilies[i].s.length; j++)
                {
                    var smilie = ' <i style="background-image: url(' + ('{VC_REP}' + 'img/chat/smilies/' + smilies[i].r + '.png') + ');" class="vc_smilie" >' + jQuery.trim(smilies[i].s[j].toString()) + '</i> ';
                    var regex = new RegExp(visitorChat.escapeRegExp(smilies[i].s[j]), 'g');
                    text = text.replace(regex, smilie);
                }
            }

            var wAddress = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
            var eAddress = /\w+@[a-zA-Z_]+?(?:\.[a-zA-Z]{2,6})+/gim;
            text = text
                    .replace(wAddress, '<a href="$&">$&</a>')
                    .replace(eAddress, '<a href="mailto:$&">$&</a>');

            var tmp = $("<div>" + text + "<div>");
            tmp.find("a").each(function() {
                if ($(this).attr("href").indexOf(document.domain) === -1)
                {
                    $(this).attr("target", "_blank").addClass("vc_link_external");
                }
                else
                {
                    $(this).addClass("vc_link_internal");
                }
            });
            var regex = new RegExp(visitorChat.escapeRegExp("{VC_REP}"), 'g');
            text = $(tmp).html().replace(regex, visitorChat.config.assetBase);

            return jQuery.trim(text).replace(/\n/g, '<br />');
        },
        escapeRegExp: function(str) {
            return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
        },
        playNotificationSound: function() {

            if (visitorChat.config.isNewPage || visitorChat.config.windowFocused)
            {
                return;
            }

            $("#vc_auto_tag").remove();
            var soundWav = visitorChat.config.assetBase + "files/notification/vc_blubb.wav";
            var soundMp3 = visitorChat.config.assetBase + "files/notification/vc_blubb.mp3";
            var soundOgg = visitorChat.config.assetBase + "files/notification/vc_blubb.ogg";

            $('<audio id="vc_auto_tag" autoplay="autoplay">').append($('<source>').attr('src', soundWav))
                    .append($('<source>').attr('src', soundMp3))
                    .append($('<source>').attr('src', soundOgg))
                    .appendTo('body');
            console.log("Playing notification sound...");

        },
        getMaxId: function(parent) {
            if ($(parent).find(".chatmessage").length === 0)
                return 0;

            var numbers = $(parent).find(".chatmessage").map(function() {
                return parseFloat(this.getAttribute('data-id')) || -Infinity;
            }).toArray();

            return Math.max.apply(Math, numbers);
        },
        getMinId: function(parent) {
            if ($(parent).find(".chatmessage").length === 0)
                return 0;

            var numbers = $(parent).find(".chatmessage").map(function() {
                return parseFloat(this.getAttribute('data-id')) || -Infinity;
            }).toArray();

            return Math.min.apply(Math, numbers);
        },
        scrollBottom: function(discussionId, animate) {
            console.log("Being scrolled: #tab_" + discussionId + " .discussion_container");
            if (!animate)
                $("#tab_" + discussionId + " .discussion_container").scrollTop($("#tab_" + discussionId + " .discussion_container")[0].scrollHeight);
            else
                $("#tab_" + discussionId + " .discussion_container").animate({scrollTop: $("#tab_" + discussionId + " .discussion_container")[0].scrollHeight}, 800);
        }
    };


})(jQuery);