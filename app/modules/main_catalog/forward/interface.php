<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="content px-3 pb-3" id="content">
                <div class="card-header row border-bottom-0">
                    <div class="h2 mb-0 text-light">Новые аниме на сайте</div>
                </div>
                <script type="text/javascript">
                /*$(document).ready(function() {
                        $.ajax({ //create an ajax request to display.php
                            type: "GET",
                            url: "display.php", // Сюда вставить col md-4 вывод
                            dataType: "html", //expect html to be returned                
                            success: function(response) {
                                $("#anime-content").html(response);
                                //alert(response);
                            }

                        });
                });*/
                </script>
                <div id="anime-content" class="row row-cols-md-2">
                    <?foreach ($animemainlist as $value):?>
                    <div class="col mb-4">
                        <div class="card">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img style="height:320px;" src="/<?php echo ASSETS .'img/animeposters/'.$value['image'];?>"
                                        class="img-fluid rounded-start" alt="<?php echo $value['name'];?>" />
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <div class="h5 card-title font-weight-normal mb-1"><a
                                                href="anime/<?php echo $value['link'];?>"><?php echo $value['name'];?></a>
                                        </div>
                                        <div class="moretext-<?php echo $i;?>" style="display: none;">
                                        <?php 
                                        $alt_name = explode('|', $value['alt_name']); foreach ($alt_name as $alt) {
                                            echo '<p class="card-text" style="margin:0;"><small class="text-muted">'.$alt.' </small></p>';}
                                        ?>
                                        </div><p class="card-text"><a style="cursor: pointer;" class="moreless-button" onclick="$('.moretext-<?php echo $i;?>').slideToggle();">...</a></p><?php $i++;?>
                                        <div class="raiting" style="display:inline-flex;">
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star-half-stroke"></i>
                                            <i class="fa-light fa-star"></i>
                                            <i class="fa-light fa-star"></i>
                                            <p class="raiting-text"> 8,8</p>
                                        </div>
                                        <div class="mb-2">
                                            <span><a href="#!">ТВ Сериал</a></span>
                                            <span class="mx-2" aria-hidden="true">/</span>
                                            <span class="mb-2"><a href="#!">2017</a></span>
                                            <span class="mx-2 d-none d-sm-inline" aria-hidden="true">/</span>
                                            <span class="d-none d-sm-inline">
                                                <a class="mb-2" href="#!" title="Приключения">Приключения</a>,
                                                <a class="mb-2" href="#!" title="Фэнтези">Фэнтези</a>,
                                                <a class="mb-2" href="#!" title="Экшен">Экшен</a>
                                            </span>
                                        </div>
                                        <p class="card-text"><?php 
                                        $desc = mb_substr($value['description'], 0, 180,'UTF-8');
                                        $desc = mb_substr($desc, 0, mb_strrpos($desc,'.'));
                                        echo  $desc."...";?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </div>
</div>