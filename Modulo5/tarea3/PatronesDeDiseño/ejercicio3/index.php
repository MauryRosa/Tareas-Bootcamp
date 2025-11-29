<?php
require_once 'personajes/Guerrero.php';
require_once 'personajes/Mago.php';

require_once 'decorators/Espada.php';
require_once 'decorators/Varita.php';

$guerrero = new Guerrero();
$guerreroConEspada = new Espada($guerrero);

echo $guerreroConEspada->getDescripcion() . 
     " tiene un poder de " . 
     $guerreroConEspada->getPoder() . "\n";

$mago = new Mago();
$magoConVarita = new Varita($mago);

echo $magoConVarita->getDescripcion() . 
     " tiene un poder de " . 
     $magoConVarita->getPoder() . "\n";
