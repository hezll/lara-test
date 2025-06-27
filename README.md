
---
# Laravel Contact Manager

After receiving the requirements, I aimed to follow Laravel 12's official conventions as much as possible. Both the API and CLI functionalities have been implemented, and search capabilities were added via Laravel Scout. I also wrote corresponding test cases to ensure core functionality.

One area that still needs improvement is unified exception handling. For now, the project relies on Laravel's FormRequest validation. Based on past experience, we usually define a standardized business exception handler integrated with the i18n (internationalization) system. Due to limited time, I focused on delivering a functional system where all features work end-to-end. There may still be minor bugs, which I plan to address when time permits.
The app supports:

-  RESTful API for CRUD operations
-  Command-line interface (CLI) commands
-  Centralized validation and error handling
-  Contact search (via Laravel Scout)
-  Pest tests for full feature coverage

---

##  Project Structure

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

##  Installation

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

##  API Usage

### Base URL: `/api/v1`

| Method | Endpoint              | Description               |
| ------ | --------------------- | ------------------------- |
| GET    | `/contacts`           | List/Search  (paginated) |
| GET    | `/contacts/{id}`      | Show contact details      |
| POST   | `/contacts`           | Create or update contact  |
| DELETE | `/contacts/{id}`      | Delete contact            |
| POST   | `/contacts/{id}/call` | Mark contact as called    |
| GET    | `/contacts?q=keyword` | Search contacts           |

###  Example 

```bash
ccurl -X POST https://lara-test.test/api/v1/contacts \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"name": "Alice123", "email": "alice123@example.com", "phone": "+61412345679"}'
{"data":{"id":31,"uuid":"b75f8ee2-db7f-476b-8c01-1867ec7a396b","name":"Alice123","phone":"+61412345679","email":"alice123@example.com","status":null,"is_called":null,"is_active":null,"notes":null,"tags":[],"source":null,"last_contacted_at":null,"created_at":"2025-06-27T08:20:56.000000Z","updated_at":"2025-06-27T08:20:56.000000Z"}}

curl -X POST https://lara-test.test/api/v1/contacts \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"name": "Alice", "email": "alice@example.com", "phone": "+61412345678"}'
{"message":"Phone number already exists. (and 1 more error)","errors":{"phone":["Phone number already exists."],"email":["Email address already exists."]}}


curl -G "https://lara-test.test/api/v1/contacts" \
  --data-urlencode "page=2" \
  --data-urlencode "perPage=10" \
  -H "Accept: application/json"
{"data":[{"id":16,"uuid":"61d8b57f-9ab2-4ac0-a4ca-1f03f3daa474","name":"Karson Cummerata","phone":"+61493365315","email":"szemlak@example.net","status":2,"is_called":false,"is_active":true,"notes":null,"tags":["demo","seed"],"source":"import","last_contacted_at":"2025-06-01T14:00:25.000000Z","created_at":"2025-06-26T14:09:10.000000Z","updated_at":"2025-06-26T14:09:10.000000Z"},{"id":17,"uuid":"d35d1027-b715-44e2-9c8b-b8a0abe5ae8c","name":"Ms. Isabel Bode DDS","phone":"+61446837730","email":"bruen.narciso@example.com","status":3,"is_called":false,"is_active":true,"notes":"Similique dolorem ullam aliquid illo et.","tags":["demo","seed"],"source":"import","last_contacted...

curl -G "https://lara-test.test/api/v1/contacts" \
  --data-urlencode "q=alice" \
  -H "Accept: application/json"
{"data":[{"id":21,"uuid":"53e6f7f3-4e78-40ea-8fd5-6528fee572c1","name":"Alice Test","phone":"+61412345678","email":"alice@example.com","status":0,"is_called":false,"is_active":true,"notes":"First time create","tags":["new","important"],"source":"linkedin","last_contacted_at":null,"created_at":"2025-06-27T03:11:02.000000Z","updated_at":"2025-06-27T03:11:02.000000Z"},{"id":23,"uuid":"5cedc1d1-6b6e-4f24-b111-7be6ae0378d7","name":"Alice Test","phone":"+61512345678","email":"a123lice@example.com","status":0,"is_called":false,"is_active":true,"notes":"First time create","tags":["new","important"],"source":"linkedin","last_contacted_at":null,"created_at":"2025-06-27T04:12:22.000000Z","updated_at":"2025-06-27T04:12:22.000000Z"},{"id":26,"uuid":"81301907-9166-4bd6-aa29-a69a4e992823","name":"Alice CLI","phone":"+61400000001","email":"cli@example.com","status":0,"is_called":false,"is_active":true,"notes":null,"tags":[],"source":"cli","last_contacted_at":null,"created_at":"2025-06-27T06:58:21.000000Z","updated_at":"2025-06-27T06:58:21.000000Z"},{"id":30,"uuid":"6cda8a89-7fac-4adc-b0f8-b132419fe99b","name":"Alice First","...

```

---

## CLI Commands

All commands share logic with the API (services + validation):

```bash
php artisan contact:upsert --name="Alice CLI" --email=cli123@example.com --phone="+61400000003"
Contact upserted: ID 33, Name: Alice CLI


php artisan contact:show 10  
Contact Details:
ID:      10
UUID:    9e1f89ea-c850-447a-93a8-fe63b59c15c4
Name:    Alanis Dare
Phone:   +61437674297
Email:   kulas.golden@example.org
Notes:   
Tags:    demo, seed
Source:  api
Called:  1
LastContact:  2025-06-27 07:18:05
Created: 2025-06-26 14:09:10
Updated: 2025-06-27 07:18:05


php artisan contact:list  --page=2 
+----+---------------------------+----------------------------+--------------+-----------+---------------------+---------------------+
| ID | Name                      | Email                      | Phone        | Is Called | Last Contact        | Created At          |
+----+---------------------------+----------------------------+--------------+-----------+---------------------+---------------------+
| 16 | Karson Cummerata          | szemlak@example.net        | +61493365315 |           | 2025-06-01 14:00:25 | 2025-06-26 14:09:10 |
| 17 | Ms. Isabel Bode DDS       | bruen.narciso@example.com  | +61446837730 |           | 2025-05-29 11:54:48 | 2025-06-26 14:09:10 |
| 18 | Jacynthe Friesen          | bill30@example.org         | +61407227476 |           | 2025-06-06 15:55:46 | 2025-06-26 14:09:10 |
| 19 | Prof. Chyna Buckridge DVM | bkunze@example.net         | +61403205909 | 1         |                     | 2025-06-26 14:09:10 |
| 20 | Vernice Prohaska IV       | jace63@example.com         | +61409807298 |           |                     | 2025-06-26 14:09:10 |
| 21 | Alice Test                | alice@example.com          | +61412345678 |           |                     | 2025-06-27 03:11:02 |
| 23 | Alice Test                | a123lice@example.com       | +61512345678 |           |                     | 2025-06-27 04:12:22 |
| 24 | Bob                       | bob@example.com            | +64212345678 |           |                     | 2025-06-27 04:14:33 |
| 26 | Alice CLI                 | cli@example.com            | +61400000001 |           |                     | 2025-06-27 06:58:21 |
| 30 | Alice First               | alic123e.first@example.com | +61412315678 |           |                     | 2025-06-27 07:30:43 |
| 31 | Alice123                  | alice123@example.com       | +61412345679 |           |                     | 2025-06-27 08:20:56 |
| 33 | Alice CLI                 | cli123@example.com         | +61400000003 |           |                     | 2025-06-27 08:22:57 |
+----+---------------------------+----------------------------+--------------+-----------+---------------------+---------------------+

php artisan contact:list --q=alice --page=1 --per-page=10                                                    
+----+-------------+----------------------------+--------------+-----------+--------------+---------------------+
| ID | Name        | Email                      | Phone        | Is Called | Last Contact | Created At          |
+----+-------------+----------------------------+--------------+-----------+--------------+---------------------+
| 21 | Alice Test  | alice@example.com          | +61412345678 |           |              | 2025-06-27 03:11:02 |
| 23 | Alice Test  | a123lice@example.com       | +61512345678 |           |              | 2025-06-27 04:12:22 |
| 26 | Alice CLI   | cli@example.com            | +61400000001 |           |              | 2025-06-27 06:58:21 |
| 30 | Alice First | alic123e.first@example.com | +61412315678 |           |              | 2025-06-27 07:30:43 |
| 33 | Alice CLI   | cli123@example.com         | +61400000003 |           |              | 2025-06-27 08:22:57 |
| 31 | Alice123    | alice123@example.com       | +61412345679 |           |              | 2025-06-27 08:20:56 |
+----+-------------+----------------------------+--------------+-----------+--------------+---------------------+
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
curl -L -G https://lara-test.test/api/v1/contacts/ \
  --data-urlencode "q=alice" \
  -H "Accept: application/json"

{"data":[{"id":21,"uuid":"53e6f7f3-4e78-40ea-8fd5-6528fee572c1","name":"Alice Test","phone":"+61412345678","email":"alice@example.com","status":0,"is_called":false,"is_active":true,"notes":"First time create","tags":["new","important"],"source":"linkedin","last_contacted_at":null,"created_at":"2025-06-27T03:11:02.000000Z","updated_at":"2025-06-27T03:11:02.000000Z"},{"id":23,"uuid":"5cedc1d1-6b6e-4f24-b111-7be6ae0378d7","name":"Alice Test","phone":"+61512345678","email":"a123lice@example.com","status":0,"is_called":false,"is_active":true,"notes":"First time create","tags":["new","important"],"source":"linkedin","last_contacted_at":null,"created_at":"2025-06-27T04:12:22.000000Z","updated_at":"2025-06-27T04:12:22.000000Z"},{"id":26,"uuid":"81301907-9166-4bd6-aa29-a69a4e992823","name":"Alice CLI","phone":"+61400000001","email":"cli@example.com","status":0,"is_called":false,"is_active":true,"notes":null,"tags":[],"source":"cli","last_contacted_at":null,"created_at":"2025-06-27T06:58:21.000000Z","updated_at":"2025-06-27T06:58:21.000000Z"},{"id":30,"uuid":"6cda8a89-7fac-4adc-b0f8-b132419fe99b","name":"Alice First","phone":"+61412315678","email":"alic123e.first@example.com","status":0,"is_called":false,"is_active":true,"notes":null,"tags":[],"source":null,"last_contacted_at":null,"created_at":"2025-06-27T07:30:43.000000Z",...
```

---

## Testing

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

##  TODO

* [ ] Add API authentication (e.g., token-based)
* [ ] CSV import/export commands
* [ ] Add contact tags, filters, last-contacted sorting
* [ ] Activity logs and change tracking
* [ ] Frontend UI with Vue/React or Livewire

---

##  Tech Stack

* Laravel 12
* Laravel Scout (with optional Meilisearch)
* Pest PHP (testing)
* DDD structure with DTOs, Services, Resources, FormRequests
* CLI via Artisan Commands


