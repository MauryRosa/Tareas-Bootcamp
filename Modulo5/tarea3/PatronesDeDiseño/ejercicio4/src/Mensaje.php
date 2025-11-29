<?php
class MessageContext {
    private $strategy;

    public function __construct(OutputStrategy $strategy) {
        $this->strategy = $strategy;
    }

    public function setStrategy(OutputStrategy $strategy) {
        $this->strategy = $strategy;
    }

    public function outputMessage($message) {
        $this->strategy->output($message);
    }
}
