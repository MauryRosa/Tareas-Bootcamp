<?php
require_once 'Personaje.php';

class Esqueleto implements Personaje {
    public function atacar() {
        return "El Esqueleto ataca con un arco.";
    }
    public function velocidad() {
        return "El Esqueleto tiene una velocidad media.";
    }
}
