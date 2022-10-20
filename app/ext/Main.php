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

namespace app\ext;

class Main {

    /**
     * @since 0.0.1
     * @var array
     */
    public $main = [];

    /**
     * @since 0.2
     * @var array
     */
    public $notes = [];

    /**
     * Инициализация основных настроек.
     *
     * @param object $DataBase
     *
     * @since 0.2
     */
    function __construct( $DataBase ) {

        // Проверка! (Мы находимся в программе?)
        defined('AL') != true && die();

        //Проверка на iframe
        (!empty($_GET['code']) && !empty($_GET['description'])) && exit(require PAGE_CUSTOM . '/error/index.php');

        $this->DataBase = $DataBase;

        // Получение настроек вэб-интерфейса.
        $this->main = $this->get_default_options();
    }

    /**
     * Получает и задает название подраздела из URL по умолчанию, сохраняя результат по умолчанию в сессию.
     *
     * @since 0.0.1
     *
     * @param string|bool       $section       Название подраздела.
     * @param string            $default       Значние по умолчанию.
     * @param array|null        $arr_true      Белый список.
     *
     */
    public function get_default_url_section( $section, $default, $arr_true ) {
        ! isset( $_SESSION[ $section ] ) && $_SESSION[ $section ] = $default;
        isset ( $_GET[ $section ] ) && in_array( $_GET[ $section ], $arr_true ) && $_SESSION[ $section ] = $_GET[ $section ];
    }

    /**
     * Отправка уведомлений через функцию.
     *
     * @since 0.2
     *
     * @return bool                 true
     */
    public function sendNote($text, $status, $time = 4.5)
    {
        $this->notes[] = [
            'content' => $text,
            'status' => $status,
            'time' => $time,
        ];
    }

    /**
     * Получение настроек по умолчанию для вэб-интерфейса.
     *
     * @since 0.2
     *
     * @return array                 Массив с настройками.
     */
    public function get_default_options() {
        $options = file_exists( SESSIONS . '/options.php' ) ? require SESSIONS . '/options.php' : null;
        return !isset( $options['site_name'] ) ? exit(require 'app/page/custom/install/index.php') : $options;
    }

    /**
     * Получает IP клиента с поддержкой CDN.
     * Поддерживает: CloudFlare - (другие CDN по запросу).
     *
     * @return string            IP.
     */
    public function get_client_ip_cdn()
    {
        return isset($_SERVER["HTTP_CF_CONNECTING_IP"]) ? $_SERVER["HTTP_CF_CONNECTING_IP"] : $_SERVER['REMOTE_ADDR'];
    }

    /**
    * Счетчик посещений
    */
    public function online_stats()
    {
        if(isset($_SESSION['logged']))
            $User = $_SESSION['logged'];
        else $User = 'guest';

        $client_ip = $this->get_client_ip_cdn();

        $param['ip'] = $client_ip;
        $Online = $this->DataBase->queryOneColumn("SELECT `user` FROM `online_users` WHERE `ip` = :ip", $param );

        if(empty($Online))
        {
            $params = [
                'user'  => $User,
                'ip'    => $client_ip
            ];
            $this->DataBase->query("INSERT INTO `online_users`(`id`, `user`, `ip`, `time`) VALUES (NULL, :user, :ip, NOW())", $params );
        }
        else
        {
            if($Online != $User)
            {
                $params = [
                    'user'  => $User,
                    'ip'    => $client_ip
                ];
                $this->DataBase->query('UPDATE `online_users` SET `time` = NOW(), `user` = :user WHERE `ip` = :ip', $params );
            }
            else
            {
                $this->DataBase->query("UPDATE `online_users` SET `time` = NOW() WHERE  `ip` = :ip", $param );
            }
        }

        $this->DataBase->query("DELETE FROM `online_users` WHERE `time` < SUBTIME(NOW(), '0 0:05:0')" );

        $_Param['date'] = date('m.Y');

        $_Attendance_ID = $this->DataBase->queryOneColumn('SELECT `id` FROM `site_visitors` WHERE `date` = :date', $_Param );

        if($_Attendance_ID)
        {   
            $_ParamU['id'] = $_Attendance_ID;
            $this->DataBase->query("UPDATE `site_visitors` SET `visits` = `visits` + 1 WHERE `id` = :id", $_ParamU );
        }
        else 
        {
            $this->DataBase->query("INSERT INTO `site_visitors`(`id`, `date`, `visits`) VALUES (NULL, :date, 1)", $_Param );
        }
    }
}
 ?>