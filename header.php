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
    <script src="js/jquery-3.5.1.min.js"></script>
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

    .contt {
        border:1px solid #D0D0D0;
        box-shadow:0 0 8px #D0D0D0;
        margin-top:65px;
    }

    summary {
        display:list-item;
        cursor:pointer;
    }
    </style>
</head>
<body>
<?php
    $start = microtime(true);
    require_once 'config.php';
?>

<div class="container">
    <nav class="navbar navbar-expand-md navbar-fixed-top navbar-dark bg-primary">
        <a class="navbar-brand" href="index.php">ReactOS Translation Tool</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Missing files</a></li>
                <li class="nav-item"><a class="nav-link" href="diff.php">Missing strings (Beta)</a></li>
                <li class="nav-item"><a class="nav-link" href="encoding.php">Check encoding</a></li>
            </ul>
        </div>
    </nav>
</div>

<div class="container contt theme-showcase" role="main">
