<?php

// general error handling

function handle_error($msg)
{
    echo '<div class="msg">' . $msg . '<br></div>';
}

// handle_error("This is an error message");
?>


<style>
    .msg {
        color: red;
        font-size: medium;
        text-align: center;
        font-style: italic;
    }
</style>

<script>
    const error_Msg = document.getElementsByClassName('msg');

    // Error message display timeout
    if (error_Msg) {
        setTimeout(() => {
            for (let i = 0; i < error_Msg.length; i++) {
                error_Msg[i].style.display = 'none';
            }
        }, 8000); //Dsiplays the error message to the user for 8 seconds
    } else {
        console.log("Failed To Display the Error Message");
    }
</script>