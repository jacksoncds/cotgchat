<?php
    session_start();
    $loggedUser = "Login";
    $loggedIn = false;
    $path = "login";
    if(isset($_SESSION['username']) && $_SESSION['username'] != ""){
        $loggedIn = true;
        $loggedUser = $_SESSION['username'];
        $path = "dashboard";
    }

?>
<html>

    <head>
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        
        <script src="js/lib/angular.min.js"></script>
        <script src="js/lib/angular-route.min.js"></script>
        <script src="js/lib/material.min.js"></script>
        <script src="js/lib/ripples.min.js"></script>
        <script src="js/lib/jquery.nicescroll.min.js"></script>
        
        <!-- Material Design fonts -->
        <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700">
        <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/icon?family=Material+Icons">

        <!-- Bootstrap -->
        <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <!-- Bootstrap Material Design -->
        <link rel="stylesheet" type="text/css" href="css/bootstrap-material-design.min.css">
        <link rel="stylesheet" type="text/css" href="css/ripples.min.css">

        <link href="css/main.css" rel="stylesheet"/>
        <link href="css/app.css" rel="stylesheet"/>
        <link href="css/font-awesome.min.css" rel="stylesheet"/>

        <title>COTG XChat</title>
    </head>
    <body ng-app="xchat">
        <div id="main-wrap" class="container" ng-controller="mainController">
            <nav class="navbar navbar-default dark-background-color">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">COTG XChat</a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-center">
                        <li ng-class="{ active: route.current.activeMenu === 'home' }"><a href="#!/" class="active">Home</a></li>
                        <li ng-class="{ active: route.current.activeMenu === 'pricing' }"><a href="#!/pricing">Pricing</a></li>
                        <li ng-class="{ active: route.current.activeMenu === 'register' }"><a href="#!/register">Sign up</a></li>
                        <li ng-class="{ active: route.current.activeMenu === 'faq' }"><a href="#!/faq">FAQ</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li ng-class="{ active: route.current.activeMenu === 'login' }"><a href="#!/<?php echo $path ?>"><?php echo $loggedUser ?></a></li>
                        <li><a href="/#!/" ng-click="logout()" style="display:<?php if($loggedIn){echo 'block';} else {echo 'none';} ?>">Logout</a></li>
                        
                    </ul>
                    </div>
                </div>
                </nav>
        </div>
        <!-- Content -->
        <div ng-view>

        </div>
        <footer class="navbar navbar-default navbar-fixed-bottom">
            <ul class="nav navbar-nav navbar-center">
                <li><a>&copy; 2017 XChat</a></li>
                <li><a href="#!/bugreport">Report a bug</a></li>
                <li><a href="#!/contact">Contact</a></li>
            </ul>
        </footer>

        <!-- Script -->
        <script src="js/routes.js"></script>
        <script src="js/main.js"></script>
        <script src="js/controllers.js"></script>
        <script src="js/directives.js"></script>
        <script src="js/chat.js"></script>
    </body>

</html>
