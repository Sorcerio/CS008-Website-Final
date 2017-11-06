<?php
// parse the url into htmlentities to remove any suspicious vales that someone
// may try to pass in. htmlentities helps avoid security issues.

$phpSelf = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, "UTF-8");

// break the url up into an array, then pull out just the filename
$path_parts = pathinfo($phpSelf);

 ?>	
<!DOCTYPE html>
<html lang="en">
    <head>
<!-- you can add php code here (similar to nav.php) to print a different title on each page -->
        <title>CBBR Inc.</title>
        <meta charset="utf-8">
        <meta name="author" content="Brody Childs and Treuvor Holowinsky">
        <meta name="description" content="Clean Buring Black Rocks Inc. official website. Home of the revolutionary Clean Burning Black Rock or 'CBBR'." >
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Link to your custom style below -->
        <link rel="stylesheet" type="text/css" href="css/mainStyle.css">

    </head>

    <?php
    
        // giving each body tag an id really helps with css later on
        print '<body id="' . $path_parts['filename'] . '">';

        // Debug Protocol
        $debug = false;
        if(isset($_GET["debug"])) {
            $debug = true;
        }

        // Path Setup
        $domain = "//";
        
        $server = htmlentities($_SERVER["SERVER_NAME"], ENT_QUOTES, 'UTF-8');

        $domain .= $server;

        if($debug) {
            print '<p>php Self: '.$phpSelf;
            print '<p>Path Parts<pre>';
            print_r($path_parts);
            print '</pre></p>';
            
        }
    /*
        // Inclulde all Libraries
        print PHP_EOL.'<!-- include libraries -->'.PHP_EOL;

        require_once("lib/security.php");

        if($path_parts['filename'] == "form") {
            print PHP_EOL.'<!-- include form libraries -->'.PHP_EOL;
            include "lib/validation-functions.php";
            include "lib/mail-message.php";
        }

        print PHP_EOL.'<!-- finished including libraries -->'.PHP_EOL;
    */
    ?>
<!-- ######################     Start of Body   ############################ -->