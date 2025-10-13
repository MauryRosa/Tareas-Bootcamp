<?php

function mergeSort($arr) {
    if (count($arr) <= 1) {
        return $arr;
    }

    $mid = floor(count($arr) / 2);
    $left = array_slice($arr, 0, $mid);
    $right = array_slice($arr, $mid);

    return merge(mergeSort($left), mergeSort($right));
}
function merge($left, $right) {
    $result = [];
    $i = $j = 0;

    while ($i < count($left) && $j < count($right)) {
        if (strcmp($left[$i], $right[$j]) <= 0) {
            $result[] = $left[$i];
            $i++;
        } else {
            $result[] = $right[$j];
            $j++;
        }
    }

    while ($i < count($left)) {
        $result[] = $left[$i];
        $i++;
    }

    while ($j < count($right)) {
        $result[] = $right[$j];
        $j++;
    }

    return $result;
}
// Ejemplo de uso
$words = ["soda", "pupusas", "horchata", "agua", "tamal", "elote", "fresco", "pan", "quesadilla"];
echo "Lista original:\n";
print_r($words);
$sortedWords = mergeSort($words);
echo "Lista ordenada alfabéticamente:\n";   
print_r($sortedWords);
?>