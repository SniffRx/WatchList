<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?php echo $Modules->get_page_description()?>">
    <meta property="og:description" content="<?php echo $Modules->get_page_description()?>">
    <meta name="author" content="https//vk.com/SniffRx">
    <link rel="icon" href="<?php echo $Main->main['site'] ?>favicon.ico" type="image/x-icon">
    <title><?php echo $Modules->get_page_title()?></title>
    <meta property="og:title" content="<?php echo $Modules->get_page_title()?>">
    <meta name="theme-color" media="(prefers-color-scheme: dark)" content="black">
    <!--meta property="og:image" content="<?php //echo $Modules->get_page_image()?>">
    <link rel="image_src" href="<?php //echo $Modules->get_page_image()?>">
    <meta name="twitter:image" content="<?php //echo $Modules->get_page_image()?>"-->

    <link rel="stylesheet" type="text/css" href="<?php echo $Main->main['site'] . 'cache/assets/css/FontAwesome'?>.css">
    <script src="<?php echo $Main->main['site'] ?>cache/assets/js/vendors/jquery/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

    <?php if( empty( $Main->main['enable_css_cache'] ) ) : ?>
        <?php for ( $style = 0, $style_s = sizeof( $Modules->css_library ); $style < $style_s; $style++ ):?>
    <link rel="stylesheet" type="text/css" href="<?php echo $Main->main['site'] . $Modules->css_library[ $style ]?>">
<?php   endfor;
if( ! empty( $Modules->module_init['page'][ $Modules->route ]['css'] ) ):
    for ( $css = 0, $css_s = sizeof( $Modules->module_init['page'][ $Modules->route ]['css'] ); $css < $css_s; $css++ ):?>
    <link rel="stylesheet" type="text/css" href="<?php echo $Main->main['site'] . 'app/modules/' . $Modules->module_init['page'][ $Modules->route ]['css'][ $css ]['name'] . '/assets/css/' . $Modules->module_init['page'][ $Modules->route ]['css'][ $css ]['type'] . '.css'?>">
    <?php if(isset($Modules->template_modules)):
        if(isset($Modules->template_modules[ $Modules->module_init['page'][ $Modules->route ]['css'][ $css ]['name'] ][ 'css' ])) { ?>
            <link rel="stylesheet" href="<?php echo $Main->main['site'] . 'app/templates/' . $Main->main['theme'] . '/modules/' . $Modules->module_init['page'][ $Modules->route ]['css'][ $css ]['name'] . '/dop.css'; ?>">
        <?php }
    endif;
  endfor;
endif;
else: ?>
    <link rel="stylesheet" type="text/css" href="<?php echo ! file_exists( ASSETS_CSS . '/generation/style_generated.min.ver.' . $Modules->actual_library['actual_css_ver'] . '.css' ) ? $Main->main['site'] . 'app/templates/'.$Main->main['theme'].'css/style' :  $Main->main['site'] . 'cache/assets/css/generation/style_generated.min.ver.' . $Modules->actual_library['actual_css_ver']?>.css">
<?php endif; ?>
    <link rel="stylesheet" type="text/css" href="<?php echo $Main->main['site'] . '/cache/assets/css/animations/hover-min'?>.css">
    <style>
<?php echo $Graphics->get_css_color_palette()?>
</style>
</head>

<?php 
 //$Modules->text_test_out(); - метод дебага
?>

<?php
// Дополнительный пулл под модули, которые должны быть объявлены на каждой странице - afterhead
if( ! empty( $Modules->module_init['interface_always']['afterhead'] ) ):
    for ( $module_id = 0, $c_mi = sizeof( $Modules->module_init['interface_always']['afterhead'] ); $module_id < $c_mi; $module_id++ ):
        $file = MODULES . $Modules->module_init['interface_always']['afterhead'][ $module_id ]['name'] . '/forward/interface_always.php';
        file_exists( $file ) && require $file;
    endfor;
endif;

// Подгрузка данных из модулей которые относятся к интерфейсу - afterhead
if( ! empty( $Modules->module_init['page'][ $Modules->route ]['interface']['afterhead'] ) ):
    for ( $module_id = 0, $c_mi = sizeof( $Modules->module_init['page'][ $Modules->route ]['interface']['afterhead'] ); $module_id < $c_mi; $module_id++ ):
        $file = MODULES . $Modules->module_init['page'][ $Modules->route ]['interface']['afterhead'][ $module_id ] . '/forward/interface.php';
        file_exists( $file ) && require $file;
    endfor;
endif?>
<main>