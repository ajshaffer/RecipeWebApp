<?php

session_start();
session_unset();
session_destroy();

$state = 1;

?>

<!DOCTYPE html>
    <html>
        <head>
            <meta http-equiv="refresh" content="3;url=index.php">
            <script>
                var countdown = 3; // Set the countdown duration in seconds

                function updateCountdown() {
                if (countdown > 0) {
                    document.getElementById("countdown").textContent = countdown;
                    countdown--;
                    setTimeout(updateCountdown, 1000); // Update every second
                }
                }

                // Start the countdown when the page loads
                window.onload = function() {
                updateCountdown();
                }
            </script>
        </head>
        <body>
            <h3>You have been logged out. You will be returned to the login page in <span id="countdown">3</span> seconds.</h3>
        </body>
    </html>