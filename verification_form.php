<?php
session_start();



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>verification_form</title>
</head>

<body>
    <div>
        <form action="password-reset-code.php" method="POST">
            <div>
                <label for="">Enter the otp</label>
                <input type="text" name="otp" value="" placeholder="Enter the otp ">

            </div>
            <div>
                <button type="submit" name='submit_otp'>Submit</button>
            </div>

        </form>
    </div>


</body>

</html>