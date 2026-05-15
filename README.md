# EventManager

Sistema simples de gerenciamento de eventos e observadores em PHP utilizando o padrão **Observer**.

A classe `EventManager` permite:

* Registrar eventos
* Adicionar observadores (observers)
* Notificar listeners
* Registrar reações customizadas
* Recuperar eventos registrados
* Remover observers
* Listar observers ativos

---

# Estrutura

```php
namespace event_manager;

use event_manager\Observers;

abstract class EventManager
```

A classe utiliza um array estático para armazenar todos os eventos registrados:

```php
public static $events = array();
```

---

# Métodos Disponíveis

## add()

Registra um novo evento ou adiciona um observer a um evento existente.

### Assinatura

```php
EventManager::add($name, $observer = null);
```

### Parâmetros

| Parâmetro   | Tipo   | Descrição         |
| ----------- | ------ | ----------------- |
| `$name`       | string | Nome do evento    |
| `$observer`   | object | Observer opcional |

### Exemplo

```php
EventManager::add('user.created');
```

Com observer:

```php
EventManager::add('user.created', new UserObserver());
```

---

## record()

Registra uma reação personalizada para um evento.

### Assinatura

```php
EventManager::record($name, ReactionsInterface $reaction);
```

### Exemplo

```php
EventManager::record('user.created', new UserReaction());
```

---

## retrieve()

Recupera um evento registrado.

### Assinatura

```php
EventManager::retrieve($name);
```

### Retorno

* Objeto do evento
* `null` caso não exista

### Exemplo

```php
$event = EventManager::retrieve('user.created');
```

---

## exists()

Verifica se um evento existe.

### Assinatura

```php
EventManager::exists($name);
```

### Retorno

```php
true | false
```

### Exemplo

```php
if (EventManager::exists('user.created')) {
    // evento existe
}
```

---

## attach()

Adiciona um observer a um evento existente.

### Assinatura

```php
EventManager::attach($name, $observer);
```

### Exemplo

```php
EventManager::attach('user.created', new UserObserver());
```

---

## deattach()

Remove um observer de um evento.

### Assinatura

```php
EventManager::deattach($name, $index);
```

### Exemplo

```php
EventManager::deattach('user.created', 'UserObserver');
```

---

## notify()

Notifica todos os observers registrados para o evento.

### Assinatura

```php
EventManager::notify($name, &$paramn);
```

### Parâmetros

| Parâmetro | Tipo   | Descrição                    |
| --------- | ------ | ---------------------------- |
| `$name`     | string | Nome do evento               |
| `$paramn`   | object | Objeto enviado aos observers |

### Exemplo

```php
$user = new stdClass();
$user->name = 'John';

EventManager::notify('user.created', $user);
```

---

## clear()

Remove todos os observers de um evento.

### Assinatura

```php
EventManager::clear($name);
```

### Exemplo

```php
EventManager::clear('user.created');
```

---

## keys()

Lista todos os observers registrados no evento.

### Assinatura

```php
EventManager::keys($name);
```

### Exemplo

```php
$list = EventManager::keys('user.created');
```

---

# Exemplo Completo

## Criando um Observer

```php
class UserObserver
{
    public function user_created($user)
    {
        echo "Usuário criado: {$user->name}";
    }
}
```

---

## Registrando Evento

```php
EventManager::add('user_created');
```

---

## Adicionando Observer

```php
EventManager::attach('user_created', new UserObserver());
```

---

## Disparando Evento

```php
$user = new stdClass();
$user->name = 'Maria';

EventManager::notify('user_created', $user);
```

---

# Fluxo Básico

```text
Evento → Observers → Notify → Reações
```

---

# Dependências

Esta classe depende de:

* `Observers`
* `ReactionsInterface`

---

# Possíveis Melhorias

Alguns pontos podem ser aprimorados na implementação atual:

* Correção do método `clear()`:

```php
return (isset($o))? $events[$name] = null: false;
```

Provavelmente deveria ser:

```php
return (isset($o)) ? self::$events[$name] = null : false;
```

---

* Correção no método `record()`:

Existe referência a variável inexistente:

```php
$observer
```

---

* Melhorar tipagem com PHP 8+

Exemplo:

```php
public static function exists(string $name): bool
```

---

# Licença

A Licença Apache 2.0 é uma licença de software de código aberto permissiva e popular. Ela permite o uso, modificação, distribuição e comercialização do software, inclusive em projetos fechados, desde que mantenha os créditos de autoria, inclua uma cópia da licença e relate as alterações feitas.

---