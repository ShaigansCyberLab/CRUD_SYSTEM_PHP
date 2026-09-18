# CRUD System PHP

A secure PHP CRUD application built with PHP 8+, MariaDB/MySQL, PDO, Composer, and environment-based configuration.

The project started as a simple CRUD application and was improved with security-focused practices:

- Environment variable configuration
- Dedicated database application user
- Password hashing with bcrypt
- PDO prepared statements
- Session hardening
- Security response headers
- Login rate limiting
- MySQL/MariaDB backend support

---

# Features

## Authentication

- Admin login system
- Passwords stored using bcrypt hashes
- Session-based authentication
- Login attempt protection

## Database

- MariaDB/MySQL support
- PDO connection
- Prepared statements
- Dedicated database user
- UTF-8 (`utf8mb4`) support

## Security

Implemented protections:

- `HttpOnly` session cookies
- `SameSite=Lax` cookies
- Strict session mode
- Content Security Policy headers
- Clickjacking protection
- MIME sniffing protection
- Referrer policy
- Login rate limiting

---

# Requirements

- PHP 8.0+
- MariaDB/MySQL
- Composer

Required PHP extensions:

- PDO
- PDO MySQL
- mbstring
- json

---

# Installation

## 1. Clone the repository

```bash
git clone https://github.com/ShaigansCyberLab/CRUD_SYSTEM_PHP.git

cd CRUD_SYSTEM_PHP

---

# License

This project is licensed under the MIT License.

See the [LICENSE](LICENSE) file for details.
