<?php
function compruebaEspacios($email): bool
{
    $emailSinEspacios=trim($email);

    $validar=false;

    if(strpos($emailSinEspacios," ") === false){

        $validar=true;
    }
    return $validar;
}

function compruebaDominio($email): bool
{
    $emailSinEspacios=trim($email);
    $posicion_arroba = strpos($emailSinEspacios, "@");
    $posicion_punto = strpos($emailSinEspacios, ".", $posicion_arroba);

    $validar=false;

    if ($posicion_arroba !== false && $posicion_punto !== false) {
        $validar = true;
    }

    return $validar;
}

function validarMailCompleto($email): bool
{
    $validar=false;

    if(compruebaEspacios($email) && compruebaDominio($email) ){
        $validar=true;
    }
    return $validar;
}
