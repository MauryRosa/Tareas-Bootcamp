<?php
require_once __DIR__ . '/../personajes/PersonajeGame.php';

abstract class ArmaDecorator implements PersonajeVideojuego {
    protected $personaje;

    public function __construct(PersonajeVideojuego $personaje) {
        $this->personaje = $personaje;
    }

    abstract public function getDescripcion();
    abstract public function getPoder();
}
