<?php
require_once __DIR__ . '/ArmaDecorator.php';

class Varita extends ArmaDecorator {
    public function getDescripcion() {
        return $this->personaje->getDescripcion() . " con Varita";
    }
    public function getPoder() {
        return $this->personaje->getPoder() + 15;
    }
}
