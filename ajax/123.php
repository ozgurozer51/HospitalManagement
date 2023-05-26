

<?php
include "../controller/fonksiyonlar.php";
$sql = verilericoklucek("select name_surname,id from users where lower(name_surname)  like  ('%$_GET[term]%') or upper(name_surname) like ('%$_GET[term]%') FETCH FIRST 10 ROWS ONLY;");
echo json_encode($sql);
