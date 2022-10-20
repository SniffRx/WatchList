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

class Auth {

    /**
     * @since 0.2
     * @var array
     */
    public    $user_auth = [];

    /**
     * @since 0.2
     * @var array
     */
    public    $base_info = [];

    /**
     * @since 0.2
     * @var array
     */
    public    $lastconnect = [];

    /**
     * @since 0.2
     * @var int
     */
    private   $admins = 0;

     /**
     * @since 0.2
     * @var object
     */
    public    $Main;

    /**
     * @since 0.2
     * @var object
     */
    public    $DataBase;

    /**
     * Организация работы вэб-приложения с авторизацией.
     *
     * @param object $Main
     * @param object $DataBase
     *
     * @since 0.2
     */
    function __construct( $Main, $DataBase ) {

        defined('AL') != true && die();

        // Импорт основного класса.
        $this->Main = $Main;

        // Импорт класса отвечающего за работу с базой данных.
        $this->DataBase = $DataBase;

        if( isset( $_SESSION['logged'])) :

            // Проверяем сессию
            $Main->main['session_check'] === 1 && $this->check_session();

            // Проверяем пользователя на привилегии
            ! isset($_SESSION['user_admin']) && $this->check_session_admin();

            // Получение информации о авторизованном пользователе.
            $this->get_authorization_sidebar_data();
        endif;

        // Работа с авторизацией
        isset( $_POST['log_in'] ) && ! empty( $_POST['_login'] ) && ! empty( $_POST['_pass'] ) && $this->authorization_check();

        // Выход пользователя из аккаунта.
        if(isset( $_GET["auth"] ) && $_GET["auth"] == 'logout') {
            if ( ! empty( $_GET["auth"] ) && $_GET["auth"] == 'logout' ) {
                session_unset();
                session_destroy();
                setcookie('session', null, 1);
                //header('Location: ' . $this->Main->main['site']);
                header("Refresh:0");
                if ( ! headers_sent() ) {?>
                    <script type="text/javascript">window.location.href="<?php echo $this->Main->main['site'] ?>";</script>
                    <noscript><meta http-equiv="refresh" content="0;url=<?php echo $this->Main->main['site'] ?>" /></noscript>
                    <?php exit;
                }
            }
        }

        //П роверка куки на авторизацию ( Херовый метод :( )
        ( $Main->main['auth_cock'] == 1 && !empty($_SESSION) && empty($_COOKIE['session']) ) && $this->check_cookie();

        ( $Main->main['auth_cock'] == 1 && empty($_SESSION) && !empty($_COOKIE['session']) ) && $this->auth_cookie();

        if( $Main->main['auth_cock'] == 0 && !empty( $_COOKIE['session'] ) ) unset($_COOKIE['session']);
    }

    // Запись в куки данных о сессии
    private function check_cookie()
    {
        foreach ($_SESSION as $key => $val)
        {
            setcookie("session[".$key."]", $val, strtotime('+1 day'));
        }
    }

    // Авторизация пользователя с помощью куки
    private function auth_cookie()
    {
        $_SESSION = $_COOKIE['session'];
        //header("Location: ".$this->Main->main['site']);
        header("Refresh:0");
    }

    // Проверка авторизованного пользователя на принадлежность ко списку администраторов.
    public function check_session_admin() {
        $result = $this->DataBase->query("SELECT `usertype` FROM `users` WHERE `login`= '{$_SESSION['logged']}' LIMIT 1" );
        if( ! empty( $result ) ):
            $_SESSION['usertype'] = $result['usertype'];
        endif;
    }

    // Проверка печенек авторизованного пользователя.
    public function check_session() {
        if ( $_SESSION['USER_AGENT'] != $_SERVER['HTTP_USER_AGENT'] || $_SESSION['REMOTE_ADDR'] != $this->Main->get_client_ip_cdn() ):
            session_unset() && session_destroy() && header("Location: ".$this->Main->main['site']);
        endif;
    }

    // Авторизация администратора по логину и паролю.
    public function authorization_check() {

        $param = ['login' => action_text_clear( $_POST['_login'] )];

        $result = $this->DataBase->query("SELECT `id`, `login`, `usertype`, `password`, `active` FROM `users` WHERE `login` = :login", $param);
        if(password_verify(action_text_clear($_POST['_pass']), $result['password']) && $result['active'] == 1) {
            if ( ! empty( $result ) ):

                session_regenerate_id();

                $_SESSION["logged"] = $result['login'];

                $_SESSION['userid'] = $result['id'];

                // Пользователь. Заголовок User-Agent.
                $_SESSION['USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];

                // Пользователь. IP.
                $_SESSION['REMOTE_ADDR'] = $this->Main->get_client_ip_cdn();

                // Пользователь. Административная инфомация.
                $_SESSION['usertype'] = $result['usertype'];

                //return true; //return ???
            endif;
        } else {$result = ""; }//return false; //return ???

        // Обновление страницы.
        //header("Location: ".$this->Main->main['site']);
        header("Refresh:0");
    }

    // Получение информации о авторизованном пользователе для вывода данных в боковую панель.
    public function get_authorization_sidebar_data() {

                // Запрос о получении информации об авторизовавшемся пользователе.
                $this->base_info = $this->DataBase->query("SELECT `avatar`, `login`, `email`, `usertype`, `birthday`, `gender`, 'registred' FROM `users` WHERE `login` LIKE '%{$_SESSION['logged']}%' LIMIT 1");

                // Если Пользователь  находится в таблице, заполняем итоговый массив.
                if ( ! empty( $this->base_info ) ):
                    // Базовая информация о пользователе.
                    $this->user_auth[] = $this->base_info;
                endif;
        
        // При отсутствии пользователя в таблицах, собираем - массив исключение.
        if ( empty( $this->user_auth[0] ) ):
            // Информация о пользователе.
            $this->user_auth[0] = ['login' => '_Unknown', 'lastconnect' => ''];
        endif;
    }

}