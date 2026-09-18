# Glossary with CRUD - PHP / MySQL

A simple glossary application with a public-facing term browser and a
password-protected admin panel for full CRUD operations.

## Requirements

- PHP 8.1+
- MySQL 5.7+ / MariaDB 10.4+
- A web server with `mod_rewrite` (Apache) or equivalent

## Setup

1. **Database** - import the schema:

```sql
CREATE DATABASE IF NOT EXISTS glossary CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE glossary;

CREATE TABLE terms (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    term       VARCHAR(255) NOT NULL,
    definition TEXT         NOT NULL,
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

2. **Environment variables** - set before starting the web server:

```bash
export DB_USER=glossary_app
export DB_PASS=your_strong_password
```

3. **Admin password** - generate a bcrypt hash and put it in `config.php`:

```php
echo password_hash('your_password', PASSWORD_BCRYPT, ['cost' => 12]);
```

## Project Structure

```
.
├── admin/                  Admin controllers (CRUD)
├── controller/
│   ├── app.php             Bootstrap - loads all dependencies
│   └── functions/          Config, data layer, routing helpers
├── view/                   All view templates
│   └── admin/              Admin-specific views
├── data.json               Seed data (used by FileDataProvider)
├── index.php               Public glossary index
├── detail.php              Public term detail page
├── definition.php          Public definition lookup
├── login.php / logout.php  Auth
└── style.css               Cyber-terminal theme
```

## Security Notes

- All output is escaped with `e()` (htmlspecialchars).
- Forms are protected with per-session CSRF tokens.
- Passwords are stored as bcrypt hashes (`password_hash` / `password_verify`).
- Database queries use PDO prepared statements exclusively.
- Credentials are loaded from environment variables only - no hardcoded fallbacks.
