<?

$fk_merchant_id = '62662'; //merchant_id ID магазина free-kassa.ru http://free-kassa.ru/merchant/cabinet/help/
$fk_merchant_key = 'x9xywfdi'; //Секретный ключ http://free-kassa.ru/merchant/cabinet/profile/tech.php

if (isset($_GET['prepare_once'])) {
    $hash = md5($fk_merchant_id.":".$_GET['oa'].":".$fk_merchant_key.":".$_GET['l']);
    echo '<hash>'.$hash.'</hash>';
    exit;
}
?>