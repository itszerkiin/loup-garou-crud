
# Loup-Garou CRUD Application

## Description
This is a CRUD application for managing "Loup-Garou" game entities such as cards, compositions, and user accounts. The application provides an admin dashboard for managing these entities.

## Prerequisites
- WAMP (Windows Apache MySQL PHP) server
- Composer (for managing PHP dependencies)

## Installation

1. Clone the repository or download the project files.
2. Move the project folder to your WAMP `www` directory (e.g., `C:/wamp64/www/loup-garou-crud`).
3. Open a terminal in the project folder and run the following command to install dependencies:

    ```bash
    composer install
    ```

4. Create a database using `phpMyAdmin` and configure the database connection in the `.env` file or the configuration file (update `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD`).

## Configuration

1. Create a new database in `phpMyAdmin` and update the database settings in the project configuration file:

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=loup_garou_db
    DB_USERNAME=root
    DB_PASSWORD=
    ```

2. Ensure WAMP is running and both Apache and MySQL services are active.

## Usage

1. Access the project in your browser using the following URL:

    ```
    http://localhost/loup-garou-crud
    ```

2. You will find the following sections:

    - **Admin Dashboard**: Accessible via `views/admin/dashboard.php` for managing the overall application.
    - **Cards Management**: Create, edit, and list game cards in `views/cartes`.
    - **Compositions Management**: Manage different compositions of cards in `views/compositions`.
    - **User Login & Registration**: Users can log in or register in `views/utilisateurs/login.php` and `register.php`.

## Testing

This project uses PHPUnit for testing. You can run the tests using the following command:

```bash
./vendor/bin/phpunit
```

Make sure the tests are properly configured in your environment before running them.

## License
This project is open-source and available under the [MIT License](LICENSE).

