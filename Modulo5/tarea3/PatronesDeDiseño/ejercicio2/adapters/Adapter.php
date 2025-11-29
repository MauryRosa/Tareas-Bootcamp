<?php
require_once __DIR__ . '/../interfaces/Windows10.php';
require_once __DIR__ . '/../legacy/Windows7.php';

class Windows7ToWindows10Adapter implements Windows10 {
    private $windows7;

    public function __construct(Windows7 $windows7) {
        $this->windows7 = $windows7;
    }

    public function openFile($file) {
        return $this->windows7->openOldFile($file);
    }
}
