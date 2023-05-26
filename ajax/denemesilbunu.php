<?php
include "../controller/fonksiyonlar.php";
$sql = verilericoklucek("select * from users fetch first 7 rows only");
echo json_encode($sql);

