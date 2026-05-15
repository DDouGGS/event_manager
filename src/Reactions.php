<?php

namespace event_manager;

use event_manager\ReactionsInterface;
use event_manager\HandlerInterface;

class Reactions implements ReactionsInterface
{
    protected $event         = null;
    protected $protocol      = null;

    // Evento construtor da classe
    public function __construct(HandlerInterface $event)
    {
        $this->event = $event;
        $this->protocol = (string) microtime(true);
    }

    // Criar instancia da classe
    public static function make($event)
    {
        return new Observers($event);
    }

    // Dispara o evento para os observadores
    public function notify(object &$paramn)
    {
        if(method_exists($this->event, 'handler')){
            $this->event->handler($paramn);
            return true;
        }
        return false;
    }
}
