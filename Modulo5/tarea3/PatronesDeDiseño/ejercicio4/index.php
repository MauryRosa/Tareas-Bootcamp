<?php
require_once __DIR__ . "/src/Mensaje.php";
require_once __DIR__ . "/src/strategies/ConsoleOutput.php";
require_once __DIR__ . "/src/strategies/JsonOutput.php";
require_once __DIR__ . "/src/strategies/TxtFileOutput.php";

$message = "Holiwis!";

// Console
$context = new MessageContext(new ConsoleOutput());
$context->outputMessage($message);

// JSON
$context->setStrategy(new JsonOutput());
$context->outputMessage($message);

// TXT file (ruta absoluta)
$archivo = __DIR__ . "/output.txt";
$context->setStrategy(new TxtFileOutput($archivo));
$context->outputMessage($message);
