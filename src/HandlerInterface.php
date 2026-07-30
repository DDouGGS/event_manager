<?php

namespace event_manager;

interface HandlerInterface
{
    // Reagir ao disparo do evento.
    public function handler(array $paramn = array());
}
