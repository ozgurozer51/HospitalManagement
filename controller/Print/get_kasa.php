<? include("../admin/config.php");

require __DIR__ . '/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

use Mike42\Escpos\PrintConnectors\NetworkPrintConnector; // ETHERNET

// $connector =  new WindowsPrintConnector("smb://DESKTOP-COIDOD1/incyazilim");  // PRINTER NAME
$connector =  new WindowsPrintConnector("\\DESKTOP-COIDOD1\incyazilim");  // PRINTER NAME
// if($_GET["yazici"]==1){
	
// $connector = new NetworkPrintConnector("192.168.1.7", 9100); // ETHERNET
	
// }else if($_GET["yazici"]==2){
	
// $connector = new NetworkPrintConnector("192.168.1.8", 9100); // ETHERNET
	
// }else {
// $connector = new NetworkPrintConnector("192.168.1.4", 9100); // ETHERNET
// }
$printer = new Printer($connector);

// MODES: 1 Normal, 2 Büyük, 3 Çok Büyük
$mode = 1;
if($mode == 1) {
$karakter_uzunlugu = 36;
$urun_isim_boyu = 23;
$rakam_isim_boyu = 4;
$fiyat_isim_boyu = 8; 
$pr_h = 1;
$pr_w = 1;
}
if($mode == 2) {
$karakter_uzunlugu = 36;
$urun_isim_boyu = 25;
$rakam_isim_boyu = 4;
$fiyat_isim_boyu = 8;
$pr_h = 2;
$pr_w = 1;
}
if($mode == 3) {
$karakter_uzunlugu = 14;
$urun_isim_boyu = 14;
$rakam_isim_boyu = 2;
$fiyat_isim_boyu = 8;
$pr_h = 2;
$pr_w = 2;
}
if($mode == 4) {
$karakter_uzunlugu = 19;
$urun_isim_boyu = 32;
$rakam_isim_boyu = 2;
$fiyat_isim_boyu = 8;
$pr_h = 1;
$pr_w = 2;
}

mb_internal_encoding("utf-8");
function str_pad_unicode($str, $pad_len, $pad_str = ' ', $dir = STR_PAD_RIGHT) {
    $str_len = mb_strlen($str);
    $pad_str_len = mb_strlen($pad_str);
    if (!$str_len && ($dir == STR_PAD_RIGHT || $dir == STR_PAD_LEFT)) {
        $str_len = 1; // @debug
    }
    if (!$pad_len || !$pad_str_len || $pad_len <= $str_len) {
        return $str;
    }

    $result = null;
    if ($dir == STR_PAD_BOTH) {
        $length = ($pad_len - $str_len) / 2;
        $repeat = ceil($length / $pad_str_len);
        $result = mb_substr(str_repeat($pad_str, $repeat), 0, floor($length))
                . $str
                . mb_substr(str_repeat($pad_str, $repeat), 0, ceil($length));
    } else {
        $repeat = ceil($str_len - $pad_str_len + $pad_len);
        if ($dir == STR_PAD_RIGHT) {
            $result = $str . str_repeat($pad_str, $repeat);
            $result = mb_substr($result, 0, $pad_len);
        } else if ($dir == STR_PAD_LEFT) {
            $result = str_repeat($pad_str, $repeat);
            $result = mb_substr($result, 0, 
                        $pad_len - (($str_len - $pad_str_len) + $pad_str_len))
                    . $str;
        }
    }

    return $result;
} 

$sorguC = $db->prepare("SELECT * FROM `order` ORDER BY id DESC");
$sorguC->execute(array($order_id));
$sorce = $sorguC->fetch(PDO::FETCH_ASSOC);


if($_GET['order_id'] != "") $order_id = $_GET['order_id'];

$table_idid = $sorce['id'];
$sorgu = $db->prepare("SELECT order.id,order.garson_id,tables.name FROM `order` LEFT JOIN tables ON order.table_id=tables.id WHERE order.id = ?");
$sorgu->execute(array($order_id));
$sor = $sorgu->fetch(PDO::FETCH_ASSOC);

$table_name = $sor['name'];
$garson_id = $sor['garson_id'];

	$sorgu = $db->prepare("SELECT * FROM `garsonlar` WHERE id = ?");
	$sorgu->execute(array($garson_id));
	$mus = $sorgu->fetch(PDO::FETCH_ASSOC);
	$isim = $mus['isim'];
	
	
// HEADER
$printer -> setJustification(Printer::JUSTIFY_CENTER);
$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);

$printer -> setEmphasis(true);
$printer -> text("VISION ANKARA");
$printer -> setEmphasis(false);

$printer -> feed();
$printer -> text("$table_name\n");
$printer -> feed();

$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> selectPrintMode();
$printer -> setTextSize($pr_w, $pr_h);


$printer -> text("-----------------------\n");

 
$tarih = date('d.m.Y');
$saat = date('H:i:s');

$printer -> setEmphasis(true);
$printer -> setTextSize(1, 1);
$printer -> text("Adisyon No : $table_idid\n");
$printer -> text("Tarih : $tarih\n");
$printer -> text("Garson : $isim\n");
$printer -> text("Saat : $saat\n");
$printer -> text("Masa : $table_name\n"); 
$printer -> setTextSize(2, 1);
$printer -> text("----------------\n");
$printer -> setEmphasis(false);
$sorgu = $db->prepare("SELECT id,product_name,count,product_price FROM `order_detail` WHERE order_id = ? order by id asc");
$sorgu->execute(array($order_id));
$order_details = $sorgu->fetchAll(PDO::FETCH_ASSOC);
$toplam = 0;


$printer -> setEmphasis(true);
$printer -> setTextSize(1, 1);
		$printer -> text("ÜRÜN                   ADET      TUTAR\n"); 
$printer -> text("-----------------------\n");
$printer -> setEmphasis(false);
foreach($order_details as $or) {
	$str = replace_tr($or['product_name']);
	$s_count = $or['count'];
	$fiyat = number_format($or['count'] * $or['product_price'],2);
	$toplam = $toplam + ($or['count'] * $or['product_price']);
	$str_l = strlen($str);
	if($str_l > $karakter_uzunlugu) {
		$start = 0;
		$page = ceil($str_l / $karakter_uzunlugu);
		for($i = 0;$i < $page;$i++) {
			$sub_part = substr($str, $start, $karakter_uzunlugu);
			$start = $start + $karakter_uzunlugu;
			$sub_part = str_pad_unicode($sub_part, $urun_isim_boyu);
			if($i == 0) {
				$s_count = str_pad($s_count, $rakam_isim_boyu, " ", STR_PAD_LEFT);
				$fiyat = str_pad($fiyat, $fiyat_isim_boyu, " ", STR_PAD_LEFT);
				$printer -> text("$sub_part$s_count$fiyat\n");
$printer -> setTextSize(1, 1);
				//echo "$sub_part$s_count$fiyat\n";
			}else{
$printer -> setTextSize(1, 1);
				$printer -> text("$sub_part\n");
				//echo "$sub_part\n";
			}
		}

	}else{
		$str = str_pad_unicode($str, $urun_isim_boyu);
		$s_count = str_pad($s_count, $rakam_isim_boyu, " ", STR_PAD_LEFT);
		$fiyat = str_pad($fiyat, $fiyat_isim_boyu, " ", STR_PAD_LEFT);
$printer -> setTextSize(1, 1);
		$printer -> text("$str$s_count$fiyat\n");
		//echo "$str$s_count$fiyat\n";
		
	}
}


$printer -> text("-----------------------\n");

// TOPLAM TARAFI
$printer -> feed();
$printer -> setEmphasis(true);
$toplam_str = str_pad("TOPLAM", $urun_isim_boyu);
$toplam_t_str = str_pad(number_format($toplam,2), $rakam_isim_boyu + $fiyat_isim_boyu, " ", STR_PAD_LEFT);
$printer -> text("$toplam_str$toplam_t_str\n");
//echo "$toplam_str$toplam_t_str";
$printer -> setEmphasis(false);

$printer -> setEmphasis(true);
$printer -> setTextSize(1, 1);
$printer -> text("\n            Afiyet olsun.Yine bekleriz...\n"); 
$printer -> setEmphasis(false);
$printer -> feed(2); 
// FOOTER:
$printer -> setJustification(Printer::JUSTIFY_CENTER);
$date = date('H:i:s d.m.Y');
$printer -> setTextSize(1, 1);
$printer -> text("$date\n");
$printer -> setTextSize(1, 1);
$printer -> text("INC YAZILIM\n www.incyazilim.com.tr");


$printer -> feed(2);

$printer -> cut();
$printer -> close();

?>