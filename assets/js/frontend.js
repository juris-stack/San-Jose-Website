jQuery(function($) {
    
    $('#nav-toggle').click(function(e) {
        $('#main-menu').toggleClass('open');
        e.preventDefault();
    });
    
    var chatLogFile = '';
    var chatInterval = 0;
    $( '#chat-form' ).on('submit', function(e){
        $('#chat-notice, #chat-status, #chat-name, #chat-email, #chat-submit').hide();
        $('#chat-box, #end-chat').show();
        var data = {
            text: $('#chat-message').val(),
            email: $('#chat-email').val(),
            name: $('#chat-name').val(),
            action: 'ajax_start_chat'
        };
        $.post('ajax.php', data,
            function (results) {
                var json_obj = $.parseJSON(results);
                chatLogFile = json_obj;
                $('#chat-message').val( '' ).focus();
                loadchat();
                chatInterval = setInterval ( function() {
                    loadchat();
                }, 2500 );
            }
        );
        e.preventDefault();
    });
    
    $( document ).on( 'keypress', '#chat-message', function(e){
        var code = e.keyCode ? e.keyCode : e.which;
        if( code == 10 || code == 13 ) {
            var message = $(this).val();
            if( message ) {
                var data = {
                    text: message,
                    name: $('#chat-name').val(),
                    file: chatLogFile,
                    action: 'ajax_chat_send_message'
                };
                $.post('ajax.php', data);
                $(this).val( '' ).focus();
                e.preventDefault();
            }
        }
    });
    
    $('#chat-menu-close').click(function(e) {
        $('#chat').addClass('open');
        e.preventDefault();
    });
    
    $('#minimize-chat').click(function(e){
        $('#chat').removeClass('open');
        e.preventDefault();
    });
    
    $('#end-chat').click(function(e){
        if( confirm('Are you sure you want to end this chat session?') ) {
            var data = {
                name: $('#chat-name').val(),
                file: chatLogFile,
                action: 'ajax_chat_end'
            };
            $.post('ajax.php', data, function (results) {
                loadchat();
            });
            $("#chat-message, #end-chat").hide();
            $('#chat').addClass('ended');
            clearInterval(chatInterval);
            e.preventDefault();
        }
    });
    
    var loadchat = function() {
        var oldscrollHeight = $("#chat-box").prop("scrollHeight") - 20; //Scroll height before the request
        $.ajax({
            url: chatLogFile,
            cache: false,
            success: function(html){		
                $("#chat-box").html(html); //Insert chat log into the #chatbox div	

                //Auto-scroll			
                var newscrollHeight = $("#chat-box").prop("scrollHeight") - 20; //Scroll height after the request
                if(newscrollHeight > oldscrollHeight){
                    $("#chat-box").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
                }				
            }
        });
    };
});