<?php

//print_r($Admin);

//if(! isset( $_SESSION['user_admin'] )) echo "Код ошибки: 013. Доступ закрыт!";die();?>
<div class="container pt-5">
<div class="row">
    <?php switch ( get_section( 'section', 'modules' ) ) {
        case 'modules':
            require MODULES . 'adminpanel' . '/includes/modules.php';
            break;
        /*case 'servers':
            require MODULES . 'adminpanel' . '/includes/servers.php';
            break;
        case 'db':
            require MODULES . 'adminpanel' . '/includes/db.php';
            break;
        case 'web':
            require MODULES . 'adminpanel' . '/includes/web.php';
            break;
         case 'stats':
            require MODULES . 'adminpanel' . '/includes/stats.php';
            break;*/
        default:
            require MODULES . 'adminpanel' . '/includes/general.php';
            break;
    }?>
</div>
</div>