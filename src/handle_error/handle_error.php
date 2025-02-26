<?php

// general error handling

function handle_error($msg)
{
    echo '<div class="msg">' . $msg . '<br></div>';
}

// general success handling

function success_message($msg)
{
    echo '<div class="msg-success">' . $msg . '<br></div>';
}

// general success handling

function redirect_message($msg)
{
    echo '<div class="msg-redirect">' . $msg . '<br></div>';
}


?>


<style>
    .msg {
        color: red;
        font-size: medium;
        text-align: center;
        font-style: italic;
    }

    .msg-success,
    .msg-redirect {

        color: green;
        font-size: medium;
        font-weight: bold;
        text-align: center;
        font-style: italic;
        box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.21);
        background-color: rgba(218, 243, 239, 0.52);
        text-align: center;
    }
</style>

<script>
    const error_Msg = document.getElementsByClassName('msg');
    const success_Msg = document.getElementsByClassName('msg-success');
    const redirect_Msg = document.getElementsByClassName('msg-redirect');

    // Error message display timeout
    if (error_Msg) {
        setTimeout(() => {
            for (let i = 0; i < error_Msg.length; i++) {
                error_Msg[i].style.display = 'none';
            }
        }, 12000); //Dsiplays the error message to the user for 8 seconds
    } else {
        console.log("Failed To Display the Error Message");
    }

    // Success message display timeout
    if (success_Msg) {
        setTimeout(() => {
            for (let i = 0; i < success_Msg.length; i++) {
                success_Msg[i].style.display = 'none';
            }
        }, 12000); //Dsiplays the success message to the user for 12 seconds
    } else {
        console.log("Failed To Display the Success Message");
    }

    // Redirect message display timeout
    if (redirect_Msg) {
        setTimeout(() => {
            for (let i = 0; i < redirect_Msg.length; i++) {
                redirect_Msg[i].style.display = 'none';
            }
        }, 40000); //Dsiplays the redirect message to the user for 40 seconds
    } else {
        console.log("Failed To Display the Success Message");
    }
</script>