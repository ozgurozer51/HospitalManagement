<?php include "../../controller/fonksiyonlar.php";

date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');

$islem = $_GET['islem'];

if($islem=="hasta-form-sil"){

   $auhority = sql_authority($_SESSION['id'], "rapor-form-sil");

   if ($auhority !=1){ ?>

       <script>
           alertify.set('notifier', 'delay', 8);
           alertify.error('Silme Yetkiniz Bulunmamaktadır');
       </script>

<?php       exit(); } }



if ($islem == "form-ekle"){

    $table_name   = $_POST['table_name'];
    unset($_POST['table_name']);
    unset($_POST['group1']);

    $sql  = sql_insert($table_name , $_POST);

    $_POST['group1']['referance_table_name'] = $table_name;
    $_POST['group1']['patient_id'] = $_POST['patient_id'];
    $_POST['group1']['protocol_no'] = $_POST['protocol_no'];
    $sql_select = sql_select("select id from $table_name fect where status='1' order by id desc fetch first 1 rows only");
    $_POST['group1']['form_id'] = $sql_select[0]['id'];
    $sql_2 = sql_insert("patients_reports_forms" ,  $_POST['group1']);

    echo $sql_2;
    echo $sql;

    if ($sql == 1 && $sql_2 == 1) { ?>
        <script>
            alertify.set('notifier', 'delay', 8);
            alertify.success('Form Ekleme İşlemi Başarılı');
        </script>
    <?php } else { ?>
        <script>
            alertify.set('notifier', 'delay', 8);
            alertify.error('Form Ekleme İşlemi Başarısız');
        </script>
  <?php  }

}if($islem == "hasta-form-guncelle"){
    $id = $_POST['form-id'];
    $table_name = $_POST['table_name'];
    unset($_POST['form-id']);
    unset($_POST['table_name']);
    foreach ($_POST AS $x => $val){
        if(!$_POST[$x]){
            unset($_POST[$x]);
        }
    }

        $sql = direktguncelle($table_name , "id" , $id , $_POST );

    if ($sql == 1) { ?>

        <script>
            alertify.set('notifier', 'delay', 8);
            alertify.success('İşlem Başarılı');
        </script>

    <?php } else { ?>

        <script>
            alertify.set('notifier', 'delay', 8);
            alertify.error('İşlem Başarısız');
        </script>

    <?php  }}if($islem=="hasta-form-sil"){

    $detail = $_GET['delete_detail'];
    $id = $_GET['form_id'];
    $id_2 = $_GET['patients_reports_forms_id'];
    $table_name = $_GET['name'];

    $sql=  sql_delete($table_name,"id",$id,$detail);
    $sql_2=sql_delete("patients_reports_forms","id",$id_2,$detail);

    echo $sql;
    echo $sql_2;

    if ($sql == 1 && $sql_2 == 1) { ?>
        <script>
            alertify.set('notifier', 'delay', 8);
            alertify.success('İşlem Başarılı');
        </script>

    <?php } else { ?>

        <script>
            alertify.set('notifier', 'delay', 8);
            alertify.error('İşlem Başarısız');
        </script>

    <?php  }}


