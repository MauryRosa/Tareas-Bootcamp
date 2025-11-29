<?php
require_once 'Factory/PersonajeFactory.php';

try {
    $nivel = 'dificil'; // Cambiar a 'facil' para crear un Esqueleto
    $personaje = PersonajeFactory::crearPersonaje($nivel);
    echo $personaje->atacar() . PHP_EOL;
    echo $personaje->velocidad() . PHP_EOL;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}


