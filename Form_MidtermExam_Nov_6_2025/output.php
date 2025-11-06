<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form Output by Ofilanda</title>
    <link rel="stylesheet" href="design.css">
</head>
<body>

    <div class="container">
        <h2>Output:</h2>

    <?php
    
    if (isset($_POST['user_name']) && !empty($_POST['user_name'])) {
    
        $name = $_POST['user_name'];
        echo "Hello, " . htmlspecialchars($name) . "!";
        
    } else {
        echo "You did not enter a name.";
    }

    if (isset($_POST['email_add']) && !empty($_POST['email_add'])) {
    
        $email = $_POST['email_add'];
        echo "<br>Your email address is: " . htmlspecialchars($email);
        
    } else {
        echo "<br>You did not enter an email address.";
    }
    ?>

    <br><br>
    <a href="form.html">Go Back</a>

    </div>

</body>
</html>