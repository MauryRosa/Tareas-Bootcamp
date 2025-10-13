
<?php
function insertionSort($arr) {
    $n = count($arr);
    for ($i = 1; $i < $n; $i++) {
        $key = $arr[$i];
        $j = $i - 1;

        while ($j >= 0 && strcmp($arr[$j], $key) > 0) {
            $arr[$j + 1] = $arr[$j];
            $j--;
        }
        $arr[$j + 1] = $key;
    }
    return $arr;
}
// Ejemplo de uso
$names = ["Carlos", "Ana", "Luis", "Beatriz", "David", "Elena", "Fernando", "Gabriela"];
echo "Lista original:\n";
print_r($names);
$sortedNames = insertionSort($names);
echo "Lista ordenada alfabéticamente:\n";
print_r($sortedNames);
?>