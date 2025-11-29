<?php
require_once __DIR__ . "/../interfaces/OutputStrategy.php";

class JsonOutput implements OutputStrategy {
    public function output($message) {
        echo "JSON Output: " . json_encode(["message" => $message]) . PHP_EOL;
    }
}
