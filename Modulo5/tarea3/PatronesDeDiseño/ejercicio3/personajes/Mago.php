<?php
require_once __DIR__ . '/PersonajeGame.php';

class Mago implements PersonajeVideojuego {
    public function getDescripcion() {
        return "Mago";
    }
    public function getPoder() {
        return 30;
    }
}
