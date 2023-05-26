
<?php
include "../controller/fonksiyonlar.php";
$sql = verilericoklucek("select insert_datetime as start, name_surname as title from users");
echo json_encode($sql);
