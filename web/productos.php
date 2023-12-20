<?php
include '../models/ProductModel.php';


$model= new ProductModel();


$filter=array();


$filter[]='deleted = "' ."false".'"';

if(isset($_GET['item'])){
    $filter[]='item = "' .$_GET['item'].'"';
}

$products = $model->findAllAll($filter);




?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Formulario de Registro LOA</title>
    <link href="css/estilos.css" rel="stylesheet" type="text/css">
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css">

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>



<body>
<div class="background" ></div>
<div class="front container">
    <div class="row">
        <div class="col-sm-8 offset-sm-2 col-xs-10 offset-xs-1">
                <fieldset class="withLogo">
                    <img src="img/logoloa.png" class="logoloa" />

                    <legend><h4>Productos</h4></legend>

                    <form>
                        <input type="text" size="30" onkeyup="showResult(this.value)">
                        <div id="livesearch"></div>
                    </form>

                </fieldset>

                </p>


        </div>
    </div>
</div>
</div>


</body>
</html>


<script>
    function showResult(str) {
        if (str.length==0) {
            document.getElementById("livesearch").innerHTML="";
            document.getElementById("livesearch").style.border="0px";
            return;
        }
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (this.readyState==4 && this.status==200) {
                document.getElementById("livesearch").innerHTML=this.responseText;
               //    document.getElementById("livesearch").style.border="1px solid #A5ACB2";
            }
        }
        xmlhttp.open("GET","livesearch.php?q="+str,true);
        xmlhttp.send();
    }
</script>

