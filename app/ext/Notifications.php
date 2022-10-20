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

class Notifications {

    public $Modules;
    public $DataBase;

    function __construct( $DataBase ) {

        // Проверка на основную константу.
        defined('AL') != true && die();

        $this->DataBase = $DataBase;

        // Чет оно шизу ловит
        //$this->NotificationDelete();

		$this->NotificationsRender();
	}

/**
    * Функция оправки уведемлений
    *
    * @param string $username 		Стим айди уведомляемого
    * @param string $text 			Название перевода
    * @param string $url 			Ссылка, например на платеж
    */
    public function SendNotification( $username, $text, $url ) {
        //Проверка Параметров на пустоту
        if($values_insert == null){
            //Если пустые укажем для json что это массив
            $values_insert =[];
        }
        //Устанавливаем параметры для SQL запроса
        $param = [
            'username' => $username,
            'text' => $text,
            'url' => $url
        ];
        $this->DataBase->query("INSERT INTO `notifications`(`login`, `text`, `url`, `seen`, `status`) VALUES ('{$param['username']}', '{$param['text']}', '{$param['url']}', 0, 0)");
    }
    
    /**
    * Функция прослушивания на пост запрос о выводе уведемлений
    */
	public function NotificationsRender() {
		//Проверка на ссессию авторизации и на POST запросы
		if( ! empty( $_SESSION['logged'] ) && ! empty( $_POST['notific'] ) || ! empty( $_SESSION['logged'] ) && ! empty( $_POST['entryid'] ) )
		{
			if(!empty($_POST['notific'])){
				//Если POST о просмотре запроса вызываем функцию обновления уведемления просмотра
				$this->NotificationUpdate($_POST['notific']);
		    } else {
		    	//Вызываем функцию поиска не прочтенных уведомлений
		        $unread = $this->NotificationsEach(true);
		        //Подсчитываем сколько не прочтенных
		        $unread_count = count($unread);
		        //---
		        $count = 0;
		        //Крутим массив полученых данных
		        foreach ($unread as $notification) {
		        	//проверка если уведомлени не больше 6 То подготавливаем html вывод последних 6 уведомлений
		            if ($count < 6) {
		                $notifications[] = array(
		                    'id' => $notification['id'],
		                    'seen' => $notification['seen'],
		                    'url' => $notification['url'],
		                    'html' => '<li onclick="main_notifications_chek('.$notification['id'].')" class="notifications-item list-group-item">
									    	<a href="'.$notification['url'].'" class="list-group-item-text">
									    		<div class="row">
									    			<div class="icon">
										    			Icon
										    		</div>
										    		<div class="text">
										    			'.$notification['text'].'
										    		</div>
									    		</div>
									    	</a>
									 	</li>',
		                );
		                ++$count;
		            }
		        }
		        //Вывод
                if ( ! empty( $notifications ) ):
                    echo json_encode(array('count' => $unread_count, 'no_notifications' => 'No_Notifications', 'notifications' => array_reverse($notifications)));
                    exit;
                else:
                    echo json_encode(array('count' => $unread_count, 'no_notifications' => 'No_Notifications', 'notifications' => null));
                    exit;
                endif;
		    }
		}
	}

	/**
    * Функция подготовки вывода уведомлений 
    *
    * @param bool $view 	параметр для звукового уведомления
    *
    * @return array         Уведомления которые ещё не были показаны, актуальноость.
    */
	public function NotificationsEach( $view ) {
        $param = ['username'=> $_SESSION['logged']];
        $NotificationsEach = $this->DataBase->queryAll("SELECT * FROM `notifications` WHERE `status` = 0 AND `login` = '$param[username]' ORDER BY `id` DESC");
        $deliver = [];
        
        foreach($NotificationsEach as $notification){
            $values = json_decode($notification['values_insert']);

            $text = $notification['text'];

            if(!$values){
                $values = [];
            }
            //Заменяем параметры для мультиязычности
            foreach($values as $key => $val){
                $text = str_replace('%' . $key . '%', $val, $text);
            }
            //собираем параметры в массив
            $deliver[] = array('id' => $notification['id'], 'text' => $text, 'seen' => $notification['seen'], 'url' => $notification['url']);
            //Обновлям параметра для звукового уведомления
            if($view && !$notification['seen']){
                $this->DataBase->query("UPDATE `notifications` SET `seen` = 1 WHERE `login` = '$param[username]'");
            }
        }
        return $deliver;
    }

    /**
    * Функция обновления статуса уведомлений на прсмотренные 
    *
    * @param int $id 	Айди уведомления
    */
    public function NotificationUpdate($id) {
    	$param = ['username'=> $_SESSION['logged'],'id'=> $id];
        $this->DataBase->query("UPDATE `notifications` SET `status` = 1 WHERE `login` = '$param[username]' AND `id` = $param[id]");
    }
    
    /**
    * Удалить просмотренные уведомления
    */
    public function NotificationDelete() {
        $this->DataBase->query("DELETE FROM `notifications` WHERE `seen` = '1'");
    }

    /**
    * Функция вывода всех уведомлений
    *
    */
   	public function GetAllNotifications() {
   		$param = ['username' => $_SESSION['logged']];
        return $this->DataBase->queryAll("SELECT * FROM `notifications` WHERE `login` = '$param[username]'  ORDER BY `date` DESC");
   	}

   	/**
    * Функция обновления статуса уведомлений на прсмотренные 
    *
    */
   	public function MarkAllNotifications() {
   		$param = ['username'=> $_SESSION['logged']];
        return $this->DataBase->queryAll("UPDATE `notifications` SET `status` = 1 WHERE `login` = :'$param[username]'");
   	}

    public function debuggg(){
        echo 'ok';
    }
}