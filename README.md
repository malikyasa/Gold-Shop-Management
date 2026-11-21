# Gold Shop Management System

PHP + MySQL web app for running a jewellery/gold shop: inventory, customers, sales with printable invoices, purchases, and a live dashboard.

## Setup (XAMPP / WAMP / LAMP)
1. Copy this folder into your web root (`htdocs/gold-shop-management`).
2. Start Apache and MySQL.
3. Import `database/gold_shop.sql` (phpMyAdmin > Import, or `mysql -u root < database/gold_shop.sql`). This creates `gold_shop_db`, all tables, two users and sample data.
4. If your MySQL credentials differ, edit `config/database.php` (or set `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`).
5. The site URL path is auto-detected, so the folder can have any name or location inside htdocs.
6. Open http://localhost/gold-shop-management/

## Default logins (change after first login)
| Role  | Email                | Password   |
|-------|----------------------|------------|
| Admin | admin@goldshop.com   | Admin@123  |
| Staff | staff@goldshop.com   | Staff@123  |

Admins can delete records; staff can add/edit and sell.
To change a password, generate a hash with `php -r 'echo password_hash("NewPass", PASSWORD_DEFAULT);'` and update the `users.password` column.

## Features
- Login with bcrypt hashing, session regeneration, login throttling, 30-minute idle timeout
- Inventory, customers (search, add, edit, delete)
- Sales: stock checked and locked in a transaction, invoice generated, printable; deleting a sale restores stock
- Purchases: increase stock and update purchase price; deleting reverses it
- Dashboard with live totals, revenue, low-stock list, recent sales

## Security
Prepared statements everywhere, output escaping, CSRF tokens on all forms, deletes via POST and admin-only, role checks.

## Structure
`auth/` login/logout · `config/` DB · `includes/` layout + helpers · `modules/` inventory, customers, sales, purchases · `dashboard/` · `database/` SQL schema
