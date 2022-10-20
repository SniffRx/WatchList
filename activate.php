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

// Директория содержащая графические кэш-файлы.
define('CACHE', 'cache/');

// Директория с основными кэш-файлами.
define('SESSIONS', CACHE . 'sessions/');

// Создание/возобновление сессии.
session_start();

// Импортирование глобального класса отвечающего за работу с базами данных.
require_once 'app/ext/DataBase.php';

// Импортирование класса отвечающего за работу активации почты.
require_once 'activate/Activ.php';

// Создание экземпляра класса работающего с базами данных.
$DataBase = new app\ext\DataBase();
// Создание экземпляра класса работающего с активацией почты.
$Activ = new activate\Activ($DataBase);
?>