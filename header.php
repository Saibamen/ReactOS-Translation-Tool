<?php session_start();
if (isset($_GET['lang']) && !empty($_GET['lang'])) {
    $_SESSION['lang'] = htmlspecialchars($_GET['lang']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>ReactOS Translation Tool</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/vnd.microsoft.icon">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>

    <style type="text/css">
    body {
        background-color:#fff;
        font:13px/20px normal Helvetica, Arial, sans-serif;
        color:#4F5155;
        margin:40px;
    }

    h1 {
        color:#444;
        background-color:transparent;
        border-bottom:1px solid #D0D0D0;
        font-size:19px;
        font-weight:400;
        margin:0 0 14px;
        padding:14px 15px 10px;
    }

    code {
        font-family:Consolas, Monaco, Courier New, Courier, monospace;
        font-size:12px;
        background-color:#f9f9f9;
        border:1px solid #D0D0D0;
        color:#002166;
        display:block;
        margin:14px 0;
        padding:12px 10px;
    }

    #body {
        margin:0 15px;
    }

    p.footer {
        text-align:right;
        font-size:11px;
        border-top:1px solid #D0D0D0;
        line-height:32px;
        margin:20px 0 0;
        padding:0 10px;
    }

    #menu {
        border:1px solid #D0D0D0;
        box-shadow:0 0 8px #D0D0D0;
        font-size:16px;
        font-weight:700;
        color:#4b7bc0;
        margin:10px;
        padding:10px;
    }

    a {
        color:#444;
        text-decoration:none;
    }

    a:hover {
        color:#4b7bc0;
        text-decoration:none;
    }

    .contt {
        border:1px solid #D0D0D0;
        box-shadow:0 0 8px #D0D0D0;
        margin-top:65px;
    }
    </style>
</head>
<body>
<?php
    $start = microtime(true);
    require_once 'config.php';
?>

<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">ReactOS Translation Tool</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="index.php">Missing files</a></li>
                <li><a href="diff.php">Missing strings (Beta)</a></li>
                <li><a href="encoding.php">Check encoding</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container contt theme-showcase" role="main">
