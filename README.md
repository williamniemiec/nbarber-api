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
	<a href='https://wniemiec-api-nbarber.onrender.com'><img alt='Deploy' src='https://render.com/images/deploy-to-render-button.svg' width=200/></a>
</p>

<hr />

## ❇ Introduction
nBarber is an API built with Laravel Framework for a simple barber system. This application was made for the sole purpose of learning the Laravel framework better. You can interact with the project through the Railway platform ([click here to access](https://wniemiec-api-nbarber.onrender.com/request-docs)).

### Login information
| Email| Password |
|------- | ----- |
| william@email.com |123|

## ⚠ Warnings
The hosting service may have a certain delay (~ 1 min) for uploading the application so the loading of the website may have a certain delay. 

## 📖 Documentation
See [here](https://wniemiec-api-nbarber.onrender.com/request-docs) the OpenAPI documentation.

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
![application](https://raw.githubusercontent.com/williamniemiec/nbarber-api/master/docs/images/design/architecture.png)

#### Database
![database](https://raw.githubusercontent.com/williamniemiec/nbarber-api/master/docs/images/design/database.png)

## 📁 Files

### /
|        Name        |Type|Description|
|----------------|-------------------------------|-----------------------------|
|app|`Directory`|The app directory contains the core code of the application. Almost all of the classes in your application will be in this directory.|
|bootstrap|`Directory`|Contains the app.php file which bootstraps the framework. This directory also houses a cache directory which contains framework generated files for performance optimization such as the route and services cache files.|
|conf|`Directory`|Hosting platform configuration files.|
|config|`Directory`|Contains all of your application's configuration files.|
|database|`Directory`|Contains your database migrations, model factories, and seeds.|
|docs |`Directory`|Documentation files.|
|lang|`Directory`|Houses all of your application's language files.|
|public|`Directory`|Contains the `index.php` file, which is the entry point for all requests entering your application and configures autoloading. This directory also houses your assets such as images, JavaScript, and CSS.|
|resources|`Directory`|Contains views as well as raw, un-compiled assets such as CSS or JavaScript.|
|routes|`Directory`|Contains all of the route definitions for your application. By default, several route files are included with Laravel: `web.php`, `api.php`, `console.php`, and `channels.php`. <ul><li>The web.php file contains routes that the RouteServiceProvider places in the web middleware group, which provides session state, CSRF protection, and cookie encryption. If your application does not offer a stateless, RESTful API then all your routes will most likely be defined in the web.php file.</li><li>The api.php file contains routes that the RouteServiceProvider places in the api middleware group. These routes are intended to be stateless, so requests entering the application through these routes are intended to be authenticated via tokens and will not have access to session state.</li><li>The console.php file is where you may define all of your closure based console commands. Each closure is bound to a command instance allowing a simple approach to interacting with each command's IO methods. Even though this file does not define HTTP routes, it defines console based entry points (routes) into your application.</li><li>The channels.php file is where you may register all of the event broadcasting channels that your application supports.</li></ul>|
|scripts|`Directory`|Hosting platform configuration files.|
|storage|`Directory`|Contains logs, compiled Blade templates, file based sessions, file caches, and other files generated by the framework. This directory is segregated into app, framework, and logs directories. The app directory may be used to store any files generated by the application. The framework directory is used to store framework generated files and caches. Finally, the logs directory contains the application's log files.|
|tests|`Directory`|contains your automated tests.|
|.env.example  |`File`|Environment variables (you should copy it, renaming to '.env')|
|[createdb.sh]()|`File`|Script responsible for creating application database|
