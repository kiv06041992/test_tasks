<?php

spl_autoload_register(function ($class) {
    include 'class/' . $class . '.php';
});

$gift = new Gift();
$db = DB::getDB();

$quantity = intval($argv[1]);
if (!$quantity) {
	$quantity = 2;
}


$ug = $db->query("SELECT ug.id, ug.quantity, u.email, g.name FROM user_gifts AS ug 
					LEFT JOIN gifts AS g ON ug.gift_id=g.id
					LEFT JOIN users AS u ON ug.user_id=u.id
				WHERE g.type=1 AND ug.status=2
				ORDER BY ug.id ASC
				LIMIT {$quantity}");

$i=0;
while ($row = $ug->fetch(PDO::FETCH_ASSOC)) {
	$i++;
	$arID[$row['id']] = $row['id'];
	//APIBank
	echo $i.':'. $row['name'].':'.$row['quantity'].':'.$row['email']."\r\n";
}


if ($arID) {
	$db->query("UPDATE user_gifts SET status=3 WHERE id IN (".implode(",", $arID).")");
}