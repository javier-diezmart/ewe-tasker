<?php

session_start();

if($_GET['error']==1){
?>
    <script type="text/javascript">
        alert("Sorry, that username already exists.");
    </script>
<?php    
}else if($_GET['error']==2){
?>
    <script type="text/javascript">
        alert("Incorrect username or password.");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-social/4.12.0/bootstrap-social.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

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
        <?php if (!isset($_SESSION['user'])) { ?>
        <section class="section-2">
            <div class="container">    
                <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
                    <div class="panel panel-info" >
                        <div class="panel-heading">
                            <div class="panel-title">Sign In</div>
                        </div>     

                        <div style="padding-top:30px" class="panel-body" >

                            <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                            
                            <form id="loginform" class="form-horizontal" role="form" method="post" action="./controller/newUserController.php">

                                <div style="margin-bottom: 25px" class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                    <input id="login-username" type="text" class="form-control" name="username" value="" placeholder="username or email">                                        
                                </div>
                                
                                <div style="margin-bottom: 25px" class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <input id="login-password" type="password" class="form-control" name="password" placeholder="password">
                                </div>


                                
                                <div class="input-group">
                                  <div class="checkbox">
                                    <label>
                                      <input id="login-remember" type="checkbox" name="remember" value="1"> Remember me
                                  </label>
                              </div>
                          </div>


                          <div style="margin-top:10px" class="form-group">
                            <!-- Button -->

                            <div class="col-sm-12 controls">
                              <input id="btn-login" type="submit" name="action" value="Login" class="btn btn-success">
                              <input id="btn-fblogin" type="submit" name="action" value="Sign Up" class="btn btn-primary">
                          </div>
                      </div>
                  </form>     
              </div>                     
          </div>  
      </div>

    </div> 
</section>
<?php }else{ ?>
<section class="section-2"><div class="container"><div class="row"><div class="col-md-4"><a href="./twitterconnect.php" class="btn btn-block btn-social btn-twitter">
    <span class="fa fa-twitter"></span> Connect with Twitter
</a></div><div class="col-md-4"><a class="btn btn-block btn-social btn-google">
<span class="fa fa-google"></span> Connect with Google
</a></div><div class="col-md-4"><a class="btn btn-block btn-success">
<span class="fa fa-facebook"></span> Connected with Facebook
</a></div></div></div>
<div class="container">
    <h1>My rules</h1>
    <!-- Header -->
    <div class="row">
        <div class="col-md-2 col-md-offset-2">
            <p class="fragment-title">If</p>
        </div>
        <div class="col-md-1">
        </div>
        <div class="col-md-2">
            <p class="fragment-title">Then</p>
        </div>
    </div>

    <?php

                        // Get the list of rules in HTML format.
                        $viewController = new ViewController();
                        $rulesHTML = $viewController->getView(GET_RULES_VIEW_USER);

                        foreach ($rulesHTML as $ruleHTML) {
                            echo $ruleHTML;
                        }

                    ?>
</div>  	
</section>  <!-- section-2 -->
<?php } ?>
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
