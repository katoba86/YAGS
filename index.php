<?php
error_reporting(E_ALL);
ini_set("display_errors","on");

include("Suggest.php");

$suggest = null;
if(isset($_GET["phrase"])){


    if(isset($_GET["removeWord"])){
        $removeWord=true;
    }else{
        $removeWord=false;
    }

    $suggest = new Suggest($_GET["phrase"],$_GET["startLetter"],$_GET["endLetter"],$removeWord);
    $suggest->run();



}


?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>YAGS - Yet another Google-Suggest Tool</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>


<div class="container" style="margin-top:100px;">

    <div class="row">
        <div class="col-md-3">

            <div class="panel panel-primary">
                <div class="panel-heading">Such-Einstellungen</div>
                <div class="panel-body">

                        <form method="GET" action="index.php">
                            <div class="form-group">
                                <label for="phrase">Suche-Phrase</label>
                                <input type="text" class="form-control" id="phrase" placeholder="Such-Phrase" value="<?=(isset($_GET["phrase"]))?$_GET["phrase"]:"";?>" name="phrase"/>
                            </div>
                            <div class="form-group">
                                <label for="phrase">Start-Buchstabe</label>
                                <input type="text" class="form-control" id="phrase" value="<?=(isset($_GET["startLetter"]))?$_GET["startLetter"]:"a";?>" placeholder="Start-Buchstabe" name="startLetter"/>
                            </div>
                            <div class="form-group">
                                <label for="phrase">End-Buchstabe</label>
                                <input type="text" class="form-control" id="phrase" value="<?=(isset($_GET["endLetter"]))?$_GET["endLetter"]:"g";?>" placeholder="End-Buchstabe" name="endLetter"/>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input <?=(isset($_GET["removeWord"]))?"checked":"";?> value="1" name="removeWord" type="checkbox"> Entferne Phrase
                                </label>
                            </div>


                        <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                </div>
            </div>

        </div>
        <div class="col-md-9">


            <div class="panel panel-primary">
                <div class="panel-heading">Ergebnisse</div>
                <div class="panel-body">
                    <?php
                    if($suggest==null) {
                        ?>
                        <div id="result">Bis jetzt keine Ergebnisse</div>
                    <?php
                    } else {
                        $output=$suggest->getRet();

                     ?>
                        <div class="col-md-6">
                            <ul class="list-group">
                            <?php
                            for($i=0;$i<(count($output)/2);$i++){
                                if(!isset($output[$i])){continue;}
                             echo '<li class="list-group-item">'.$output[$i].'</li>';
                            }
                            ?>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-group">
                                <?php
                                for($i=ceil(count($output)/2);$i<count($output);$i++){
                                    if(!isset($output[$i])){continue;}
                                    echo '<li class="list-group-item">'.$output[$i].'</li>';
                                }
                                ?>
                            </ul>
                        </div>



                    <?php
                    }
                    ?>
                </div>
            </div>



        </div>
    </div>







</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
</body>
</html>