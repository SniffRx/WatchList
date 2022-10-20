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

/**
 * Фикс функции file_get_contents для криво настроенного SSL сертификата под Nginx.
 *
 * @since 0.0.1
 *
 * @param string $file      Путь до файла который необходимо прочитать и вывести.
 *
 * @return string|false     Выводит содержимое файла.
 */
function file_get_contents_fix( $file ) {
    return file_get_contents( $file, false, stream_context_create( array( "ssl" => array("verify_peer" => false, "verify_peer_name" => false ) ) ) );
}

/**
 * Фикс функции header для криво настроенного сервера, релоады происходят за счёт JS.
 *
 * @since 0.2
 *
 * @param  string $url      Переадрисация по URL.
 */
function header_fix( $url ) {
    if ( ! headers_sent() )
    {
        echo '<script type="text/javascript">window.location.href="' . $url . '";</script>';
        echo '<noscript><meta http-equiv="refresh" content="0;url=' . $url . '" /></noscript>';
    }
    else
    {
        header("Location: ".$url);
    }
}

/**
 * Перезапуск страницы.
 *
 * @since 0.2
 */
function refresh() {
    return header("Refresh: 0");
}

/**
 * Открывает iframe блок, по умолчанию для страниц ошибок.
 *
 * @since 0.2
 *
 * @param int       $code          Код ошибки.
 * @param string    $description   Описание ошибки.
 * @param bool      $die           Прекращение работы скрипта.
 *
 * @return string|false            Выводит новый URL с измененным значением подраздела.
 */
function get_iframe( $code, $description, $die = true ) {
    echo '<style>body {background:#000;}</style><iframe style="margin:0;border:0;width:100%;height:100%" src="?code=' . $code . '&description=' . $description . '"></iframe>';
    $die == true && die();
}

/**
 * Процент от числа ( Округление ).
 *
 * @since 0.2
 *
 * @param int        $int      Число.
 * @param int        $all      Всего.
 *
 * @return int                  Итог.
 */
function action_int_percent_of_all( $int = 0, $all = 0 ) {
    if( $int == 0 || $all == 0 ) {
        $res = 0;
    } else {
        $res = floor( 100 * $int / $all );
    }
    return is_nan ( $res ) ? 0 : $res;
}

/**
 * Сокращение вывода var_export до одной строки.
 *
 * @since 0.2
 *
 * @param array $var        Массив данных
 * @param boolean $return   Вид вывода.
 *
 * @return string           Вывод содержимого.
 */
function var_export_min($var, $return = true) {
    if ( is_array( $var ) ) {
        $toImplode = array();
        foreach ( $var as $key => $value ) {
            $toImplode[] = var_export( $key, true ).'=>'.var_export_min( $value, true );
        }
        $code = 'array('.implode(',', $toImplode).')';
        if ($return) return $code;
        else echo $code;
    } else {
        return var_export($var, $return);
    }
} 

/**
 * Вывод var_export в более оптимальном виде.
 *
 * @since 0.2
 *
 * @param   array    $var     Массив данных
 * @param   boolean  $return  Вид вывода.
 *
 * @return  string            Вывод содержимого.
 */
function var_export_opt( $var, $return = true ) {
    return var_export( $var, $return );
}

/**
 * Получает и задает название подраздела из URL по умолчанию.
 *
 * @since 0.2
 *
 * @param  string $section       Название подраздела.
 * @param  string $default       Значние по умолчанию.
 *
 * @return string|false          Выводит итоговое значение раздела.
 */
function get_section( $section, $default ) {
    return isset( $_GET[ $section ] ) ? action_text_clear( $_GET[ $section ] ) : $default;
}

/**
 * Получить URL страницы.
 *
 * @since 0.2
 *
 * @param  int $type          Тип URL.
 *
 * @return string             URL страницы.
 */

function get_url( $type ) {
    $url_clear = action_text_clear( $_SERVER["REQUEST_URI"] );
    switch ( $type ) {
        case 1:
            return '//' . $_SERVER["SERVER_NAME"] . $url_clear;
            break;
        case 2:
            return '//' .$_SERVER['HTTP_HOST'] . explode( '?', $url_clear, 2 )[0];
            break;
    }
}

/**
 * Меняет определенное значение подраздела на новое.
 *
 * @since 0.2
 *
 * @param string $url           URL для изменения.
 * @param string $command       Название подраздела.
 * @param string $change        Новое значение подраздела.
 *
 * @return string|false         Выводит новый URL с измененным значением подраздела.
 */
function set_url_section( $url, $command, $change ) {
    // Получаем массив всех подразделов.
    $query = $_GET;

    // Присваеваем подразделу новое значение.
    $query[$command] = $change;

    // Генерируем новую ссылку.
    $finally = urldecode( http_build_query( $query ) );
    return $url . '?' . $finally;
}

function action_text_clear($text)
{
    return stripslashes( htmlspecialchars( strip_tags( trim( stripslashes( $text ) ) ), ENT_QUOTES,'ISO-8859-1', true ) );
}

/**
 * Очистка текста до последнего слэша
 *
 * @since 0.2
 *
 * @param string        $text   Текст.
 *
 * @return string               Текст после последнего слэша
 */
function action_text_clear_before_slash( $text ) {
    return array_reverse( explode( "/", $text ) )[0];
}

/**
 * Проверка на дубликат файла.
 *
 * @since 0.2
 *
 * @param  string        $file     Ссылка на первый файл.
 * @param  string        $file_2   Ссылка на второй файл.
 *
 * @return bool                    Итог проверки.
 */
function check_duplicate_files( $file, $file_2 ) {
    return ( file_exists( $file ) && file_exists( $file_2 ) && filesize( $file ) === filesize( $file_2 ) ) ? true : false;
}

?>