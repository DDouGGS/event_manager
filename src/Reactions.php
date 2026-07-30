<?php

namespace event_manager;

use event_manager\ReactionsInterface;
use event_manager\HandlerInterface;
use event_manager\ventManager;

abstract class Reactions implements ReactionsInterface, HandlerInterface
{
    protected $name          = null;
    protected $protocol      = null;
    protected $afterReaction = null;

    // Evento construtor da classe
    public function __construct($name = null)
    {
        $this->name     = $name;
        $this->protocol = (string) microtime(true);
    }

    // Dispara o evento para os observadores
    public function notify(array $paramn = array())
    {
        return $this->handler($paramn);
    }

    // manipulador do evento
    abstract public function handler(array $paramn = array());

    // Reação após o evento
    public function after(string $name, ReactionsInterface $reaction)
    {
        try {
            $this->setAfterReaction($reaction);
            if ($this->existAfterReaction()) {
                return EventManager::reaction($name, $this->getAfterReaction());
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        return false;
    }

    // getAfterReaction
    public function getAfterReaction()
    {
        return $this->afterReaction;
    }

    // setAfterReaction
    public function setAfterReaction(ReactionsInterface $reaction)
    {
        if (isset($reaction) && is_object($reaction)) {
            $this->afterReaction = $reaction;
            return true;
        }
        return $this;
    }

    // existAfterReaction
    public function existAfterReaction()
    {
        return isset($this->afterReaction) && is_object($this->afterReaction);
    }
}
