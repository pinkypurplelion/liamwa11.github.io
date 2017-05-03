<html>

    <!--Add <title> tag to the <head> section to denote page title-->

    <head>
        <meta charset = "utf-8">
        <meta http-equiv = "X-UA-Compatible" content = "IE=edge">
        <meta name = "viewport" content = "width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

        <title>About | langus.me</title>

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

        <!-- TODO: Add e-mail just underneath the intro line that is underneath the page header, for this page only. Maybe add contact page & link in footer.-->
        <!--Header-->
        <div class="container-fluid">
            <!-- Large & Extra Large Devices -->
            <div class="row justify-content-center hidden-md-down">
                <div class="jumbotron-fluid text-center">
                    <br> <br> <br>
                    <h1 class="display-1">About Me</h1>
                    <p class="lead">Just a little bit about me.</p>
                    <hr class="my-3">
                </div>
            </div>
            <!--Small & Medium Devices-->
            <div class="row justify-content-center hidden-xs-down hidden-lg-up">
                <div class="jumbotron-fluid text-center">
                    <br> <br> <br>
                    <h1 class="display-1">About Me</h1>
                    <p class="lead">Just a little bit about me.</p>
                    <hr class="my-3">
                </div>
            </div>
            <!--Extra Small Devices-->
            <div class="row justify-content-center hidden-sm-up">
                <div class="jumbotron-fluid text-center">
                    <br> <br> <br>
                    <h1 class="display-2">About Me</h1>
                    <p class="lead">Just a little bit about me.</p>
                    <hr class="my-3">
                </div>
            </div>
        </div>

        <!--Main Content-->
        <div class="container-fluid">
            <br>
            <div class="row text-center justify-content-center">
                <div class="col-md-6">
                    <h3>Who Am I</h3>
                    <hr>
                </div>
            </div>
            <div class="row text-center justify-content-center hidden-md-down">
                <div class="col-md-4 align-items-center">
                    <p class="text-muted align-items-center">
                        <a class="lead">
                            <br>
                            I am a student in Australia, who loves all things Media & Technology. I am currently taking Media & Digital Technologies within school, and thoroughly enjoy these classes. I enjoy designing and creating games in my spare time, and have numerous projects currently, including <a href="langus.itch.io/spacial-invasion" target="_blank">Spacial Invasion</a>, which is an interesting take on the classic game Space Invaders. You can find the rest of my planned and current games <a href="games.php" target="_blank">here</a>. I am also experimenting with android app & game development, and am planning a small and simple android game currently.
                        </a>
                    </p>
                </div>
                <div class="col-md-3">
                    <img src="myAvatar.png" class="img-fluid" alt="My Avatar" style="max-height: 300px">
                </div>
            </div>
            <div class="row text-center justify-content-center hidden-lg-up">
                <div class="col-md-8 align-items-center">
                    <p class="text-muted align-items-center">
                        <a class="lead">
                            <br>
                            I am a student in Australia, who loves all things Media & Technology. I am currently taking Media & Digital Technologies within school, and thoroughly enjoy these classes. I enjoy designing and creating games in my spare time, and have numerous projects currently, including <a href="langus.itch.io/spacial-invasion" target="_blank">Spacial Invasion</a>, which is an interesting take on the classic game Space Invaders. You can find the rest of my planned and current games <a href="games.php" target="_blank">here</a>. I am also experimenting with android app & game development, and am planning a small and simple android game currently.
                        </a>
                    </p>
                </div>

            </div>
            <div class="row text-center justify-content-center hidden-lg-up">
                <div class="col-md-4">
                    <img src="myAvatar.png" class="img-fluid" alt="My Avatar" style="max-height: 300px">
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <br> <br>
            <div class="row text-center justify-content-center">
                <div class="col-md-6">
                    <hr>
                    <h3>Where You Can Find Me</h3>
                    <hr>
                </div>
            </div>
            <div class="row text-center justify-content-center">
                <div class="col-md-4">
                    <p class="lead">
                        I happen to have my fingers in lots of honey pots throughout the internet, and here are just a couple of places where you can find me.
                    </p>
                    <hr>
                </div>
            </div>
            <div class="row text-center justify-content-center">
                <div class="col-md-4">
                    <p class="lead">
                        liam@langus.me
                    </p>
                </div>
                <hr>
            </div>
            <div class="row text-center justify-content-center">
                <div class="col-md-4">
                    <a href="https://twitter.com/langusOfficial" class="twitter-follow-button" data-size="large" data-show-count="false">Follow @langusOfficial</a><script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
                    <a class="twitter-timeline" href="https://twitter.com/langusOfficial">Tweets by langusOfficial</a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
                </div>
            </div>
        </div>
    </body>

    <footer>
        <!--Footer-->
        <?php include "footer.php";?>
    </footer>

</html>