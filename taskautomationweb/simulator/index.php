<?php
    session_start();

    if (isset($_SESSION['user'])) {
        //logged in HTML and code here
    } else {
       //Not logged in HTML and code here
?>
        <script type="text/javascript">
            alert("Sorry, you have to be logged to access this page.");
            window.location.href = "../user.php";
        </script>
<?php
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Task Automation Web</title>

        <!-- CSS -->
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/ionicons.min.css">
        <link rel="stylesheet" href="../css/owl.carousel.min.css">
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="../css/custom.css">

        <!-- font -->
        <link href='http://fonts.googleapis.com/css?family=Nova+Square' rel='stylesheet'>
        <link rel="stylesheet" href="../font/font.css">
    </head>
    <body>
        <div id="wrapper">
            <section class="section-1">
                <div class="container">
                    <header class="site-header">
                        <div class="row">
                            <div class="col-sm-4 col-xs-8">
                                <h1 class="logo"><a href="../index.html">TaskAutomation</a></h1>
                            </div>
                            <div class="col-sm-8 col-xs-4">
                                <nav class="navbar pull-right" role="navigation">
                                    <!-- Brand and toggle get grouped for better mobile display -->
                                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                                        <span class="ion-navicon"></span>
                                    </button>

                                    <!-- Collect the nav links, forms, and other content for toggling -->
                                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                        <ul class="nav navbar-nav">
                                            <li><a href="../user.php">User</a></li>
                                            <li><a href="../channels.php">Channels</a></li>
                                            <li><a href="../rules.php">Rules</a></li>
                                            <li><a href="../contact.html">FAQ</a></li>
                                        </ul>
                                    </div><!-- /.navbar-collapse -->
                                </nav>
                            </div>
                        </div>  <!-- row -->
                    </header>   <!-- site header -->

                </div>
            </section>
            
            <section class="section-2">

                <div class="container">

                    <!-- Header -->
                    <div id="canvas-row" class="row">
                        <div id="canvas-div" class="col-md-9">
                            <script src="js/game.js"></script>
                        </div>
                        <div class="col-md-3">
                            <h2>Instructions:</h2>
                            <p>- Press arrow keys for moving the user. Depending on the distance to each sensor, the corresponding action will be triggered.
                            </br>- Place the beacons wherever you want by dragging & dropping them.
                            </br>- Each beacon has the following id:
                            </br>&nbsp;&nbsp;&nbsp;Blue Beacon: A1B2C3
                            </br>&nbsp;&nbsp;&nbsp;Green Beacon: D4E5F6
                            </br>&nbsp;&nbsp;&nbsp;Purple Beacon: G7H8I9 
                            </br>- Those ids must be the Presence Sensor id parameter of the rules.</p>

                        </div>
                    </div>

                </div>
            </section>  <!-- section-2 -->

            <footer class="site-footer">
                <div class="copyright">
                    &copy; 2016 gsi.dit.upm.es
                </div>
            </footer>
        </div>  <!-- wrapper -->

        <!-- js -->
        <script src="../js/jquery-2.1.3.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        
    </body>
</html>
