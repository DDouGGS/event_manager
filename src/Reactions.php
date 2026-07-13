<?php

namespace event_manager;

use event_manager\ReactionsInterface;

abstract class Reactions implements ReactionsInterface
{
    protected $event    = null;
    protected $protocol = null;

    // Evento construtor da classe
    public function __construct()
    {
        $this->protocol = (string) microtime(true);
    }

    // Criar instancia da classe
    public static function make()
    {
        return new Reactions();
    }

    // Dispara o evento para os observadores
    public function notify($paramn)
    {
        return $this->handler($paramn);
    }

    abstract public function handler($paramn);
}
