<?php
require_once __DIR__ . '/PersonajeGame.php';

class Guerrero implements PersonajeVideojuego {
    public function getDescripcion() {
        return "Guerrero";
    }
    public function getPoder() {
        return 50;
    }
}
