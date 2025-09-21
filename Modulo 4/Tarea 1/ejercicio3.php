<?php

function esPalindromo($cadena) {
    $cadena = strtolower(str_replace(' ', '', $cadena));
    return $cadena === strrev($cadena);
}

//ejemplo :)
$texto = "El Coach me pondra un dies en esta tarea =) ";

if (esPalindromo($texto)) {
    echo "\"$texto\" es un palíndromo.";
} else {
    echo "\"$texto\" no es un palíndromo.";
}

//Ejemplo 2
$texto2 = "Somos o no somos";
if (esPalindromo($texto2)) {
    echo "\n\"$texto2\" es un palíndromo.";
} else {
    echo "\n\"$texto2\" no es un palíndromo.";
}
?>