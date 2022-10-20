
<div class="container-fluid footer">
    <footer class="py-3 my-4">
        <ul class="nav justify-content-center border-bottom pb-3 mb-3">
            <li class="nav-item"><a class="nav-link px-2 text-muted" href="" target="_blank">Политика
                    конфиденциальности</a></li>
            <li class="nav-item"><a class="nav-link px-2 text-muted" href="" target="_blank">Оферта</a></li>
            <li class="nav-item"><a class="nav-link px-2 text-muted" href="" target="_blank">Соц. сети</a></li>
        </ul>
        <p class="text-center text-muted">Copyright © 2022 - <?php echo Date('Y');?> | <a href="//sniffrx.ru"
                target="_blank">SniffRx</a> | Allrights reserved | version: #<?php echo VERSION?> | DESIGN BY <a
                href="https://vk.com/sniffrx" target="_blank">SniffRx</a></p>
    </footer>
</div>
</main>
<script src="<?php echo $Main->main['site'] ?>cache/assets/js/owl.carousel.js"></script>
<script src="<?php echo $Main->main['site'] ?>cache/assets/js/owl.animate.js"></script>
<script src="<?php echo $Main->main['site'] ?>cache/assets/js/owl.autoheight.js"></script>
<script src="<?php echo $Main->main['site'] ?>cache/assets/js/owl.autorefresh.js"></script>
<script src="<?php echo $Main->main['site'] ?>cache/assets/js/owl.lazyload.js"></script>
<script src="<?php echo $Main->main['site'] ?>cache/assets/js/vendors/jquery/jquery-ui.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous">
</script>

<script>
//let domain = '<?php //echo $Main->main['site'] ?>';
</script>
<?php if( empty( $Main->main['enable_js_cache'] ) ) :
        for ( $js = 0, $js_s = sizeof( $Modules->js_library ); $js < $js_s; $js++ ):?>
<script src="<?php echo $Main->main['site'] . $Modules->js_library[ $js ]?>"></script>
<?php   endfor;
if( ! empty( $Modules->module_init['page'][ $Modules->route ]['js'] ) ):
    for ( $js = 0, $js_s = sizeof( $Modules->module_init['page'][ $Modules->route ]['js'] ); $js < $js_s; $js++ ):?>
<script
    src="<?php echo $Main->main['site'] . 'app/modules/' . $Modules->module_init['page'][ $Modules->route ]['js'][ $js ]['name'] . '/assets/js/' . $Modules->module_init['page'][ $Modules->route ]['js'][ $js ]['type'] . '.js'?>">
</script>
<?php if(isset($Modules->template_modules)):
    if(isset($Modules->template_modules[ $Modules->module_init['page'][ $Modules->route ]['js'][ $css ]['name'] ][ 'js' ])) { ?>
<script
    src="<?php echo $Main->main['site'] . 'app/templates/' . $Main->main['theme'] . '/modules/' . $Modules->module_init['page'][ $Modules->route ]['js'][ $css ]['name'] . '/dop.js'; ?>">
</script>
<?php }
    endif;endfor;
endif;
      else:?>
<script
    src="<?php echo ! file_exists( ASSETS_JS . '/generation/app_generated.min.ver.' . $Modules->actual_library['actual_js_ver'] . '.js' ) ? $Main->main['site'] . 'cache/assets/js/app' :  $Main->main['site'] . 'cache/assets/js/generation/app_generated.min.ver.' . $Modules->actual_library['actual_js_ver']?>.js">
</script>
<?php endif;

if(!empty($Main->notes)):
    for($i = 0; $i < sizeof($Main->notes); $i++)
        echo "<script>note({content: '".$Main->notes[$i]['content']."',type: '".$Main->notes[$i]['status']."',time: ".$Main->notes[$i]['time']."});</script>";
endif;

// Дополнительный пулл под модули, которые должны быть объявлены на каждой странице - inbodyend
if( ! empty( $Modules->module_init['interface_always']['inbodyend'] ) ):
    for ( $module_id = 0, $c_mi = sizeof( $Modules->module_init['interface_always']['inbodyend'] ); $module_id < $c_mi; $module_id++ ):
        $file = MODULES . $Modules->module_init['interface_always']['inbodyend'][ $module_id ]['name'] . '/forward/interface_always.php';
        file_exists( $file ) && require $file;
    endfor;
endif;

// Подгрузка данных из модулей которые относятся к интерфейсу - inbodyend
if( ! empty( $Modules->module_init['page'][ $Modules->route ]['interface']['inbodyend'] ) ):
    for ( $module_id = 0, $c_mi = sizeof( $Modules->module_init['page'][ $Modules->route ]['interface']['inbodyend'] ); $module_id < $c_mi; $module_id++ ):
        $file = MODULES . $Modules->module_init['page'][ $Modules->route ]['interface']['inbodyend'][ $module_id ] . '/forward/interface.php';
        file_exists( $file ) && require $file;
    endfor;
endif?>
</body>
<?php
// Дополнительный пулл под модули, которые должны быть объявлены на каждой странице - afterbody
if( ! empty( $Modules->module_init['interface_always']['afterbody'] ) ):
    for ( $module_id = 0, $c_mi = sizeof( $Modules->module_init['interface_always']['afterbody'] ); $module_id < $c_mi; $module_id++ ):
        $file = MODULES . $Modules->module_init['interface_always']['afterbody'][ $module_id ]['name'] . '/forward/interface_always.php';
        file_exists( $file ) && require $file;
    endfor;
endif;

// Подгрузка данных из модулей которые относятся к интерфейсу - afterbody
if( ! empty( $Modules->module_init['page'][ $Modules->route ]['interface']['afterbody'] ) ):
    for ( $module_id = 0, $c_mi = sizeof( $Modules->module_init['page'][ $Modules->route ]['interface']['afterbody'] ); $module_id < $c_mi; $module_id++ ):
        $file = MODULES . $Modules->module_init['page'][ $Modules->route ]['interface']['afterbody'][ $module_id ] . '/forward/interface.php';
        file_exists( $file ) && require $file;
    endfor;
endif?>

</html>
<script>
$(document).ready(function() {
    $(".owl-carousel").owlCarousel({
        margin: 25,
        autoWidth: true,
        autoHeight: false,
        checkVisible: true,
    });
});
</script>

<div id="scrollUp" class="scrollUp" style="position: fixed; z-index: 1000; cursor: pointer;"><i
        class="fa-thin fa-hand-point-up"></i> Наверх</div>

<script>
$(document).ready(function() {
    var button = $('#scrollUp');
    $(window).scroll(function() {
        if ($(this).scrollTop() > 300) {
            button.fadeIn();
        } else {
            button.fadeOut();
        }
    });
    button.on('click', function() {
        $('body, html').animate({
            scrollTop: 0
        }, 1);
        return false;
    });
});
</script>

<style>
.scrollUp {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    padding: 7px 15px 7px 10px;
    left: 20px;
    bottom: 50px;
    background-color: rgba(51, 51, 51, .88);
    border: 1px solid rgba(51, 51, 51, .88);
    color: #fff;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    user-select: none;
    border-radius: 2px;
    display: none;
    /*position: fixed;
    right: 20px;
    bottom: 100px;			
    color: #fff;
    background-color: #000;
    text-align: center;
    font-size: 30px;
    padding: 15px;*/
    transition: .3s;
}

.scrollUp:hover {
    background-color: #333;
}

@media (max-width: 991px) {
    .scrollUp {
        display: none !important;
    }
}
</style>

<script>
[...document.querySelectorAll('[data-bs-toggle="tooltip"]')]
  .forEach(el => new bootstrap.Tooltip(el))
</script>