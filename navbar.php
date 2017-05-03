<html>
<head>
    <meta charset = "utf-8">
    <meta http-equiv = "X-UA-Compatible" content = "IE=edge">
    <meta name = "viewport" content = "width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Bootstrap -->
    <link href = "css/bootstrap.min.css" rel = "stylesheet">

    <meta name="google-site-verification" content="eCbLFYyToczFY0EkYoWVaUPu0tvewErKv4WSRhpPjnc" />

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<nav class="navbar navbar-toggleable-lg navbar-light bg-faded fixed-top">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="index.php">langus.me</a>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
            <li <?php if (stripos($_SERVER['REQUEST_URI'],'index.php') !== false) {echo 'class="active"';} ?> ><a class="nav-item nav-link" href="index.php">Home</a></li>
            <li <?php if (stripos($_SERVER['REQUEST_URI'],'about.php') !== false) {echo 'class="active"';} ?> ><a class="nav-item nav-link" href="about.php">About Me</a></li>
            <li <?php if (stripos($_SERVER['REQUEST_URI'],'support.php') !== false) {echo 'class="active"';} ?> ><a class="nav-item nav-link" href="support.php">Support Me</a></li>
            <li <?php if (stripos($_SERVER['REQUEST_URI'],'gamedev.php') !== false) {echo 'class="active"';} ?> ><a class="nav-item nav-link" href="gamedev.php">Games</a></li>
            <li <?php if (stripos($_SERVER['REQUEST_URI'],'appdev.php') !== false) {echo 'class="active"';} ?> ><a class="nav-item nav-link" href="appdev.php">Apps</a></li>

            <span class="navbar-text navbar-toggler-right text-sm-center">
                L.J Angus
            </span>
        </div>
    </div>
</nav>
</html>