<?php
function binarySearch(array $array, $target): int {
    $inicio = 0;
    $fin = count($array) - 1;

    while ($inicio <= $fin) {
        // Calcular el índice medio
        $medio = floor(($inicio + $fin) / 2);

        // Comparación y ajuste de los límites
        if ($array[$medio] == $target) {
            return $medio; // ¡Encontrado! Devuelve el índice.
        } elseif ($array[$medio] < $target) {
            $inicio = $medio + 1; // Descartar la mitad inferior (el objetivo está a la derecha)
        } else { // $array[$medio] > $target
            $fin = $medio - 1;   // Descartar la mitad superior (el objetivo está a la izquierda)
        }
    }
    return -1;
}
$data = [2, 5, 8, 12, 16, 23, 38, 56, 72, 91]; 
$target1 = 23;
$target2 = 100;

echo "Buscando $target1: Índice " . binarySearch($data, $target1) . "\n";
echo "Buscando $target2: Índice " . binarySearch($data, $target2) . "\n";
?>