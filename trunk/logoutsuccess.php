<?php
/** 
 * Pages shown if the logout is successful
 *
 * @author Muhammad Mainul Hossain
 * @copyright Muhammad Mainul Hossain, Karlsruhe, Germany, 2009
 * @version 0.1
*/ 
if(!isset($_SESSION)) { session_start();}  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Logout</title>
        <link href="style/style.css" rel="stylesheet" type="text/css">
    </head>

<body>
    <?php include("header.php") ?>
    <div id="content">
    <h4> You are successfully logged out.</h4>
    <h3>Thank You for your visit. Please <a href="login.php" >login</a> again soon.</h3>
    </div>
    <?php include("footer.php") ?>
</body>
</html>