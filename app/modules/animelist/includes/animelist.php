<?php $animedata = $AnimeList->GetTags();?>

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

</style>

<?php if(isset( $_SESSION['usertype'] ) && $_SESSION['usertype'] == 3){}?>
<?php

//print_r($animedata);

?>

<div class="container pt-5">
    <div class="row">
        <div class="col-12">
            <div class="content px-3 pb-3" id="content">
                <div id="anime-content" class="row row-cols-md-2">
                    <?php $alt_text=0; foreach ($animedata as $value):?>
                    <div class="col mb-4">
                        <div class="card">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img class="img-fluid rounded-start" style="height:300px"
                                        src="/<?php echo ASSETS .'img/animeposters/'.$value['image'];?>" />
                                    <!-- <img class="img-fluid rounded-start" style="height:300px"
                                        src="data:image/jpeg;base64,<?php //echo base64_encode( $value['image'] );?>" /> -->
                                        
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <div class="h5 card-title font-weight-normal mb-1"><a
                                                href="<?php echo$value['link']?>"><?php echo $value['name']; ?></a></div>

                                                <div class="moretext-<?php echo $alt_text;?>" style="display: none;">
                                        <?php 
                                        $alt_name = explode('|', $value['alt_name']); foreach ($alt_name as $alt) {
                                            echo '<p class="card-text" style="margin:0;"><small class="text-muted">'.$alt.' </small></p>';}
                                        ?>
                                        </div><p class="card-text"><a style="cursor: pointer;" class="moreless-button" onclick="$('.moretext-<?php echo $alt_text;?>').slideToggle();">...</a></p><?php $alt_text++;?>
                                        
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
                                                if(!empty($value['tags']) || !empty($value['tag_link'])) {$anim_tag = explode(", ", $value['tags']);$anim_tag_link = explode(", ", $value['tag_link']);}
                                                for ($i=0; $i < sizeof($anim_tag); $i++) { 
                                                    if(!empty($anim_tag) || !empty($anim_tag_link)):?>
                                                        <a class="mb-2" href="/anime/filter" title="<?php echo $anim_tag[$i]; ?>"><?php echo $anim_tag[$i]; ?></a><?php if(sizeof($anim_tag)-1 != $i) {?><span class="text-light">,</span><?php }?>
                                                        <!-- <a class="mb-2" href="/anime/<?php //echo $anim_tag_link[$i];?>" title="<?php //echo $anim_tag[$i]; ?>"><?php //echo $anim_tag[$i]; ?></a><?php //if(sizeof($anim_tag)-1 != $i) {?><span class="text-light">,</span><?php //}?> -->
                                                    <?php endif;
                                                }?>
                                                <?php //echo '<a class="mb-2" href="#!" title="'.$value['tags'].'">'.$value['tags'].'</a>';?>
                                            </span>
                                        </div>
                                        <p class="card-text"><?php 
                                        
                                        $desc = mb_substr($value['description'], 0, 180,'UTF-8');
                                        $desc = mb_substr($desc, 0, mb_strrpos($desc,'.'));

                                        echo  $desc."...";//$value['description'];?></p>
                                        <!-- php func description-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?endforeach;?>

                </div>
            </div>
        </div>
    </div>
</div>