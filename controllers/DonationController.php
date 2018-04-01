<?php
/**
 * @author Stefano Campanella
 * Date: 04/03/18
 * Time: 22.20
 */

namespace site\controllers;


use nigiri\Controller;

class DonationController extends Controller
{
    public function actionIndex(){
        $amount = empty($_GET['amount'])?0:(int)$_GET['amount'];
        return self::renderView("donation/index", ['amount' => $amount]);
    }

    public function actionConfirmation() {
        return self::renderView("donation/confirmation", [
          'donation_id' => empty($_GET['donation'])?'':$_GET['donation'],
          'reward' => empty($_GET['reward'])?false:(bool)$_GET['reward']]);
    }
}
