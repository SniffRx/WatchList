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

namespace app\modules\animelist\ext;

class AnimeList {

	/**
     * @var object
     */
    public $DataBase;

	function __construct( $Main, $DataBase, $Modules ) {
		// Проверка на основную константу.
        defined('AL') != true && die();

        // Работа с базой данных.
        $this->DataBase = $DataBase;

        // Работа с ядром.
        $this->Main = $Main;

        // Работа с модулями.
        $this->Modules = $Modules;
	}

	public function getAnimeList() {
		return $this->DataBase->queryAll("SELECT * FROM anime LIMIT 10");
	}

	public function getNewAnimeList($count) {
		return $this->DataBase->queryAll("SELECT * FROM anime ORDER BY id DESC LIMIT ".$count);

	}

    public function GetPage($animeitem) {
        //if(empty($animeitem)) return 'anime: '.$animeitem;
        // $animename = $this->translateanimeurl($animeitem);
        $namesql = $this->DataBase->query("SELECT * FROM anime WHERE link = '".$animeitem."'");
        if(isset($namesql) && !empty($namesql)) return $namesql;
    }

    public function GetTags() {
        return $this->DataBase->queryAll("SELECT anime.*, GROUP_CONCAT(tags.name SEPARATOR ', ') AS tags, GROUP_CONCAT(tags.link SEPARATOR ', ') AS tag_link
        FROM       anime
        LEFT JOIN  anime_tags ON anime_tags.item_id = anime.id
        LEFT JOIN  tags ON anime_tags.tag = tags.id
        group by anime.id");

    }

    public function GetAnime($animeitem) {
        return $this->DataBase->query("SELECT uwatch.watch, uwill.will, uwatched.watched, ulost.lost, ufavourite.favourite, users_animelist.allwatch, anime.*, GROUP_CONCAT(tags.name SEPARATOR ', ') AS tags, GROUP_CONCAT(tags.link SEPARATOR ', ') AS tag_link
        FROM       anime
        LEFT JOIN  anime_tags ON anime_tags.item_id = anime.id
        LEFT JOIN  tags ON anime_tags.tag = tags.id
        JOIN (SELECT anime.id, COUNT(watch_now) AS watch FROM users_animelist LEFT JOIN anime ON anime.id = users_animelist.item WHERE anime.link = '".$animeitem."' AND watch_now = 1) uwatch
        JOIN (SELECT anime.id, COUNT(will) AS will FROM users_animelist LEFT JOIN anime ON anime.id = users_animelist.item WHERE anime.link = '".$animeitem."' AND will = 1) uwill
        JOIN (SELECT anime.id, COUNT(watched) AS watched FROM users_animelist LEFT JOIN anime ON anime.id = users_animelist.item WHERE anime.link = '".$animeitem."' AND watched = 1) uwatched
        JOIN (SELECT anime.id, COUNT(lost) AS lost FROM users_animelist LEFT JOIN anime ON anime.id = users_animelist.item WHERE anime.link = '".$animeitem."' AND lost = 1) ulost
        JOIN (SELECT anime.id, COUNT(favourite) AS favourite FROM users_animelist LEFT JOIN anime ON anime.id = users_animelist.item WHERE anime.link = '".$animeitem."' AND favourite = 1) ufavourite
        JOIN (SELECT anime.id, item, SUM(watch_now + will + watched + lost + favourite) AS allwatch FROM users_animelist LEFT JOIN anime ON anime.id = users_animelist.item WHERE anime.link = '".$animeitem."') users_animelist
        WHERE anime.link = '".$animeitem."'
        GROUP by anime.id");
    }

    public function GetAnimeProfile($animeitem) {
        $animeid = $this->GetAnime($animeitem);
        $animeid = $animeid['id'];
        return $this->DataBase->query("SELECT watch_now,will,watched,lost,favourite,item
        FROM users_animelist
        WHERE item = $animeid AND login_id = ".$_SESSION['userid']." GROUP BY item");
    }

    public function PushUserList($animeitem, $uanimestatus) {
        $animeid = $this->GetAnimeProfile($animeitem);
        $animelogin = $_SESSION['userid'];
        if($animeid == NULL) {
            $animeid = $this->GetAnime($animeitem);
            $animeid = $animeid['id'];
            return $this->DataBase->query("INSERT INTO users_animelist (login_id, item, ".$uanimestatus.") VALUES (".$animelogin.", ".$animeid.", 1)");
        } else {
            if($animeid['watch_now'] == 0 && $animeid['will'] == 0 && $animeid['watched'] == 0 && $animeid['lost'] == 0 && $animeid['favourite'] == 0) {
                $animeid = $animeid['item'];
                return $this->DataBase->query ('DELETE FROM users_animelist WHERE item = "'.$animeid.'" AND login_id = "'.$animelogin.'"');
            } else {
                if(isset($animeid['watch_now']) && $animeid['watch_now'] == 1) {$animelast = "watch_now";}
                if(isset($animeid['will']) && $animeid['will'] == 1) {$animelast = "will";}
                if(isset($animeid['watched']) && $animeid['watched'] == 1) {$animelast = "watched";}
                if(isset($animeid['lost']) && $animeid['lost'] == 1) {$animelast = "lost";}
                if(isset($animeid['favourite']) && $animeid['favourite'] == 1) {$animelast = "favourite";}
                $animeid = $animeid['item'];
                return $this->DataBase->query ('UPDATE users_animelist SET '.$uanimestatus.' = 1, '.$animelast.' = 0 WHERE item = "'.$animeid.'" AND login_id = "'.$animelogin.'"');
            }            
        }
    }
}


/*


SELECT users_animelist.watch, users_animelist.will, users_animelist.watched, users_animelist.lost, users_animelist.favourite, users_animelist.allwatch, anime.*, GROUP_CONCAT(tags.name SEPARATOR ', ') AS tags, GROUP_CONCAT(tags.link SEPARATOR ', ') AS tag_link
        FROM       anime
        LEFT JOIN  anime_tags ON anime_tags.item_id = anime.id
        LEFT JOIN  tags ON anime_tags.tag = tags.id
        LEFT JOIN (SELECT anime.id, item, COUNT(watch_now) AS watch, COUNT(will) AS will, COUNT(watched) AS watched, COUNT(lost) AS lost, COUNT(favourite) AS favourite, SUM(watch_now + will + watched + lost + favourite) AS allwatch FROM users_animelist LEFT JOIN anime ON anime.id = users_animelist.item WHERE anime.link = 'spice-and-wolf' AND watch_now = 1 OR will = 1 OR watched = 1 OR lost = 1 OR favourite = 1 GROUP by anime.id) users_animelist
        WHERE anime.link = '".$animeitem."'
        GROUP by anime.id




*/