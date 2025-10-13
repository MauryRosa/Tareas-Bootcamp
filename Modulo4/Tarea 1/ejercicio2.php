<?php
function esPrimo($num) {
    if ($num <= 1) {
        return false; 
    }
    for ($i = 2; $i <= sqrt($num); $i++) {
        if ($num % $i == 0) {
            return false; 
        }
    }
    return true; 
}

//ejemplo :)
$numero = 15;
if (esPrimo($numero)) {
    echo "$numero es un número primo.";
} else {
    echo "$numero no es un número primo.";
}

?>