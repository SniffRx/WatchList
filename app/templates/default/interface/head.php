<header class="p-3 bg-dark text-white">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
                    <use xlink:href="#bootstrap"></use>
                </svg>
            </a>

            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="/" class="btn btn-primary nav-link px-2 text-secondary ms-2">Главная</a></li>
                <li><a href="/anime" class="btn nav-link px-2 text-white ms-2">Каталог</a></li>
          <!--<li><a href="#" class="btn nav-link px-2 text-white ms-2">Новости</a></li> -->
                <li><a href="/about" class="btn nav-link px-2 text-white ms-2">О сайте</a></li>
                <!-- <li><a href="#" class="btn nav-link px-2 text-white ms-2">Случайное аниме</a></li> -->
            </ul>

            <?php //print_r($_SESSION);?>

            <?php if( ! empty( $_SESSION['logged'] ) ):?>
            <?php 

            if(isset($_SESSION['usertype']) && !empty($_SESSION['usertype']) && $_SESSION['usertype'] == 3):?> <a
                type="button" class="btn btn-primary mx-2" href="<?php echo $Main->main['site'].'adminpanel/';?>"><i class="fa-duotone fa-people-pulling"></i></a>
            <?php endif;?>

            <a type="button" class="btn btn-primary mx-2"
                href="<?php echo $Main->main['site'].'profile/'.$_SESSION['logged'];?>"><i
                    class="fa-solid fa-user"></i></a>

            <span class="_logout">
                <a type="button" class="btn btn-primary" href="<?php echo $Main->main['site']?>/?auth=logout"><i
                        class="fa-solid fa-door-open"></i></a>
            </span>

            <?php else:?>
            <div class="text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#login">Войти</button>
                <a type="button" class="btn btn-primary" href="/register">Регистрация</a>
            </div>
            <?php endif;?>
        </div>
    </div>
</header>

<?php if( empty( $_SESSION['logged'] ) ):?>
<!-- Modal -->
<div id="login" class="modal fade" tabindex="-1" aria-labelledby="Auth" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="Auth">Авторизация</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center form-signin">
                <form id="log_in" enctype="multipart/form-data" method="post">
                    <div class="form-floating">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user"></i></span>
                            <input name="_login" type="text" class="form-control" placeholder="Username"
                                aria-label="Username" aria-describedby="basic-addon1" value="">
                        </div>
                    </div>
                    <div class="form-floating">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-key"></i></span>
                            <input name="_pass" type="password" class="form-control" placeholder="Password"
                                aria-label="Password" aria-describedby="basic-addon1" autocomplete="on" value="">
                        </div>
                    </div>
                    <input class="w-100 btn btn-lg btn-primary" name="log_in" type="submit" value="LogIn">
            </div>
            </form>
        </div>
    </div>
</div>
</div>
<?php endif;?>