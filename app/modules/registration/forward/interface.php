<?php isset( $_SESSION['logged'] ) && header('Location: ' . $this->Main->main['site']);

if(isset($_POST['register_user']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) &&
   !empty($_POST['register_user']) && !empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']))
{
    echo "<script>window.onload = function() {note({
        content: 'Зайдите на почту!',
        type: 'success',
        time: 5
    });};</script>";
}?>

<?php if (count($Register->errors) > 0): ?>
<?php foreach ($Register->errors as $error): ?>
<?php echo "<script>window.onload = function() {note({content: '".$error."',type: 'error',time: 5});};</script>"; ?>
<?php endforeach;?>
<?php endif;?>



<div class="container pt-5">
    <div class="row">
        <div class="text-center form-signin">
            <form enctype="multipart/form-data" method="post">
                <h1 class="h3 mb-3 fw-normal text-light">SignUp</h1>
                <div class="form-floating">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user"></i></span>
                        <input name="username" type="text" class="form-control" placeholder="Username"
                            aria-label="Username" aria-describedby="basic-addon1">
                    </div>
                </div>
                <div class="form-floating">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-at"></i></span>
                        <input name="email" type="email" class="form-control" placeholder="Email" aria-label="Email"
                            aria-describedby="basic-addon1">
                    </div>
                </div>
                <div class="form-floating">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-key"></i></span>
                        <input name="password" type="password" class="form-control" placeholder="Password"
                            aria-label="Password" aria-describedby="basic-addon1" autocomplete="on">
                    </div>
                </div>
                <!--button class="w-100 btn btn-lg btn-primary" type="submit">SignUp</button-->
                <input class="w-100 btn btn-lg btn-primary" type="submit" name="register_user" Value="SignUp">
            </form>
        </div>
    </div>
</div>

<?php 

//$DataBase->get_new_connect();
?>

<?php //print_r($DataBase->query('SELECT `login` from users'));?>
<?php //print_r($DataBase->query('SELECT `login` from users WHERE `login` = "'.$_POST['username'].'"'));?>