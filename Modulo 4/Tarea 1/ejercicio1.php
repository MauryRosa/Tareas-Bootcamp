<?php

function generarFibonacci($n) {
    $fibonacci = [];
    if ($n <= 0) {
        return $fibonacci; 
    } elseif ($n == 1) {
        $fibonacci[] = 0; 
        return $fibonacci;
    }
    $fibonacci[] = 0;
    $fibonacci[] = 1;

    for ($i = 2; $i < $n; $i++) {
        $nextTerm = $fibonacci[$i - 1] + $fibonacci[$i - 2];
        $fibonacci[] = $nextTerm;
    }

    return $fibonacci;
}

//ejemplo :)
$n = 10;
$fibonacciSeries = generarFibonacci($n);
echo "Los primeros $n términos de la serie Fibonacci son: " . implode(", ", $fibonacciSeries);

?>