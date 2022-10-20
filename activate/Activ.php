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

namespace activate;

class Activ
{
    /**
     * @since 0.2
     * @var string
     */
    public $activation_code;

    /**
     * @since 0.2
     * @var string
     */
    public $email;

    /**
     * @since 0.2
     * @var object
     */
    public $DataBase;

    /**
     * Организация работы вэб-приложения с авторизацией.
     *
     * @param object $DataBase
     *
     * @since 0.2
     */
    function __construct($DataBase) {

        defined('AL') != true && die();

        //Импорт класса отвечающего за работу с базой данных.
        $this->DataBase = $DataBase;

        //Присваиваем значения, которое получили
        $email = $_GET['email'];
        $activation_code = $_GET['activation_code'];

        //Проверка на существование $email и $activation_code
        if(is_string($email) && !empty($email) && is_string($activation_code) && !empty($activation_code)) {
            $result = $this->find_unverified_user($activation_code, $email);
            // Если пользователь есть в базе и активация прошла успешно
            if ($result && $this->activate_user($result['id'])) {
                echo 'You account has been activated successfully. Please login here. <a href="/">Home</a>';
            }
        }
    }

    //Удаление пользователя по id
    public function delete_user_by_id(int $id, int $active = 0) {
        $params = ['id' => $id, 'active' => $active];
        $result = $this->DataBase->query("DELETE FROM `users` WHERE `id` = :id and active=:active", $params);
        echo 'The activation link is not valid, please register again.';
    }

    //Поиск пользователя по активации почты
    public function find_unverified_user(string $activation_code, string $email) {

        //Запрос на проверку активации почты
        $param = ['email'=>$email];
        $result = $this->DataBase->query("SELECT `id`, `activation_code`, `activation_expiry` < now() as expired FROM `users` WHERE `active` = 0 AND `email`=:email", $param);

        if ($result) {
            // Код истек, удаление активного пользователя с истекшим кодом активации
            if ((int)$result['expired'] === 1) {
                $this->delete_user_by_id($result['id']);
                return null;
            }
            // Проверка кода
            if (password_verify($activation_code, $result['activation_code'])) {
                return $result;
            }
        }

        return null;
    }

    //Активация пользователя
    public function activate_user(int $user_id) {
        $param = ['id' => $user_id];
        $result = $this->DataBase->query("UPDATE `users` SET `active` = 1, `activated_at` = CURRENT_TIMESTAMP WHERE `id`=:id", $param);
        echo 'You account has been activated successfully. Please login here. <a href="/">Home</a>';
    }
}
?>