![](https://raw.githubusercontent.com/williamniemiec/nbarber-api/master/docs/images/logo/logo.jpg)

<h1 align='center'>nBarber API</h1>
<p align='center'>API built with the Laravel Framework for a barber system.</p>
<p align="center">
	<a href="https://github.com/williamniemiec/nbarber-api/actions/workflows/windows.yml"><img src="https://github.com/williamniemiec/nbarber-api/actions/workflows/windows.yml/badge.svg" alt=""></a>
	<a href="https://github.com/williamniemiec/nbarber-api/actions/workflows/macos.yml"><img src="https://github.com/williamniemiec/nbarber-api/actions/workflows/macos.yml/badge.svg" alt=""></a>
	<a href="https://github.com/williamniemiec/nbarber-api/actions/workflows/ubuntu.yml"><img src="https://github.com/williamniemiec/nbarber-api/actions/workflows/ubuntu.yml/badge.svg" alt=""></a>
	<a href="https://www.php.net/"><img src="https://img.shields.io/badge/PHP-8+-D0008F.svg" alt="PHP compatibility"></a>
	<a href="https://github.com/williamniemiec/nbarber-api/releases"><img src="https://img.shields.io/github/v/release/williamniemiec/nbarber-api" alt="Release"></a>
	<a href="https://github.com/williamniemiec/nbarber-api/blob/master/LICENSE"><img src="https://img.shields.io/github/license/williamniemiec/nbarber-api" alt="License"></a>
</p>
<p align="center">
	<a href='https://wniemiec-api-nbarber.up.railway.app/request-docs'><img alt='Deploy' src='https://railway.app/button.svg' width=200/></a>
</p>

<hr />

## ❇ Introduction
nBarber is an API built with Laravel Framework for a simple barber system. This application was made for the sole purpose of learning the Laravel framework better. You can interact with the project through the Railway platform ([click here to access](https://wniemiec-api-nbarber.up.railway.app/request-docs)).

### Login information
| Email| Password |
|------- | ----- |
| william@email.com |123|

## ⚠ Warnings
The hosting service may have a certain delay (~ 1 min) for uploading the application so the loading of the website may have a certain delay. 

## 📖 Documentation
See [here](https://wniemiec-api-nbarber.up.railway.app/request-docs) the OpenAPI documentation.

## ✔ Requiremens
- [Node v12+](https://nodejs.org/);
- [PHP 8](https://www.php.net/);
- [Postgres 15](https://www.php.net/);
- [Composer](https://getcomposer.org/).

## ℹ How to run

1. Create database
> chmod +x createdb.sh && ./createdb.sh

2. Install project
> composer install

3. Run migrations
> php artisan make:migration

4. Seed database
> php artisan db:seed

5. Run project
> php artisan serve


## 🚩 Changelog
Details about each version are documented in the [releases section](https://github.com/williamniemiec/nbarber-api/releases).

## 🗺 Project structure

#### Application
![application](https://raw.githubusercontent.com/williamniemiec/nbarber-api/master/docs/images/design/architecture.jpg)

#### Database
![database](https://raw.githubusercontent.com/williamniemiec/nbarber-api/master/docs/images/design/database.png)

## 📁 Files

### /
|        Name        |Type|Description|
|----------------|-------------------------------|-----------------------------|
|docs |`Directory`|Documentation files|
|src  |`Directory`|Application and test files|
|.env.example  |`File`|Environment variables (you should copy it, renaming to '.env')|
|createdb.sh  |`File`|Script for creating project database in Postgres|

