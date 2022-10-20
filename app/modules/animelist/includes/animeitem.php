<?php $animedataitem = $AnimeList->GetAnime($animeitem);
if(isset( $_SESSION['usertype'] )) $animeuseritem = $AnimeList->GetAnimeProfile($animeitem);
// print_r($_SESSION['logged']);
//print_r($animeuseritem = $AnimeList->GetAnimeProfile($animeitem));
?>
<style>
.card {
    border: none;
    background: radial-gradient(275.91% 312.39% at 50.15% -176.99%, #565064 0%, #3F3457 100%);
}

.raiting {
    color: #FFE459;
    text-shadow: 1px 1px 2px black;
}

.raiting i {
    margin-right: 5px;
}

.raiting .raiting-text {
    margin-left: 5px;
    color: white;
}

.tooltip-inner {
    max-width: 450px;
}
</style>

<?php if(isset( $_SESSION['usertype'] ) && $_SESSION['usertype'] == 3){}?>
<?php

//print_r($animedataitem['name']);

?>
<div class="container pt-5">
    <div class="row">
        <div class="col-12">
            <div class="content px-3 pb-3" id="content">
                <div class="card">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <!-- <img class="img-fluid rounded-start"
                                src="data:image/jpeg;base64,<?php //echo base64_encode( $animedataitem['image'] );?>" /> -->
                            <img class="img-fluid rounded-start"
                                src="/<?php echo ASSETS .'img/animeposters/'.$animedataitem['image'];?>" />

                            <div class="text-center">
                                <!-- <div class="bg-dark p-4 text-center" style="z-index:-10;margin: -190px 0 0 !important;"> -->
                                <div class="media-body card text-white bg-dark-profile shadow-lg">
                                    <div class="row">
                                        <div class="col m-2 d-flex align-items-stretch flex-column">
                                            <div class="card card-about">
                                                <?php if(isset( $_SESSION['usertype'] ) && $_SESSION['usertype'] == 3){
                                                    $uw = 0;
                                                    if(isset($animeuseritem['watch_now']) && $animeuseritem['watch_now'] == 1) {$uw = 1;}
                                                    if(isset($animeuseritem['will']) && $animeuseritem['will'] == 1) {$uw = 2;}
                                                    if(isset($animeuseritem['watched']) && $animeuseritem['watched'] == 1) {$uw = 3;}
                                                    if(isset($animeuseritem['lost']) && $animeuseritem['lost'] == 1) {$uw = 4;}
                                                    if(isset($animeuseritem['favourite']) && $animeuseritem['favourite'] == 1) {$uw = 5;}
                                                    ?>
                                                <div class="dropdown mb-2">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        Добавить в список <?php
                                                        switch ($uw) {
                                                            case 1:
                                                                echo '(Смотрю)';
                                                                break;
                                                            case 2:
                                                                echo '(Буду смотреть)';
                                                                break;
                                                            case 3:
                                                                echo '(Просмотрено)';
                                                                break;
                                                            case 4:
                                                                echo '(Брошено)';
                                                                break;
                                                            case 5:
                                                                echo '(Любимое)';
                                                                break;
                                                            default:
                                                                # code...
                                                                break;
                                                        }
                                                        ?>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <form enctype="multipart/form-data" method="post">
                                                        <li><button name="watch_now" class="dropdown-item <?php if($uw == 1){echo 'active';}?>">Смотрю</button></li>
                                                        <li><button name="will" class="dropdown-item <?php if($uw == 2){echo 'active';}?>">Буду смотреть</button></li>
                                                        <li><button name="watched" class="dropdown-item <?php if($uw == 3){echo 'active';}?>">Просмотрено</button></li>
                                                        <li><button name="lost" class="dropdown-item <?php if($uw == 4){echo 'active';}?>">Брошено</button></li>
                                                        <li><button name="favourite" class="dropdown-item <?php if($uw == 5){echo 'active';}?>" >Любимое</button></li>
                                                        </form>
                                                    </ul>
                                                </div>
                                                <button type="button" class="btn btn-secondary mb-2"
                                                    data-bs-toggle="modal" data-bs-target="#FriendsList">
                                                    У друзей в списках
                                                </button>
                                                <div class="modal fade" id="FriendsList" tabindex="-1"
                                                    aria-labelledby="FriendsList" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content bg-dark" style="width:560px;">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="FriendsList">У друзей в
                                                                    списках</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="container">
                                                                    <div class="row">
                                                                        <?php for ($i=0; $i < 25; $i++) {?>
                                                                        <div class="col-md-4 pb-3">
                                                                            <div class="card" style="width: 10rem;">
                                                                                <img width="200px" height="200px"
                                                                                    src="/<?php echo ASSETS .'img/animeposters/'.$animedataitem['image'];?>"
                                                                                    class="card-img-top img-thumbnail rounded"
                                                                                    alt="Friend<?php echo $i;?>">
                                                                                <p class="card-title">
                                                                                    Friend<?php echo $i;?></p>
                                                                            </div>
                                                                        </div>
                                                                        <?php }?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-primary">Save
                                                                    changes</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php } else { ?>
                                                <p style="font-size: 10pt;">Хотите добавить аниме в свой список? Тогда
                                                    <cite style="text-decoration:underline"><a type="button" data-bs-toggle="modal"
                    data-bs-target="#login">войдите</a></cite> или <cite
                                                        style="text-decoration:underline"><a
                                                            href="/register">зарегистрируйтесь!</a></cite>
                                                </p>
                                                <?php }?>
                                                <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-html="true"
                                                    title='<h5>В списках у людей</h5>
                                                    <div class="row">
                                                        <div class="col-4"><p>Пользователей</p></div>
                                                        <div class="col-4"><p>Процент</p></div>
                                                        <div class="col-4"><p>Список</p></div></div>
                                                        <div class="row">
                                                            <div class="col-4"><p><?php echo $animedataitem['watch'];?></p></div>
                                                            <div class="col-4"><p><?php echo action_int_percent_of_all($animedataitem['watch'], $animedataitem['allwatch']);?>%</p></div>
                                                            <div class="col-4"><p>Смотрю</p></div>
                                                            <div class="col-4"><p><?php echo $animedataitem['will'];?></p></div>
                                                            <div class="col-4"><p><?php echo action_int_percent_of_all($animedataitem['will'], $animedataitem['allwatch']);?>%</p></div>
                                                            <div class="col-4"><p>Буду смотреть</p></div>
                                                            <div class="col-4"><p><?php echo $animedataitem['watched'];?></p></div>
                                                            <div class="col-4"><p><?php echo action_int_percent_of_all($animedataitem['watched'], $animedataitem['allwatch']);?>%</p></div>
                                                            <div class="col-4"><p>Просмотрено</p></div>
                                                            <div class="col-4"><p><?php echo $animedataitem['lost'];?></p></div>
                                                            <div class="col-4"><p><?php echo action_int_percent_of_all($animedataitem['lost'], $animedataitem['allwatch']);?>%</p></div>
                                                            <div class="col-4"><p>Брошено</p></div>
                                                            <div class="col-4"><p><?php echo $animedataitem['favourite'];?></p></div>
                                                            <div class="col-4"><p><?php echo action_int_percent_of_all($animedataitem['favourite'], $animedataitem['allwatch']);?>%</p></div>
                                                            <div class="col-4"><p>Любимое</p></div>
                                                        </div>
                                                        <div class=""><p>В списках у <?php if ($animedataitem['allwatch'] == NULL){echo 0;}else{echo $animedataitem['allwatch'];}?> человек</p></div>'>
                                                    У людей в списках
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <div class="h5 card-title font-weight-normal mb-1"><a
                                        href="#!"><?php echo $animedataitem['name'];?></a></div>
                                <?php 
                                        $alt_name = explode('|', $animedataitem['alt_name']); foreach ($alt_name as $alt) {echo '<p class="card-text" style="margin:0;"><small class="text-muted">'.$alt.' </small></p>';}
                                        ?>
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
                                    <!-- php func categories-->
                                    <span><a href="#!">ТВ Сериал</a></span>
                                    <span class="mx-2" aria-hidden="true">/</span>
                                    <span class="mb-2"><a href="#!">2017</a></span>
                                    <span class="mx-2 d-none d-sm-inline" aria-hidden="true">/</span>
                                    <span class="d-none d-sm-inline">
                                        <?php $anim_tag = [];
                                                      $anim_tag_link = [];
                                                if(!empty($animedataitem['tags']) || !empty($animedataitem['tag_link'])) {$anim_tag = explode(", ", $animedataitem['tags']);$anim_tag_link = explode(", ", $animedataitem['tag_link']);}
                                                for ($i=0; $i < sizeof($anim_tag); $i++) { 
                                                    if(!empty($anim_tag) || !empty($anim_tag_link)):?>
                                        <a class="mb-2" href="/anime/filter"
                                            title="<?php echo $anim_tag[$i]; ?>"><?php echo $anim_tag[$i]; ?></a><?php if(sizeof($anim_tag)-1 != $i) {?><span
                                            class="text-light">,</span><?php }?>
                                        <!-- <a class="mb-2" href="/anime/<?php //echo $anim_tag_link[$i];?>" title="<?php //echo $anim_tag[$i]; ?>"><?php //echo $anim_tag[$i]; ?></a><?php //if(sizeof($anim_tag)-1 != $i) {?><span class="text-light">,</span><?php //}?> -->
                                        <?php endif;
                                                }?>
                                        <?php //echo '<a class="mb-2" href="#!" title="'.$value['tags'].'">'.$value['tags'].'</a>';?>
                                    </span>
                                </div>
                                <p class="card-text"><?php echo $animedataitem['description'];?></p>
                                <!-- php func description-->
                            </div>
                        </div>
                    </div>
                </div>
                <?//endforeach;?>
            </div>
        </div>
    </div>
</div>