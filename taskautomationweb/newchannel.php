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

                <div class="row">
                    <div class="col-md-12">

                        <h2>New Channel</h2>
                        <form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="./controller/newChannelsController.php">

                            <div class="row">
                                <div class="form-group">
                                    <label for="channel_title" class="col-sm-1 control-label">Title</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="channel_title" placeholder="door" value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="channel_description" class="col-sm-1 control-label">Description</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="channel_description" placeholder="This channel represents a connected door lock able to detect when the door is opened, closed or shut, but it also can open, lock or unlock the door" value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="nicename" class="col-sm-1 control-label">Nicename</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="channel_nicename" placeholder="Connected Door" value="">
                                    </div>
                                </div>    
                                
                                <div class="form-group">
                                    <label for="channel_img" class="col-sm-1 control-label">Image</label>
                                    <div class="col-sm-10">
                                        <input type="file" name="channel_img" id="fileToUpload">
                                    </div>
                                </div> 

                                <div class="form-group">
                                    <div class="col-sm-offset-9 col-sm-3">
                                        <button id="add_event" class="btn btn-success btn-add" type="button">
                                                Add event
                                            </button>
                                            <button id="add_action" class="btn btn-success btn-add" type="button">
                                                Add action
                                            </button>
                                    </div>
                                </div> 

                                <div class="form-group">
                                    <div class="col-sm-10 col-sm-offset-2">
                                        <!-- Will be used to display an alert to the user-->
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div id="event" class="col-md-4 col-md-offset-1">
                                    <h3>Event</h3>
                                        <div class="form-group">
                                            <label for="title" class="col-sm-2 control-label">Title</label>
                                            <div class="col-sm-9 col-sm-offset-1">
                                                <input type="text" class="form-control" name="event_title[]" placeholder="New tweet" value="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="message" class="col-sm-2 control-label">Rule</label>
                                            <div class="col-sm-9 col-sm-offset-1">
                                                <textarea placeholder="?a :knows ?b.
?a!:age math:lessThan #PARAM_1#" class="form-control" rows="4" name="event_rule[]"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="prefix" class="col-sm-2 control-label">Prefix</label>
                                            <div class="col-sm-9 col-sm-offset-1">
                                                <textarea placeholder="@prefix : <ppl#>. @prefix math: <http://www.w3.org/2000/10/swap/math#>." class="form-control" rows="4" name="event_prefix[]"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-10 col-sm-offset-2">
                                                <!-- Will be used to display an alert to the user-->
                                            </div>
                                        </div>
                                </div>
                            </div><!-- row-->

                            <div class="row">
                                <div id="action" class="col-md-4 col-md-offset-1">
                                    <h3>Action</h3>
                                        <div class="form-group">
                                            <label for="title" class="col-sm-2 control-label">Title</label>
                                            <div class="col-sm-9 col-sm-offset-1">
                                                <input type="text" class="form-control" name="action_title[]" placeholder="Turn on" value="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="message" class="col-sm-2 control-label">Rule</label>
                                            <div class="col-sm-9 col-sm-offset-1">
                                                <textarea placeholder="?b :knows ?a" class="form-control" rows="4" name="action_rule[]"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="prefix" class="col-sm-2 control-label">Prefix</label>
                                            <div class="col-sm-9 col-sm-offset-1">
                                                <textarea placeholder="@prefix : <ppl#>." class="form-control" rows="4" name="action_prefix[]"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-10 col-sm-offset-2">
                                                <!-- Will be used to display an alert to the user-->
                                            </div>
                                        </div>
                                </div>
                            </div><!-- row-->
                            <div class="form-group">
                                <div class="col-sm-2 col-sm-offset-10">
                                    <input id="submit" name="submit" type="submit" value="Send" class="btn btn-primary">
                                </div>
                            </div>
                        </form>
                    </div>
                </div><!-- row-->
            </div> <!-- /container -->

                <!-- <div class="row">
                    <div class="col-md-12">
                        <div class="control-group" id="fields">
                            <label class="control-label" for="field1">Nice Multiple Form Fields</label>
                        
                            <div class="controls">
                                <form role="form" autocomplete="off">
                                    <div class="entry input-group col-xs-3">


                                        <input class="form-control" name="fields[]" type="text" placeholder="Type something">
                                        <span class="input-group-btn">
                                            <button class="btn btn-success btn-add" type="button">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                        </span>
                                    </div>

                                </form>
                            </div>
                            <br>
                            <small>
                                Press
                                <span class="glyphicon glyphicon-plus gs"></span>
                                to add another form field :)
                            </small>
                        </div>
                    </div>
                </div> -->
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
<script type="text/javascript">

document.getElementById('add_action').onclick = newAction;
document.getElementById('add_event').onclick = newEvent;


var i = 0;
var j = 0;

function newAction() {
    var original = document.getElementById('action');
    var clone = original.cloneNode(true); // "deep" clone
    clone.id = "action" + ++i; // there can only be one element with an ID
    original.parentNode.appendChild(clone);
}

function newEvent() {
    var original = document.getElementById('event');
    var clone = original.cloneNode(true); // "deep" clone
    clone.id = "event" + ++j; // there can only be one element with an ID
    original.parentNode.appendChild(clone);
}

    // $(function(){
    //     $(document).on('click', '.btn-add', function(e){
    //         e.preventDefault();

    //         var controlForm = $('.controls form:first'),
    //             currentEntry = $(this).parents('.entry:first'),
    //             newEntry = $(currentEntry.clone()).appendTo(controlForm);

    //         newEntry.find('input').val('');
    //         controlForm.find('.entry:not(:last) .btn-add')
    //             .removeClass('btn-add').addClass('btn-remove')
    //             .removeClass('btn-success').addClass('btn-danger')
    //             .html('<span class="glyphicon glyphicon-minus"></span>');
    //         }).on('click', '.btn-remove', function(e){
    //             $(this).parents('.entry:first').remove();

    //             e.preventDefault();
    //             return false;
    //         });
    // });


</script>
</body>
</html>
