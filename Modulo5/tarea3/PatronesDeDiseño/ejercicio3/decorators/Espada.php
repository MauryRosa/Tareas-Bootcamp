<?php
require_once __DIR__ . '/ArmaDecorator.php';

class Espada extends ArmaDecorator {
    public function getDescripcion() {
        return $this->personaje->getDescripcion() . " con Espada";
    }
    public function getPoder() {
        return $this->personaje->getPoder() + 20;
    }
}
