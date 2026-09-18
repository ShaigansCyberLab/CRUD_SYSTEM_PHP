# CRUD System PHP

A secure PHP CRUD application built with PHP, PDO, and MariaDB.

This project demonstrates secure database interaction, environment-based configuration, password hashing, and basic CRUD operations.

The goal of this project is to practice real-world PHP development patterns and security fundamentals.

---

# Features

## Database

- MariaDB / MySQL support
- PDO database connection
- UTF-8 (`utf8mb4`) support
- Prepared statements
- Protected database credentials through environment variables

## CRUD Operations

- Create records
- Read records
- Update records
- Delete records

## Security

Implemented security practices:

- No database passwords stored in source code
- Environment variable based configuration
- PDO prepared statements to prevent SQL injection
- Password hashing using PHP `password_hash()`
- Password verification using `password_verify()`
- Output escaping with `htmlspecialchars()`
- Separation between database user and application administrator

---

# Technology Stack

## Backend

- PHP 8+
- PDO
- MariaDB 11+
- MySQL-compatible database

## Development Environment

- Linux
- Apache/Nginx or PHP built-in server

---

# Project Structure
