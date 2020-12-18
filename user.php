<?PHP
# �������
function TimerSet(){
	list($seconds, $microSeconds) = explode(' ', microtime());
	return $seconds + (float) $microSeconds;
}

$_timer_a = TimerSet();

#������ ������
if (!isset($_COOKIE['rsite'])) {
setcookie('rsite', $_SERVER['HTTP_REFERER'], time() + 24 * 3600);
}

# ����� ������
@session_start();

# ����� ������
@ob_start();

# Default
$_OPTIMIZATION = array();
$_OPTIMIZATION["title"] = "������������� ������ ����";
$_OPTIMIZATION["description"] = "������������� ������ ���� � ������� �������� �����, ������� ������� ����, ��� ������������ ��������� � ��������.";
$_OPTIMIZATION["keywords"] = "������, payeer, ���������, ��� ��������, �����, ����, ����, �������, �������, ��������, ����������, ����� � �������, �������������2017";

# ��������� ��� Include
define("CONST_RUFUS", true);

# ������������� �������
function __autoload($name){ include("classes/_class.".$name.".php");}

# ����� �������
$config = new config;

# �������
$func = new func;

# ��������� REFERER
include("inc/_set_referer.php");

# ���� ������
$db = new db($config->HostDB, $config->UserDB, $config->PassDB, $config->BaseDB);

$life_time = new life_time($db);
$life_time->CheckTime();

# �����
@include("inc/_header2.php");

# ���������� ������
if(!isset($_SESSION["user_id"])){ Header("Location: /"); return; }

		if(isset($_GET["menu"])){

			$menu = strval($_GET["menu"]);

			switch($menu){

		case "promo": include("account/_promo.php"); break; // ����� ���������
		case "partnership": include("account/_partnership.php"); break; // ����������� ���������
		case "myreferrals": include("account/_myreferrals.php"); break; // ������ ���������
		case "exchange": include("account/_exchange.php"); break; // �������� �����
		case "outpay": include("account/_outpay.php"); break; // ������� ������������
		case "insert": include("account/_insert.php"); break; // ���������� �������
		case "settings": include("account/_settings.php"); break; // ���������
		case "chat": include("account/_chat.php"); break; // ���������
		case "daily": include("account/_daily.php"); break; // ���������� �����
		case "user_bonus": include("account/_user_bonus.php"); break; // ����������
		case "bankomet": include("account/_back.php"); break; // ��������
		case "lottery": include("account/_lottery.php"); break; // �������
		case "wall": include("account/_wall.php"); break; // ����� ������������
		case "carpark": include("account/_carpark.php"); break; // ������� ���� � ���� �������
		case "claim": include("account/_claim.php"); break; // ������� �������
		case "training": include("account/_training.php"); break; // ������� �������
		case "levels": include("account/_levels.php"); break; // ������
		case "profile": include("account/_user_account.php"); break; // ������
		case "calculator": include("account/_calculator.php"); break; // ������
case "otziv": include("pages/_otziv.php"); break; // ������
        case "chat": include("pages/account/_chat.php"); break; // ���

		# �������
case "serfing": include("account/_serfing.php"); break; // �������
case "serfing_add": include("account/_serfing_add.php"); break; // �������
case "serfing_view": include("account/_serfing_view.php"); break; // �������
case "serfing_cabinet": include("account/_serfing_cabinet.php"); break; // �������


		case "output": @session_destroy(); Header("Location: /"); return; break; // �����

			# �������� ������
			default: @include("pages/_404.php"); break;

			}

		}else @include("account/_user_account.php");

# ������
@include("inc/_footer2.php");

# ������� ������� � ����������
$content = ob_get_contents();

# ������� �����
ob_end_clean();

# �������� ������
$content = str_replace("{!TITLE!}",$_OPTIMIZATION["title"],$content);
$content = str_replace('{!DESCRIPTION!}',$_OPTIMIZATION["description"],$content);
$content = str_replace('{!KEYWORDS!}',$_OPTIMIZATION["keywords"],$content);
$content = str_replace('{!GEN_PAGE!}', sprintf("%.5f", (TimerSet() - $_timer_a)) ,$content);

# ����� �������
	if(isset($_SESSION["user_id"])){

		$user_id = $_SESSION["user_id"];
		$db->Query("SELECT money_b, money_p FROM db_users_b WHERE id = '$user_id'");
		$balance = $db->FetchArray();

		$content = str_replace('{!BALANCE_B!}', sprintf("%.2f", $balance["money_b"]) ,$content);
		$content = str_replace('{!USER_ID!}', $user_id ,$content);
		$content = str_replace('{!BALANCE_P!}', sprintf("%.2f", $balance["money_p"]) ,$content);
	}

// ������� �������
echo $content;
?>