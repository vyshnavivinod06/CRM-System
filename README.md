Laravel CRM Coding Test

This project is a Laravel-based CRM application developed as part of a PHP developer coding test.

The system demonstrates scalable contact creation workflows, account and lead management, API development, database optimization, testing, and clean architecture principles.

Features
Extensible contact creation architecture
Account creation with automatic contact generation
Lead creation with automatic contact generation
REST API endpoints
Simple browser UI for testing
Seeded sample CRM data
Feature and unit tests
MySQL query optimization example
Tech Stack
PHP 8.3+
Laravel 13
MySQL
PHPUnit
Project Structure

Important components used in the project:

App\Services\ContactCreator
App\Contracts\ContactSource
App\Services\ContactSources\AccountContactSource
App\Services\ContactSources\LeadContactSource
App\Data\ContactData

The architecture follows the Open/Closed Principle by allowing new contact sources to be added without modifying the core creator service.

## Installation ##

1. Clone the repository:

git clone https://github.com/vyshnavivinod06/CRM-System.git

 2 .Move into the project directory:

  cd CRM-System

3 . Install dependencies:

composer install

4 .Create the environment file:

cp .env.example .env

5. Generate the application key:

6. php artisan key:generate
7. Database Configuration

8. Configure MySQL credentials in .env:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=crm
DB_USERNAME=root
DB_PASSWORD=

Create the database:

CREATE DATABASE crm;

Run migrations and seeders:

php artisan migrate --seed
Run The Application

Start the Laravel development server:

php artisan serve

Open in browser:

http://127.0.0.1:8000/crm

If port 8000 is unavailable:

php artisan serve --port=8010

Then open:

http://127.0.0.1:8010/crm
API Endpoints
Create Account
POST /api/accounts

#####  Request body: #####

{
  "name": "Acme BV",
  "first_name": "Ada",
  "last_name": "Lovelace",
  "email": "ada@example.com",
  "phone": "+31000000000"
}
Create Lead
POST /api/leads

Request body:

{
  "first_name": "Grace",
  "last_name": "Hopper",
  "email": "grace@example.com",
  "company_name": "Compiler Labs",
  "status": "new"
}
Seed Data

Seeder files:

database/seeders/DatabaseSeeder.php
database/seeders/CrmSeeder.php

The CRM seeder uses the real application service:

App\Services\ContactCreator

This ensures seeded contacts follow the same workflow used by APIs and browser forms.

Run the seeder manually:

php artisan db:seed

Reset and reseed the database:

php artisan migrate:fresh --seed
Tests

Run automated tests:

php artisan test

Covered scenarios:

Account contact creation
Lead contact creation
Request validation
Future source extensibility
Architecture & Design

The contact generation workflow is centered around:

App\Contracts\ContactSource

Any future source can generate contacts by implementing the contract and passing the adapter into:

ContactCreator::createFrom(ContactSource $source)

This keeps the system flexible and scalable.

Assumptions
Every account or lead generates one contact immediately
Accounts are treated as B2B entities
Leads become business contacts when company_name exists
Contacts maintain source tracking using:
source_type
source_id
Authentication was intentionally omitted as it was outside the test scope
MySQL Query Optimization

Optimization file:

database/sql/leads_optimization.sql

Original query:

WHERE leads.account_id = 1
  AND leads.deleted_at IS NULL
ORDER BY leads.id DESC
LIMIT 100 OFFSET 0

Optimized composite index:

KEY leads_account_deleted_id_desc_idx
(account_id, deleted_at, id DESC)
Benefits
Optimizes filtering by account_id
Improves soft-delete filtering
Matches descending sorting by id
Reduces large scans and filesorts
Improves LIMIT query performance

Execution plan validation can be done using:

EXPLAIN ANALYZE
SELECT ...
Author

Vyshnavi Vinod

GitHub:
https://github.com/vyshnavivinod06/CRM-System
