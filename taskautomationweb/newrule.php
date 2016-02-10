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

    <?php
        // Neccesary files.
        require_once('./controller/config.php');
        require_once('./controller/viewController.php');
    ?>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EWETasker</title>

    <!-- CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/ionicons.min.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/custom.css">

    <!-- Pop Up CSS -->
    <link rel="stylesheet" href="./css/vex.css" />
    <link rel="stylesheet" href="./css/vex-theme-os.css" />

    <!-- font -->
    <link href='http://fonts.googleapis.com/css?family=Nova+Square' rel='stylesheet'>
    <link rel="stylesheet" href="font/font.css">

    <!-- js -->
    <script src="js/jquery-2.1.3.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <!-- pop ups -->
    <script src="./js/vex.combined.min.js"></script>
    <script>vex.defaultOptions.className = 'vex-theme-os';</script>
    
    <script type="text/javascript">

        var globalChannelOne;
        var globalChannelTwo;
        var globalEvent;
        var globalAction;
        var globalEventParameters;
        var globalActionParameters;

        function DragDrop(drag, drop) {

            var drag = document.getElementById(drag);
            var drop = document.getElementById(drop);

            drag.ondragstart = function(e)
            {
                //Guardamos el id del elemento para transferirlo al elemento drop
                //Contenido es una clave que nos permitirá acceder al valor asignado
                e.dataTransfer.setData("contenido", e.target.id);
            }

            drop.ondragover = function(e){
                //Cancelar el evento que impide que podamos soltar el elemento drag
                e.preventDefault();
            }

            drop.ondrop = function(e){
            //Obtenemos los datos a través de la clave contenido, en este caso el id
                var id = e.dataTransfer.getData("contenido");
                showEventPopUp(id, e.target.id);
                console.log(e.target.id);
                e.target.appendChild(document.getElementById(id));
            }
        }

        function showRuleInfoPopUp(){
            // Recibo el número de parámetros.
            
            //console.log('Numero de parametros del evento: ' + globalEventParameters);
            //console.log('Numero de parametros de la accion: ' + globalActionParameters);

            var inputString = "<input name=\"ruletitle\" type=\"text\" placeholder=\"Title\" required />\n<input name=\"place\" type=\"text\" placeholder=\"Place\" required />\n<input name=\"ruleDescription\" type=\"text\" placeholder=\"Description\" required />\n";
            for(var i = 1; i<=globalEventParameters; i++){
                inputString += "<input name=\"eventparam\" type=\"text\" placeholder=\"Event parameter number " + i + "\" required />\n"
            }

            for(var i = 1; i<=globalActionParameters; i++){
                inputString += "<input name=\"actionparam\" type=\"text\" placeholder=\"Action parameter number " + i + "\" required />\n"                
            }
            vex.dialog.open({
                                    message: 'Please, fill the rule info:',
                                    input: inputString,
                                    buttons: [
                                        $.extend({}, vex.dialog.buttons.YES, {
                                          text: 'OK'
                                        }), $.extend({}, vex.dialog.buttons.NO, {
                                          text: 'Back'
                                        })
                                      ],
                                      callback: function(dataRule) {
                                        if (dataRule === false) {
                                          return console.log('Cancelled');
                                        }
                                        console.log('El evento de la accion es: ' + dataRule.actionparam);
                                        $.ajax({ url: './controller/newRulesController.php',
                                                 data: {rule_title: dataRule.ruletitle,
                                                        rule_description: dataRule.ruleDescription,
                                                        rule_place: dataRule.place,
                                                        event_params : dataRule.eventparam,
                                                        action_params : dataRule.actionparam},
                                                 type: 'post',
                                                 success: function(output) {
                                                              //alert(output);
                                                              window.location.href='./rules.php';
                                                          }
                                        });
                                        return console.log('Event: ', dataRule.eventTitle);
                                      }
                                    });
                                                  
                                
        }

        function showEventPopUp(channelTitle, dropId){


            switch(dropId){
                case "drop-event":
                    firstCommand = 'getEvents';
                    selectorName = 'eventsTitle';
                    message = 'Select the event:';
                    secondCommand = 'saveEvent';
                    globalChannelOne = channelTitle;
                    break;
                case "drop-action":
                    firstCommand = 'getActions';
                    selectorName = 'actionsTitle';
                    message = 'Select the action:';
                    secondCommand = 'saveAction';
                    globalChannelTwo = channelTitle;
                    break;
            }

            var eventsTitle = [];
            var eventsNumOfParams = [];
            $.ajax({ url: './ajaxPHPHelper.php',
                     data: {command: firstCommand,
                            channelTitle: channelTitle},
                     type: 'post',
                     success: function(output) {

                                var jsonData = JSON.parse(output);

                                for (var i = 0; i < jsonData.length; i++) {
                                    var counter = jsonData[i];
                                    eventsTitle.push(counter.eventTitle);
                                    eventsNumOfParams.push(counter.numberOfParams);
                                    //console.log(counter.numberOfParams);
                                    //console.log(eventsTitle[i]);
                                }

                                var selector = "<select name=\'eventTitle\' class=\'form-control\' id=\'sel1\'>";

                                for(var i = 0; i < eventsTitle.length; i++){
                                    selector = selector + "<option>" + eventsTitle[i] + "</option>";
                                }

                                selector = selector + "</select>";

                                vex.dialog.open({
                                    message: message,
                                    //input: "<input name=\"username\" type=\"text\" placeholder=\"Username\" required />\n<input name=\"password\" type=\"password\" placeholder=\"Password\" required />",
                                    input: selector,
                                    buttons: [
                                        $.extend({}, vex.dialog.buttons.YES, {
                                          text: 'OK'
                                        }), $.extend({}, vex.dialog.buttons.NO, {
                                          text: 'Back'
                                        })
                                      ],
                                      callback: function(data) {
                                        if (data === false) {
                                          return console.log('Cancelled');
                                        }

                                        switch(dropId){
                                            case "drop-event":
                                                globalEvent = data.eventTitle;
                                                globalEventParameters = eventsNumOfParams[eventsTitle.indexOf(data.eventTitle)];
                                                break;
                                            case "drop-action":
                                                globalAction = data.eventTitle;
                                                globalActionParameters = eventsNumOfParams[eventsTitle.indexOf(data.eventTitle)];
                                                break;
                                        }

                                        $.ajax({ url: './ajaxPHPHelper.php',
                                                 data: {command: secondCommand,
                                                        channelTitle: channelTitle,
                                                        eventTitle: data.eventTitle},
                                                 type: 'post',
                                                 success: function(output) {
                                                              //alert(output);
                                                          }
                                        });
                                        return console.log('Event: ', data.eventTitle);
                                      }
                                    });
                                }
                            });



        }

    </script>

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
                

                <!-- Button Add Rule -->
                    
                <div class="row btn-new">
                    <div class="col-md-1 col-md-offset-5">
                        <button onclick="showRuleInfoPopUp()" type="button" class="btn btn-success">Save Rule</button>
                    </div>
                </div>


                <!-- Drop -->
                <div class="row">

                    <!-- Event channel -->
                    <div class="col-md-4">
                        <h2 class="drop-titles">If</h2>
                        <div id="drop-event"></div>
                    </div>

                    <!-- Arrow -->
                    <div class="col-md-4">
                        <div class="fragment-arrow">
                            <img class="img img-circle img-responsive img-arrow" src="./img/arrow.png"/>
                        </div>
                    </div>

                    <!-- Action channel -->
                    <div class="col-md-4">
                        <h2 class="drop-titles">Then</h2>
                        <div id="drop-action"></div>
                    </div>

                </div>

                <!-- Channel List -->
                <div class="row">

                    <?php

                        // Get the list of rules in HTML format.
                        $viewController = new ViewController();
                        $channelsHTML = $viewController->getView(GET_CHANNELS_IMAGES);

                        $i = 0;
                        foreach ($channelsHTML as $channelHTML) {
                            if($i%5==0) echo DUMMY_CHANNEL_COL;
			//if($i%5==0 && $i>0) echo DUMMY_CHANNEL_COL;
                            echo $channelHTML;
                            $i++;
                        }

                        echo '<script type="text/javascript">var channels = document.getElementsByClassName("img-channel");
        var channel;
        for (channel = 0; channel<channels.length; channel++) {
            DragDrop(channels[channel].id, "drop-action");
            DragDrop(channels[channel].id, "drop-event");
        }   </script>';
        
                    ?>


                </div>
                <!--<div id="drag" draggable="true"></div>-->
            </div> <!-- /container -->


</section>  <!-- section-2 -->

<footer class="site-footer">
    <div class="copyright">
        &copy; 2016 gsi.dit.upm.es
    </div>
</footer>
</div>  <!-- wrapper -->



</body>
</html>
