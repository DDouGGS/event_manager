<?php

namespace event_manager;

interface ReactionsInterface
{
    // Dispara o evento para os observadores
    public function notify(array $params = array());
}
