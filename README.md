<br/>
<p align="center">
  <a href="https://github.com/SniffRx/WatchList">
    <img src="images/logo.png" alt="Logo" width="80" height="80">
  </a>

  <h3 align="center">WatchList</h3>

  <p align="center">
    WatchList CMS for media sites.
    <br/>
    <br/>
    <a href="https://github.com/SniffRx/WatchList"><strong>Explore the docs »</strong></a>
    <br/>
    <br/>
    <a href="https://github.com/SniffRx/WatchList">View Demo</a>
    .
    <a href="https://github.com/SniffRx/WatchList/issues">Report Bug</a>
    .
    <a href="https://github.com/SniffRx/WatchList/issues">Request Feature</a>
  </p>
</p>

![Downloads](https://img.shields.io/github/downloads/SniffRx/WatchList/total) ![Contributors](https://img.shields.io/github/contributors/SniffRx/WatchList?color=dark-green) ![Issues](https://img.shields.io/github/issues/SniffRx/WatchList) ![License](https://img.shields.io/github/license/SniffRx/WatchList) 

## Table Of Contents

* [About the Project](#about-the-project)
* [Built With](#built-with)
* [Getting Started](#getting-started)
  * [Prerequisites](#prerequisites)
  * [Installation](#installation)
* [Usage](#usage)
* [License](#license)
* [Authors](#authors)
* [Acknowledgements](#acknowledgements)

## About The Project

![Screen Shot](https://user-images.githubusercontent.com/37187657/177745377-7afd3c68-ae01-4526-a839-6d16ef642a58.png)

Данный проект разрабатывается как CMS ядро под медиа сайты.
В проекте уже реализовано:
* Авторизация
* Регистрация
* Админ панель
* Главная страница
* Страница с медиа (фильм, сериал)
* Страница со списком медиа
* Страница о сайте
* Страница с профилем пользователя
* Модульность

## Built With

TODO

* [Bootstrap]()

## Getting Started

Здесь я опишу основную настройку для данной CMS.

### Prerequisites

* nginx
* mail server
* min php7.4
* php-bcmath
* php-mbstring
* php-pdo_mysql
* mysql

### Installation

1. Скачать и распаковать архив
2. Настройка сайта nginx
```nginx
location / {
        index index.php;
        try_files $uri $uri/ /index.php?$query_string;
        rewrite ^([^.]*[^/])$ $1/ permanent;
        rewrite !.(gif|jpg|png|ico|css|js|svg|js_controller.php)$ /index.php;
    }
```
3. Включите php модули `bcmath`,`mbstring`,`pdo_mysql`
4. Импортируйте файл `watchlist.sql` в базу данных
5. Переместить все файлы на сервер
6. Открыть папку `/cache/sessions`
7. Изменить файл `db.php`
```php
<?php return array (
    'HOST' => 'localhost',
    'PORT' => '3306',
    'USER' => 'usr',
    'PASS' => 'pass',
    'DB'   => 'db'
);
```
8. Изменить файл `options.php`
Меняем только `language, site_name, description, site`
поле `site` обязательно должно иметь вид: `//домен/`
```php
<?php
return array(
    'enable_css_cache'  =>  0,
    'enable_js_cache'   =>  0,
    'auth_cock'         =>  0,
    'session_check'     =>  0,
    'language'          =>  'RU',
    'site_name'         =>  'SiteName',
    'Description'       =>  'SiteDescription',
    'site'              =>  '//domain.name/',
    'theme'             =>  'default');
?>
```
9. Открыть корневой раздел сайта
10. Изменить файл `index.php`
```php
const APP_URL = 'https://domain/';
const SENDER_EMAIL_ADDRESS = 'admin@domain.name';
```
## Usage

Если Вы хотите сделать себя администратором, то нужно сделать следующее:
1. Зарегистрироваться на сайте и подтвердить почту
2. Зайти в базу данных
3. Найти пользователя в таблице `users`
4. Установить параметр `usertype` на 3

* usertype 0 -> пользователь
* usertype 1 -> VIP
* usertype 2 -> модератор
* usertype 3 -> администратор

### Creating A Pull Request

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

Distributed under the MIT License. See [LICENSE](https://github.com/SniffRx/WatchList/blob/main/LICENSE.md) for more information.

## Authors

* [SniffRx](https://github.com/SniffRx) - *Junior Programmer* - *Created WatchList*

## Acknowledgements
