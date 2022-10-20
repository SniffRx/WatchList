<div class="col-md-8">
    <div class="col-md-12 mb-4">
        <div class="card bg-dark">
            <div class="card-header">
                <h5 class="badge">Порядок загрузки модулей</h5>
                <div class="select-panel select-panel-pages badge">
                    <select onChange="window.location.href=this.value">
                        <option style="display:none" value="" disabled selected>
                            <?php echo get_section('module_page', 'home') == 'sidebar' ? ' ' : 'Страница:' ?>
                            <?php echo get_section( 'module_page', 'home' )?></option>
                        <?php for ($i = 0;$i < $Modules->module_init_page_count;$i++) {$id_module = array_keys( $Modules->module_init['page'] )[ $i ]?>
                        <option value="<?php echo set_url_section(get_url(2), 'module_page', $id_module) ?>">
                            <a href="<?php echo set_url_section(get_url(2), 'module_page', $id_module) ?>"><?php echo 'Страница'?>:
                                <?php echo $id_module?></a>
                        </option>
                        <?php } ?>
                        <option value="<?php echo set_url_section(get_url(2), 'module_page', 'sidebar') ?>">
                            <a href="<?php echo set_url_section(get_url(2), 'module_page', 'sidebar') ?>">sidebar</a>
                        </option>
                    </select>
                </div>
                <div class="select-panel select-panel-pages badge">
                    <select onChange="window.location.href=this.value">
                        <option style="display:none" value="" disabled selected>
                            <?php echo get_section( 'module_interface_adjacent', 'afternavbar' )?></option>
                        <option
                            value="<?php echo set_url_section(get_url(2), 'module_interface_adjacent', 'afternavbar')?>">
                            <a
                                href="<?php echo set_url_section(get_url(2), 'module_interface_adjacent', 'afternavbar')?>">afternavbar</a>
                        </option>
                        <option
                            value="<?php echo set_url_section(get_url(2), 'module_interface_adjacent', 'afterhead')?>">
                            <a
                                href="<?php echo set_url_section(get_url(2), 'module_interface_adjacent', 'afterhead')?>">afterhead</a>
                        </option>
                        <option
                            value="<?php echo set_url_section(get_url(2), 'module_interface_adjacent', 'inbodyend')?>">
                            <a
                                href="<?php echo set_url_section(get_url(2), 'module_interface_adjacent', 'inbodyend')?>">inbodyend</a>
                        </option>
                        <option
                            value="<?php echo set_url_section(get_url(2), 'module_interface_adjacent', 'afterbody')?>">
                            <a
                                href="<?php echo set_url_section(get_url(2), 'module_interface_adjacent', 'afterbody')?>">afterbody</a>
                        </option>
                    </select>
                </div>
            </div>
            <div class="card-container">
                <?php if( get_section( 'module_page', 'home' ) != '' ):?>
                <div class="dd" id="nestable">
                    <ol class="dd-list">
                        <?php
                        if( get_section( 'module_page', 'home' ) == 'sidebar' ):
                            $c_m_p = sizeof( $Modules->module_init['sidebar'] );
                        else:
                            $c_m_p = sizeof( $Modules->module_init['page'][ get_section( 'module_page', 'home' ) ]['interface'][ get_section( 'module_interface_adjacent', 'afternavbar' ) ] );
                        endif;
                        for ( $i = 0; $i < $c_m_p; $i++ ) {
                            if( get_section( 'module_page', 'home' ) == 'sidebar' ):
                                $data_id = $Modules->module_init['sidebar'][ $i ];
                                $data_title = $Modules->modules[$Modules->module_init['sidebar'][ $i ]]['title'];
                            else:
                                $data_id =  $Modules->module_init['page'][ get_section( 'module_page', 'home' ) ]['interface'][ get_section( 'module_interface_adjacent', 'afternavbar' ) ][ $i ];
                                $data_title = $Modules->modules[$Modules->module_init['page'][ get_section( 'module_page', 'home' ) ]['interface'][ get_section( 'module_interface_adjacent', 'afternavbar' ) ][ $i ]]['title'];
                            endif?>
                        <li class="dd-item" data-id="<?php echo $data_id?>">
                            <a class="module_setting"
                                href="<?php echo $Main->main['site']?>adminpanel/?section=modules&options=<?php echo $data_id?>"><i
                                    class="zmdi zmdi-chevron-right zmdi-hc-fw"></i></a>
                            <div class="dd-handle"><?php echo $data_title?></div>
                        </li>
                        <?php } ?>
                    </ol>
                    <input type="hidden" id="nestable-output">
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-dark">
            <div class="card-header">
                <h5 class="badge">Добавить аниме на сайт</h5>
                <!-- <div class="select-panel select-panel-pages badge"></div> -->
            </div>
            <form enctype="multipart/form-data" method="post">
            <div class="card-container">
                <div class="mb-3 p-2">
                    <label for="name" class="form-label text-light">Название аниме</label>
                    <input type="text" class="form-control" name="name" placeholder="Вайолет Эвергарден">
                </div>
                <div class="mb-3 p-2">
                    <label for="link" class="form-label text-light">Ссылка</label>
                    <input type="text" class="form-control" name="link" placeholder="violet-evergarden">
                </div>
                <div class="mb-3 p-2">
                    <label for="alt_name" class="form-label text-light">Альтернативное название аниме</label>
                    <p><small style="color:white">Если вы хотите указать несколько названий, просим после каждого наименования использовать символ | чтобы разделять их.</small></p>
                    <textarea class="form-control" name="alt_name" rows="3" placeholder="Violet Evergarden|ヴァイオレット・エヴァーガーデン"></textarea>
                </div>
                <div class="mb-3 p-2">
                    <label for="image" class="form-label text-light">Постер</label>
                    <input class="form-control" type="file" name="image">
                </div>
                <div class="mb-3 p-2">
                    <label for="desc" class="form-label text-light">Описание</label>
                    <textarea class="form-control" name="desc" rows="3" placeholder="Описание"></textarea>
                </div>
                <div class="mb-3 p-2">
                    <div class="text-light">
                        <div class="row g-3">
                            <div class="d-flex align-items-center text-light"><label for="genres" class="form-label text-light">Жанры (Don't Work) </label>
                                <div class="checkselect bg-dark" id="genres" required="">
                                    <?php for ($i=0; $i < 10; $i++) { ?>
                                    <label class="bg-dark"><input type="checkbox" name="genres[]" value="<?php echo $i;?>">
                                        Безумие</label>
                                    <?php }?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-center pb-3">
                    <button name="add_anime" type="submit" class="input-group-text btn-primary">Добавить аниме</button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="card bg-dark">
        <div class="card-header">
            <h5 class="text-light">Настройки</h5>
        </div>
        <div class="card-container" style="display: flex;flex-direction: column;align-items: center;">
            <div class="btn_form pt-3 mb-3">
                <form enctype="multipart/form-data" method="post">
                    <input class="input-group-text btn-primary" type="submit" name="clear_cache_modules"
                        Value="Обновить кеш модулей">
                </form>
            </div>
            <div class="btn_form mb-3">
                <form enctype="multipart/form-data" method="post">
                    <input class="input-group-text btn-primary" type="submit" name="clear_translator_cache"
                        Value="Обновить кеш переводов">
                </form>
            </div>
            <div class="btn_form mb-3">
                <form enctype="multipart/form-data" method="post">
                    <input class="input-group-text btn-primary" type="submit" name="clear_modules_initialization"
                        Value="Обновить лист модулей">
                </form>
            </div>
            <div class="btn_form mb-3">
                <form enctype="multipart/form-data" method="post">
                    <input class="input-group-text btn-primary" type="submit" name="clear_templates_cache"
                        Value="Очистить кеш шаблонов">
                </form>
            </div>
        </div>
    </div>
</div>

<?php
    // if(isset($_POST['add_anime']))echo $_POST['add_anime'];
    // if(isset($_POST['clear_cache_modules']))echo $_POST['clear_cache_modules'];
    // if(isset($_POST['clear_translator_cache']))echo $_POST['clear_translator_cache'];
    // if(isset($_POST['clear_modules_initialization']))echo $_POST['clear_modules_initialization'];
    // if(isset($_POST['clear_templates_cache']))echo $_POST['clear_templates_cache'];
    ?>

<script>
(function($) {
    function setChecked(target) {
        var checked = $(target).find("input[type='checkbox']:checked").length;
        if (checked) {
            $(target).find('select option:first').html('Выбрано: ' + checked);
        } else {
            $(target).find('select option:first').html('Выберите из списка');
        }
    }

    $.fn.checkselect = function() {
        this.wrapInner('<div class="checkselect-popup"></div>');
        this.prepend(
            '<div class="checkselect-control">' +
            '<select class="form-control"><option></option></select>' +
            '<div class="checkselect-over bg-dark text-light"></div>' +
            '</div>'
        );

        this.each(function() {
            setChecked(this);
        });
        this.find('input[type="checkbox"]').click(function() {
            setChecked($(this).parents('.checkselect'));
        });

        this.parent().find('.checkselect-control').on('click', function() {
            $popup = $(this).next();
            $('.checkselect-popup').not($popup).css('display', 'none');
            if ($popup.is(':hidden')) {
                $popup.css('display', 'block');
                $(this).find('select').focus();
            } else {
                $popup.css('display', 'none');
            }
        });

        $('html, body').on('click', function(e) {
            if ($(e.target).closest('.checkselect').length == 0) {
                $('.checkselect-popup').css('display', 'none');
            }
        });
    };
})(jQuery);

$('.checkselect').checkselect();
</script>

<style>
.checkselect {
    position: relative;
    display: inline-block;
    min-width: 200px;
    text-align: left;
}

.checkselect-control {
    position: relative;
    padding: 0 !important;
}

.checkselect-control select {
    position: relative;
    display: inline-block;
    width: 100%;
    margin: 0;
    padding-left: 5px;
    /*height: 30px;*/
}

.checkselect-over {
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    cursor: pointer;
}

.checkselect-popup {
    display: none;
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    width: 100%;
    height: auto;
    max-height: 200px;
    position: absolute;
    top: 100%;
    left: 0px;
    border: 1px solid #dadada;
    border-top: none;
    background: #fff;
    z-index: 9999;
    overflow: auto;
    user-select: none;
}

.checkselect-popup::-webkit-scrollbar {
    width: 8px;
}

.checkselect-popup::-webkit-scrollbar-track {
    background: #2A203F !important;
}

.checkselect-popup::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #F43B86 50.88%, #FF85B6 155.26%);
    border-radius: 20px;
    border: 3px solid transparent !important;
}

.checkselect label {
    position: relative;
    display: block;
    margin: 0;
    padding: 4px 6px 4px 25px;
    font-weight: normal;
    font-size: 1em;
    line-height: 1.1;
    cursor: pointer;
}

.checkselect-popup input {
    position: absolute;
    top: 5px;
    left: 8px;
    margin: 0 !important;
    padding: 0;
}

.checkselect-popup label:hover {
    background: #03a2ff;
    color: #fff;
}

.checkselect-popup fieldset {
    display: block;
    margin: 0;
    padding: 0;
    border: none;
}

.checkselect-popup fieldset input {
    left: 15px;
}

.checkselect-popup fieldset label {
    padding-left: 32px;
}

.checkselect-popup legend {
    display: block;
    margin: 0;
    padding: 5px 8px;
    font-weight: 700;
    font-size: 1em;
    line-height: 1.1;
}
</style>