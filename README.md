---

```markdown
# Laravel Contact Manager (DDD + REST + CLI + Search)

A Laravel 12 project implementing a robust contact management system using Domain-Driven Design (DDD) architecture. The app supports:

- RESTful API for CRUD operations
- Command-line interface (CLI) commands
- Centralized validation and error handling
- Contact search (via Laravel Scout)
- Pest tests for full feature coverage

---

## Project Structure

```

app/
├── Console/
│   └── Commands/
│       └── Contact/
│           ├── ContactCallCommand.php
│           ├── ContactListCommand.php
│           └── ContactUpsertCommand.php
├── Domain/
│   └── Contact/
│       ├── DTOs/
│       │   └── ContactListData.php
│       ├── Enums/
│       │   └── ContactStatus.php
│       ├── Exceptions/
│       │   └── ContactNotFoundException.php
│       ├── Http/
│       │   ├── Controllers/
│       │   │   └── V1/
│       │   │       └── ContactController.php
│       │   └── Resources/
│       │       └── ContactResource.php
│       ├── Models/
│       │   └── Contact.php
│       ├── Requests/
│       │   ├── ContactStoreRequest.php
│       │   └── ContactSearchRequest.php
│       ├── Services/
│       │   └── ContactService.php
│       └── Contracts/
│           └── ContactServiceInterface.php

routes/
├── api.php

tests/
├── Feature/
│   ├── ContactApiTest.php
│   └── Console/
│       └── ContactCommandTest.php

````

---

## Installation

```bash
git clone https://github.com/hezll/lara-test.git
cd lara-test

composer install
cp .env.example .env
php artisan key:generate

php artisan migrate
php artisan db:seed


````

---

## API Usage

### 🔗 Base URL: `/api/v1`

| Method | Endpoint              | Description               |
| ------ | --------------------- | ------------------------- |
| GET    | `/contacts`           | List/Search  (paginated) |
| GET    | `/contacts/{id}`      | Show contact details      |
| POST   | `/contacts`           | Create or update contact  |
| DELETE | `/contacts/{id}`      | Delete contact            |
| POST   | `/contacts/{id}/call` | Mark contact as called    |
| GET    | `/contacts?q=keyword` | Search contacts           |

### Example (Create Contact)

```bash
curl -X POST http://lara-test.test/api/v1/contacts \
  -H "Accept: application/json" \
  -d '{"name": "Alice", "email": "alice@example.com", "phone": "+61412345678"}'
```

---

## CLI Commands

All commands share logic with the API (services + validation):

```bash
php artisan contact:upsert --name="Alice CLI" --email=cli@example.com --phone="+61400000001"
php artisan contact:show --id=1
php artisan contact:list --name=alice --page=1 --per-page=10
php artisan contact:call --id=1
```

---

## Search Support

This project integrates [Laravel Scout](https://laravel.com/docs/scout) to support contact search via:

* `name`
* `email`
* `phone`

### 🛠 Default Driver: `Meilisearch`

To switch to **Meilisearch**:

1. Install packages:

```bash
composer require meilisearch/meilisearch-php http-interop/http-factory-guzzle
```

2. Run Meilisearch:

```bash
docker run -d \
  --name meilisearch \
  -p 7700:7700 \
  -e MEILI_MASTER_KEY=masterKey123123 \
  getmeili/meilisearch
```

3. Update `.env`:

```
SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=http://localhost:7700
MEILISEARCH_KEY=masterKey123123
```

4. Rebuild index:

```bash
php artisan scout:flush App\\Domain\\Contact\\Models\\Contact
php artisan scout:import App\\Domain\\Contact\\Models\\Contact
```

5. Search via API:

```bash
curl -G http://lara-test.test/api/v1/contacts \
  --data-urlencode "q=alice" \
  -H "Accept: application/json"
```

---

## 🧪 Testing

This project uses [Pest PHP](https://pestphp.com/) for testing.

Run all tests:

```bash
./vendor/bin/pest
```

Or run a specific test file:

```bash
./vendor/bin/pest tests/Feature/ContactApiTest.php
./vendor/bin/pest tests/Feature/Console/ContactCommandTest.php
```

---

## Validation

* Input validated using Laravel `FormRequest`
* Phone numbers follow E.164 format
* Unique constraint on phone and email
* Validation rules are reused in CLI via service logic

---

## TODO

* [ ] Add API authentication (e.g., token-based)
* [ ] CSV import/export commands
* [ ] Add contact tags, filters, last-contacted sorting
* [ ] Activity logs and change tracking
* [ ] Frontend UI with Vue/React or Livewire

---

## Tech Stack

* Laravel 12
* Laravel Scout (with optional Meilisearch)
* Pest PHP (testing)
* DDD structure with DTOs, Services, Resources, FormRequests
* CLI via Artisan Commands

```

---


```
