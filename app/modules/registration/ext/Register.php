<?php

namespace app\modules\registration\ext;

class Register
{
    public $errors = [];
    public $Modules;
    public $DataBase;

    function __construct($Modules, $DataBase) {
        defined('AL') != true && die();

        $this->Modules = $Modules;
        $this->DataBase = $DataBase;
    }

    public function generate_activation_code(): string
    {
        return bin2hex(random_bytes(16));
    }

    public function is_get_request(): bool
    {
        return strtoupper($_SERVER['REQUEST_METHOD']) === 'GET';
    }

    public function session_flash(...$keys): array
    {
        $data = [];
        foreach ($keys as $key) {
            if (isset($_SESSION[$key])) {
                $data[] = $_SESSION[$key];
                unset($_SESSION[$key]);
            } else {
                $data[] = [];
            }
        }
        return $data;
    }

    public function is_post_request(): bool
    {
        return strtoupper($_SERVER['REQUEST_METHOD']) === 'POST';
    }

    public function check_user() {

        if(isset($_POST['register_user'])) {
            $username = stripcslashes(strip_tags($_POST['username']));
            $email = stripcslashes(strip_tags($_POST['email']));
            $password = stripcslashes(strip_tags($_POST['password']));


            if(isset($username) && !empty($username)) {
                if(strlen($username) >= 5) {
                    if (preg_match('/^[a-zA-Z0-9]+$/', $username) == 0) {
                        $this->errors['username'] = "Username is not valid!";return false;
                    } else {
                        $result = $this->DataBase->query("SELECT * FROM users WHERE login='$username' LIMIT 1");
                        if($result) {$this->errors['username'] = "Username already exists";return false;}}
                    } else {$this->errors['username'] = "Username must be more 5 characters.";return false;}
                } else { $this->errors['username'] = "Username not found"; return false;}

                if(isset($email) && !empty($email)) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {$this->errors['email'] = "Email is not valid";return false;} else {
                    $result = $this->DataBase->query("SELECT * FROM users WHERE email='$email' LIMIT 1");
                    if ($result) {$this->errors['email'] = "Email already exists";return false;}}
                } else {$this->errors['email'] = 'Email not found';}

            if(isset($password) && !empty($password)) {
                if (strlen($password) > 20 || strlen($password) < 5) {
                    $this->errors['password'] = "Password must be between 5 and 20 characters long!";return false;
                }
            } else {$this->errors['password'] = "Password not found";return false;}

            return $this->action_registration_user();
        }
    }

    public function action_registration_user(int $expiry = 1 * 24  * 60 * 60) {

        $activation_code = $this->generate_activation_code();
        $username           = stripcslashes(strip_tags($_POST['username']));
        $email              = stripcslashes(strip_tags($_POST['email']));
        $password           = stripcslashes(strip_tags($_POST['password']));
        $user_password_hash = password_hash($password, PASSWORD_BCRYPT);

        if(empty($username) || empty($email) || empty($password)) return null;

        $params = [
            'username'  =>  $username,
            'email'     =>  $email,
            'password'  => $user_password_hash,
            'activation_code' => password_hash($activation_code, PASSWORD_DEFAULT),
            'activation_expiry' => date('Y-m-d H:i:s',  time() + $expiry)
        ];

        $this->DataBase->query("INSERT INTO `users` (login, email, password, activation_code, activation_expiry) VALUES (:username, :email, :password, :activation_code, :activation_expiry)", $params);
    
        $this->send_activation_email($username, $email, $activation_code);
    }

    public function send_activation_email(string $username, string $email, string $activation_code): void {
        // create the activation link
        $activation_link = APP_URL . "activate.php?email=$email&activation_code=$activation_code";

        // set email subject & body
        $subject = 'Please activate your account';
        $message = <<<MESSAGE
                Hi $username, Please click the following link to activate your account: $activation_link
                MESSAGE;
        // email header
        $header = "From:" . SENDER_EMAIL_ADDRESS;

        // send the email
        mail($email, $subject, nl2br($message), $header);
    }
}