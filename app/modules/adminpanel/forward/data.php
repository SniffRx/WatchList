<?php

print_r($_SESSION['usertype']);

// Ведущая проверка.
if(! isset( $_SESSION['usertype'] ) || $_SESSION['usertype'] != 3 ) {get_iframe( '013','Доступ закрыт' ); die();}
//if(! isset( $_SESSION['user_admin'] )) echo "Код ошибки: 013. Доступ закрыт!";die();

// Импортирования класса для работы с панелью администратора.

//use app\ext\Pdox;
use app\modules\adminpanel\ext\Admin;

// Создаём экземпляр класса для работы с админкой
$Admin = new Admin ( $Main, $Modules, $DataBase/*, $Auth,  $Translate*/ );

# Настройки модулей

// Нажатие на кнопку - Очистить кэш модулей.
isset( $_POST['clear_cache_modules'] ) && $Admin->action_clear_all_cache();

// Нажатие на кнопку - Обновить список модулей.
isset( $_POST['clear_modules_initialization'] ) && $Admin->action_clear_modules_initialization();

// Нажатие на кнопку - Очистить кэш перевоов.
isset( $_POST['clear_translator_cache'] ) && $Admin->action_clear_translator_cache();

// Перемещение блоков - Порядок загрузки модулей.
isset( $_POST['data'] ) && $Admin->edit_modules_initialization();

// Нажатие на кнопку - Настройки модуля -> Сохранить.
isset( $_POST['module_save'] ) && $Admin->edit_module();

# Основные настройки

// Нажатие на кнопку - Основные настройки -> Сохранить.
//isset( $_POST['option_one_save'] ) && $Admin->edit_options();

# Настройка базы данных

// Нажатие на кнопку - Добавить DB.
//isset( $_POST['function'] ) && $Admin->action_db_add_connection();

//!empty($_GET['section']) && $_GET['section'] == 'stats' ? $Chart_Visits =  $Admin->charts_attendance() : NULL;

#Настройка шаблонов

//Нажатие на кнопку - Очистить кеш шаблонов
isset( $_POST['clear_templates_cache'] ) && $Admin->clear_templates_cache();

if(isset($_POST['name'])) $name = stripcslashes(strip_tags($_POST['name']));

if(isset($_POST['alt_name'])) $alt_name = stripcslashes(strip_tags($_POST['alt_name']));

if(isset($_POST['link'])) $link = stripcslashes(strip_tags($_POST['link']));

if(isset($_FILES['image'])) {
    if(getimagesize($_FILES['image']['tmp_name'])[0] == 600 || getimagesize($_FILES['image']['tmp_name'])[1] == 847) {
        move_uploaded_file($_FILES['image']['tmp_name'], ASSETS .'/img/animeposters/'.md5($_FILES['image']['name']).".".strtolower(substr(strrchr(basename($_FILES['image']['name']), '.'),1)));
        $image = md5($_FILES['image']['name']).".".strtolower(substr(strrchr(basename($_FILES['image']['name']), '.'),1));
    }
}

if(isset($_POST['desc'])) $desc = $_POST['desc'];

//Добавление аниме на сайт
isset( $_POST['add_anime'] ) && $Admin->add_anime($name, $alt_name, $link, $image, $desc);

// Задаём заголовок страницы.
//$Modules->set_page_title( $Main->main['site_name'] . ' :: Admin_panel');