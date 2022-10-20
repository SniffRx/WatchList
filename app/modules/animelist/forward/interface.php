<?php

if(isset($animetarget) && !empty($animetarget) && $animetarget == 'animelist') {
    //$animedata = $AnimeList->getAnimeList();
    require MODULES . 'animelist' . '/includes/animelist.php';
} else if(isset($animetarget) && !empty($animetarget) && $animetarget == 'filter') {
    //$animedata = $AnimeList->filtering();
    require MODULES . 'animelist' . '/includes/filter.php';
    //echo 'Тут будет всё по фильтру!';
} else if(isset($animeitem) && !empty($animeitem)) {
    //print_r($AnimeList->GetPage($animeitem));
    require MODULES . 'animelist' . '/includes/animeitem.php';
} else {}//header('Location: /');}

?>