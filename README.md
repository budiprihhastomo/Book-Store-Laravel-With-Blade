<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

## 🎉 About Book Store

Book Store is an application about book management CRUD. This application was created using the base Laravel Framework as the backend service and Laravel Blade as the frontend.

## ✨ Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

To run this project, yarn / npm tools and composer are needed and make sure you have imported the book_store.sql database which is already available in this project. Don't forget to setup the ENVIRONTMENT_VARIBALE or .env file.

## 🚀 Installation And Usage

-   Run this command, to clone the project.

```
git clone https://gitlab.com/budiprihhastomo/book-store.git

-- or --

git clone https://github.com/budiprihhastomo/book-store.git
```

-   Run this command, to install dependency for running the application.

```
composer install && npm install

```

-   Run this command, to migrate the database to your local machine.

```
:: Call Action to Migrate Database
php artisan migrate --seed

:: Create Secret Key Laravel App
php artisan key:generate
```

-   Run this command, to running the application.

```
:: Command To Running Backend Service (Laravel)
php artisan serve
```

## 📷 Screenshoot

-   Dashboard (Non-Auth User)
    ![alt text](https://gitlab.com/budiprihhastomo/book-store/-/raw/master/docs/images/Dashboard.PNG)
-   Books Page
    ![alt text](https://gitlab.com/budiprihhastomo/book-store/-/raw/master/docs/images/Books.PNG)
-   Authors Page
    ![alt text](https://gitlab.com/budiprihhastomo/book-store/-/raw/master/docs/images/Authors.PNG)
-   Modal Create And Update Book
    ![alt text](https://gitlab.com/budiprihhastomo/book-store/-/raw/master/docs/images/BooksModal.PNG)
-   Modal Create And Update Author
    ![alt text](https://gitlab.com/budiprihhastomo/book-store/-/raw/master/docs/images/AuthorsModal.PNG)

## 👤 Author

-   Budi Prih Hastomo

## 📝 License

Copyright © 2020 Budi Prih Hastomo.
This project is MIT licensed.
