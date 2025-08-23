

# Fake Store API Integration

## ⚙️ Setup

### Copiar .env.example para .env
```bash
cp .env.example .env
````

### Composer install para gerar o vendor
```bash
composer install
````

A aplicação roda em **Docker** utilizando o Laravel Sail.

### Subir os containers
```bash
./vendor/bin/sail up -d
````

### Rodar as migrations

```bash
./vendor/bin/sail artisan migrate
```

### Iniciar o worker de filas (necessário para a sincronização)

```bash
./vendor/bin/sail artisan queue:work
```

---

## 🔄 Sincronização

Para iniciar a sincronização com a Fake Store API, acesse o endpoint:

```
/store/sync
```

---

## 📡 Endpoints Disponíveis

* `GET /products/categories` → lista categorias.
* `GET /products` → lista produtos (com paginação, filtros e ordenação).
* `GET /products/{product}` → consulta produto por ID interno.
* `GET /statistics` → estatísticas agregadas.

Uma coleção do **Postman** com todas as rotas e exemplos de filtragem está disponível em:

```
./fteam.postman_collection.json
```

---

## 📝 Logs

Os logs estruturados são gravados em:

```
storage/logs/laravel.json.log
```

Para visualização em tempo real no terminal, recomenda-se:

```bash
tail -f storage/logs/laravel.json.log | jq
```

---
## 📝 Logs

Os logs estruturados são gravados em:

```
storage/logs/laravel.json.log
```

Para visualização em tempo real no terminal, recomenda-se:

```bash
tail -f storage/logs/laravel.json.log | jq
```

-----

## ⚙️ Configuração do `.env`

É possível personalizar a integração alterando as seguintes variáveis no arquivo `.env`:

* **Provedor e URL da API**:

  ```bash
  STORE_PROVIDER_NAME='fake-store-api'
  API_STORE_URL='https://fakestoreapi.com'
  ```

  Os valores padrões já estão definidos como *fallback* na configuração do Laravel.

* **Configurações de Cache e Log**:
  Certifique-se de que a *stack* de logs e o *driver* de cache estão respectivamente como json e redis.

  ```bash
  LOG_STACK=json
  CACHE_STORE=redis
  ```

-----



## 🗂️ Modelagem de Dados

Foram criadas duas tabelas principais:

* **categories**

    * Campos: `id`, `name`, timestamps.
    * Índice em `name` para otimizar buscas/listagens.

* **products**

    * Campos: `id`, `title`, `description`, `price`, `external_id`, `category_id`, timestamps.
    * Relação **1\:N** com `categories`.
    * Índices em `title` (busca textual) e `price` (filtros e ordenação).
    * `external_id` é `UNIQUE` para evitar duplicidades vindas da Fake Store API.
    * O campo `category_id` é definido como `foreignId()->constrained()`, o que faz o **Laravel criar automaticamente o índice e a constraint de chave estrangeira**, dispensando configuração manual.

---

## 📌 Observações

* A sincronização usa **queue** para não travar o request principal.
* Logs estruturados (JSON) permitem correlação de requisições com tempo de resposta.
* Índices foram escolhidos para suportar as principais consultas exigidas pelo desafio (filtros de categoria, preço, e busca textual).

---
