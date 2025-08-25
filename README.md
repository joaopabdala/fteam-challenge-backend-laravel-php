# Fake Store API Integration

## ⚙️ Setup

### Copy `.env.example` to `.env`
```bash
cp .env.example .env
````

### Run Composer install to generate the vendor folder

```bash
composer install
```

## The application runs in **Docker** using Laravel Sail.

### Start the containers

```bash
./vendor/bin/sail up -d
```

### Run the migrations

```bash
./vendor/bin/sail artisan migrate
```

### Start the queue worker (required for synchronization)

```bash
./vendor/bin/sail artisan queue:work
```

---

## 🔄 Synchronization

To start synchronization with the Fake Store API, access the endpoint:

```
/store/sync
```

---

## 📡 Available Endpoints

* `POST /store/sync` → triggers synchronization with the external API.
* `GET /products/categories` → lists categories.
* `GET /products` → lists products (with pagination, filters, and sorting).
* `GET /products/{product}` → fetch a product by its internal ID.
* `GET /statistics` → aggregated statistics.

Example query with filters for `/products`:

```
/products?title=Men&category_id=3&max_price=50.00&min_price=10.00&order_by_price=desc&per_page=1
```

A **Postman collection** with all routes and filtering examples is available at:

```
./fteam.postman_collection.json
```

---

## 📝 Logs

Structured logs are stored in:

```
storage/logs/laravel.json.log
```

For real-time visualization in the terminal, it is recommended to use:

```bash
tail -f storage/logs/laravel.json.log | jq
```

---

## ⚙️ `.env` Configuration

You can customize the integration by changing the following variables in the `.env` file (an Adapter + Factory design pattern was implemented to allow future API providers to be swapped easily):

* **API Provider and URL**:

  ```bash
  STORE_PROVIDER_NAME='fake-store-api'
  API_STORE_URL='https://fakestoreapi.com'
  ```

> **Note**: Default values are already set as Laravel config *fallbacks*.

**Architecture Note**: The application uses **Adapter** and **Factory** patterns for API consumption. This approach was chosen to make the data source easily replaceable in the future, ensuring scalability and maintainability.

* **Cache and Log Configuration**:
  Make sure the log stack and cache driver are set to JSON and Redis respectively.

  ```bash
  LOG_STACK=json
  CACHE_STORE=redis
  ```

---

## 🗂️ Data Modeling

Two main tables were created:

* **categories**

    * Fields: `id`, `name`.
    * Index on `name` to optimize searches and listings.

* **products**

    * Fields: `id`, `title`, `description`, `price`, `external_id`, `category_id`.
    * **1\:N** relationship with `categories`.
    * Indexes on `title` (text search) and `price` (filters and ordering).
    * `external_id` is `UNIQUE` to avoid duplicates from the Fake Store API.
    * The `category_id` field uses `foreignId()->constrained()`, which makes **Laravel automatically create both the index and the foreign key constraint**, eliminating the need to declare them manually in the migration.

---

## 📌 Notes

* Synchronization uses **queues** so as not to block the main request.
* Structured logs (JSON) allow request/response correlation and response time tracking.
* Indexes were chosen to support the main queries required by the challenge (category filtering, price filtering, text search).

---
