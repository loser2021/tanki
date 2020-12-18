<?PHP
# Автоподгрузка классов
function __autoload($name){ include("classes/_class.".$name.".php");}

# Класс конфига
$config = new config;

# Функции
$func = new func;

# База данных
$db = new db($config->HostDB, $config->UserDB, $config->PassDB, $config->BaseDB);

//extract($_POST);

$fk_merchant_id = '62662'; //merchant_id ID мазагина в free-kassa.ru (http://free-kassa.ru/merchant/cabinet/help/)
$fk_merchant_key = 'x9xywfdi'; //Секретное слово http://free-kassa.ru/merchant/cabinet/profile/tech.php
//$fk_merchant_key2 = '08r82dhn'; //Секретное слово2 (result) http://free-kassa.ru/merchant/cabinet/profile/tech.php


$hash = md5($fk_merchant_id.":".$_POST['AMOUNT'].":".$fk_merchant_key.":".$_POST['MERCHANT_ORDER_ID']);

 if ($hash != $_POST['SIGN'])
 {


$db->Query("SELECT * FROM db_payeer_insert WHERE id = '".intval($_POST['MERCHANT_ORDER_ID'])."'");
	if($db->NumRows() == 0){ echo $_POST['MERCHANT_ORDER_ID']."|error"; exit;}

	$payeer_row = $db->FetchArray();
	if($payeer_row["status"] > 0){ echo $_POST['MERCHANT_ORDER_ID']."|success"; exit;}

	$db->Query("UPDATE db_payeer_insert SET status = '1' WHERE id = '".intval($_POST['MERCHANT_ORDER_ID'])."'");

	$ik_payment_amount = $payeer_row["sum"];
	$user_id = $payeer_row["user_id"];

	# Настройки
	$db->Query("SELECT * FROM db_config WHERE id = '1' LIMIT 1");
	$sonfig_site = $db->FetchArray();

   $db->Query("SELECT user, referer_id FROM db_users_a WHERE id = '{$user_id}' LIMIT 1");
   $user_ardata = $db->FetchArray();
   $user_name = $user_ardata["user"];
   $refid = $user_ardata["referer_id"];

   # Зачисляем баланс
   $serebro = $ik_payment_amount;

   $db->Query("SELECT ref_proc FROM db_users_b WHERE id = '{$refid}' LIMIT 1");
   $ref_proc = $db->FetchRow();

   $db->Query("SELECT insert_sum FROM db_users_b WHERE id = '{$user_id}' LIMIT 1");
   $ins_sum = $db->FetchRow();

   /* ====== Рефералка 3 уровней ====== */
$db->Query("SELECT user, referer_id, referer_id2, referer_id3 FROM db_users_a WHERE id = '{$user_id}' LIMIT 1");
    $user_ardata = $db->FetchArray();
    $ref2 = $user_ardata["referer_id2"];
    $ref3 = $user_ardata["referer_id3"];

    # Задаем процент рефки
    $to_referer  = ($serebro * 0.12)+($serebro*$ref_proc); // Первый уровень - 7 процента
    $to_referer2 = ($serebro * 0.03); // Второй уровень - 2 процента
    $to_referer3 = ($serebro * 0.01); // Третий уровень - 1 процент

    $db->Query("UPDATE db_users_b SET money_p = money_p + $to_referer2 WHERE id = '$ref2'");
    $db->Query("UPDATE db_users_b SET money_p = money_p + $to_referer3 WHERE id = '$ref3'");
    $db->Query("UPDATE db_users_a SET doxod2 = doxod2 + $to_referer2 WHERE id = '$user_id'");
    $db->Query("UPDATE db_users_a SET doxod3 = doxod3 + $to_referer3 WHERE id = '$user_id'");
    /* ====== /Рефералка 3 уровней ====== */
   # Добавляем 5%
      $proc=$serebro+$serebro*0.1;


   /* if($sum >= 1000){
    $proc=$serebro+$serebro*1;

    }else{

    $proc=$serebro+$serebro*0.05;
    }*/

   $lsb = time();

   $db->Query("UPDATE db_users_b SET money_b = money_b + '$proc', pay_points = pay_points + '$to_pay_points', a_t = a_t + '$add_tree', last_sbor = '$lsb', insert_sum = insert_sum + '$ik_payment_amount' WHERE id = '{$user_id}'");

   # Зачисляем средства рефереру и дерево
   $db->Query("UPDATE db_users_b SET money_p = money_p + $to_referer, from_referals = from_referals + '$to_referer'  WHERE id = '$refid'");




   # Статистика пополнений
   $da = time();
   $dd = $da + 60*60*24*15;
   $db->Query("INSERT INTO db_insert_money (user, user_id, money, serebro, date_add, date_del)
   VALUES ('$user_name','$user_id','$ik_payment_amount','$serebro','$da','$dd')");

   # Конкурс инвесторов
$usname = $user_name;
$db->Query("INSERT INTO db_invcompetition_users (user, user_id, points) VALUES ('$usname','$user_id','0')");

$db->Query("SELECT * FROM db_invcompetition WHERE status = '0' LIMIT 1");
$invcomp = $db->FetchArray();

$db->Query("SELECT COUNT(*) FROM db_invcompetition_users WHERE user_id = '{$user_id}'");
$rett = $db->FetchArray();

if ($invcomp["date_add"] >= 0 AND $invcomp["date_end"] > $da){
$db->Query("UPDATE db_invcompetition_users SET points = points + '$ik_payment_amount' WHERE user_id = '$user_id'");
} else
$db->Query("UPDATE db_invcompetition_users SET points = points + '0' WHERE user_id = '$user_id'");


# Конкурс
$competition = new competition($db);
$competition->UpdatePoints($user_id, $ik_payment_amount);

# Платежные баллы
$pp = new pay_points($db);
$pp ->UpdatePayPoints($ik_payment_amount,$user_id);

$db->Query("UPDATE db_users_b SET a_t = a_t + '$a_t', b_t = b_t + '$b_t', c_t = c_t + '$c_t', d_t = d_t + '$d_t', e_t = e_t + '$e_t',  f_t = f_t + '$f_t', last_sbor = '$lsb' WHERE id = '{$user_id}'");

	# Обновление статистики сайта
	$db->Query("UPDATE db_stats SET all_insert = all_insert + '$ik_payment_amount' WHERE id = '1'");


	   require_once 'sms.ru.php';

$smsru = new SMSRU('577a10de-db2e-18f4-d527-d145dbfe0a4b'); // Ваш уникальный программный ключ, который можно получить на главной странице

$data = new stdClass();
$data->to = '380990443832';
$data->text = 'Free-Kassa + '.$serebro.''; // Текст сообщения
$data->from = 'TanksMoney'; // Если у вас уже одобрен буквенный отправитель, его можно указать здесь, в противном случае будет использоваться ваш отправитель по умолчанию
// $data->time = time() + 7*60*60; // Отложить отправку на 7 часов
// $data->translit = 1; // Перевести все русские символы в латиницу (позволяет сэкономить на длине СМС)
// $data->test = 1; // Позволяет выполнить запрос в тестовом режиме без реальной отправки сообщения
// $data->partner_id = '1'; // Можно указать ваш ID партнера, если вы интегрируете код в чужую систему
$sms = $smsru->send_one($data); // Отправка сообщения и возврат данных в переменную

if ($sms->status == "OK") { // Запрос выполнен успешно
    //echo "Сообщение отправлено успешно. ";
    //echo "ID сообщения: $sms->sms_id. ";
    //echo "Ваш новый баланс: $sms->balance";
} else {
    //echo "Сообщение не отправлено. ";
    //echo "Код ошибки: $sms->status_code. ";
    //echo "Текст ошибки: $sms->status_text.";
}


die('YES');
 exit;
 }
?>