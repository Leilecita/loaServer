<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 20/12/2023
 * Time: 12:01
 */

include '../models/ProductModel.php';

$model= new ProductModel();


$filter=array();



//get the q parameter from URL
$q = $_GET["q"];

$filter[]='deleted = "' ."false".'"';
$filter[] = '(brand like "%'.$_GET['q'].'%" OR model like "%'.$_GET['q'].'%" OR item like "%'.$_GET['q'].'%" OR type like "%'.$_GET['q'].'%")';
$x = $model->findAllAll($filter);

//lookup all links from the xml file if length of q>0
if (strlen($q)>0) {
    $hint="";


    for ($i = 0; $i < count($x); ++$i){

        $y=$x[$i]['item'];
        $z=$x[$i]['type'];
        $l=$x[$i]['brand'];
        $k=$x[$i]['price'];

        if ($hint=="") {
            $hint="<p>" . $y ." ". $z ." ".$l."  $".$k. "</p>";
        } else {
            $hint=$hint . "<br/><p>".$y." ".$z." ".$l." $".$k."</p>";
        }
    }
}

// Set output to "no suggestion" if no hint was found
// or to the correct values
if ($hint=="") {
    $response="no suggestion";
} else {
    $response = $hint;
}

//output the response
echo $response;
