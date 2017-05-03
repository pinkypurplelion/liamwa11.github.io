<html>

    <!--Add <title> tag to the <head> section to denote page title-->

    <head>
        <meta charset = "utf-8">
        <meta http-equiv = "X-UA-Compatible" content = "IE=edge">
        <meta name = "viewport" content = "width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

        <title>Error 404 | langus.me</title>

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

    <body>
        <!--NavBar-->
        <div>
            <?php include 'navbar.php';?>
        </div>

        <div class = "container-fluid">
            <div class = "row justify-content-center hidden-md-down">
                <div class = "jumbotron-fluid text-center">
                    <br> <br> <br>
                    <h1 class = "display-2">Error 404</h1>
                    <p class = "lead">Sorry, but we cannot find the page you are looking for.</p>
                    <hr class = "my-3">
                    <p class = "lead">
                        <a class = "btn btn-outline-success btn-lg" href = "index.php" role = "button">Take Me Home</a>
                    <hr>
                    </p>
                </div>
            </div>
            <!-- Large & Extra Large Devices -->
            <div class = "row justify-content-center hidden-xs-down hidden-lg-up">
                <div class = "jumbotron-fluid text-center">
                    <br> <br> <br>
                    <h1 class = "display-3">Error 404</h1>
                    <p class = "lead">Sorry, but we cannot find the page you are looking for.</p>
                    <hr class = "my-3">
                    <p class = "lead">
                        <a class = "btn btn-outline-success btn-lg" href = "index.php" role = "button">Take Me Home</a>
                    <hr>
                    </p>
                </div>
            </div>
            <!-- Small & Medium Devices -->
            <div class = "row justify-content-center hidden-sm-up">
                <div class = "jumbotron-fluid text-center">
                    <br> <br> <br>
                    <h1 class = "display-4">Error 404</h1>
                    <p class = "lead">Sorry, but we cannot find the page you are looking for.</p>
                    <hr class = "my-3">
                    <p class = "lead">
                        <a class = "btn btn-outline-success btn-lg" href = "index.php" role = "button">Take Me Home</a>
                    <hr>
                    </p>
                </div>
            </div>
        </div>
    </body>

    <footer>
        <!--Footer-->
        <?php include "footer.php";?>
    </footer>

</html>