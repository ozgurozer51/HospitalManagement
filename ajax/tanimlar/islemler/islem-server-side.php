<?php
include "../../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$islem=$_GET['islem'];

//session_start();
//ob_start();
//$KULLANICI_ID = $_SESSION['id'];

    $sql_sorgu = "select * from transaction_detail where transaction_id = {$_POST["id"]} and status!='2'  ";
    $sql_sorgu2 = "select transaction_id from transaction_detail where transaction_id = {$_POST["id"]}";

    $where = [];

    $order = ['id', 'desc'];
    $column = $_POST['order'][0]['column'];
    $columnname = $_POST['columns'][$column]['data'];
    $columnorder = $_POST['order'][0]['dir'];

    if (isset($columnname) && !empty($columnname) && isset($columnorder) && !empty($columnorder)) {
        $order[0] = $columnname;
        $order[1] = $columnorder;
    }

    if (!empty($_POST['search']['value'])) {
        $where[] .= "lower(transaction_detail.transaction_name)" . " like ('%" . $_POST["search"]["value"] . "%') ";
    }


    if (count($where) > 0) {
        $sql_sorgu .= ' and ' . implode(' or ', $where);
    }

     $sql_sorgu .= ' order by ' . $order[0] . ' ' . $order[1] . ' ' ;

    if ($_POST['length'] != -1) {
        $sql_sorgu .= 'offset ' . $_POST['start'] . ' rows' . ' ';
        $sql_sorgu .= 'fetch next ' . $_POST['length'] . ' rows only' . ' ';
    }

    $sql_sonuc = verilericoklucek($sql_sorgu);
    $sql_sonuc2 = verilericoklucek($sql_sorgu2);

    $response = [];
    $response['data'] = [];
    $response['recordsTotal'] = count($sql_sonuc);
    $response['recordsFiltered'] = count($sql_sonuc2);

    foreach ($sql_sonuc as $item) {
        $response['data'][] = [
            'status' => $item['status'],
            'islem' => ($item["status"]==1) ? butontanimla("","1") : butontanimla("","1") ,
            'transaction_name' => $item['transaction_name'],
            'cost' => $item['cost'],
            'unit_id' => $item['unit_id'],
            'islem2'  =>  "<button class='btn btn-sm btn-success' id='islemdetayidetaylifiyat' type='button' data-bs-toggle='modal' data-bs-target='#islem-modal' islem-id='$item[id]'><img src='assets/icons/icons8-checked-checkbox-80.png' alt='icon' width='30px'></button>"
                              ];
    }

    echo json_encode($response);

