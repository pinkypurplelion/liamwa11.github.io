<html>
    <head>
    <meta charset = "utf-8">
    <meta http-equiv = "X-UA-Compatible" content = "IE=edge">
    <meta name = "viewport" content = "width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Welcome | langus.me</title>

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
    <a class="navbar-brand" href="index.html">langus.me</a>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
            <li <?php if (stripos($_SERVER['REQUEST_URI'],'index.php') !== false) {echo 'class="active"';} ?> ><a class="nav-item nav-link" href="index.html">Home</a></li>
            <a class="nav-item nav-link active" href="index.html">Home<span class="sr-only">(current)</span></a>
            <a class="nav-item nav-link" href="about.html">About Me</a>
            <a class="nav-item nav-link" href="http://github.com/LiamWA11" target="_blank">GitHub Repos</a>
            <a class="nav-item nav-link" href="blog.html">Blog</a>
            <a class="nav-item nav-link" href="support.html">Support Me</a>
            <a class="nav-item nav-link" href="tutor.html">Tutoring</a>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Programming Projects
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="apps.html">Applications</a>
                    <a class="dropdown-item" href="games.html">Games</a>
                    <a class="dropdown-item" href="wip.html">Works in Progress</a>
                    <a class="dropdown-item" href="submit.html">??Submit An Idea??</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Games
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                    <a class="dropdown-item" href="gil.html">Games That I Like</a>
                    <a class="dropdown-item" href="watch.html">Watch Me Play Games</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Products & Services
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink3">
                    <a class="dropdown-item" href="web.html">Commission A Website</a>
                    <a class="dropdown-item" href="code.html">Commission A Programming Project</a>
                    <a class="dropdown-item" href="store.html">Store</a>
                </div>
            </li>
            <a class="nav-item nav-link" href="other.html">Other</a>
            <span class="navbar-text navbar-toggler-right text-sm-center">
                L.J Angus
            </span>
        </div>
    </div>
    </nav>
</html>