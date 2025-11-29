<?php
require_once __DIR__ . "/../interfaces/OutputStrategy.php";

class ConsoleOutput implements OutputStrategy {
    public function output($message) {
        echo "Console Output: " . $message . PHP_EOL;
    }
}
