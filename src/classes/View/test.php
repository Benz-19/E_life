<html>

<head>
    <meta charset="utf-8" />
    <title>PHP Web Sockets With Ratchet</title>
    <link href="./style.css" rel="stylesheet">
    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
</head>

<body>
    <div class="chat_window">
        <div class="top_menu">
            <div class="buttons">
                <div class="button close">
                </div>
                <div class="button minimize">
                </div>
                <div class="button maximize">
                </div>
            </div>
            <div class="title">Chat</div>
        </div>
        <ul class="messages"></ul>
        <div class="bottom_wrapper clearfix">
            <div class="message_input_wrapper">
                <input class="message_input" placeholder="Type your message here..." />
            </div>
            <div class="send_message">
                <div class="icon"></div>
                <div class="text">Send</div>
            </div>
        </div>
    </div>
    <div class="message_template">
        <li class="message">
            <div class="avatar"></div>
        <li>
    </div>
    <div class="text_wrapper">
        <div class="text">
        </div>
    </div>
    </li>
    </div>
    <script>
        // Socket Server
        var conn = new WebSocket('ws://localhost:8080');
        conn.onopen = function(e) {
            console.log("Connection established!");
        };
        (function() {
            var Message;
            Message = function(arg) {
                this.text = arg.text, this.message_side = arg.message_side;
                this.draw = function(_this) {
                    return function() {
                        var $message;
                        $message = $($('.message_template').clone().html());
                        $message.addClass(_this.message_side).find('.text').html(_this.text);
                        $('.messages').append($message);
                        return setTimeout(function() {
                            return $message.addClass('appeared');
                        }, 0);
                    };
                }(this);
                return this;
            };
            $(function() {
                var getMessageText, message_side, sendMessage;
                message_side = 'right';
                getMessageText = function() {
                    var $message_input;
                    $message_input = $('.message_input');
                    conn.send($message_input.val());
                    return $message_input.val();
                };
                sendMessage = function(text, message_side) {
                    var $messages, message;
                    if (text.trim() === '') {
                        return;
                    }
                    $('.message_input').val('');
                    $messages = $('.messages');
                    message_side = message_side || 'left';
                    message = new Message({
                        text: text,
                        message_side: message_side
                    });
                    message.draw();
                    return $messages.animate({
                            scrollTop: $messages.prop('scrollHeight')
                        },
                        300
                    );
                };
                $('.send_message').click(function(e) {
                    return sendMessage(getMessageText());
                });
                $('.message_input').keyup(function(e) {
                    if (e.which === 13) {
                        return sendMessage(getMessageText());
                    }
                });
                conn.onmessage = function(e) {
                    console.log(e.data);
                    sendMessage(e.data, 'right');
                };
            });
        }.call(this));
    </script>
</body>

</html>