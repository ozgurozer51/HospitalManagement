<?php
include "../../../controller/fonksiyonlar.php";
$islem = $_GET['islem'];

if ($islem == "hastane-ekle") {
    $sql = direktekle("hospital", $_POST);

} elseif ($islem == "hastane-update") {
    $id = $_POST['id'];
    unset($_POST['id']);
    $sql = direktguncelle("hospital", "id", $id, $_POST);

} elseif ($islem == "hastane-sil") {
    $id = $_POST['id'];
    unset($_POST['id']);
    $detay = $_POST['delete_detail'];
    $sql = canceldetail('hospital', 'id', $id, $detay);

} elseif ($islem == "hastane-aktiflestir") {
    $id = $_POST['getir'];
    $sql = backcancel('hospital', 'id', $id);

} elseif ($islem == "bina-ekle") {
    $sql = direktekle("hospital_building", $_POST);

} elseif ($islem == "bina-update") {
    $id = $_POST['id'];
    unset($_POST['id']);
    unset($_POST['hospital_id']);
    if(is_numeric($_POST['skrs_institution_code'])) {
        $sql = direktguncelle("hospital_building", "id", $id, $_POST);
    }

} elseif ($islem == "bina-sil") {
    $id = $_POST['id'];
    unset($_POST['id']);
    $detay = $_POST['delete_detail'];
    $sql = canceldetail('hospital_building', 'id', $id, $detay);

} elseif ($islem == "bina-aktiflestir") {
    $id = $_POST['getir'];
    $sql = backcancel('hospital_building', 'id', $id);
}

if ($islem == "kat-ekle") {
    $sql = direktekle("hospital_floor", $_POST);

} elseif ($islem == "kat-update") {
    $id = $_POST['id'];
    unset($_POST['id']);
    $sql = direktguncelle("hospital_floor", "id", $id, $_POST);

} elseif ($islem == "kat-sil") {
    $id = $_POST['id'];
    unset($_POST['id']);
    $detay = $_POST['delete_detail'];
    $sql = canceldetail("hospital_floor", "id", $id, $detay);

} elseif ($islem == "kat-aktiflestir") {
    $id = $_POST['getir'];
    $sql = backcancel('hospital_floor', 'id', $id);
} elseif ($islem == "oda-ekle") {
    $sql = direktekle("hospital_room", $_POST);

} elseif ($islem == "oda-update") {
    $id = $_POST['id'];
    unset($_POST['id']);
    $sql = direktguncelle("hospital_room", "id", $id, $_POST);

} elseif ($islem == "oda-sil") {
    $id = $_POST['id'];
    unset($_POST['id']);
    $detay = $_POST['delete_detail'];
    $sql = canceldetail("hospital_room", "id", $id, $detay);

} elseif ($islem == "oda-aktiflestir") {
    $id = $_POST['getir'];
    $sql = backcancel('hospital_room', 'id', $id);
}

if ($sql == 1) { ?>
    <script>
        alertify.success('İşlem Başarılı');
    </script>
<?php } else { ?>
    <script>
        alertify.error('İşlem Başarısız');
    </script>
<?php }