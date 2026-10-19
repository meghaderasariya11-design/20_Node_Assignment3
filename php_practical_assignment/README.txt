PHP PRACTICAL ASSIGNMENT - BEGINNER PROJECT
===========================================

Requirements covered:
1. Shopping cart: admin login, category CRUD, product CRUD with picture upload/resize, logout.
2. User site: category list, products by category, AJAX pagination, CAPTCHA registration, login, add-to-cart.
3. Import students.xml into MySQL.
4. Export MySQL students table to students.xml.
5. List files in a directory.
6. Call an Express REST API from PHP.
7. Call a PHP REST API from Express.

REQUIREMENTS
- XAMPP (Apache, PHP, MySQL)
- PHP extensions: PDO MySQL, GD (for image resizing), SimpleXML
- Node.js for the Express API example

SETUP
1. Copy this folder to: C:\xampp\htdocs\php_practical_assignment
2. Start Apache and MySQL in XAMPP.
3. Open phpMyAdmin and import database.sql.
4. Edit config.php if your MySQL username/password differs.
5. Open http://localhost/php_practical_assignment/
6. Default admin: admin@example.com / admin123
   The project creates this default admin when you first open setup_admin.php.
   Open http://localhost/php_practical_assignment/setup_admin.php once, then delete that file.
7. For image upload/resize, make sure PHP GD is enabled in php.ini, then restart Apache.
8. Register a normal user from the user site.

IMPORTANT
- This is an educational assignment, not production-ready security.
- Change default credentials and use HTTPS for real projects.
- PHP must have permission to write to uploads/.
- XML import expects students.xml with <students><student>...</student></students>.
- For the Express examples, see express_api/README.txt.
