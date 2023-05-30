<?php 
$coon = mysqli_connect('127.0.0.1:3310','root','','login2');

if(!$coon){
    echo 'error No se pudo Conectar';
}