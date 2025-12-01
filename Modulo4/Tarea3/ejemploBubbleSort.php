<?php
function quickSort(array $array): array {
    // 1. Caso Base: Si el arreglo tiene 0 o 1 elemento, ya está ordenado.
    if (count($array) < 2) {
        return $array;
    }
    // 2. Elegir un Pivote: Usamos el primer elemento como pivote.
    $pivot = $array[0];

    // Inicializar sub-arreglos
    $left = [];
    $right = [];

    // 3. Partición: Recorrer el resto de elementos y dividirlos
    for ($i = 1; $i < count($array); $i++) {
        if ($array[$i] < $pivot) {
            $left[] = $array[$i]; // Elementos menores al pivote
        } else {
            $right[] = $array[$i]; // Elementos mayores o iguales al pivote
        }
    }
    // 4. Recursión: Aplicar QuickSort a los sub-arreglos y combinar
    return array_merge(quickSort($left), [$pivot], quickSort($right));
}
$data = [10, 80, 30, 90, 40, 50, 70];
$sorted_data = quickSort($data);

echo "Arreglo Original: " . implode(", ", $data) . "\n";
echo "Arreglo Ordenado: " . implode(", ", $sorted_data) . "\n"; 
?>