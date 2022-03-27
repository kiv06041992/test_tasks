<?php

spl_autoload_register(function ($class) {
    include 'class/' . $class . '.php';
});


$request 	= $_REQUEST;
$url 	 	= $_REQUEST['path'];
$user 		= new User();
$gift 		= new Gift();

switch ($url) {
	case '':
	case '/': {
		$page = ($user->isAuth)?'gift':'auth';
		View::showPage($page);
	} break;
	case '/user/auth': {
		if (!$user->isAuth) {
			$user->auth($request['email'], $request['password']);
		} 
		
		if ($user->isAuth) {
			echo 1;
		} else {
			echo 0;
		}
	} break;
	case '/gift/get': {
		if ($user->isAuth) {
			
			$randGift = $gift->getRandomGift();
			$rID = $gift->setUserGifts($user->id, $randGift);
			
			if ($rID) {
				$r = $gift->get($randGift['gift_id']);
				echo json_encode(['id' => $rID,
								'name' => $r['name'],
								 'quantity' => $randGift['quantity']]);
			} else {
				echo json_encode(['error' => 'произошла ошибка']);
			}
		}
	} break;
	case '/gift/status/set': {
		if ($user->isAuth) {
			$r = $gift->getUserGifts($request['id']);
			if ($r['user_id'] == $user->id) {
				$gift->setGiftStatus($request['id'], $request['status']);
			}
		}
	} break;
	default: {
		header("HTTP/1.0 404 Not Found");
	}
}