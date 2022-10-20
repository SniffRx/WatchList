<?php
/**
 * @author Sergey (SniffRx) <sniffrxofficial@gmail.com>
 *
 * @link https://steamcommunity.com/profiles/76561198038416053
 * @link https://github.com/sniffrx
 * @link https://vk.com/sniffrx
 * @link https://t.me/sniffrxlife
 * @link https://www.youtube.com/channel/UCqpi610ZDvZR6MpEO8UDTqw
 *
 * @license (GNU General Public License Version 3) / (MIT License) Wait for dev/release
 */


// Задаём основную кодировку страницы.
header('Content-Type: text/html; charset=utf-8');

// Отключаем вывод ошибок. (DEBAG MODE equal 1)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Ограничиваем время выполнения скрипта.
set_time_limit(4);

// Проверка на нахождение в проекте (константа нужна для модульности сайта)
define('AL', true);

// Версия AnimeList.
define('VERSION', '0.3.2');

//Константы для почты (TODO: потом будут переделаны и добавлены в другой скрипт)
const APP_URL = 'https://URL/';
const SENDER_EMAIL_ADDRESS = 'admin@domain.name';

// Основная директория вэб-приложения.
define('APP', 'app/');

// Директория с модулями.
define('MODULES', APP . 'modules/');

// Директория с основными конфигурационными файлами.
define('INCLUDES', APP . 'includes/');

// Директория содержащая основные блоки вэб-приложения.
define('PAGE', APP . 'page/general/');

// Директория содержащая дополнительные блоки вэб-приложения.
define('PAGE_CUSTOM', APP . 'page/custom/');

// Директория с шаблонами.
define('TEMPLATES', APP . 'templates/');

// Директория содержащая графические кэш-файлы.
define('CACHE', 'cache/');

// Директория с ресурсами.
define('ASSETS', CACHE . 'assets/');

// Директория с CSS шаблонами.
define('ASSETS_CSS', ASSETS . 'css/');

// Директория с JS библиотеками.
define('ASSETS_JS', ASSETS . 'js/');

// Директория с основными кэш-файлами.
define('SESSIONS', CACHE . 'sessions/');

// Директория содержащая изображения.
define('IMG', CACHE . 'img/');

// Директория содержащая логи.
define('LOGS', CACHE . 'logs/');

// Регистраниция основных функций.
require INCLUDES . 'functions.php';

// Создание/возобновление сессии.
session_start();

// Включение буферизации.
ob_start();

// Импортирование глобального класса отвечающего за работу с базами данных.
use app\ext\DataBase;

// Импортирование основного глобального класса.
use app\ext\Main;

// Импортирование глобального класса отвечающего за работу с модулями.
use app\ext\Modules;

// Импортирование класса уведомлений.
use app\ext\Notifications;

// Импортирование глобального класса отвечающего за работу с авторизованными пользователями.
use app\ext\Auth;

// Импортирование графического класса.
use app\ext\Graphics;

//Использование роутинга страниц.
use app\ext\AltoRouter;

// __autoload() - автозагрузка php скриптов.
spl_autoload_register( function( $class ) {
    $path = str_replace( '\\', '/', $class . '.php' );
    file_exists( $path ) && require $path;
} );

// Создание экземпляра класса работающего с базами данных.
$DataBase = new DataBase;

// Создание экземпляра класса уведомлений.
$Notifications = new Notifications ( $DataBase );

// Создание основного экземпляра класса.
$Main = new Main ($DataBase);

// Создание экземпляра класса работающего с роутингом.
$Router = new AltoRouter;

//Подстановка http https для работы ссылок.
empty( $Main->main['site'] ) && $Main->main['site'] = '//' . preg_replace('/^(https?:)?(\/\/)?(www\.)?/', '', $_SERVER['HTTP_REFERER']);

// Добавление корневого роута.
$Router->setBasePath( parse_url($Main->main['site'], PHP_URL_PATH));

// Создание экземпляра класса работающего с модулями.
$Modules = new Modules ( $Main, $Notifications, $Router );

// Создание экземпляра класса работающего с авторизацией пользователей.
$Auth = new Auth ( $Main, $DataBase );

// Создание экземпляра графического класса.
$Graphics = new Graphics ( $Main, $Modules, $DataBase, $Auth, $Notifications, $Router );

// Запуск счетчика переходов(поситителей)
$Main->online_stats();
?>