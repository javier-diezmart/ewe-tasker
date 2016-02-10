<?php
    session_start();

    if (isset($_SESSION['user'])) {
        //logged in HTML and code here
    } else {
       //Not logged in HTML and code here
?>
        <script type="text/javascript">
            alert("Sorry, you have to be logged to access this page.");
            window.location.href = "./user.php";
        </script>
<?php
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>EWETasker</title>

        <!-- CSS -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/ionicons.min.css">
        <link rel="stylesheet" href="css/owl.carousel.min.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/custom.css">

        <!-- font -->
        <link href='http://fonts.googleapis.com/css?family=Nova+Square' rel='stylesheet'>
        <link rel="stylesheet" href="font/font.css">
    </head>
    <body>

    <?php
        // Neccesary files.
        require_once('./controller/config.php');
        require_once('./controller/viewController.php');
    ?>

        <div id="wrapper">
            <section class="section-1">
                <div class="container">
                    <header class="site-header">
                        <div class="row">
                            <div class="col-sm-4 col-xs-8">
                                <h1 class="logo"><a href="index.html">ewetasker</a></h1>
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
                                            <li><a href="user.php">User</a></li>
                                            <li><a href="channels.php">Channels</a></li>
                                            <li><a href="rules.php">Rules</a></li>
                                            <li><a href="contact.html">FAQ</a></li>
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

                	<!-- Button Add Channel -->
                	<div class="row btn-new">
                        <div class="col-md-2 col-md-offset-5">
                            <button onclick="location.href='./newchannel.php'" type="button" class="btn btn-success">Create New Channel</button>
                        </div>

                    </div>

                    <?php

                        // Get the list of rules in HTML format.
                        $viewController = new ViewController();
                        $channelsHTML = $viewController->getView(GET_CHANNELS_VIEW);

                        foreach ($channelsHTML as $channelHTML) {
                            echo $channelHTML;
                        }

                    ?>

                </div>
            </section>  <!-- section-2 -->

            <footer class="site-footer">
                <div class="copyright">
                    &copy; 2016 gsi.dit.upm.es
                </div>
            </footer>
        </div>  <!-- wrapper -->

        <!-- js -->
        <script src="js/jquery-2.1.3.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        
    </body>
</html>
