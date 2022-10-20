<!-- Set up your HTML -->
<div class="d-none d-sm-block px-4 py-5 my-5 carousel-bg">
    <div class="py-3 text__underline">
        <!-- style="background-color: #333; background-color: rgba(0, 0, 0, 0.04);border-bottom: 1px solid rgba(0, 0, 0, 0.135);border-top: 1px solid rgba(0, 0, 0, 0.135);"> -->
        <div class="container">
            <div class="row">
                <div class="col-12" style="min-height: 344px;">
                    <div class="h2 mb-3">
                        <a class="text-carousel" href="/anime/season/2022/spring">Аниме весеннего
                            сезона</a>
                    </div>
                    <div class="owl-carousel" style="transform: translate3d(0%, 0px, 0px);">
                    <?php //print_r($carousellist);
                    
                    foreach ($carousellist as $value):?>
                    

                    <div class="item">
                        <div class="position-relative"><a href="anime/<?php echo $value['link'];?>">
                                <div class="anime-grid-lazy loaded" style="background-image: url(/<?php echo ASSETS .'img/animeposters/'.$value['image'];?>); opacity: 1;">
                                <!-- <div class="anime-grid-lazy loaded" style="background-image: url(data:image/jpeg;base64,<?php echo base64_encode( $value['image'] );?>); opacity: 1;"> -->
                                </div>
                            </a>
                        </div>
                        <div class="card-body px-0">
                            <div class="h5"><a class="text-carousel" href="anime/<?php echo $value['link'];?>" title="<?php echo $value['name'];?>"><?php echo $value['name'];?></a></div>
                        </div>
                    </div>


                    <?php endforeach;?>

                    
                        <?php //for ($i=0; $i < 2; $i++) { ?>
                        <!--div class="item">
                            <div class="position-relative"><a href="#!">
                                    <div class="anime-grid-lazy loaded"
                                        style="background-image: url(https://animego.org/media/cache/thumbs_300x420/upload/anime/images/6251bccd0c189924153791.jpg); opacity: 1;">
                                    </div>
                                </a>
                            </div>
                            <div class="card-body px-0">
                                <div class="h5"><a class="text-carousel" href="#!" title="Семья шпиона">Семья
                                        шпиона</a></div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="position-relative"><a href="#!">
                                    <div class="anime-grid-lazy loaded"
                                        style="background-image: url(https://animego.org/media/cache/thumbs_300x420/upload/anime/images/626fcd64ae476063341526.jpg); opacity: 1;">
                                    </div>
                                </a>
                            </div>
                            <div class="card-body px-0">
                                <div class="h5"><a class="text-carousel" href="#!" title="Книга
                                            магии для начинающих с нуля">Книга
                                        магии для начинающих с нуля</a></div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="position-relative"><a href="#!">
                                    <div class="anime-grid-lazy loaded"
                                        style="background-image: url(https://animego.org/media/cache/thumbs_300x420/upload/anime/images/625479c6c3aa7500894859.jpg); opacity: 1;">
                                    </div>
                                </a>
                            </div>
                            <div class="card-body px-0">
                                <div class="h5"><a class="text-carousel" href="#!" title="Она
                                            и её кот: Всё меняется">Она
                                        и её кот: Всё меняется</a></div>
                            </div>
                        </div-->
                        <?php //} ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>