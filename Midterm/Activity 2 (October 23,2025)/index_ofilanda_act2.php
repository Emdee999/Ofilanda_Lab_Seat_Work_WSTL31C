<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Demo</title>
</head>
<body>

    <?php

    $name ="Marc Dave Ofilanda";
    $greeting = "Hello I'm $name !!!";

    $outputname = "Activity 2 (October 23, 2025)";
    echo $outputname;

    $output = $greeting;
    echo $output;
    ?>

    <h1>PHP Activity: Math Functions, Loops, Constants, Superglobals, and Form Handling</h1>

    <h2>Math Functions</h2>
    <?php
        $num1 = 16;
        $num2 = 3;
        echo "<p>Square root of $num1: " . sqrt($num1) . "</p>";
        echo "<p>Power of $num2 to the power of 3: " . pow($num2, 3) . "</p>";
    ?>

    <h2>Loops</h2>
    <p>Using a for loop to print numbers from 1 to 5:</p>
    <?php
        for ($i = 1; $i <= 5; $i++) {
            echo "<p>Number: $i</p>";
        }
    ?>

    <h2>Constants</h2>
    <?php
        define("SITE_NAME", "My PHP Demo Site");
        echo "<p>Site Name: " . SITE_NAME . "</p>";
    ?>

    <h2>PHP Superglobals</h2>
    <p>Example of <strong>$_SERVER</strong> superglobal:</p>
    <?php
        echo "<p>Your current PHP script is located at: " . $_SERVER['SCRIPT_NAME'] . "</p>";
        echo "<p>Client IP address: " . $_SERVER['REMOTE_ADDR'] . "</p>";
    ?>

    <h2>Form Handling</h2>
    <form method="POST" action="">
        <label for="name">Enter your name:</label>
        <input type="text" id="name" name="name">
        <input type="submit" value="Submit">
    </form>

    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = htmlspecialchars($_POST['name']);
            if (!empty($name)) {
                echo "<p>Hello, $name! You can now go HOME!!!</p>";
            } else {
                echo "<p>Please enter your name.</p>";
            }
        }
    ?>

    

</body>
</html>
