<?php
class View {
	public static function showPage($page) {
		View::showHeader();
		View::showBody($page);
		View::showFooter($page);
	}
	
	public static function showHeader() {
		?>
			<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
		<?
	}
	
	public static function showBody($page) {
		?>
		<div id="content">
		<?
		switch($page) {
			case 'auth': {
				?>
					<input placeholder="email" name="email" type="text"><br><br>
					<input placeholder="password" name="password" type="password"><br><br>
					<button id="auth">Войти</button>
					
				<?
			} break;
			case 'gift': {
				?>
					<button id="get_gift">Получить подарок</button>
				<?
			} break;
		}
		?>
		</div>
		<?
	}
	
	public static function showFooter($page) {
		switch($page) {
			case 'auth': {
				?>
					<script>
						$("#auth").click(function() {
							flag = true;
							email 		= $("input[name='email']").val();
							password 	= $("input[name='password']").val();
						
							if (email == '') {
								alert('Заплните поле email');
								flag = false;
							}
							
							if (password == "") {
								alert("Заплните поле password");
								flag = false;
							} 
							
							$.post("/", {path: "/user/auth", email: email, password: password}, function(d) {
								if (d == 1) {
									location.href="/";
								} else {
									alert('Неправильный email или password');
								}
							});
						});
					</script>
				<?
			} break;
			case 'gift': {
				?>
					<script>
						
						$("#get_gift").click(function() {
							$.post('/', {path: '/gift/get'}, function(d) {
								d = JSON.parse(d);
								if (!d.error) {
									
									$("#content").html("Вы выиграли: " + d.name);
									$("#content").append("<br>");
									$("#content").append("В количестве: " + d.quantity);
									$("#content").append("<br>");
									$("#content").append("<button class='action_gift' value='2'>Принять</button>");
									$("#content").append("<button class='action_gift' value='4'>Отказаться</button>");
									$("#content").append("<button disabled>Конвертировать</button>");
									
									giftID = d.id;
									
									$(".action_gift").on("click", function() {
										status = $(this).val();
										$.post('/', {path: '/gift/status/set', status: status, id: giftID}, function() {
											alert('Играем дальше');
											location.href='/';
										});
									});
									
								} else {
									alert(d.error);
								}
							});
							
						});
						
						
					</script>
				<?
			}
		}
	}
}