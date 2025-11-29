<?php
require_once 'Personaje.php';

class Zombi implements Personaje {
    public function atacar() {
        return "El Zombi ataca con sus garras.";
    }
    public function velocidad() {
        return "El Zombi tiene una velocidad lenta.";
    }
}
