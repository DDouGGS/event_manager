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
        return self::observer($name, $observer);
    }

    // Adiciona novo observer para o evento
    private static function emptyObserver($name)
    {
        try {
            self::$events[$name] = new Observers($name);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        return true;
    }

    // Registra um novo evento ou acrescenta observador a um já existente
    public static function observer($name, $observer = null)
    {
        try {
            // register listener
            if (isset($name) && !empty($name)) {
                if (self::exists($name)) {
                    // with observer
                    if (isset($observer) && is_object($observer)) {
                        if (method_exists($observer, $name)) {
                            self::$events[$name]->attach($observer);
                            return true;
                        }
                        return false;
                    }
                    return self::emptyObserver($name);
                }
                // with observer
                if (isset($observer) && is_object($observer)) {
                    self::$events[$name] = new Observers($name, $observer);
                    return true;
                }
                return self::emptyObserver($name);
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        return false;
    }

    // Registra uma reação de evento
    public static function reaction($name, ReactionsInterface $reaction)
    {
        try {
            // register listener
            if (isset($name) && !empty($name)) {
                if (self::exists($name)) {
                    // with reaction
                    if (isset($reaction) && is_object($reaction)) {
                        self::$events[$name] = $reaction;
                        return true;
                    }
                    return false;
                }
                // with reaction
                if (isset($reaction) && is_object($reaction)) {
                    self::$events[$name] = $reaction;
                    return true;
                }
                return false;
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        return false;
    }

    // Recupera evento registrado
    public static function retrieve($name)
    {
        try {
            return (isset(self::$events[$name])) ? self::$events[$name] : null;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        return null;
    }

    // Existencia do evento
    public static function exists($name)
    {
        return (isset(self::$events[$name])) ? true : false;
    }

    // Exclui um observador do observers de evento
    public static function deattach($name, $index)
    {
        try {
            if (!isset($name) || empty($name) || !isset($index) || empty($index)) {
                return false;
            }
            $o = self::retrieve($name);
            if ($o instanceof ObserversInterface) {
                return (isset($o)) ? $o->deattach($index) : false;
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        return false;
    }

    // Notifica os observadores do evento
    public static function notify($name, array $paramn = array())
    {
        try {
            $o = self::retrieve($name);
            if ($o instanceof ObserversInterface || $o instanceof ReactionsInterface) {
                return (isset($o)) ? $o->notify($paramn) : false;
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        return false;
    }

    // Limpa todos os observadores para um determinado evento
    public static function clear($name)
    {
        try {
            $o = self::retrieve($name);
            if ($o instanceof ObserversInterface || $o instanceof ReactionsInterface) {
                $events[$name] = null;
                return true;
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        return false;
    }

    // Lista os observers de determinado evento
    public static function keys($name)
    {
        try {
            $o = self::retrieve($name);
            if ($o instanceof ObserversInterface) {
                return (isset($o)) ? $o->keys() : array();
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        return false;
    }
}
