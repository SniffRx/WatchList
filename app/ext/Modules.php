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

class Modules {

    /**
     * @since 0.0.1
     * @var array
     */
    public $modules = [];

    /**
     * @since 0.0.1
     * @var int
     */
    public $modules_count = 0;

    /**
     * @since 0.0.1
     * @var int
     */
    public $templates_count = 0;

    /**
     * @since 0.0.1
     * @var array
     */
    public $module_init = [];

    /**
     * @since 0.0.1
     * @var int
     */
    public $module_init_page_count = 0;

    /**
     * @since 0.0.1
     * @var array
     */
    public $user_info = [];

    /**
     * @since 0.0.1
     * @var array
     */
    public $scan_modules = [];

    /**
     * @since 0.0.1
     * @var array
     */
    public $scan_templates = [];

    /**
     * @since 0.0.1
     * @var array
     */
    public $template_modules = [];

    /**
     * @since 0.0.1
     * @var array
     */
    public $actual_library = [];

    /**
     * @since 0.0.1
     * @var array
     */
    public $css_library = [];

    /**
     * @since 0.0.1
     * @var array
     */
    public $js_library = [];

    /**
     * @since 0.0.1
     * @var string
     */
    public $page_title = '';

    /**
     * @since 0.0.1
     * @var string
     */
    public $page_description = '';

    /**
     * @since 0.0.1
     * @var object
     */
    public  $Main;

    /**
     * @since 0.2.122
     * @var object
     */
    public $Notifications;

    /**
     * @since 0.0.1
     * @var object
     */
    public    $Router;

     /**
     * @since 0.0.1
     * @var string
     */
    public  $route;

    /**
     * Организация работы вэб-приложения с модулями.
     *
     * @param object $Main
     * @param object $Notifications
     *
     * @since 0.0.1
     */
    function __construct( $Main, $Notifications, $Router ) {

        // Проверка! (Мы находимся в программе?)
        defined('AL') != true && die();

        $this->Main = $Main;
        $this->Notifications = $Notifications;
        $this->Router  = $Router;

        //Проверка на iframe
        //(!empty($_GET['code']) && !empty($_GET['description'])) && exit(require PAGE_CUSTOM . '/error/index.php');

        // Получение кэшированного списка модулей.
        $this->modules = $this->get_modules();

        // Подсчёт количества модулей.
        $this->modules_count = sizeof( $this->modules );

        // Получение списка инициализвации модулей в определенном порядке.
        $this->module_init = $this->get_module_init();

        //Получение количества из списка модулей
        $this->module_init_page_count = sizeof( $this->module_init['page'] );

        // Получение кеша всех шаблонов
        $this->templates = $this->get_templates_init();

        // Сканирование папки с модулями.
        $this->scan_templates = array_diff( scandir( TEMPLATES, 1 ), array( '..', '.', '_disabled' ) );

        $this->template_modules = $this->get_cache_template_modules();

        // Сканирование дополнительных модулей в шаблоне
        (file_exists(TEMPLATES . $Main->main['theme'] . 'modules/')) && $this->get_template_modules();

         // Подсчёт количества модулей.
         $this->templates_count = sizeof( $this->scan_templates );

        //Добавление новых роутов
        $this->AddRoutes();

        // Тупо сохраняем пока
        $match = $Router->match()['target'] ?? $Router->SearchRoute();

        $this->route = ( strpos( $match, "?language=" ) !== false ) ? "home" : $match;

        $basename = parse_url($Main->main['site'], PHP_URL_PATH);

        (empty( $basename ) && empty( $this->route ) || $_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == $basename) && $this->route = 'home';

        // Библиотека актуальности.
        $this->actual_library = $this->get_actual_library();

        isset( $_SESSION['user_admin'] ) && $this->check_actual_modules_list();

        // Проверка JS файлов.
        $this->check_generated_js();

        // Проверка таблици стилей.
        $this->check_generated_style();

        // Сканирование и добавление новых шаблонов
        $this->check_actual_templates();

        // Проверка для роутера страниц
        ! empty( $checkroute ) && empty( $this->module_init['page'][ $this->route ] ) && get_iframe( '009', 'Данная страница не существует' );

        $_SESSION['page_redirect'] = $this->route;

        $this->check_copy();
    }

    // Хз зачем я создал эту функцию, просто для получения кеша определенных модулей
    public function get_cache_template_modules()
    {
        if(file_exists(SESSIONS . 'templates_modules_cache.php'))
            return require SESSIONS . 'templates_modules_cache.php';
    }

    /*public function text_test_out() { // Метод дебага

        $count_test = $this->modules;
        $count_test2 = $this->module_init;

        return var_dump($count_test).var_dump($this->module_init);
    }*/

/**
     * Добавление шаблонов в отдельное кеширование.
     *
     * @since 0.0.1
     *
     * @param string $module   Корневое название модуля.
     *
     * @return $result         Возвращает кэш модуля.
     */
    public function get_templates_init()
    {
        if(!file_exists(SESSIONS . 'templates_cache.php'))
        {
            $scan = array_diff( scandir( TEMPLATES, 1 ), array( '..', '.', '_disabled' ) );
            if( sizeof($scan) != 0 ) 
            {
                foreach ($scan as $key => $val)
                {
                    $result[ $scan[ $key ] ] = json_decode( file_get_contents( TEMPLATES . $scan[ $key ] . '/description.json') , true);
                }
            }

            // Создание/редактирование кэша шаблона.
            file_put_contents( SESSIONS . 'templates_cache.php', '<?php return '.var_export_min( $result ).";" );

            return $result;
        }
        return require SESSIONS . 'templates_cache.php';
    }

    /**
     * Добавление роутов исходя из модулей.
     *
     * @since 0.0.1
     *
     * @param string $module       Корневое название модуля.
     *
     * @return array|false         Возвращает кэш модуля.
     */
    public function AddRoutes()
    {
        if(is_array($this->module_init['page']) || is_object($this->module_init['page'])){
        //Цикл для добавления роутов исходя из страниц модулей
        foreach ($this->module_init['page'] as $key => $val)
            $this->Router->map('GET', '/'.$key.'/', $key, $key);

        return;
        }
    }

    /**
     * Получить библиотеку актуальных данных.
     *
     * @since 0.0.1
     *
     * @return array    Актуальная информация.
     */
    public function get_actual_library() {
        if( file_exists( SESSIONS . '/actual_library.json' ) ):
            return json_decode( file_get_contents( SESSIONS . '/actual_library.json') , true );
        else:
            $actual = ['actual_css_ver' => 0, 'actual_js_ver' => 0, 'actual_modules_count' => 0];
            file_put_contents( SESSIONS . '/actual_library.json', json_encode( $actual ) );
            return $actual;
        endif;
    }

    /**
     * Проверка на актуальный список модулей.
     *
     * @since 0.0.1
     */
    public function check_actual_templates()
    {
        $keys = array_keys($this->templates);

        $scan_temp = is_array($this->scan_templates)? array_values(array_diff($keys, $this->scan_templates)): array();
        //$scan_temp = array_values(array_diff($keys, $this->scan_templates));
        $scan_temp_key = is_array($keys)? array_values(array_diff($this->scan_templates, $keys)): array();
        //$scan_temp_key = array_values(array_diff($this->scan_templates, $keys));

        if(!empty($scan_temp) || !empty($scan_temp_key))
        {
            foreach (array_keys($this->templates) as $val)
            {
                $search = array_search($val, $this->scan_templates);
                if($this->scan_templates[$search] == $val)
                {
                    $templates[] = $val;
                }
                else
                {
                    $note[] = ['text' => "Модуль удален {$val}", 'status' => 'error'];
                }
            }

            // Проверка на модули, которых нет в оригинальном массиве, и их добавление в итоговый массив
            foreach ($this->scan_templates as $val)
            {
                $search = array_search($val, array_keys($this->templates));
                if(array_keys($this->templates)[$search] != $val)
                {
                    $templates[] = $val;
                    $note[] = ['text' => "Модуль добавлен {$val}"];
                }
            }

            for($i = 0; $i < sizeof( $templates ); $i++)
                $result[ $templates[ $i ] ] = json_decode( file_get_contents( TEMPLATES . $templates[ $i ] . '/description.json') , true);

            file_put_contents( SESSIONS . 'templates_cache.php', '<?php return '.var_export_min( $result ).";" );

            if(!empty($note) && isset($_SESSION['user_admin']))
            {
                for($i = 0; $i < sizeof($note); $i++)
                    $this->Main->sendNote($note[$i]['text'], $note[$i]['status'] ?? 'success', 3);
            }
            unlink(SESSIONS . 'templates_modules_cache.php');

            $this->action_clear_style_cache();
            
            header("Refresh:3");
        }
    }

/**
     * Проверка на актуальный список модулей.
     *
     * @since 0.2.122
     */
    public function check_actual_modules_list() {
        // Сканирование папки с модулями.
        $scan_modules = array_diff( scandir( MODULES, 1 ), array( '..', '.', '_disabled' ) );

        $keys = array_keys($this->modules);
        $scan_temp_modules = array_values(array_diff($scan_modules, $keys));
        $scan_temp_modules_key = array_values(array_diff($keys, $scan_modules));

        if(!empty($scan_temp_modules) || !empty($scan_temp_modules_key)):
            // Удаление тех модулей, которых нет в выборке из папки
            foreach (array_keys($this->modules) as $val)
            {
                $search = array_search($val, $scan_modules);
                if($scan_modules[$search] == $val)
                {
                    $modules[] = $val;
                }
                else
                {
                    $note[] = ['text' => "Модуль удалён {$val}", 'status' => 'error'];
                }
            }

            // Проверка на модули, которых нет в оригинальном массиве, и их добавление в итоговый массив
            foreach ($scan_modules as $val)
            {
                $search = array_search($val, array_keys($this->modules));
                if(array_keys($this->modules)[$search] != $val)
                {
                    $modules[] = $val;
                    $note[] = ['text' => "Модуль добавлен {$val}"];
                }
            }
            
            for ( $i = 0, $c = sizeof( $modules ); $i < $c; $i++ ):
                
                // Получение информации о модуле
                $modules_desc[ $modules[ $i ] ] = json_decode( file_get_contents( MODULES . $modules[ $i ] . '/description.json') , true);

                if ($modules_desc[ $modules[ $i ] ]['setting']['status'] == 1 && $modules_desc[ $modules[ $i ] ]['required']['php'] <= PHP_VERSION && $modules_desc[ $modules[ $i ] ]['required']['core'] <= VERSION && $modules_desc[ $modules[ $i ] ]['page'] != 'all'):
                    if( ! empty( $modules_desc[ $modules[ $i ] ]['setting']['interface'] ) && $modules_desc[ $modules[ $i ] ]['setting']['interface'] == 1 ):
                        $result['page'][ $modules_desc[ $modules[ $i ] ]['page'] ]['interface'][ empty( $modules_desc[ $modules[ $i ] ]['setting']['interface_adjacent'] ) ? 'afternavbar' : $modules_desc[ $modules[ $i ] ]['setting']['interface_adjacent'] ][] = $modules[ $i ];
                    endif;
                    if( ! empty( $modules_desc[ $modules[ $i ] ]['setting']['interface_always'] ) && $modules_desc[ $modules[ $i ] ]['setting']['interface_always'] == 1 ):
                        $result['interface_always'][ empty( $modules_desc[ $modules[ $i ] ]['setting']['interface_always_adjacent'] ) ? 'afternavbar' : $modules_desc[ $modules[ $i ] ]['setting']['interface_always_adjacent'] ][] = ['name' => $modules[ $i ] ] ;
                    endif;
                    ! empty( $modules_desc[ $modules[ $i ] ]['setting']['data'] ) && $modules_desc[ $modules[ $i ] ]['setting']['data'] == 1 && $result['page'][ $modules_desc[ $modules[ $i ] ]['page'] ]['data'][] = $modules[ $i ];
                    ! empty( $modules_desc[ $modules[ $i ] ]['setting']['data_always'] ) && $modules_desc[ $modules[ $i ] ]['setting']['data_always'] == 1 && $result['data_always'][] = $modules[ $i ];
                    ! empty( $modules_desc[ $modules[ $i ] ]['setting']['js'] ) && $modules_desc[ $modules[ $i ] ]['setting']['js'] == 1 && $result['page'][ $modules_desc[ $modules[ $i ] ]['page'] ]['js'][] = ['name' => $modules[ $i ], 'type' => $modules_desc[ $modules[ $i ] ]['setting']['type']];
                    ! empty( $modules_desc[ $modules[ $i ] ]['setting']['css'] ) && $modules_desc[ $modules[ $i ] ]['setting']['css'] == 1 && $result['page'][ $modules_desc[ $modules[ $i ] ]['page'] ]['css'][] = ['name' => $modules[ $i ], 'type' => $modules_desc[ $modules[ $i ] ]['setting']['type']];
                endif;
                
            endfor;

            if(file_exists(SESSIONS . 'modules_initialization.php'))
            {
                $cache = require SESSIONS . 'modules_initialization.php';

                if (! function_exists("array_key_last")) {
                    function array_key_last($array) {
                        if (!is_array($array) || empty($array)) {
                            return NULL;
                        }
                       
                        return array_keys($array)[count($array)-1];
                    }
                }

                //Циклы на распределение в сайдбаре, и на главной
                foreach ($result['sidebar'] as $key => $val)
                {
                    $search = array_search($val, $cache['sidebar']);
                    if($cache['sidebar'][$search] == $val)
                        $res[$search] = $val;
                    else
                        $res[array_key_last($result['sidebar']) + sizeof($res) ?? 1] = $val;

                }
                foreach ($result['page']['home']['interface']['afternavbar'] as $key => $val)
                {
                    $search = array_search($val, $cache['page']['home']['interface']['afternavbar']);
                    if($cache['page']['home']['interface']['afternavbar'][$search] == $val)
                        $restwo[$search] = $val;
                    else
                        $restwo[array_key_last($result['page']['home']['interface']['afternavbar']) + sizeof($restwo) ?? 1] = $val;
                }

                //Сортировка массивов по ключам
                ksort($res);
                ksort($restwo);
                //Присваивание итоговому результату отсортированные массивы
                $result['sidebar'] = array_values($res);
                $result['page']['home']['interface']['afternavbar'] = array_values($restwo);
            }

            // Сохраняем наш файл с перебором модулей.
            file_put_contents( SESSIONS . 'modules_initialization.php', '<?php return '.var_export_min( $result ).";\n" );

            // Создание/редактирование кэша модулей.
            file_put_contents( SESSIONS . 'modules_cache.php', '<?php return '.var_export_min( $modules_desc ).";" );

            // Удаление, чтобы не мешал
            unlink( SESSIONS . 'actual_library.json' );

            // Удаляем так же переводы, а то че они, нахер пусть уходят
            unlink( SESSIONS . 'translator_cache.php' );

            // Высылает уведомления админам 
            if(!empty($note) && isset($_SESSION['user_admin']))
            {
                for($i = 0; $i < sizeof($note); $i++)
                    $this->Main->sendNote($note[$i]['text'], $note[$i]['status'] ?? 'success', 3);
            }

            $this->action_clear_style_cache();

            header("Refresh:3");
        endif;
    }

    /**
     * Полностью очистить кэш вэб-приложения включая кэш модулей.
     */
    function action_clear_style_cache() {
        // Ссылки на кэшируемые файлы.
        $cache_files = [
            'css_cache' => ASSETS_CSS . '/generation/style_generated.min.ver.' . $this->actual_library['actual_css_ver'] . '.css',
            'js_cache' => ASSETS_JS . '/generation/app_generated.min.ver.' . $this->actual_library['actual_js_ver'] . '.js',
        ];

        // Удаляем файл с генерируемыми стилями.
        file_exists( $cache_files['css_cache'] ) && unlink( $cache_files['css_cache'] );

        // Удаляем файл с генерируемыми JS библиотекой.
        file_exists( $cache_files['js_cache'] ) && unlink( $cache_files['js_cache'] );
    } 

    private function check_copy() {
        $get = file_get_contents_fix(PAGE . "footer.php");
        if(!strpos($get, "SniffRx"))
            exit(get_iframe("Ой", 'Удалять копирайты нельзя!'));
    }

    /**
     * Инициализация модулей.
     *
     * @since 0.0.1
     *
     * @return array         Возвращает список модулей для инициализации.
     */
    public function get_module_init() {
        
        // При отсутствии списока модулей для дальнейшей инициализации, выполняется создание данного списка.
        if ( ! file_exists( SESSIONS . 'modules_initialization.php' ) ):

            $result = [];

            for ( $i = 0; $i < $this->modules_count; $i++ ):

                // Перебором забираем корневое название модуля.
                $module = array_keys( $this->modules )[ $i ];
                if (
                     $this->modules[ $module ]['setting']['status'] == 1
                     && $this->modules[ $module ]['required']['php'] <= PHP_VERSION
                     && $this->modules[ $module ]['required']['core'] <= VERSION
                     && $this->modules[ $module ]['page'] != 'all'
                 ):
                    if( ! empty( $this->modules[ $module ]['setting']['interface'] ) && $this->modules[ $module ]['setting']['interface'] == 1 ):
                        $result['page'][ $this->modules[ $module ]['page'] ]['interface'][ empty( $this->modules[ $module ]['setting']['interface_adjacent'] ) ? 'afternavbar' : $this->modules[ $module ]['setting']['interface_adjacent'] ][] = $module;
                    endif;
                    if( ! empty( $this->modules[ $module ]['setting']['interface_always'] ) && $this->modules[ $module ]['setting']['interface_always'] == 1 ):
                        $result['interface_always'][ empty( $this->modules[ $module ]['setting']['interface_always_adjacent'] ) ? 'afternavbar' : $this->modules[ $module ]['setting']['interface_always_adjacent'] ][] = ['name' => $module ] ;
                    endif;
                    ! empty( $this->modules[ $module ]['setting']['data'] ) && $this->modules[ $module ]['setting']['data'] == 1 && $result['page'][ $this->modules[ $module ]['page'] ]['data'][] = $module;
                    ! empty( $this->modules[ $module ]['setting']['data_always'] ) && $this->modules[ $module ]['setting']['data_always'] == 1 && $result['data_always'][] = $module;
                    ! empty( $this->modules[ $module ]['setting']['js'] ) && $this->modules[ $module ]['setting']['js'] == 1 && $result['page'][ $this->modules[ $module ]['page'] ]['js'][] = ['name' => $module, 'type' => $this->modules[ $module ]['setting']['type']];
                    ! empty( $this->modules[ $module ]['setting']['css'] ) && $this->modules[ $module ]['setting']['css'] == 1 && $result['page'][ $this->modules[ $module ]['page'] ]['css'][] = ['name' => $module, 'type' => $this->modules[ $module ]['setting']['type']];
                 endif;
            endfor;

            for ( $i2 = 0; $i2 < $c = sizeof( $result['page'] ); $i2++ ):

                // Перебором забираем корневое название страницы.
                $page = array_keys( $result['page'] )[ $i2 ];

                for ( $i = 0; $i < $this->modules_count; $i++ ):

                    // Перебором забираем корневое название модуля.
                    $module = array_keys( $this->modules )[ $i ];

                    if (
                        $this->modules[ $module ]['setting']['status'] == 1
                        && $this->modules[ $module ]['required']['php'] <= PHP_VERSION
                        && $this->modules[ $module ]['required']['core'] <= VERSION
                        && $this->modules[ $module ]['page'] == 'all'
                    ):
                        if( ! empty( $this->modules[ $module ]['setting']['interface'] ) && $this->modules[ $module ]['setting']['interface'] == 1 ):
                            $result['page'][ $page ]['interface'][ empty( $this->modules[ $module ]['setting']['interface_adjacent'] ) ? 'afternavbar' : $this->modules[ $module ]['setting']['interface_adjacent'] ][] = $module;
                        endif;
                        if( ! empty( $this->modules[ $module ]['setting']['interface_always'] ) && $this->modules[ $module ]['setting']['interface_always'] == 1 ):
                            $result['interface_always'][ empty( $this->modules[ $module ]['setting']['interface_always_adjacent'] ) ? 'afternavbar' : $this->modules[ $module ]['setting']['interface_always_adjacent'] ][] = ['name' => $module ] ;
                        endif;
                        ! empty( $this->modules[ $module ]['setting']['data'] ) && $this->modules[ $module ]['setting']['data'] == 1 && $result['page'][ $page ]['data'][] = $module;
                        ! empty( $this->modules[ $module ]['setting']['data_always'] ) && $this->modules[ $module ]['setting']['data_always'] == 1 && $result['data_always'][] = $module;
                        ! empty( $this->modules[ $module ]['setting']['js'] ) && $this->modules[ $module ]['setting']['js'] == 1 && $result['page'][ $page ]['js'][] = ['name' => $module, 'type' => $this->modules[ $module ]['setting']['type']];
                        ! empty( $this->modules[ $module ]['setting']['css'] ) && $this->modules[ $module ]['setting']['css'] == 1 && $result['page'][ $page ]['css'][] = ['name' => $module, 'type' => $this->modules[ $module ]['setting']['type']];
                    endif;
                endfor;
            endfor;


            // Сохраняем наш файл с перебором модулей.
            file_put_contents( SESSIONS . 'modules_initialization.php', '<?php return '.var_export_min( $result ).";\n" );
        endif;
        return require SESSIONS . 'modules_initialization.php';
    }

    /**
     * Получение кэша определенного модуля.
     *
     * @since 0.0.1
     *
     * @param string $module       Корневое название модуля.
     *
     * @return array|false         Возвращает кэш модуля.
     */
    public function get_module_cache( $module ) {
        if( file_exists(MODULES . $module . '/temp/cache.php' ) ):
            return require MODULES . $module . '/temp/cache.php';
        else:
            ! file_exists( MODULES . $module . '/temp' ) && mkdir( MODULES . $module . '/temp', 0777, true );
            file_put_contents( MODULES . $module . '/temp/cache.php', '<?php return [];' );
            return [];
        endif;
    }

    /**
     * Задать кэш для определенного модуля.
     *
     * @since 0.0.1
     *
     * @param string $module        Корневое название модуля.
     * @param array $data           Массив данных.
     */
    public function set_module_cache( $module, $data ) {
        ! file_exists( MODULES . $module . '/temp' ) && mkdir( MODULES . $module . '/temp', 0777, true );
        file_put_contents( MODULES . $module . '/temp/cache.php', '<?php return '.var_export_min( $data ).";" );
    }

    /**
     * Получение кэша модулей.
     *
     * @since 0.0.1
     *
     * @return array            Выводит массив с полным описанием модулей.
     */
    public function get_modules() {
        $result = [];
        // Проверка на существование кэша модулей и кэша переводов. (Translate TODO)
        if ( ! file_exists( SESSIONS . 'modules_cache.php' ) ) {
            // Сканирование папки с модулями.
            $this->scan_modules = array_diff( scandir( MODULES, 1 ), array( '..', '.', '_disabled' ) );

            // Подсчёт количества модулей.
            $this->modules_count = sizeof( $this->scan_modules );

            if( $this->modules_count != 0 ) {
                // Цикл перебора описания модулей.
                for ( $i = 0; $i < $this->modules_count; $i++ ) {
                    // Получение описания определенного модуля.
                    $result[ $this->scan_modules[ $i ] ] = json_decode( file_get_contents( MODULES . $this->scan_modules[ $i ] . '/description.json') , true);
                }

            }

            // Создание/редактирование кэша модулей.
            file_put_contents( SESSIONS . 'modules_cache.php', '<?php return '.var_export_min( $result ).";" );
        }
        return require SESSIONS . 'modules_cache.php';
    }

    /**
     * Проверка сгенерированного стиля.
     *
     * @since 0.2
     */
    public function check_generated_style() {
        if( empty( $this->Main->main['enable_css_cache'] ) ) :

            $this->css_library[] = ASSETS_CSS . 'style.css';

            $this->css_library[] = TEMPLATES . $this->Main->main['theme'] .'/assets/css/style.css';

            // Подсчёт количества под-стилей.
            $css_library = array_diff( scandir( TEMPLATES . $this->Main->main['theme'] .'/assets/css/css_library/', 1 ), array( '..', '.' ) );

            // После проверки на существование подстиля, добавление ссылки подстиля в массив для компрессии.
            for ( $cgs = 0, $cgs_c = sizeof( $css_library ); $cgs < $cgs_c; $cgs++ ) {
                file_exists( TEMPLATES . $this->Main->main['theme'] .'/assets/css/css_library/' . $css_library[ $cgs ] . '/' . (int) $this->Main->main[ $css_library[ $cgs ] ] . '.css' ) && $this->css_library[] = TEMPLATES . $this->Main->main['theme'] .'/assets/css/css_library/' . $css_library[ $cgs ] . '/' . (int) $this->Main->main[ $css_library[ $cgs ] ] . '.css';
            }

        else:
            // При отсутствии списока модулей для дальнейшей инициализации, выполняется создание данного списка.
            if ( ! file_exists( SESSIONS . '/actual_library.json' ) || empty( $this->actual_library['actual_css_ver'] ) || ! file_exists( ASSETS_CSS . '/generation/style_generated.min.ver.' . $this->actual_library['actual_css_ver'] . '.css' ) ):

                $files_css_compress = [];

                // Проверка на существование каталога с генерируемыми файлами
                ! file_exists( ASSETS_CSS . 'generation' ) && mkdir( ASSETS_CSS . 'generation', 0777, true );

                // Если файл с темой существует, добавить ссылку на файл в массив для компрессии.
                file_exists( ASSETS_CSS .'/style.css' ) && $files_css_compress[0] = ASSETS_CSS . '/style.css';

                // Добавление файла темы
                file_exists( TEMPLATES . $this->Main->main['theme'] .'/assets/css/style.css' ) && $files_css_compress[1] = TEMPLATES . $this->Main->main['theme'] .'/assets/css/style.css';

                // Подсчёт количества под-стилей.
                $css_library = array_diff( scandir( TEMPLATES . $this->Main->main['theme'] .'/assets/css/css_library/', 1 ), array( '..', '.' ) );

                // После проверки на существование подстиля, добавление ссылки подстиля в массив для компрессии.
                for ( $cgs = 0, $cgs_c = sizeof( $css_library ); $cgs < $cgs_c; $cgs++ ) {
                    file_exists( TEMPLATES . $this->Main->main['theme'] .'/assets/css/css_library/' . $css_library[ $cgs ] . '/' . (int) $this->Main->main[ $css_library[ $cgs ] ] . '.css' ) && $files_css_compress[] = TEMPLATES . $this->Main->main['theme'] .'/assets/css/css_library/' . $css_library[ $cgs ] . '/' . (int) $this->Main->main[ $css_library[ $cgs ] ] . '.css';
                }

                for ( $i = 0; $i < $this->modules_count; $i++ ):

                    // Перебором забираем корневое название модуля.
                    $module = array_keys( $this->modules )[ $i ];

                    // Если модуль проходит проверку и имеет свою стилистику, то забираем ссылку на стиль в массив для компрессии.
                    if (
                        $this-_modules[ $module ]['setting']['status'] == 1
                        && $this->modules[ $module ]['required']['php'] <= PHP_VERSION
                        && $this->modules[ $module ]['required']['core'] <= VERSION
                    ):
                        array_key_exists('css', $this->modules[ $module ]['setting'] ) && $this->modules[ $module ]['setting']['css'] == 1 && $files_css_compress[] = MODULES . $module . '/assets/css/' . $this->modules[ $module ]['setting']['type'] . '.css';
                    endif;
                endfor;

                // Сжимаем все файлы из массива.
                $final_css_compress = $this->action_css_compress( $files_css_compress );

                // Обновляем актуальность кэша.
                $this->actual_library['actual_css_ver'] = time();
                file_put_contents( SESSIONS . '/actual_library.json', json_encode( $this->actual_library ) );

                // Очистка старых кэш файлов
                $temp_files = glob(ASSETS_CSS . 'generation/*');
                foreach( $temp_files as $temp_file ){
                    if( is_file( $temp_file ) )
                        unlink( $temp_file );
                }

                // Сохраняем итоговый CSS файл.
                file_put_contents( ASSETS_CSS . '/generation/style_generated.min.ver.' . $this->actual_library['actual_css_ver'] . '.css', $final_css_compress );
            endif;
        endif;
    }

    /**
     * Проверка сгенерированного JavaScript.
     *
     * @since 0.0.1
     */
    public function check_generated_js() {

        if( empty( $this->Main->main['enable_js_cache'] ) ):

            $this->js_library[] = ASSETS_JS . 'app.js';

            //$this->js_library[] = TEMPLATES . $this->Main->main['theme'] .'assets/js/app.js'; - исправить потом!!!
        else:
            // При отсутствии списока модулей для дальнейшей инициализации, выполняется создание данного списка.
            if ( ! file_exists( SESSIONS . '/actual_library.json' ) || ! file_exists( ASSETS_JS . '/generation/app_generated.min.ver.' . $this->actual_library['actual_js_ver'] . '.js' ) || empty( $this->actual_library['actual_js_ver'] ) ):

                // Проверка на существование каталога с генерируемыми файлами
                ! file_exists( ASSETS_JS . 'generation' ) && mkdir( ASSETS_JS . 'generation', 0777, true );

                file_exists( ASSETS_JS . '/app.js' ) && $files_js_compress[] = ASSETS_JS . '/app.js';

                file_exists( TEMPLATES . $this->Main->main['theme'] .'/assets/js/app.js' ) && $files_js_compress[] = TEMPLATES . $this->Main->main['theme'] .'/assets/js/app.js';

                // Перебором забираем корневое название модулей.
                for ( $i = 0; $i < $this->modules_count; $i++ ):
                    $module = array_keys( $this->modules )[ $i ];
                    if (
                        $this->modules[ $module ]['setting']['status'] == 1
                        && $this->modules[ $module ]['required']['php'] <= PHP_VERSION
                        && $this->modules[ $module ]['required']['core'] <= VERSION
                    ):
                        array_key_exists('js', $this->modules[ $module ]['setting'] ) && $this->modules[ $module ]['setting']['js'] == 1 && $files_js_compress[] = MODULES . $module . '/assets/js/' . $this->modules[ $module ]['setting']['type'] . '.js';
                    endif;
                endfor;

                $final_js_compress = $this->action_js_compress( $files_js_compress );

                $this->actual_library['actual_js_ver'] = time();

                // Обновляем options
                file_put_contents( SESSIONS . '/actual_library.json', json_encode( $this->actual_library ) );

                // Очистка старых кэш файлов
                $temp_files = glob( ASSETS_JS . 'generation/*' );
                foreach( $temp_files as $temp_file ) {
                    if( is_file( $temp_file ) )
                        unlink( $temp_file );
                }

                // Сохраняем итоговый JS файл.
                file_put_contents( ASSETS_JS . '/generation/app_generated.min.ver.' . $this->actual_library['actual_js_ver'] . '.js', $final_js_compress );
            endif;
        endif;
    }

    /**
     * Задать заглавие страницы.
     *
     * @since 0.0.1
     *
     * @param string $text             Заголовок страницы.
     */
    public function set_page_title( $text ) {
        $this->page_title = $text;
    }

    /**
     * Получить загловок страницы.
     *
     * @since 0.0.1
     *
     * @return  string $text             Заголовок страницы.
     */
    public function get_page_title() {
        return empty( $this->page_title ) ? $this->Main->main['site_name'] : $this->page_title;
    }

    /**
     * Задать описание страницы.
     *
     * @since 0.0.1
     *
     * @param string $text             Описание страницы.
     */
    public function set_page_description( $text ) {
        $this->page_description = $text;
    }

    /**
     * Получить описание страницы.
     *
     * @since 0.0.1
     *
     * @return  string $text             Описание страницы.
     */
    public function get_page_description() {
        return empty( $this->page_description ) ? $this->Main->main['Description'] : $this->page_description;
    }
}