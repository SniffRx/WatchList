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

class Graphics {

    /**
     * @since 0.0.1
     * @var object
     */
    public    $Main;

    /**
     * @since 0.0.1
     * @var object
     */
    public    $Modules;

    /**
     * @since 0.0.1
     * @var object
     */
    public    $DataBase;

    /**
     * @since 0.0.1
     * @var object
     */
    public    $Auth;

    /**
     * @since 0.2
     * @var object
     */
    public    $Notifications;

    /**
     * @since 0.0.1
     * @var object
     */
    public    $Router;

     /**
     * Инициализация графической составляющей вэб-интерфейса с подгрузкой модулей.
     * 
     * @param object $Translate
     * @param object $General
     * @param object $Modules
     * @param object $DataBase
     * @param object $Auth
     * @param object $Notifications
     *
     * @since 0.0.1
     */

    function __construct( $Main, $Modules, $DataBase, $Auth, $Notifications, $Router ) {

        // Проверка на основную константу.
        defined('AL') != true && die();

        // Присвоение глобальных объектов.
        $Graphics       = $this;
        $this->Main     = $Main;
        $this->Modules  = $Modules;
        $this->DataBase = $DataBase;
        $this->Auth     = $Auth;
        $this->Notifications = $Notifications;
        $this->Router   = $Router;

        (empty($Modules->module_init['page'][ $Modules->route ]) && !isset($_GET['auth'])) && get_iframe("404", "Извините, данной страницы не существует!");

        $module_route_init_load = [];
        if(isset($Modules->module_init['page'][ $Modules->route ]['data'] )) $module_route_init_load = $Modules->module_init['page'][ $Modules->route ]['data'];

        // Подгрузка данных из модулей которые не относятся к интерфейсу и должны быть получены до начала рендера страницы.
        for ( $module_id = 0, $c_mi = sizeof( $module_route_init_load ); $module_id < $c_mi; $module_id++ ):
            $file = MODULES . $Modules->module_init['page'][ $Modules->route ]['data'][ $module_id ] . '/forward/data.php';
            file_exists( $file ) && require $file;
        endfor;

        // Дополнительный поток под модули, которые должны задействовать ядро на постоянной основе, а не локально.
        if( ! empty( $Modules->module_init['data_always'] ) ):
            for ( $module_id = 0, $c_mi = sizeof( $Modules->module_init['data_always'] ); $module_id < $c_mi; $module_id++ ):
                $file = MODULES . $Modules->module_init['data_always'][ $module_id ] . '/forward/data_always.php';
                file_exists( $file ) && require $file;
            endfor;
        endif;

        // Рендер блока - Head
        require PAGE . 'head.php';

        //Рендер кастомного head
        (file_exists(TEMPLATES . $Main->main['theme'] . '/interface/head.php')) && require TEMPLATES . $Main->main['theme'] . '/interface/head.php';

        //Рендер кастомного тела страницы
        (file_exists(TEMPLATES . $Main->main['theme'] . '/interface/container.php')) && require TEMPLATES . $Main->main['theme'] . '/interface/container.php';

        // Дополнительный пулл под модули, которые должны быть объявлены на каждой странице - afternavbar
        if( ! empty( $Modules->module_init['interface_always']['afternavbar'] ) ):
            for ( $module_id = 0, $c_mi = sizeof( $Modules->module_init['interface_always']['afternavbar'] ); $module_id < $c_mi; $module_id++ ):
                $file = MODULES . $Modules->module_init['interface_always']['afternavbar'][ $module_id ]['name'] . '/forward/interface_always.php';
                file_exists( $file ) && require $file;
            endfor;
        endif;

        // Подгрузка данных из модулей которые относятся к интерфейсу - afternavbar
        if( ! empty( $Modules->module_init['page'][ $Modules->route ]['interface']['afternavbar'] ) ):
            for ( $module_id = 0, $c_mi = sizeof( $Modules->module_init['page'][ $Modules->route ]['interface']['afternavbar'] ); $module_id < $c_mi; $module_id++ ):
                $file = MODULES . $Modules->module_init['page'][ $Modules->route ]['interface']['afternavbar'][ $module_id ] . '/forward/interface.php';
                file_exists( $file ) && require $file;
            endfor;
        endif;

        // Рендер блока - Footer
        require PAGE . 'footer.php';
    }
    /**
     * Получение и вывод цветовой палитный сайта.
     *
     * @since 0.0.1
     * 
     * @return string         Цветовая плалитра ( CSS / Style / ROOT )
     */
    public function get_css_color_palette() {
        return ':root' . str_replace( '"', '', str_replace( '",', ';', file_get_contents_fix ( 'app/templates/' . $this->Main->main['theme'] . '/colors.json' ) ) ) .  ' ';
    }
}
?>