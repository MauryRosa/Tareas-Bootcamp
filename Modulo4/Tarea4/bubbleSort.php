<?php
function bubbleSortDescending($arr) {
    $n = count($arr);
    for ($i = 0; $i < $n - 1; $i++) {
        for ($j = 0; $j < $n - $i - 1; $j++) {
            if ($arr[$j] < $arr[$j + 1]) {
                $temp = $arr[$j];
                $arr[$j] = $arr[$j + 1];
                $arr[$j + 1] = $temp;
            }
        }
    }
    return $arr;
}

// Ejemplo de uso
$numbers = [34, -2, 45, 0, -2, 23, 45, -10, 67, 34];
echo "Lista original:\n";
print_r($numbers);
$sortedNumbers = bubbleSortDescending($numbers);
echo "Lista ordenada de forma descendente:\n";
print_r($sortedNumbers);

?>