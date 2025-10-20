<!doctype html>
<html>
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link href="css/estilos.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

<body>
<div class="background" ></div>
  <h2><em> </em></h2>

    <div class="col-sm-8 offset-sm-2 col-xs-10 offset-xs-1">
        <div class="card text-center loa-card">

            <div class="card-header loa-header">
                <?php if(isset($form['info']) && $form['info'] == "actualizada"){ ?>
                    Ya existe un registro con el dni <?php echo $form['dni'] ?>
                <?php }else{ ?>
                    Suscripción completa
                <?php } ?>

            </div>

            <div class="card-body">
                <?php if($form['info'] == "actualizada"){ ?>
                    <p class="card-text loa-text"> Se actualizó la información para el/la alumno/a </p>
                    <p class="card-text loa-text"> <strong><?php echo ucfirst( $form['name']) ?> <?php echo  ucfirst($form['apellido']) ?></strong></p>
                <?php }else{ ?>
                    <h5 class="card-title loa-text" > <?php echo ucfirst( $form['name']) ?> <?php echo  ucfirst($form['apellido']) ?></h5>
                   <!-- <p class="card-text loa-text"> <?php echo $form['dni'] ?></p> -->
                <?php } ?>

            </div>
            <div class="card-footer text-muted loa-header">


                <p class="card-text loa-text">  Seguinos en <i class="fab fa-instagram"></i> loa.surflife
                   </p>

            </div>
        </div>
        <?php echo "" ?>
</body>
</html>

<style>
    .fa-instagram {
        font-size: 20px;
        margin-right: 3px;
        margin-left: 3px;
    }
    .fa-whatsapp {
       /* color: #25D366; */
        margin-left: 3px;
    }



</style>


