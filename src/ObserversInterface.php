<?php

namespace event_manager;

interface ObserversInterface
{
    // Adiciona observador para o evento
    public function attach($observer);

    // Exclui observador para o evento
    public function deattach($index);

    // Dispara o evento para os observadores
    public function notify(array $params = array());

    // Limpa os observadores
    public function clear();

    // Lista de observers para o evento
    public static function keys();
}
