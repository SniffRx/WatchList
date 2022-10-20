<button class="btn btn-primary search-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
    aria-controls="offcanvasRight"
    style="z-index:999;position:fixed;right:0;margin-top:38px;transform:rotateZ(270deg);transform-origin:right;right:20px;">
    <i class="fa-thin fa-filter"></i> Поиск
</button>

<div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasRight"
    aria-labelledby="offcanvasRightLabel" style="background:rgb(21 16 32 / 50%);">
    <div class="offcanvas-header">
        <h5 id="offcanvasRightLabel">Фильтр</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">

    <div class="row g-3">
            <div class="d-flex align-items-center text-light">Жанры

                <!--div class="ml-2 pl-1">
                    <span data-bs-toggle="tooltip" data-bs-placement="top"
                        title="В синем положении индикатора, выводятся те аниме в которых присутствуют все выбранные вами жанры. В сером положении, выводятся аниме в которых присутствует хотя бы один из выбранных вами жанров.">
                        <i class="fa-light fa-circle-question"></i></span>
                </div>
                    <div class="slider-toggle d-flex noUi-target noUi-ltr noUi-horizontal">
                        <div class="noUi-base">
                            <div class="noUi-connects"></div>
                            <div class="noUi-origin" style="transform: translate(-100%, 0px); z-index: 4;">
                                <div class="noUi-handle noUi-handle-lower" data-handle="0" tabindex="0" role="slider"
                                    aria-orientation="horizontal" aria-valuemin="0.0" aria-valuemax="1.0"
                                    aria-valuenow="0.0" aria-valuetext="0.00"></div>
                            </div>
                        </div>
                    </div-->
                
            </div>
            <!--select class="form-select" id="country" required="">
                <option value="">Выберите жанр</option>
                <?php //for ($i=0; $i < 10; $i++) { ?>
                    <option>Безумие</option>
                <?php //}?>
            </select-->

            <div class="checkselect" id="genres" required="">
            <?php for ($i=0; $i < 10; $i++) { ?>
                <label><input type="checkbox" name="genres[]" value="<?php echo $i;?>"> Безумие</label>
                <?php }?>
            </div>

            <!--div class="d-flex align-items-center">Тип</div>
            <select class="form-select" id="country" required="">
                <option value="">Выберите тип</option>
                <?php //for ($i=0; $i < 10; $i++) { ?>
                    <option>Безумие</option>
                <?php //}?>
            </select-->
            <div class="d-flex align-items-center text-light">Тип</div>
            <div class="checkselect" id="type" required="">
            <?php for ($i=0; $i < 10; $i++) { ?>
                <label><input type="checkbox" name="type[]" value="<?php echo $i;?>"> Фильм</label>
                <?php }?>
            </div>

            <!--div class="d-flex align-items-center">Статус</div>
            <select class="form-select" id="country" required="">
                <option value="">Выберите статус</option>
                <?php //for ($i=0; $i < 10; $i++) { ?>
                    <option>Безумие</option>
                <?php //}?>
            </select-->
            <div class="d-flex align-items-center text-light">Статус</div>
            <div class="checkselect" id="status" required="">
            <?php for ($i=0; $i < 10; $i++) { ?>
                <label><input type="checkbox" name="status[]" value="<?php echo $i;?>"> Вышел</label>
                <?php }?>
            </div>
            
            <!--div class="d-flex align-items-center">Возрастное ограничение</div>
            <select class="form-select" id="country" required="">
                <option value="">Выберите рейтинг</option>
                <?php //for ($i=0; $i < 10; $i++) { ?>
                    <option>Безумие</option>
                <?php //}?>
            </select-->
            <div class="d-flex align-items-center text-light">Возростное ограничение</div>
            <div class="checkselect" id="pg" required="">
            <?php for ($i=0; $i < 10; $i++) { ?>
                <label><input type="checkbox" name="pg[]" value="<?php echo $i;?>"> R+</label>
                <?php }?>
            </div>

            <button class="btn btn-block btn-lg btn-primary cursor-pointer" style="width:100%"
                type="submit">Искать</button>                
        </div>
    </div>
</div>

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
				'<div class="checkselect-over"></div>' +
			'</div>'
		);	
 
		this.each(function(){
			setChecked(this);
		});		
		this.find('input[type="checkbox"]').click(function(){
			setChecked($(this).parents('.checkselect'));
		});
 
		this.parent().find('.checkselect-control').on('click', function(){
			$popup = $(this).next();
			$('.checkselect-popup').not($popup).css('display', 'none');
			if ($popup.is(':hidden')) {
				$popup.css('display', 'block');
				$(this).find('select').focus();
			} else {
				$popup.css('display', 'none');
			}
		});
 
		$('html, body').on('click', function(e){
			if ($(e.target).closest('.checkselect').length == 0){
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
    background: #2A203F!important;
}
.checkselect-popup::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #F43B86 50.88%, #FF85B6 155.26%);
    border-radius: 20px;
    border: 3px solid transparent!important;
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
	margin:  0;
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