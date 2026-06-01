# Laravel CRM Coding Test

This project implements the PHP developer test as a focused Laravel CRM application.

It includes:

- Extensible contact creation from multiple source types.
- Account and lead flows that automatically create contacts.
- API endpoints and a small browser UI for testing the flows.
- Seed data for quick review.
- Feature/unit tests.
- MySQL query optimization SQL and notes.

## Tech Stack

- PHP 8.3+
- Laravel 13
- MySQL
- PHPUnit

## Setup

Install dependencies:

```bash
composer install
```

Create the environment file:

```bash
cp .env.example .env
php artisan key:generate
```

Configure MySQL in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=crm
DB_USERNAME=root
DB_PASSWORD=
```

Create the database in MySQL if it does not exist:

```sql
CREATE DATABASE crm;
```

Run migrations and seed sample data:

```bash
php artisan migrate --seed
```

## Run The App

```bash
php artisan serve
```

Open:

```text
http://127.0.0.1:8000/crm
```

If port `8000` is busy:

```bash
php artisan serve --port=8010
```

Then open:

```text
http://127.0.0.1:8010/crm
```

The browser page lets you create accounts, create leads, and view generated contacts.

## API Endpoints

Create an account and contact:

```http
POST /api/accounts
```

```json
{
  "name": "Acme BV",
  "first_name": "Ada",
  "last_name": "Lovelace",
  "email": "ada@example.com",
  "phone": "+31000000000"
}
```

Create a lead and contact:

```http
POST /api/leads
```

```json
{
  "first_name": "Grace",
  "last_name": "Hopper",
  "email": "grace@example.com",
  "company_name": "Compiler Labs",
  "status": "new"
}
```

## Seed Data

Seeder files:

- `database/seeders/DatabaseSeeder.php`
- `database/seeders/CrmSeeder.php`

`DatabaseSeeder` calls `CrmSeeder`. The CRM seeder uses the real application service, `App\Services\ContactCreator`, so contacts are generated through the same flow used by the API and web forms.

Run the seeder:

```bash
php artisan db:seed
```

Seeded records:

- 3 accounts:
  - Acme BV
  - Northwind Traders
  - Compiler Labs
- 3 leads:
  - Katherine Johnson / Orbital Analytics
  - Margaret Hamilton / Apollo Software
  - Barbara Liskov / B2C lead with no company
- 6 contacts total:
  - 3 contacts from accounts
  - 3 contacts from leads

Reset the database and seed again:

```bash
php artisan migrate:fresh --seed
```

Inspect seeded records in Tinker:

```bash
php artisan tinker
```

```php
App\Models\Account::with('contact')->get();
App\Models\Lead::with('contact')->get();
App\Models\Contact::all();
```

Open the seeded data in the browser:

```text
http://127.0.0.1:8000/crm
```

or, if using the alternate port:

```text
http://127.0.0.1:8010/crm
```

## Tests

```bash
php artisan test
```

The tests cover:

- Creating a contact from an account.
- Creating a contact from a lead.
- Request validation.
- Extending the contact creator with a future source.

## Design

The contact creation flow is centered on `App\Contracts\ContactSource`.

Important classes:

- `App\Services\ContactCreator`
- `App\Contracts\ContactSource`
- `App\Services\ContactSources\AccountContactSource`
- `App\Services\ContactSources\LeadContactSource`
- `App\Data\ContactData`

`ContactCreator::createFrom(ContactSource $source)` accepts any source that implements the contract. This keeps the creator closed for modification and open for new sources.

To add another source later:

1. Create a model for the source.
2. Create a source adapter that implements `ContactSource`.
3. Pass that adapter to `ContactCreator::createFrom()`.

## Assumptions

- Creating an account or lead immediately creates one contact.
- Accounts represent B2B customers, so account contacts are marked as business contacts.
- Leads are marked as business contacts when `company_name` is present.
- Contacts store `source_type` and `source_id` for traceability.
- Authentication is omitted because it was not part of the test requirement.

## MySQL Query Optimization

The optimized table/query file is:

```text
database/sql/leads_optimization.sql
```

The original query filters and sorts like this:

```sql
WHERE leads.account_id = 1
  AND leads.deleted_at IS NULL
ORDER BY leads.id DESC
LIMIT 100 OFFSET 0
```

The key optimization is this composite index:

```sql
KEY leads_account_deleted_id_desc_idx (account_id, deleted_at, id DESC)
```

Why it improves performance:

- `account_id` is the first equality filter.
- `deleted_at` supports the soft-delete condition.
- `id DESC` matches the requested order.
- MySQL can stop after finding the newest 100 matching rows.

Expected execution changes from a large scan/filesort to an index range scan. On a production-sized dataset this should be verified with:

```sql
EXPLAIN ANALYZE
SELECT ...
```

No million-row dataset was provided with the test, so the exact timing improvement is documented as an expected plan improvement rather than a local benchmark.
