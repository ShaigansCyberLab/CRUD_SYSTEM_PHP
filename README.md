# PHP CRUD Glossary

A small PHP glossary application with a public term browser and a password-protected admin panel for full CRUD operations.

The project is intentionally built without a framework to demonstrate core PHP, PDO, authentication, sessions, CSRF protection, input validation, and basic web-application security.

## Features

* Create, read, update, and delete glossary terms
* Public glossary browser
* Term search
* Individual term pages
* Password-protected admin panel
* CSRF protection
* Secure session handling
* Login throttling
* Password hashing
* Input validation
* XSS-safe output escaping
* PDO prepared statements
* MySQL / MariaDB support
* Alternative JSON data provider
* Responsive cyber-terminal UI

## Tech Stack

* PHP 8.1+
* MySQL 5.7+ / MariaDB 10.4+
* PDO
* Bootstrap 5.3
* HTML5
* CSS3
* JSON

## Project Structure

```text
.
├── admin/
│   ├── create.php
│   ├── delete.php
│   ├── edit.php
│   └── index.php
│
├── controller/
│   ├── app.php
│   └── functions/
│       ├── config.php
│       ├── data.class.php
│       ├── dataprovider.class.php
│       ├── filedataprovider.class.php
│       ├── GlossaryTerm.class.php
│       ├── mysqldataprovider.class.php
│       └── routing_functions.php
│
├── database/
│   └── schema.sql
│
├── view/
│   ├── admin/
│   ├── definition.view.php
│   ├── detail.view.php
│   ├── index.view.php
│   ├── layout.view.php
│   ├── login.view.php
│   └── notfound.view.php
│
├── data.json
├── definition.php
├── detail.php
├── index.php
├── login.php
├── logout.php
├── style.css
├── .env.example
├── .gitignore
└── README.md
```

## Requirements

* PHP 8.1 or newer
* PHP extensions:

  * `pdo`
  * `pdo_mysql`
  * `mbstring`
* MySQL 5.7+ or MariaDB 10.4+
* Apache, Nginx, or PHP's built-in development server

## Database Setup

Create the database and table using:

```bash
mysql -u root -p < database/schema.sql
```

The application database user should have only the permissions required for CRUD operations:

```sql
SELECT
INSERT
UPDATE
DELETE
```

It does not need administrative privileges.

If you prefer to create the user manually:

```sql
CREATE DATABASE glossary
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE glossary;

CREATE TABLE terms (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    term VARCHAR(255) NOT NULL,
    definition TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_terms_term (term)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

CREATE USER 'glossary_app'@'localhost'
    IDENTIFIED BY 'YOUR_STRONG_DATABASE_PASSWORD';

GRANT SELECT, INSERT, UPDATE, DELETE
    ON glossary.terms
    TO 'glossary_app'@'localhost';

FLUSH PRIVILEGES;
```

## Configuration

The application does not store database passwords or administrator password hashes in the PHP source code.

Set the required environment variables before starting PHP.

```bash
export DB_USER=glossary_app
export DB_PASS='your_database_password'

export ADMIN_EMAIL='admin@admin.com'
```

Generate an administrator password hash:

```bash
php -r 'echo password_hash("your-strong-password", PASSWORD_DEFAULT), PHP_EOL;'
```

Then export the generated hash:

```bash
export ADMIN_PASSWORD_HASH='paste_generated_hash_here'
```

For better shell hygiene, the password can be entered without displaying it:

```bash
read -rsp "Admin password: " ADMIN_PASSWORD
echo

export ADMIN_PASSWORD_HASH="$(
    printf '%s' "$ADMIN_PASSWORD" |
    php -r 'echo password_hash(stream_get_contents(STDIN), PASSWORD_DEFAULT);'
)"

unset ADMIN_PASSWORD
```

Verify that the required variables exist:

```bash
printenv | grep -E '^(DB_USER|DB_PASS|ADMIN_EMAIL|ADMIN_PASSWORD_HASH)='
```

Do not commit these values to Git.

## Run Locally

Using PHP's built-in development server:

```bash
php -S 127.0.0.1:8000 -t .
```

Open:

```text
http://127.0.0.1:8000
```

The application is designed to use HTTPS automatically when deployed behind an HTTPS web server.

## Security

The project includes several defensive measures.

### Input validation

User-controlled values are:

* checked for scalar values
* trimmed
* validated for UTF-8
* length-limited
* validated before reaching the database

HTML escaping is performed only when data is rendered into HTML.

### XSS protection

All dynamic HTML output passes through:

```php
e($value)
```

which uses `htmlspecialchars()` with UTF-8 and `ENT_QUOTES`.

### SQL injection protection

Database operations use PDO prepared statements.

User input is never concatenated directly into SQL statements.

Search wildcard characters are also escaped so `%` and `_` supplied by users do not become uncontrolled SQL `LIKE` patterns.

### CSRF protection

State-changing forms use session-bound CSRF tokens generated with:

```php
random_bytes()
```

and verified using:

```php
hash_equals()
```

CSRF protection is applied to:

* Login
* Create
* Update
* Delete
* Logout

### Authentication

Passwords are never stored in plaintext.

Administrator passwords are represented by password hashes generated with:

```php
password_hash()
```

and verified with:

```php
password_verify()
```

The password hash is supplied through an environment variable rather than stored in the repository.

### Session security

Sessions use:

* HTTP-only cookies
* SameSite=Lax
* Secure cookies when HTTPS is detected
* PHP strict session mode
* Session ID regeneration after authentication
* Periodic session ID regeneration
* 30-minute inactivity timeout

### Login protection

Failed login attempts are throttled to reduce casual brute-force attempts.

The application also performs password verification against a valid hash when an unknown email is submitted to reduce obvious username-enumeration timing differences.

For production deployments, stronger rate limiting should normally be implemented at the reverse proxy, WAF, or application infrastructure layer.

### Security headers

The application sends headers including:

```text
Content-Security-Policy
X-Content-Type-Options
X-Frame-Options
Referrer-Policy
Permissions-Policy
Strict-Transport-Security (HTTPS)
```

Bootstrap JavaScript is not loaded because this application does not require it.

### Database privileges

The application database account should have only:

```text
SELECT
INSERT
UPDATE
DELETE
```

It should not have administrative permissions such as:

```text
CREATE
DROP
ALTER
GRANT
CREATE USER
```

### Secrets

Secrets should never be committed to Git.

Use environment variables for:

```text
DB_USER
DB_PASS
ADMIN_EMAIL
ADMIN_PASSWORD_HASH
```

A `.env.example` file is included only as a configuration reference.

## Data Providers

The project separates the application from its storage implementation using a `DataProvider` abstraction.

Current providers:

```text
DataProvider
├── MySqlDataProvider
└── FileDataProvider
```

The application currently initializes:

```php
new MySqlDataProvider(...)
```

The JSON provider remains available for learning and experimentation.

## Development

Check all PHP files for syntax errors:

```bash
find . -name "*.php" -print0 | xargs -0 -n1 php -l
```

Check Git status:

```bash
git status
```

Review changes before committing:

```bash
git diff
```

## Design

The interface uses a dark cyber-terminal aesthetic with:

* Cyan terminal accents
* Dark blue/gray surfaces
* Responsive layouts
* Mobile-friendly tables and forms
* Reduced-motion support

## Learning Goals

This project was built to practice:

* Core PHP
* Object-oriented PHP
* MVC-style separation
* PDO
* MySQL / MariaDB
* CRUD operations
* Authentication
* Session management
* CSRF protection
* XSS prevention
* SQL injection prevention
* Input validation
* Secure configuration
* Basic web security
* Git and GitHub workflows

## Future Improvements

Possible future work:

* Pagination
* Multiple administrator accounts
* Role-based access control
* Server-side login rate limiting
* Automated tests
* PHPUnit
* Docker deployment
* CI security checks
* Database migrations
* Production deployment configuration

## Author

**ShaigansCyberLab**

GitHub:

https://github.com/ShaigansCyberLab
