# EventManager

Sistema de gerenciamento de eventos e observadores em PHP utilizando o padrão **Observer** e suporte a **Reações**.

A classe `EventManager` permite:

* Registrar eventos e observadores (observers)
* Notificar observadores e reações enviando parâmetros
* Registrar reações customizadas (`ReactionsInterface`)
* Anexar (`attach`) e desanexar (`deattach`) observadores
* Verificar a existência e recuperar eventos registrados
* Listar observadores ativos (`keys`) e limpar eventos (`clear`)

---

## 📌 Estrutura e Namespace

```php
namespace event_manager;

use event_manager\Observers;
use event_manager\ReactionsInterface;
use event_manager\ObserversInterface;

abstract class EventManager
```

A classe utiliza uma propriedade estática para armazenar os eventos e observadores registrados:

```php
public static $events = array();
```

---

## 🚀 Instalação e Requisitos

### Requisitos
* PHP 7.x ou superior

### Autoload via Composer
Certifique-se de carregar o autoloader no seu projeto:

```php
require_once __DIR__ . '/vendor/autoload.php';

use event_manager\EventManager;
```

---

## 🛠️ Métodos Disponíveis

### `add($name, $observer = null)`
Registra um novo evento ou acrescenta um observador a um evento já existente (atalho para `observer()`).

* **Parâmetros:**
  * `string $name`: Nome do evento.
  * `object|null $observer`: Instância da classe observadora (opcional).
* **Retorno:** `bool`

```php
EventManager::add('user.created');
// ou com um observer
EventManager::add('user.created', new UserObserver());
```

---

### `observer($name, $observer = null)`
Registra um novo evento ou adiciona um observador a um evento existente. Para que o observer seja anexado com sucesso, ele deve possuir um método com o mesmo nome do evento.

* **Parâmetros:**
  * `string $name`: Nome do evento.
  * `object|null $observer`: Instância da classe observadora (opcional).
* **Retorno:** `bool`

```php
EventManager::observer('user_created', new UserObserver());
```

---

### `reaction($name, ReactionsInterface $reaction)`
Registra uma reação direta a um evento através de uma classe que implementa `ReactionsInterface`.

* **Parâmetros:**
  * `string $name`: Nome do evento.
  * `ReactionsInterface $reaction`: Instância de uma classe de reação.
* **Retorno:** `bool`

```php
EventManager::reaction('order.shipped', new SendEmailReaction());
```

---

### `retrieve($name)`
Recupera o objeto de evento/observador registrado pelo nome.

* **Parâmetros:**
  * `string $name`: Nome do evento.
* **Retorno:** `ObserversInterface|ReactionsInterface|null`

```php
$event = EventManager::retrieve('user.created');
```

---

### `exists($name)`
Verifica se um evento já foi registrado.

* **Parâmetros:**
  * `string $name`: Nome do evento.
* **Retorno:** `bool`

```php
if (EventManager::exists('user.created')) {
    // Evento registrado
}
```

---

### `attach($name, $observer)`
Anexa um novo observador a um conjunto de observadores de um evento já registrado.

* **Parâmetros:**
  * `string $name`: Nome do evento.
  * `object $observer`: Instância do observador.
* **Retorno:** `bool`

```php
EventManager::attach('user.created', new SendWelcomeEmailObserver());
```

---

### `deattach($name, $index)`
Remove um observador específico de um evento pelo seu índice ou chave (geralmente o nome da classe do observador).

* **Parâmetros:**
  * `string $name`: Nome do evento.
  * `string $index`: Identificador/nome da classe do observador a ser removido.
* **Retorno:** `bool`

```php
EventManager::deattach('user.created', 'UserObserver');
```

---

### `notify($name, array $paramn = array())`
Notifica todos os observadores ou executa a reação associada ao evento, passando um array de parâmetros.

* **Parâmetros:**
  * `string $name`: Nome do evento.
  * `array $paramn`: Array de parâmetros a serem enviados (padrão: `array()`).
* **Retorno:** `bool`

```php
EventManager::notify('user.created', ['id' => 1, 'email' => 'user@example.com']);
```

---

### `clear($name)`
Limpa/remove os observadores registrados para um determinado evento.

* **Parâmetros:**
  * `string $name`: Nome do evento.
* **Retorno:** `bool`

```php
EventManager::clear('user.created');
```

---

### `keys($name)`
Retorna uma lista com as chaves (nomes das classes) dos observadores registrados em determinado evento.

* **Parâmetros:**
  * `string $name`: Nome do evento.
* **Retorno:** `array`

```php
$observers = EventManager::keys('user.created');
// Retorna array com os nomes das classes dos observers
```

---

## 💡 Exemplos de Uso

### 1. Utilizando Observadores (Observers)

A classe do observador deve conter um método com o mesmo nome do evento a ser tratado:

```php
use event_manager\EventManager;

class UserObserver
{
    public function user_created(array $data)
    {
        echo "Usuário criado com sucesso: " . $data['name'];
    }
}

// 1. Registra o evento com o observador
EventManager::add('user_created', new UserObserver());

// 2. Dispara a notificação para o evento
EventManager::notify('user_created', ['name' => 'Maria']);
```

### 2. Adicionando Múltiplos Observadores

```php
class LogObserver
{
    public function user_created(array $data)
    {
        // Registra log...
    }
}

// Registra o evento
EventManager::add('user_created');

// Anexa múltiplos observadores
EventManager::attach('user_created', new UserObserver());
EventManager::attach('user_created', new LogObserver());

// Dispara o evento para todos os observadores
EventManager::notify('user_created', ['name' => 'João']);
```

### 3. Utilizando Reações (Reactions)

Reações implementam `ReactionsInterface` e lidam diretamente com o evento:

```php
use event_manager\EventManager;
use event_manager\ReactionsInterface;

class AuditReaction implements ReactionsInterface
{
    public function notify(array $paramn = array())
    {
        // Executa a reação ao evento
    }
}

// Registra a reação
EventManager::reaction('audit.log', new AuditReaction());

// Dispara a reação
EventManager::notify('audit.log', ['action' => 'login']);
```

---

## 📄 Licença

Este projeto está sob a Licença **Apache 2.0**. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.