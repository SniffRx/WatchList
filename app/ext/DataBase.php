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
use pdo;

class DataBase {

    /**
     * @since 0.2
     * @var array
     */
    private  $options = [];

    /**
     * @since 0.2
     * @var array
     */
    private  $db = [];

    /**
     * @since 0.2
     * @var array
     */
    private  $dns = [];

    /**
     * @since 0.2
     * @var array
     */
    protected $pdo = [];

    /**
     * Организация работы вэб-приложения с базой данных.
     *
     * @since 0.2
     */

    public function __construct() {

        // Проверка на основную константу.
        defined('AL') != true && die();

        $this->db = $this->get_db_options();

        // PDO Условия.
        $this->options = [
            PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT,
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ];

        //Проверка на поле [PORT]
        $this->db["PORT"] = empty($this->db["PORT"]) ? 3306 : $this->db["PORT"];

        // Сигнатура DNS.
        $this->dns = 'mysql:host=' . $this->db["HOST"] . ';port=' . $this->db["PORT"] . ';dbname=' . $this->db['DB'] . ';charset=utf8';
    }

    /**
     * Получение настроек базы данных.
     *
     * @since 0.2
     *
     * @return array                 Массив с настройками.
     */
    private function get_db_options() {
        $db = file_exists( SESSIONS . '/db.php' ) ? require SESSIONS . '/db.php' : null;
        return empty( $db ) ?  exit(require 'app/page/custom/install/index.php') : $db;
    }

    /**
     * Подключение к определенному моду базы данных.
     *
     * @since 0.2
     *
     * @return bool              существование бд
     */
    private function get_new_connect()
    {
        // Создаём подключение по PDO для определенной базы данных.
        $this->pdo = new PDO( $this->dns, $this->db['USER'], $this->db['PASS'], $this->options );
        return true;
    }

    /**
     * Подготовительный подзапрос.
     *
     * @since 0.2
     *
     * @param  string    $mod           Навание мода.
     * @param  int       $user_id       Номер пользователя.
     * @param  int       $db_id         Номер подключенной базы данных.
     * @param  string    $sql           SQL запрос.
     * @param  array     $params        Параметры.
     *
     * @return array                    Итог подготовленного подзапроса
     */
    public function inquiry( $sql, $params ) 
    {
        //Проверка на существования БД, и подключение если его нет
        if(!isset($this->pdo) || $this->pdo == null)
            $this->get_new_connect();

        $stmt = $this->pdo->prepare( $sql );

        if ( ! empty( $params ) ) {
            foreach ( $params as $key => $val ) {
                if ( is_int( $val ) ) {
                    $type = PDO::PARAM_INT;
                } else {
                    $type = PDO::PARAM_STR;
                }
                $stmt->bindValue( ':'.$key, $val, $type );
            }
        }

        $stmt->execute();
        return $stmt;
    }

    /**
     * Шаблон запроса отдающий массив с индексированными именами столбцов.
     *
     * @since 0.2
     *
     * @param  string  $mod           Навание мода.
     * @param  int     $user_id       Номер пользователя.
     * @param  int     $db_id         Номер подключенной базы данных.
     * @param  string  $sql           SQL запрос.
     * @param  array   $params        Параметры.
     *
     * @return array                  Возвращает результат SQL запроса.
     */
    public function query( $sql = null, $params = [] ) {
        $result = $this->inquiry( $sql, $params );
        if($result)
            return $result->fetch( PDO::FETCH_ASSOC );
    }

    /**
     * Шаблон запроса отдающий массив с индексированными номерами столбцов.
     *
     * @since 0.2
     *
     * @param  string  $mod           Навание мода.
     * @param  int     $user_id       Номер пользователя.
     * @param  int     $db_id         Номер подключенной базы данных.
     * @param  string  $sql           SQL запрос.
     * @param  array   $params        Параметры.
     *
     * @return array                  Возвращает результат SQL запроса.
     */
    public function queryNum( $sql = null, $params = [] ) {
        $result = $this->inquiry( $sql, $params );

        if($result)
            return $result->fetch( PDO::FETCH_NUM );
    }

    /**
     * Шаблон запроса отдающий массив со всеми строками.
     *
     * @since 0.2
     *
     * @param  string  $mod           Навание мода.
     * @param  int     $user_id       Номер пользователя.
     * @param  int     $db_id         Номер подключенной базы данных.
     * @param  string  $sql           SQL запрос.
     * @param  array   $params        Параметры.
     *
     * @return array                  Возвращает результат SQL запроса.
     */
    public function queryAll( $sql = null, $params = [] ) {
        $result = $this->inquiry( $sql, $params );

        if($result)
            return $result->fetchAll( PDO::FETCH_ASSOC );
    }

    /**
     * Шаблон запроса отдающий массив со всеми строками, парсирование ключа.
     *
     * @since 0.2
     *
     * @param  string  $mod           Навание мода.
     * @param  int     $user_id       Номер пользователя.
     * @param  int     $db_id         Номер подключенной базы данных.
     * @param  string  $sql           SQL запрос.
     * @param  array   $params        Параметры.
     *
     * @return array                  Возвращает результат SQL запроса.
     */
    public function query_all_key_pair( $sql = null, $params = [] ) {
        $result = $this->inquiry( $sql, $params );

        if($result)
            return $result->fetchAll( PDO::FETCH_KEY_PAIR );
    }

    /**
     * Шаблон запроса отдающий массив стобца.
     *
     * @since 0.2
     *
     * @param  string  $mod           Навание мода.
     * @param  int     $user_id       Номер пользователя.
     * @param  int     $db_id         Номер подключенной базы данных.
     * @param  string  $sql           SQL запрос.
     * @param  array   $params        Параметры.
     *
     * @return array                  Возвращает результат SQL запроса.
     */
    public function queryColumn( $sql = null, $params = [] ) {
        $result = $this->inquiry( $sql, $params );

        if($result)
            return $result->fetchColumn();
    }

    /**
     * Шаблон запроса отдающий данные одного стобца.
     *
     * @since 0.2
     *
     * @param  string  $mod           Навание мода.
     * @param  int     $user_id       Номер пользователя.
     * @param  int     $db_id         Номер подключенной базы данных.
     * @param  string  $sql           SQL запрос.
     * @param  array   $params        Параметры.
     *
     * @return array                  Возвращает результат SQL запроса.
     */
    public function queryOneColumn( $sql = null, $params = [] ) {
        $result = $this->inquiry( $sql, $params );

        if($result)
            return $result->fetch( PDO::FETCH_COLUMN );
    }

    /**
     * Возвращает ID последней вставленной строки.
     *
     * @since 0.2
     *
     * @param  string  $mod          Навание мода.
     * @param  int     $user_id      Номер пользователя.
     * @param  int     $db_id        Номер подключенной базы данных.
     *
     * @return int                   ID.
     */

    public function lastInsertId() 
    {
        //Проверка на существования БД, и подключение если его нет
        if(!isset($this->pdo))
            $this->get_new_connect();

            return $this->pdo->lastInsertId();
    }

    /**
     * "Разрыв соединения с базой данных".
     *
     * @since 0.2
     */
    public function __destruct() {
        unset( $this->dns );
        unset( $this->pdo );
    }
}