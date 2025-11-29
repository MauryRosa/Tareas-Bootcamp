<?php
require_once __DIR__ . "/../interfaces/OutputStrategy.php";

class TxtFileOutput implements OutputStrategy {
    private $filePath;

    public function __construct($filePath) {
        $this->filePath = $filePath;
    }

    public function output($message) {
        file_put_contents($this->filePath, "TXT Output: " . $message . PHP_EOL, FILE_APPEND);
        echo "Message written to " . $this->filePath . PHP_EOL;
    }
}
