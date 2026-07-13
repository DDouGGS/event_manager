<?php

namespace event_manager;

interface ReactionsInterface
{
    // Reagir ao disparo do evento.
    public function notify($paramn);
}
