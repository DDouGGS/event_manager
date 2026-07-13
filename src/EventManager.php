<?php

namespace event_manager;

use event_manager\Observers;

abstract class EventManager
{
    public static $events = array();

    // Registra um novo evento ou acrescenta observador a um já existente
    public static function add($name, $observer = null)
    {
        // register listener
        if (isset($name) && !empty($name)) {
            if(self::exists($name)){
                // with observer
                if(isset($observer) && is_object($observer)){
                    if(method_exists($observer, $name)){
                        self::$events[$name]->attach( get_class($observer), $observer);
                        return true;
                    }
                    return false;
                }
                return self::newObserver($name);
            }
            // with observer
            if(isset($observer) && is_object($observer)){
                self::$events[$name] = new Observers($name, get_class($observer), $observer);
                return true;
            }
            return self::newObserver($name);
        }
        return false;
    }

    // Adiciona novo observer para o evento
    private static function newObserver($name)
    {
        try{
            self::$events[$name] = new Observers($name);
        }catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
        return true;
    }

    // Registra uma reação
    public static function record($name, ReactionsInterface $reaction)
    {
        // register listener
        if (isset($name) && !empty($name)) {
            if(self::exists($name)){
                // with reaction
                if(isset($reaction) && is_object($reaction)){
                    self::$events[$name] = $reaction;
                    return true;
                }
                return false;
            }
            // with reaction
            if(isset($reaction) && is_object($reaction)){
                self::$events[$name] = $reaction;
                return true;
            }
        }
        return false;
    }

    // Recupera evento registrado
    public static function retrieve($name)
    {
        return (isset(self::$events[$name]))? self::$events[$name]: null;
    }

    // Existencia do evento
    public static function exists($name)
    {
        return (isset(self::$events[$name]))? true: false;
    }

    // Adiciona observador para o evento
    public static function attach($name, $observer)
    {
        if (!is_object($observer)) { return false; }
        $o = self::retrieve($name);
        return (isset($o))? $o->attach(get_class($observer), $observer): false;
    }

    // Exclui um observador de determinado evento
    public static function deattach($name, $index)
    {
        if (!isset($name) || empty($name) || !isset($index) || empty($index)) { return false; }
        $o = self::retrieve($name);
        return (isset($o))? $o->deattach($index): false;
    }

    // Notifica os observadores do evento
    public static function notify($name, $paramn)
    {
        $o = self::retrieve($name);
        return (isset($o))? $o->notify($paramn): false;
    }

    // Limpa todos os observadores para um determinado evento
    public static function clear($name)
    {
        $o = self::retrieve($name);
        return (isset($o))? $events[$name] = null: false;
    }

    // Lista os observers de determinado evento
    public static function keys($name)
    {
        $o = self::retrieve($name);
        return (isset($o))? $o->listing(): array();
    }
}
