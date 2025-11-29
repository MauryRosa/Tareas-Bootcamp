<?php
require_once 'adapters/Adapter.php';

// Cliente
function clientCode(Windows10 $os, $file) {
    echo $os->openFile($file) . "\n";
}

// Uso del adaptador
$windows7 = new Windows7();
$adapter = new Windows7ToWindows10Adapter($windows7);

clientCode($adapter, "documento.docx");
