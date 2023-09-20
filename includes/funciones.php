<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

function isLast($actual, $proximo) : bool{
    if($actual !== $proximo){
        return true;
    } return false;
}

//revisa que el usuario está autenticado: 
function isAuth():void{
    if(!isset($_SESSION['login'])) {
        header('Location: /');
    }
}

//proteger la URL para que solo entre el admin.
function isAdmin():void{
    if(!isset($_SESSION['admin'])){
        header('Location: /');
    }

}