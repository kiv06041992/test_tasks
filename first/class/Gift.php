<?php
/**
 * Класс для работы с подарками
 * получения/розыгрыш/изменение статуса
 *
 */
 
class Gift {
	private $db;
	
	public function __construct() {
		$this->db = DB::getDB();
	}
	
	/**
	 * Возвращает подарок по его id
	 *
	 * @param int $id id подарка
	 *
	 * @return array 
	 */
	public function get($id) {
		$gift = $this->db->prepare("SELECT * FROM gifts WHERE id=:id");
		$gift->execute(['id' => $id]);
		
		return $gift->fetch(PDO::FETCH_ASSOC);
	}
	
	/**
	 * Возвращает связь пользователь-подарок по id связи
	 *
	 * @param int $id id связи пользователь-подарок
	 *
	 * @return array
	 */
	public function getUserGifts($id) {
		$gift = $this->db->prepare("SELECT * FROM user_gifts WHERE id=:id");
		$gift->execute(['id' => $id]);
		
		return $gift->fetch(PDO::FETCH_ASSOC);
	}
	
	/**
	 * Метод выбирает случайный подорк из базы.
	 * Учитывается оставшиеся количество и размер возможного подарка
	 */
	public function getRandomGift() {
		//выбираем рандомный подарок
		$gift = $this->db->query("SELECT * FROM gifts 
							WHERE (`quantity` > 0 AND `quantity` >= `min`) OR `quantity` = -1 
							ORDER BY RAND() LIMIT 1");
		$gift = $gift->fetch(PDO::FETCH_ASSOC);
		
		$min = $gift['min'];
		//вычисляем максимально возможный размер подарка
		$max = ($gift['quantity'] == -1 || $gift['max'] <= $gift['quantity'])?$gift['max']:$gift['quantity'];
		
		$quantity = rand($min, $max);
		
		return ['gift_id' => $gift['id'],
				'quantity' => $quantity];
		
	}
	
	/**
	 * Присваивает пользователю подарок и уменьшает количество подарков
	 *
	 * @param int   $userID   id пользователя
	 * @param arrat $dataGift массив содержащий id подарка и его количество
	 *
	 * @return int  $rID      id записи пользователь-подарок
	 */
	public function setUserGifts($userID, $dataGift) {
		$db = $this->db->prepare("INSERT INTO `user_gifts` (`user_id`, `gift_id`, `status`, `quantity`, `date_create`, `date_send`) VALUES 
			(:user_id, :gift_id, 1, :quantity, NOW(), '0000-00-00 00:00:00')");
		$db->execute(['user_id' => $userID,
					 'gift_id' 	=> $dataGift['gift_id'],
					 'quantity' => $dataGift['quantity']]);
		$rID = $this->db->lastInsertId();
		
		//если количество подарков ограничено
		$gift = $this->get($dataGift['gift_id']);
		if ($gift['quantity'] != -1) {
			//уменьшаем количество остатков 
			$db = $this->db->prepare("UPDATE `gifts` SET `quantity`=quantity-:quantity WHERE  `id`=:gift_id;");
			$db->execute(['gift_id' 	=> $dataGift['gift_id'],
						  'quantity' => $dataGift['quantity']]);
		}
		
		return $rID;
		
	}
	
	/**
	 * Устанавливаем статус подарка
	 * 
	 * @param int $id     id связи пользователь-подарок
	 * @param int $status статус связи 1-ожидаем выбор статуса;2-подарок принят;3-отправлен;4-не принят
	 */
	public function setGiftStatus($id, $status) {
		$r = $this->getUserGifts($id);
		//если пользователь отказался от подарка
		if ($status == 4) {
			$gift = $this->get($r['gift_id']);
			//если количество ограничено и ждем подтверждение от пользователя
			//нужно вернуть подарок
			if ($gift['quantity'] != -1 && $r['status'] == 1) {
				//возвращаем подарок
				$db = $this->db->prepare("UPDATE `gifts` SET `quantity`=quantity+:quantity WHERE  `id`=:gift_id;");
				$db->execute(['gift_id' => $r['gift_id'],
							 'quantity'  => $r['quantity']]);
			}
		}
		
		//нельзя изменить статус если уже отказались от подарка или если его уже отправили
		if ($r['status'] != 4 && $r['status'] != 3 )
			$db = $this->db->prepare("UPDATE `user_gifts` SET `status`=:status WHERE  `id`=:id");
			$db->execute(['id' => $id,
						 'status' => $status]);
	}
}