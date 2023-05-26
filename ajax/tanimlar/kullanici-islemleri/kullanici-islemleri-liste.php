<?php
include "../../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();

$gunceltarih = date('Y/m/d H:i:s');
$kullaniciidsi=$_GET["kullaniciidsi"];
$yetkiid=$_GET["yetkiid"];
$sil=$_GET["sil"];
$islem = $_GET['islem'];
$gunceltarih = date('Y/m/d H:i:s');

if($islem=="kullanici-tanimli-birimler"){

if($kullaniciidsi!="" and $yetkiid!="")
{
    $verigetir= singular("select * from users_outhorized_units where userid='$kullaniciidsi' and unit_id='$yetkiid' and status='1'");
    if($verigetir){
        echo '<div class="alert alert-danger" role="alert">
            <a href="javascript: void(0);" class="alert-link">eklemek istediğiniz birim zaten kullanıcıda mevcut!</a> 
               </div>';
    } else {
        $_POST["unit_id"] = $yetkiid;
        $_POST["authorizing_userid"] = $_SESSION["id"];
        $_POST["userid"] = $kullaniciidsi;
        $_POST["authorizing_datetime"] = $gunceltarih;
        direktekle("users_outhorized_units", $_POST);
    }
}if($sil!="")
{
    $silmeislem= silme("users_outhorized_units","id",$_GET["sil"]);
}  ?>

<div class="modal-dialog" style=" width: 90%; max-width: 95%; ">
    <form class="modal-content"  action="doktortanimla" method="post" >
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h4 class="modal-title">kullanıcı tanımlı birimleri</h4>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-12 row">
                    <div align="right">
                        <button  id="<?php echo $kullaniciidsi; ?>"  type="button" class="btn btn-success waves-effect waves-light w-sm yetkieklebolumunuac">
                            birim ekle
                        </button>
                    </div>
                    <br/>
                    <br/>
                    <div id='islemsonuc'>
                        <table   class="table table-striped table-bordered" style=" background:white;width: 100%;">
                            <thead>
                            <tr>
                                <th>id</th>
                                <th>birim adı</th>
                                <th>yetkiyi veren</th>
                                <th>yetkiyi verme tarihi</th>
                                <th>i̇şlem</th>
                            </tr>
                            </thead>

                            <tbody>
                            <?php $sql = verilericoklucek("select * from users_outhorized_units where userid='$kullaniciidsi' and status='1' ");
                            foreach ($sql as $row){
                                $yetkibilgisi=singular("authority_definition","id",$row["id"]);
                                $yetkiveren=singular("users","id",$row["authorizing_userid"]); ?>
                                <tr>
                                    <td><?php echo $row["id"]; ?></td>
                                    <td><?php echo    birimgetir($row["unit_id"]); ?></td>
                                    <td><?php echo    $yetkiveren["name_surname"] ?></td>
                                    <td><?php echo nettarih($row["authorizing_datetime"]); ?></td>
                                    <th scope="row" id='<?php echo $row["id"]; ?>'><button  yetkiverilecekkullanici="<?php echo $kullaniciidsi; ?>"   id='<?php echo $row["id"]; ?>'  style=" line-height: 1px;" type="button" class="btn btn-danger waves-effect waves-light islem-sil"><i class="bx bx-block font-size-16 align-middle me-2"></i></button></th>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-bottom:0; margin-left:5px; background:red; color:white; margin:0; margin-left:10px;">kapat</button>
        </div>
    </form>
</div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $(".islem-sil").click(function(){
            var goster = $(this).attr('id');
            var yetkiverilecekkullaniciid = $(this).attr('yetkiverilecekkullanici');
            var confirmtext = "silmek istediğinize emin misiniz?";
            if(confirm(confirmtext)) {
                $.get("ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-liste.php", {sil: goster,kullaniciidsi:yetkiverilecekkullaniciid }, function (getveri) {
                    $('#yetkibilgisiicerik').html(getveri);
                });
            }
        });

        $(".yetkieklebolumunuac").click(function(){
            var goster = $(this).attr('id');
            $.get( "ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-liste.php", { yetkiverilecekkullanici:goster },function(getveri){
                $('#islemsonuc').html(getveri);
            });
        });
    });
</script>

<?php }elseif($islem=="birimleri-getir"){
    $kulidsi=$_GET["yetkiverilecekkullanici"];  ?>

    <table id="birim-table" class="table table-striped table-bordered" style=" background:white;width: 100%;">
        <thead>
        <tr>
            <th>id</th>
            <th>birim adı</th>
        </tr>
        </thead>
        <tbody>
        <?php  $sql = verilericoklucek("select * from units where status='1' ");
        foreach ($sql as $row) { ?>
            <tr>
                <td  yetki-id="<?php echo $row["id"]; ?>"  yetkiverilecekkullanici="<?php echo $_GET["yetkiverilecekkullanici"]; ?>" class='kullaniciyayetkiekle'><?php echo $row["id"]; ?></td>
                <td   yetkiverilecekkullanici="<?php echo $_GET["yetkiverilecekkullanici"]; ?>" yetki-id="<?php echo $row["id"]; ?>"  class='kullaniciyayetkiekle'><?php echo $row["department"]; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <script>

        $(document).ready(function() {
            $(".kullaniciyayetkiekle").click(function(){
                var yetkiid = $(this).attr('yetki-id');
                var yetkiverilecekkullaniciid = $(this).attr('yetkiverilecekkullanici');
                $.get( "ajax/kullanicitanimlibirimler.php", { yetkiid:yetkiid,kullaniciidsi:yetkiverilecekkullaniciid },function(getveri){
                    $('#yetkibilgisiicerik').html(getveri);
                });
            });

            $('#birim-table').DataTable({
                "scrollY": true,
                "scrollX": true
            });
        } );
    </script>

<!--------------------------------------------------------------------------------------------------------------------->
<!--------------------------------------------------------------------------------------------------------------------->
<!--------------------------------------------------------------------------------------------------------------------->
<!--------------------------------------------------------------------------------------------------------------------->
<!--------------------------------------------------------------------------------------------------------------------->
<?php }if($islem=="kullanici-grup-yetki-listesi"){ ?>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-white p-1" style="background-color: #3F72AF">
                <h4 class="modal-title ">Kullanıcı Grup Yetki </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form   id="formbina" action="javascript:void(0);" >
                <div class="modal-body">
                    <div class="mx-2">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="card">
                                    <div class="card-header p-2 text-white" >Grup Yetki  Listesi</div>
                                    <div class="card-body">
                                        <table class="table table-bordered table-sm table-hover" style=" background:white;width: 100%;" id="usertablo">

                                            <thead class="table-light">
                                            <tr>
                                                <th>Grup No</th>
                                                <th>Grup Adı</th>
                                                <th>Yetki Adı</th>
                                                <th>Durum</th>
                                                <th>İşlem</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php  $say=0;
                                            $demo = "select group_name,group_id,status from staff_group GROUP BY group_name,group_id,status";
                                            $ameliyathanegetir = verilericoklucek($demo);
                                            foreach ($ameliyathanegetir as $row) { ?>

                                                <tr>
                                                    <td><?php echo $row['group_id'] ?></td>
                                                    <td ><?php echo $row["group_name"] ?></td>
                                                    <td >
                                                        <?php  $say = 0;
                                                        $groupid=$row['group_id'];
                                                        if ($groupid){
                                                            $satirsay=tek("select count(group_id) as say from staff_group where group_id='$groupid'");
                                                            $getirsay=$satirsay['say'];
                                                            $demo = "select authority from staff_group inner join authority_definition u on u.id = staff_group.authority_id where staff_group.group_id='$groupid'";
                                                            $usergetir = verilericoklucek($demo);
                                                            foreach ($usergetir as $getir) { ?>
                                                                <?php $say++;
                                                                $namesurname=$getir["authority"];
                                                                if ($say < $getirsay) {
                                                                    $namesurname.= ',';
                                                                }
                                                                echo $namesurname; ?>
                                                            <?php  } }?>
                                                    </td>
                                                    <td align="center" title="Durum">
                                                        <?php if($row["status"]=='1'){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?>
                                                    </td>
                                                    <td><button  class="btn btn-success btn-sm kullanici_grup_ekle" kullaniciid="<?php echo $_GET['kullanici_id']; ?>" grupyetkiid="<?php echo $row['group_id'];?>">Ekle</button> </td>
                                                </tr>
                                            <?php }?>
                                            </tbody>
                                        </table>
                                        <div id="kullanici-biriktir"></div>
                                        <script>
                                            $('#usertablo').DataTable({
                                                "responsive": true,
                                                "language": {
                                                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                },
                                                "lengthChange": false,
                                                "info": false
                                            });
                                        </script>
                                    </div>
                                </div>

                                <div class="card mt-2">
                                    <div class="card-header p-2  text-white" >Kullanıcının Yetkileri</div>
                                    <div class="card-body">
                                        <table   class="table table-striped table-bordered nowrap display w-100" id="usertabloyetki">
                                            <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>İşlem</th>
                                                <th>Yetki Adı</th>
                                                <th>Yetki Detayı</th>
                                                <th>Yetkiyi Veren</th>
                                                <th>Yetkiyi Verdiren</th>
                                                <th>Yetkiyi Verme tarihi</th>
                                                <th>Yetkiyi Nedeni</th>
                                                <th>Geçerlilik Süresi</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            <?php  $kullaniciidsi=$_GET['kullanici_id'];
                                            $sql = verilericoklucek("select * from authority_of_pool where userid='$kullaniciidsi'");
                                            foreach ($sql as $row){
                                                $yetkibilgisi=singular("authority_definition","id",$row["authority_id"]);
                                                $yetkiveren=singular("users","id",$row["authorizing_userid"]);  ?>
                                                <tr>
                                                    <td><?php echo $row["id"]; ?></td>
                                                    <th scope="row" id='<?php echo $row["id"]; ?>'>
                                                        <div class="form-check form-switch mb-3" dir="ltr">
                                                            <input type="checkbox" class="form-check-input islemsil chend" <?php if ($row['status']==1){ ?> checked="" <?php } ?>yetkiverilecekkullanici="<?php echo $kullaniciidsi; ?>"   id='<?php echo $row["id"]; ?>'>
                                                        </div>
                                                    </th>
                                                    <td><?php echo  $yetkibilgisi["authority"] ?></td>
                                                    <td><?php echo    $yetkibilgisi["explanation"] ?></td>
                                                    <td><?php echo    $yetkiveren["name_surname"] ?></td>
                                                    <td><?php echo $row["authorizing_commissioned_userid"]; ?></td>
                                                    <td><?php echo turkcetarih($row["authorizing_datetime"]); ?></td>
                                                    <td><?php echo $row["authority_reason"]; ?></td>
                                                    <td><?php echo turkcetarih($row["period_of_validity"]); ?></td>
                                                </tr>
                                            <?php } ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('#usertabloyetki').DataTable({
                "responsive": true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
                "lengthChange": false,
                "info": false
            });
            $("body").off("click", ".kullanici_grup_ekle").on("click", ".kullanici_grup_ekle", function(e){
                var kullaniciid = $(this).attr('kullaniciid');
                var grupyetkiid = $(this).attr('grupyetkiid');
                $.ajax({
                    type:'POST',
                    url:'ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-sql.php?islem=grup-yetki-ekle',
                    data:{kullaniciid:kullaniciid,grupyetkiid:grupyetkiid},
                    success:function(e){
                        $('#sonucyaz').html(e);
                    }
                });
            });
        });

        $("body").off("click", ".islemsil").on("click", ".islemsil", function(e){
            var goster = $(this).attr('id');
            var status;
            if($(this).is(':checked')){ status = 1; }else { status = 0; }
            var yetkiverilecekkullaniciid = $(this).attr('yetkiverilecekkullanici');

            $.get("ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-sql.php?islem=yetki-grup-aktiflestir", {sil: goster,kullaniciidsi:yetkiverilecekkullaniciid,status:status }, function (getveri) {
                $('#sonucyaz').html(getveri);
            });

        });
    </script>


<?php }if($islem=="kullanici_yetkisi_getir"){

    if($kullaniciidsi!="" and $yetkiid!="")
    {
        $verigetir= tek("select * from authority_of_pool where userid='$kullaniciidsi' and authority_id='$yetkiid' and status='1'");
        if($verigetir){
            echo '<div class="alert alert-danger" role="alert"><a href="javascript: void(0);" class="alert-link">eklemek istediğiniz yetki zaten kullanıcıda mevcut!</a> </div>';

        }else {
            $_post["authority_id"] = $yetkiid;
            $_post["authorizing_userid"] = $_SESSION["id"];
            $_post["userid"] = $kullaniciidsi;
            $_post["authorizing_datetime"] = $gunceltarih;
            $_post["status"] = 1;
            direktekle("authority_of_pool", $_post);
        }
    }

    if($sil!="") {
        $silmeislem= silme("authority_of_pool","id",$_GET["sil"]);
    }  ?>

    <div class="modal-content">


            <div class="modal-body" style="height:100vh!important; max-height:100vh!important; overflow-y:auto; padding:0px!important; margin:0px!important;">

                <div class="modal-header p-1" style="padding:0px 0px 0px 0px; margin-top:0px !important;">
                    <span id="adi3"></span> <?php echo $_GET['adi']; ?> -  kullanıcı yetki düzenle
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>


<!--                        <div align="right">-->
<!--                            <button  id="--><?php //echo $kullaniciidsi; ?><!--"  type="button" class="btn btn-success waves-effect waves-light w-sm yetkieklebolumunuac">yetki ekle</button>-->
<!--                        </div>-->

                            <div class="row">

                                <div class="col-6 col-md-6 col-lg-6 col-sm-6">
                                    <div class="card">
                                        <div class="card-header p-1">Kullanıcıya Eklenmemiş Yetki Listesi</div>
                                    </div>
                                    <div class="card-body" >

                                        <table id="ekli-olmayan-yetki-listesi" class="display nowrap table table-bordered table-sm" style="font-size: 13px;">
                                            <thead>
                                            <th>ID</th>
                                            <th>Yetki Adı</th>
                                            <th>Yetki Açıklama</th>
                                            <th>Yetki Grup</th>
                                            <th>İşlem</th>
                                            </thead>

                                            <tbody>

                                            <?php $sql = sql_select("select authority_definition.id as yetki_id, *
                         from authority_definition
                         where authority_definition.status = '1'
                           and authority_definition.id != all (select authority_definition.id
                                   from authority_of_pool
                                            inner join authority_definition
                                                       on authority_of_pool.authority_id = authority_definition.id
                                   where authority_of_pool.userid = $_GET[kullaniciidsi])");

                                            foreach ($sql as $item){ ?>

                                                <tr>
                                                    <td><?php echo $item['yetki_id']; ?></td>
                                                    <td><?php echo $item['authority']; ?></td>
                                                    <td><?php echo $item['explanation']; ?></td>
                                                    <td><?php echo $item['group_id']; ?></td>
                                                    <td> <button class="btn btn-sm btn-success" id="yetki-ekle" yetki-id="<?php echo $item['yetki_id']; ?>">Ekle</button> </td>

                                                </tr>

                                            <?php  }  ?>
                                            </tbody>
                                        </table>

                                        <script>



                                            $("body").off("click", "#yetki-ekle").on("click", "#yetki-ekle", function(e){

                                                var authority_id = $(this).attr('yetki-id');
                                                var userid = <?php echo $_GET['kullaniciidsi']; ?>;


                                                $.ajax({
                                                    type: 'POST',
                                                    url: 'ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-sql.php?islem=user-insert-authority',
                                                    data: {
                                                        authority_id:authority_id,
                                                        userid:userid

                                                    },
                                                    success: function (e) {
                                                        $.get("ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-liste.php?islem=kullaniciya-ekli-olan-yetkiler", {kullaniciidsi:<?php echo $_GET['kullaniciidsi']; ?> }, function (getveri) {
                                                            $('#kullaniciya-eklenen-yetkiler').html(getveri);
                                                        });
                                                    }
                                                });
                                                 $(this).prop("disabled" , true);
                                            });

                                            $('#ekli-olmayan-yetki-listesi').DataTable({
                                                "lengthChange": false,
                                                "paging":false,
                                                "info":false,
                                                "scrollY": "80vh",
                                                "scrollX": true,
                                                "language": {
                                                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                                                },
                                            });
                                        </script>

                                    </div>

                                </div>

                                <div class="col-6 col-md-6 col-lg-6 col-sm-6">

                                    <div class="card">
                                        <div class="card-header p-1">Kullanıcıya Eklenmemiş Yetki Listesi</div>
                                    </div>
                                    <div class="card-body" id="kullaniciya-eklenen-yetkiler">


                                    </div>
                                </div>
                            </div>
            </div>
    </div>


    <script type="text/javascript">

        $.get("ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-liste.php?islem=kullaniciya-ekli-olan-yetkiler", {kullaniciidsi:<?php echo $_GET['kullaniciidsi']; ?> }, function (getveri) {
            $('#kullaniciya-eklenen-yetkiler').html(getveri);
        });


        $(document).ready(function(){
            $(".islemsil").click(function(){
                var goster = $(this).attr('id');
                var yetkiverilecekkullaniciid = $(this).attr('yetkiverilecekkullanici');

                var confirmtext = "silmek istediğinize emin misiniz?";
                if(confirm(confirmtext)) {
                    $.get("ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-liste.php?islem=kullanici_yetkisi_getir", {sil: goster,kullaniciidsi:yetkiverilecekkullaniciid }, function (getveri) {
                        $('#yetkibilgisiicerik').html(getveri);
                    });
                }
            });

            $(".yetkieklebolumunuac").click(function(){
                var goster = $(this).attr('id');
                $.get( "ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-liste.php?islem=tanimli-yetkiler", { yetkiverilecekkullanici:goster },function(getveri){
                    $('#islemsonuc').html(getveri);
                });
            });

        });
    </script>

<!--------------------------------------------------------------------------------------------------------------------->
<?php } if($islem=="kullanici_kasa_yetki"){  ?>
    <div class="card">
        <div class="card-header  text-white p-1">kullanıcıya tanımlı kasalar</div>
        <div class="card-body">

            <?php  $sql = verilericoklucek("select
    users_outhorized_safes.status as kasa_yetki_durumu,
    users_outhorized_safes.*,
    users_outhorized_safes.id as kullanicilar_yetkili_kasalar_id,
    users.id,
    users.id as users_id,   
    users.name_surname,
    safe.id,
    safe.safe_name
from
    users_outhorized_safes
        left join safe on safe.id = users_outhorized_safes.safe_id
        left join users on users.id = users_outhorized_safes.authorizing_userid
where users_outhorized_safes.userid = $_GET[kullanici_id]");
            if (!$sql){
                echo "<div class='alert alert-warning'>kullanıcıya tanımlı kasa mevcut değil</div>";
                exit(); }  ?>

            <table class="table table-bordered table-hover nowrap display w-100">
                <thead>
                <th>kasa adı</th>
                <th>verilme tarihi</th>
                <th>yetkiyi veren</th>
                <th>aktif/pasif</th>
                </thead>
                <tbody>
                <?php foreach ($sql as $item){ ?>
                    <tr>
                        <td><?php echo $item['safe_name']; ?></td>
                        <td><?php echo $item['authorizing_datetime']; ?></td>
                        <td><?php echo $item['name_surname']; ?></td>
                        <td><div class="form-check form-switch"><input class="form-check-input" id="pasif-aktif-clk" type="checkbox" role="switch" kullanici-adi="<?php echo $item['name_surname']; ?>"  kullanici-id="<?php echo $_GET['kullanici_id'];  ?>" kasa-yetki-id="<?php echo $item['kullanicilar_yetkili_kasalar_id']; ?>" <?php if($item['kasa_yetki_durumu']==1){ echo "checked"; } ?>></div></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

<!--------------------------------------------------------------------------------------------------------------------->
<?php } if($islem=="kasalar"){  ?>
    <div class="card">
        <div class="card-header text-white p-1">kullanıcıya kayıtlı olmayan tüm aktif kasalar</div>
        <div class="card-body">
            <table class="table table-bordered table-hover w-100 nowrap display">
                <thead>
                <th>kasa adı</th>
                <th>kasa kod</th>
                <th>eklenme tarihi</th>
                <th>birim</th>
                <th>kat</th>
                <th>bina</th>
                <th>kayıt eden</th>
                <th>kullanıcıya tanımla</th>
                </thead>

                <tbody>
                <?php $sql = verilericoklucek("select *
             from safe
             where safe.status = '1'
             and id != all (select safe.id
                 from users_outhorized_safes
                          inner join safe on safe.id = users_outhorized_safes.safe_id
                          inner join users on users.id = users_outhorized_safes.userid
                 where users_outhorized_safes.userid = $_GET[kullanici_id])");
                foreach ($sql as $item){ ?>
                    <tr>
                        <td><?php echo $item['safe_name']; ?></td>
                        <td><?php echo $item['safe_id']; ?></td>
                        <td><?php echo $item['authorizing_datetime']; ?></td>
                        <td><?php echo $item['unit']; ?></td>
                        <td><?php echo $item['floor']; ?></td>
                        <td><?php echo $item['building']; ?></td>
                        <td><?php  if($item['insert_userid']==1){ echo "sistem yöneticisi"; }else{ echo $item['insert_userid']; } ?></td>
                        <td><button id="kullanici_kasa_tanim_clk" class="btn btn-success btn-sm" kullanici-id="<?php echo $_GET['kullanici_id']; ?>" kasa-id="<?php echo $item['id'];?>">ekle</button> </td>
                    </tr>
                <?php } ?>
                </tbody>

            </table>

        </div>
    </div>

<?php } if($islem=="kullaniciya-kayitli-olmayan-birimleri-getir"){ ?>
    <div class="card">
        <div class="card-header p-2  text-white" >kullanıcıya kayıtlı olmayan birimler (kullanıcıya birim yetkisi vermek i̇çin ekle butonuna tıklayınız)</div>
        <div class="card-body">
            <table class="table table-bordered w-100 table-hover nowrap display" id="birimler-table">
                <thead>
                <th>birim adı</th>
                <th>i̇şlem</th>
                </thead>
                <tbody>
                <?php $sql = verilericoklucek("select *
               from units
               where status = '1'
                 and id != all (select users_outhorized_units.unit_id
                 from users_outhorized_units
                          inner join units on units.id = users_outhorized_units.unit_id
                          inner join users on users.id = users_outhorized_units.userid
                 where users_outhorized_units.userid =$_GET[kullanici_id])");
                foreach ($sql as $item){ ?>
                    <tr>
                        <td><?php echo $item['department_name']; ?></td>
                        <td><button id="birimn-ekle-clk" birim-id="<?php echo $item['id']; ?>" class="btn btn-success btn-sm">ekle</button></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        setTimeout(function() {
            $('#birimler-table').DataTable({
                "scrollY": true,
                "scrollX": true,
                "pageLength": 10,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
            });
        }, 800);
    </script>

<!--kullanıcıya eklenmiş birim yetkisi--------------------------------------------------------------------------------->
<?php }if($islem=="kullaniciya-eklenmis-birimler"){ ?>

    <div class="card">
        <div class="card-header p-2  text-white" >kullanıcıya eklenmiş birim listesi</div>
        <div class="card-body">

            <?php $sql2 = verilericoklucek("select users_outhorized_units.*,
                  users_outhorized_units.id    as kullanicilar_yetkili_birimler_id,
                  users_outhorized_units.status as birim_yetki_durumu,
                  units.id,
                  units.department_name,
                  users.id,
                  users.name_surname
           from users_outhorized_units
                    left join units on units.id = users_outhorized_units.unit_id
                    left join users on users.id = users_outhorized_units.authorizing_userid
           where users_outhorized_units.userid = '$_GET[kullanici_id]'");  ?>

            <table class="table table-bordered w-100 table-hover nowrap display" >
                <thead>
                <th>birim adı</th>
                <th>yetki verilme tarihi</th>
                <th>yetki veren</th>
                <th>pasif/aktif</th>
                </thead>
                <tbody>
                <?php foreach ($sql2 as $item){ ?>
                    <tr>
                        <td><?php echo $item['department_name']; ?></td>
                        <td><?php echo $item['authorizing_datetime']; ?></td>
                        <td><?php echo $item['name_surname']; ?></td>
                        <td><div class="form-check form-switch"><input class="form-check-input" id="pasif-aktif-birim-clk" type="checkbox" role="switch" birim-yetki-id="<?php echo $item['kullanicilar_yetkili_birimler_id']; ?>" kullanici-id="<?php echo $_GET['kullanici_id']; ?>"  <?php if($item['birim_yetki_durumu']==1){ echo "checked"; } ?>></div></td>
                    </tr>
                <?php } ?>
                </tbody>

            </table>

        </div>
    </div>

    <input  id="birim-tanim-icin-kullanici-id" type="hidden" value="<?php echo $_GET['kullanici_id']; ?>"/>

<?php }  if($islem=="yetki_tanimlarinda_olmayan_birimleri_getir"){ ?>

    <div class="card">
        <div class="card-header text-white p-2" >birim yetkisi tanımla (yetki tanımda kayıtlı olmayan birim listesi)</div>
        <div class="card-body">
            <table class="table table-bordered table-hover nowrap display w-100" id="birim-table">
                <thead>
                <th>bölüm adı</th>
                <th>ekle</th>
                </thead>
                <tbody style="cursor:pointer;">

                <?php $sql = verilericoklucek("select birimler.id,birimler.bolum_adi,yetki_tanim.id,yetki_tanim.birim_id from yetki_tanim
                          right outer join birimler
                          on yetki_tanim.birim_id = birimler.id where yetki_tanim.birim_id is null");
                foreach ($sql as $item){ ?>
                    <tr>
                        <td><?php echo $item['bolum_adi']; ?></td>
                        <td><button class="btn btn-sm btn-success" birim-id="<?php echo $item['id']; ?>" birim-adi="<?php echo $item['bolum_adi']; ?>" id="birim_yetki_ekle">yetki tanımlarına ekle</button></td>
                    </tr>
                <?php } ?>

                </tbody>
            </table>
        </div>
    </div>

    <script>
        $('#birim-table').DataTable({
            "responsive": true,
            "autoHeight": false,
            "paging": false,
            "scrollY": '50vh',
            "scrollCollapse": true,

            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
        });

        $(document).on('click', '#birim_yetki_ekle', function() {
            $.ajax({
                type: 'POST',
                url: 'ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-sql.php?islem=yeni-yetki-tanim-ekle-birim',
                data: {
                    birim_ıd:$(this).attr('birim-id'),
                    yetki:$(this).attr('birim-adi'),
                },
                success: function (e) {
                    $(".sonuc_yaz").html(e);
                    $.get("ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-liste.php?islem=tanimli_mevcut_yetkileri_getir", {}, function (e) {
                        $('#yetki_tanimlari_getir').html(e);
                    });
                }
            });
            $(this).prop("disabled" , true);
        });
    </script>

<?php }if($islem=="tanimli_mevcut_yetkileri_getir"){ ?>
    <div class="card">
        <div class="card-header text-white p-2">mecut yetkiler</div>
        <div class="card-body">
            <table class="table table-bordered table-hover nowrap display w-100 text-sm" id="mevcut-yetki-table">
                <thead>
                <th>yetki</th>
                <th>açıklama</th>
                <th>i̇şlem</th>
                </thead>
                <tbody style="cursor:pointer;">
                <?php $sql = verilericoklucek("select * from authority_definition");
                foreach ($sql as $item){ ?>
                    <tr>
                        <td><?php echo $item['authority']; ?></td>
                        <td><?php echo $item['explanation']; ?></td>
                        <td><?php if($item['status']==1){ ?>  <input class="btn btn-danger btn-sm" id="aktif_pasif_clk" durum="0" value="pasif et" yetki-id="<?php echo $item['id']; ?>"/> <?php } else { ?><input class="btn btn-success btn-sm" id="aktif_pasif_clk" durum="1" value="aktif et" yetki-id="<?php echo $item['id']; ?>"/> <?php } ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <table><tbody><tr> <td class="bg-danger text-danger">tani</td><td> = pasif </td></tr></tbody></table>
        </div>
    </div>

    <div class="yetki-tanim-alert">
        <form id="yetki_form">
            <div class="form-group">
                <label for="recipient-name" class="col-form-label">Yetki Adı:</label>
                <input type="text" class="form-control" id="YETKI_ADI" placeholder="Yetki Adı">
            </div>
            <div class="form-group">
                <label for="message-text" class="col-form-label">Açıklama:</label>
                <input class="form-control" id="YETKI_ACIKLAMA" type="text" placeholder="Açıklama" />
            </div>
        </form>
    </div>

    <script>
        var GETIR = $('.yetki-tanim-alert').html();
        $('.yetki-tanim-alert').remove();

        $('#mevcut-yetki-table').DataTable({
            "responsive": true,
            "autoHeight": false,
            "paging": false,
            "scrollY": '50vh',
            "scrollCollapse": true,

            dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },

            buttons: [
                {
                    text: 'Yeni Yetki Tanım Oluştur ',
                    className:'btn btn-info text-white YetkiTanimClk',
                    titleAttr:'Yeni Yetki Tanımı Oluşturmak İçin Tıklayınız...',
                    action: function ( e, dt, node, config ) {
//Yeni Yetki Tanımlama İçin --------------------------------------------------------------------------------------------
                        alertify.confirm(GETIR, function(){
                            $.ajax({
                                type: 'POST',
                                url: 'ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-sql.php?islem=yeni-yetki-tanim-olustur',
                                data: {
                                    authority:$('#YETKI_ADI').val(),
                                    explanation:$('#YETKI_ACIKLAMA').val(),
                                },
                                success: function (e) {
                                    $(".sonuc_yaz").html(e);
                                    $.get("ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-liste.php?islem=tanimli_mevcut_yetkileri_getir", {}, function (e) {
                                        $('#yetki_tanimlari_getir').html(e);
                                    });
                                }
                            });

                        }, function(){ alertify.warning('Yetki Tanımlama İşleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"Yeni Yetki Tanımla"});
                    },

                },
                { extend: "excel", className: "btn btn-primary btn-sm"},
                { extend: "print", className: "btn btn-primary btn-sm" , text:'Yazdır'},
                { extend: "pdf", className: "btn btn-primary btn-sm"},
                { extend: "colvis", text: "Sütun Görünürlüğü" , className: "btn btn-primary btn-sm" },
            ],

        });
    </script>

<?php }  if($islem=="tanimli-yetki-işlemleri"){  ?>

    <div class="row">
        <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12" id="yetki_tanimlari_getir"> </div>
    </div>

    <script>
        $.get("ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-liste.php?islem=tanimli_mevcut_yetkileri_getir", {}, function (e) {
            $('#yetki_tanimlari_getir').html(e);
        });
    </script>

<?php } if($islem=="tanimli-yetkiler"){ ?>

<center><h3>Bu bölümde ekli olmayan tüm yetkileri görebilirsiniz. Filtreleme bölümünde yetkileri gruplandırabilirsiniz </h3></center>
<table id="example" class="table table-striped table-bordered" style=" background:white;width: 100%;">
    <thead>
    <tr>
        <th>ID</th>
        <th>Yetki Adı</th>
        <th>Yetki Açıklama</th>
        <th>Yetki Grup</th>
    </tr>
    </thead>
    <tbody>

    <?php $sql =verilericoklucek("SELECT * FROM authority_definition");
    foreach ($sql as $row){ ?>
        <tr>
            <td   yetki-id="<?PHP ECHO $row["id"]; ?>"  yetkiverilecekkullanici="<?PHP ECHO $_GET["yetkiverilecekkullanici"]; ?>"  class='kullaniciyayetkiekle'><?PHP ECHO $row["id"]; ?></td>
            <td   yetkiverilecekkullanici="<?PHP ECHO $_GET["yetkiverilecekkullanici"]; ?>" yetki-id="<?PHP ECHO $row["id"]; ?>"  class='kullaniciyayetkiekle'><?PHP ECHO $row["authority"]; ?></td>
            <td   yetkiverilecekkullanici="<?PHP ECHO $_GET["yetkiverilecekkullanici"]; ?>" yetki-id="<?PHP ECHO $row["id"]; ?>"  class='kullaniciyayetkiekle'><?PHP ECHO $row["explanation"]; ?></td>
            <td   yetkiverilecekkullanici="<?PHP ECHO $_GET["yetkiverilecekkullanici"]; ?>" yetki-id="<?PHP ECHO $row["id"]; ?>"  class='kullaniciyayetkiekle'><?PHP ECHO $row["group_id"]; ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<script>
    $(document).ready(function() {
        $(".kullaniciyayetkiekle").click(function(){
            var yetkiid = $(this).attr('yetki-id');
            var yetkiverilecekkullaniciid = $(this).attr('yetkiverilecekkullanici');
            $.get( "ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-liste.php?islem=kullanici_yetkisi_getir", { yetkiid:yetkiid,kullaniciidsi:yetkiverilecekkullaniciid },function(getVeri){
                $('#yetkibilgisiicerik').html(getVeri);
            });
        });

        $('#example').DataTable( {
            "scrollY": true,
            "scrollX": true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
        });
    } );
</script>

<?php }if($islem=="kullaniciya-ekli-olan-yetkiler"){ ?>


    <table id="ekli-olan-yetki-listesi" class="table table-hover table-striped table-sm table-bordered nowrap display w-100" style="font-size: 13px; margin-top: 0px !important;">
        <thead>
        <tr>
            <th>id</th>
            <th>yetki adı</th>
            <th>yetki detayı</th>
            <th>yetkiyi veren</th>
            <th>yetkiyi verme tarihi</th>
            <th>i̇şlem</th>
        </tr>
        </thead>
        <tbody>

        <?php $sql = verilericoklucek("
            select * , authority_of_pool.id as yetki_id , authority_of_pool.status as yetki_durumu
            from authority_of_pool
                     inner join users on authority_of_pool.authorizing_userid = users.id
                     inner join authority_definition on authority_definition.id = authority_of_pool.authority_id
            where userid = '$kullaniciidsi'");

        foreach ($sql as $row) { ?>
            <tr>
                <td><?php echo $row["id"]; ?></td>
                <td><?php echo $row["authority"] ?></td>
                <td><?php echo $row["explanation"] ?></td>
                <td><?php echo $row["name_surname"] ?></td>
                <td><?php echo $row['authorizing_datetime']; ?></td>
                <td>
                    <div class="form-check form-switch">
                        <input class="form-check-input" yetki-id="<?php echo $row['yetki_id']; ?>" id="yetki-aktif-pasif" type="checkbox" role="switch" <?php if($row['yetki_durumu'] == 1){ echo "checked"; } ?>>
                    </div>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>

    <script>
        $("body").off("change", "#yetki-aktif-pasif").on("change", "#yetki-aktif-pasif", function(e){


            var status;
            var id = $(this).attr("yetki-id");

            if($(this).is(':checked')){ status = 1; }else { status = 0; }

            $.get("ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-sql.php?islem=yetki-al-ver", {status:status,id:id }, function (getveri) {
                $('#sonucyaz').html(getveri);
            });

        });

        $('#ekli-olan-yetki-listesi').DataTable({
            "lengthChange": false,
            "paging":false,
            "info":false,
            "scrollY": "80vh",
            "scrollX": true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
        });
    </script>


<?php } ?>
