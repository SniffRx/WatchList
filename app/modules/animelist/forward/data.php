<?php

use app\modules\animelist\ext\AnimeList;

$Router->map( 'GET|POST', 'anime/', 'animelist' );
$Router->map( 'GET|POST', 'anime/filter/', 'filter' );
$Router->map( 'GET|POST', 'anime/[:animename]/', 'animeitem' );

$Match = $Router->match();

//print_r($Match);

//print_r($Match['target']);

$animetarget = $Match['target'] ?? 0;

$animeitem = $Match['params']['animename'] ?? 0;

//$animeitem = substr($_SERVER['REQUEST_URI'], 7, -1);

$AnimeList = new AnimeList( $Main, $DataBase, $Modules);

isset( $_POST['watch_now'] ) && $AnimeList->PushUserList($animeitem, 'watch_now');
isset( $_POST['will'] ) && $AnimeList->PushUserList($animeitem, 'will');
isset( $_POST['watched'] ) && $AnimeList->PushUserList($animeitem, 'watched');
isset( $_POST['lost'] ) && $AnimeList->PushUserList($animeitem, 'lost');
isset( $_POST['favourite'] ) && $AnimeList->PushUserList($animeitem, 'favourite');

// echo $animeitem = substr($animeitem, 0, -1);

//print_r($AnimeList->GetPage($animeitem));
?>