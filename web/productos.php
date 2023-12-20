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
            <form  action="registro.php" method="POST" >
                <fieldset class="withLogo">
                    <img src="img/logoloa.png" class="logoloa" />

                    <legend><h4>Datos alumno</h4></legend>


                    <div class="container">
                        <br /><br />
                        <label>Search Employee Details</label>
                        <div id="search_area">
                            <input type="text" name="employee_search" id="employee_search" class="form-control input-lg" autocomplete="off" placeholder="Type Employee Name" />
                        </div>
                        <br />
                        <br />
                        <div id="employee_data"></div>
                    </div>


                  <?php  for ($i = 0; $i < count($products); ++$i) {

                    print($products[$i]['item']." ".$products[$i]['type']." ".$products[$i]['brand']." ".$products[$i]['model'].
                        "       $".$products[$i]['price']."<br>");

                    }

                    ?>

                </fieldset>

                </p>


                <button type="submit" class="btn btn-primary">Suscribirse</button>
            </form>
        </div>
    </div>
</div>
</div>


</body>
</html>


<script>

</script>

