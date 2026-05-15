<?php

namespace event_manager;

use event_manager\ObserversInterface;

class Observers implements ObserversInterface
{
    protected $name          = null;
    protected $protocol      = null;
    public static $observers = array();

    // Evento construtor da classe
    public function __construct($name, $index = null, $observer = null)
    {
        $this->name = $name;
        $this->protocol = (string) microtime(true);
        if(isset($index) && !empty($index) && is_object($observer)){
            $this->attach($index, $observer);
        }
    }

    // Criar instancia da classe
    public static function make($name, $index = null, $observer = null)
    {
        return new Observers($name, $index, $observer);
    }

    // Adiciona observador para o evento
    public function attach($index, $observer)
    {
        if(isset($index) && !empty($index) && is_object($observer)) {
            if(method_exists($observer, $this->name)){
                self::$observers[$index] = $observer;
                return true;
            }
            return false;
        }
        return false;
    }

    // Exclui observador para o evento
    public function deattach($index)
    {
        if (isset($index) && !empty($index)) {
            unset(self::$observers[$index]);
            return true;
        }
        return false;
    }

    // Dispara o evento para os observadores
    public function notify(object &$paramn)
    {
        foreach (self::$observers as $key => $item) {
            try {
                if(method_exists($item, $this->name)){
                    $item->{$this->name}($paramn);
                }
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        }
        return true;
    }

    // Limpa os observadores
    public function clear()
    {
        self::$observers = array();
        return empty(self::$observers) ? true : false;
    }

    // Lista de observers para o evento
    public static function keys()
    {
        return array_keys(self::$observers);
    }
}
