<?PHP
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
$_OPTIMIZATION["title"] = "Захватывающая онлайн игра";
$_OPTIMIZATION["description"] = "Захватывающая онлайн игра с выводом реальных денег, которая увлечет всех, кто интересуется финансами и доходами.";
$_OPTIMIZATION["keywords"] = "бонусы, payeer, заработок, без вложений, доход, кран, игра, серфинг, прибыль, вложения, заработать, ферма с выводом, дальнобойщики2017";

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

# Шапка
@include("inc/_header2.php");

# Блокировка сессии
if(!isset($_SESSION["user_id"])){ Header("Location: /"); return; }

		if(isset($_GET["menu"])){

			$menu = strval($_GET["menu"]);

			switch($menu){

		case "promo": include("account/_promo.php"); break; // Промо материалы
		case "partnership": include("account/_partnership.php"); break; // Партнерская программа
		case "myreferrals": include("account/_myreferrals.php"); break; // Список рефералов
		case "exchange": include("account/_exchange.php"); break; // Обменный пункт
		case "outpay": include("account/_outpay.php"); break; // Выплата пользователю
		case "insert": include("account/_insert.php"); break; // Пополнение баланса
		case "settings": include("account/_settings.php"); break; // Настройки
		case "chat": include("account/_chat.php"); break; // Настройки
		case "daily": include("account/_daily.php"); break; // Ежедневный бонус
		case "user_bonus": include("account/_user_bonus.php"); break; // Достижения
		case "bankomet": include("account/_back.php"); break; // Банкомет
		case "lottery": include("account/_lottery.php"); break; // Лотерея
		case "wall": include("account/_wall.php"); break; // Стена пользователя
		case "carpark": include("account/_carpark.php"); break; // Покупка авто и сбор прибыли
		case "claim": include("account/_claim.php"); break; // Собрать прибыль
		case "training": include("account/_training.php"); break; // Собрать прибыль
		case "levels": include("account/_levels.php"); break; // Уровни
		case "profile": include("account/_user_account.php"); break; // Уровни
		case "calculator": include("account/_calculator.php"); break; // Уровни
case "otziv": include("pages/_otziv.php"); break; // Отзывы
        case "chat": include("pages/account/_chat.php"); break; // ЧАТ

		# серфинг
case "serfing": include("account/_serfing.php"); break; // серфинг
case "serfing_add": include("account/_serfing_add.php"); break; // серфинг
case "serfing_view": include("account/_serfing_view.php"); break; // серфинг
case "serfing_cabinet": include("account/_serfing_cabinet.php"); break; // серфинг


		case "output": @session_destroy(); Header("Location: /"); return; break; // Выход

			# Страница ошибки
			default: @include("pages/_404.php"); break;

			}

		}else @include("account/_user_account.php");

# Подвал
@include("inc/_footer2.php");

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
?>