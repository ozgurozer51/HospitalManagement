<?php
include "../../controller/fonksiyonlar.php";
session_start();
ob_start();
date_default_timezone_set('Europe/istanbul');
$simdikitarih = date('Y-m-d H:i:s');
$dizi = explode(" ", $simdikitarih);
$tarih = $dizi['0'];
$saat = $dizi['1'];
$kullanicid=$_SESSION['id'];
$islem = $_GET["islem"];
if ($islem == "hizmet-tanim-getir-sql") {
    $verileri_getir = verilericoklucek("select * from process_role where  condition_code IS NOT NULL or lab_day IS NOT NULL or lab_hour IS NOT NULL ORDER BY id LIMIT 10");
    if ($verileri_getir > 0) {
        echo json_encode($verileri_getir);
    } else {
        echo 2;
    }

}

if ($islem == "hizmit-liste-serverside") {
    $sql='select * from process_role ';

    $where=[];
    $order=['id','ASC'];
    $column=$_POST['order'][0]['column'];
    $columnName=$_POST['columns'][$column]['data'];
    $columnOrder=$_POST['order'][0]['dir'];
    if (isset($columnName) && !empty($columnName) && isset($columnOrder) && !empty($columnOrder)){
        $order[0]=$columnName;
        $order[1]=$columnOrder;
    }
    if (!empty($_POST['search']['value'])){
        foreach ($_POST['columns'] as $column){
            $where[]=$column['data'].' LIKE "%'. $_POST['search']['value']. '%"';
        }
    }
    if (count($where)>0){
        $sql.=' where  '. implode(' || ',$where);
   }
    $sql.=' ORDER BY '. $order[0] .' '. $order[1].' ';

    //$sql.=' LIMIT '.$_POST['start'].','.$_POST['length'];


//var_dump($sql);
 //exit();
//    $verileri_getir = verilericoklucek("select * from process_role where  condition_code IS NOT NULL or lab_day IS NOT NULL or lab_hour IS NOT NULL ORDER BY id LIMIT 100");
    $verileri_getir = verilericoklucek($sql);
    $response=[];
    $response['data']=[];
    $response['recordsTotal']=100;
    $response['recordsFiltered']=100;
    foreach ($verileri_getir as $row) {
        $response['data'][]=[
                'id'=>$row['id'],
                'official_code'=>$row['official_code'],
                'process_name'=>$row['process_name'],
                'process_description'=>$row['process_description'],
                'lab_hour'=>$row['lab_hour'],
                'lab_hour_status'=>$row['lab_hour_status'],
                'lab_day'=>$row['lab_day'],
                'lab_day_status'=>$row['lab_day_status'],
                'condition_code'=>$row['condition_code'],
                'condition_code_status'=>$row['condition_code_status'],
                'status'=>$row['status']
        ];

    }
    if ($verileri_getir > 0) {
        echo json_encode($response);
    } else {
        echo 2;
    }

}

if($islem=="serverside") {

    $sql_sorgu ="select * from process_role ";
    $sql_sorgu_2 =sql_select("select id from process_role");
    $where = [];
    $order = ['id', 'ASC'];
    $column = $_POST['order'][0]['column'];
    $columnname = $_POST['columns'][$column]['data'];
    $columnorder = $_POST['order'][0]['dir'];

    if (isset($columnname) && !empty($columnname) && isset($columnorder) && !empty($columnorder)) {
        $order[0] = $columnname;
        $order[1] = $columnorder;
    }


    if(count($where) > 0 ){
        $sql_sorgu .= ' and ' . implode(' or ' , $where);
    }

    //arama işlemi
    if (!empty($_POST['search']['value'])){

       $ara="(lower(process_name) "." LIKE '%". $_POST['search']['value']. "%'"." or  upper(process_name) "." LIKE '%". $_POST['search']['value']. "%'"." )
        or official_code "." LIKE '%". $_POST['search']['value']. "%'"."
        or ( lower(process_description) "." LIKE '%". $_POST['search']['value']. "%'"." or upper(process_description) "." LIKE '%". $_POST['search']['value']. "%'".") ";
    }
    if (!empty($ara)){
        $sql_sorgu.=' where  '. $ara;
        $sql_sorgu_2.=' where  '. $ara;
    }
    //arama işlemi
    $sql_sorgu .='order by '. $order[0].' '.$order[1].' ';
//    $sql_sorgu .=  ' group by patient_registration.id,patient_prompts.patient_tc,units.department_name,patients.patient_surname,patients.patient_name,patients.social_assurance,transaction_definitions.definition_name ' . 'order by ' . $order[0] . ' ' . $order[1] . ' ' ;

    if($_POST['length'] != -1) {
        $sql_sorgu .= 'offset ' . $_POST['start'] . ' rows' . ' ';
        $sql_sorgu .= 'fetch next ' . $_POST['length'] . ' rows only' . ' ';
    }

//
//echo $sql_sorgu;
//exit();

    $sql_sonuc = verilericoklucek($sql_sorgu);

    $response = [];
    $response['data'] = [];
    $response['recordsTotal'] = count($sql_sorgu_2);
    $response['recordsFiltered'] = count($sql_sorgu_2);

    foreach ($sql_sonuc as $row){
        $response['data'][] = [
            'id'=>$row['id'],
            'official_code'=>$row['official_code'],
            'process_name'=>$row['process_name'],
            'process_description'=>$row['process_description'],
            'lab_hour'=>$row['lab_hour'],
            'lab_hour_status'=>$row['lab_hour_status'],
            'lab_day'=>$row['lab_day'],
            'lab_day_status'=>$row['lab_day_status'],
            'condition_code'=>$row['condition_code'],
            'condition_code_status'=>$row['condition_code_status'],
            'status'=>$row['status']
        ];
    }

    echo json_encode($response);

}






if ($islem == "kosul-kod-guncelle"){
    $id = $_GET["id"];
    $kosul_kodu_durum = tek("select condition_code_status from process_role where status=1 and id='$id'");
    $durum = $kosul_kodu_durum["condition_code_status"];
    if ($durum == 1){
        $arr = ["condition_code_status" => 0];
        $direktguncelle = direktguncelle("process_role","id",$id,$arr);
        if ($direktguncelle == 1){
            echo 1;
        }else{
            echo 2;
        }
    }else{
        $arr = ["condition_code_status" => 1];
        $direktguncelle = direktguncelle("process_role","id",$id,$arr);
        if ($direktguncelle == 1){
            echo 1;
        }else{
            echo 2;
        }
    }
}
if ($islem == "day_durum_guncelle"){
    $id = $_GET["id"];
    $kosul_kodu_durum = tek("select lab_day_status from process_role where status=1 and id='$id'");
    $durum = $kosul_kodu_durum["lab_day_status"];
    if ($durum == 1){
        $arr = ["lab_day_status" => 0];
        $direktguncelle = direktguncelle("process_role","id",$id,$arr);
        if ($direktguncelle == 1){
            echo 1;
        }else{
            echo 2;
        }
    }else{
        $arr = ["lab_day_status" => 1];
        $direktguncelle = direktguncelle("process_role","id",$id,$arr);
        if ($direktguncelle == 1){
            echo 1;
        }else{
            echo 2;
        }
    }
}
if ($islem == "saat_durum_guncelle"){
    $id = $_GET["id"];
    $kosul_kodu_durum = tek("select lab_hour_status from process_role where status=1 and id='$id'");
    $durum = $kosul_kodu_durum["lab_hour_status"];
    if ($durum == 1){
        $arr = ["lab_hour_status" => 0];
        $direktguncelle = direktguncelle("process_role","id",$id,$arr);
        if ($direktguncelle == 1){
            echo 1;
        }else{
            echo 2;
        }
    }else{
        $arr = ["lab_hour_status" => 1];
        $direktguncelle = direktguncelle("process_role","id",$id,$arr);
        if ($direktguncelle == 1){
            echo 1;
        }else{
            echo 2;
        }
    }
}
if ($islem == "islemhizmetin") {
    if ($_POST) {
        $_POST["insert_datetime"] =$simdikitarih;
        $_POST["insert_userid"] = $_SESSION["id"];

        $yatissekle = direktekle("process_role",$_POST);
var_dump($yatissekle);
        if ($yatissekle) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }

    }
}
if ($islem == "durum_guncelleaktif"){
    $idler = $_GET["id"];
    foreach ($idler as $id) {

            $arr = ["condition_code_status" => 1,"lab_day_status" => 1,"lab_hour_status" => 1];
            $direktguncelle = direktguncelle("process_role","id",$id,$arr);
            if ($direktguncelle == 1){
                echo 1;
            }else{
                echo 2;
            }
    }

}
if ($islem == "durum_guncellepasif"){
    $idler = $_GET["id"];
    foreach ($idler as $id) {
        $arr = ["condition_code_status" => 0,"lab_day_status" => 0,"lab_hour_status" => 0];
        $direktguncelle = direktguncelle("process_role","id",$id,$arr);
        if ($direktguncelle == 1){
            echo 1;
        }else{
            echo 2;
        }
    }

}
if ($islem == "listeyi-getir") {
    ?>
    <div class="card-body bg-white">
        <table class="table table-sm table-bordered w-100 display nowrap" style="font-size: 13px;" id="hizmet_tanim_tablo_datatable">
            <thead>
            <tr>
                <th>#</th>
                <th>No</th>
                <th>Hizmet Adı</th>
                <th>Sut Kodu</th>
                <th>Kural Açıklaması</th>
<!--                <th>N.K</th>-->
                <th>Kural Sut Kodu</th>
                <th>Kural Sut Kodu Durum</th>
                <th>Kural Gün</th>
                <th>Kural Gün Durum</th>
                <th>Kural Saat</th>
                <th>Kural Saat Durum</th>
                <th>Durum</th>
                <th>İşlem</th>
            </tr>
            </thead>
            <tbody style="cursor: pointer;">
            </tbody>
        </table>
    </div>
    <script>
        var count_701 = 0;
        var hizmet_tanim_tablo = $('#hizmet_tanim_tablo_datatable').DataTable({
            "serverSide": true,
            "scrollY": '55vh',
            "scrollX": true,
            "pageLength":50,
            "language": {"url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"},

            ajax: {
                url: '/ajax/tanimlar/hizmet-tanimla.php?islem=serverside',
                processing: true,
                method: 'POST',

            },
            columns:[
                {
                    data: null,
                    render: function (data) { return "<center><input class='form-check-input checkbox123' "+" type='checkbox' id='"+data.id+"'></center>"; }
                },
                {data:'id'},
                {data:'process_name'},
                {data:'official_code'},
                {data:'process_description'},
                {data:'condition_code'},
                {
                    data: null,
                    render: function (data) { if(data.condition_code_status==1){  var kosul_checked="checked"; }
                        return "<center><input class='form-check-input chk"+data.id+"'  "+kosul_checked+" type='checkbox' data-id='"+data.id+"' id='kosul_kodu_guncelle'></center>"; }
                },
                {data:'lab_day'},
                {
                    data: null,
                    render: function (data) { if(data.lab_day_status==1){  var day_checked="checked"; }
                        return "<center><input class='form-check-input chg"+data.id+"' "+day_checked+" type='checkbox' data-id='"+data.id+"' id='day_durum_guncelle'></center>"; }
                },
                {data:'lab_hour'},
                {
                    data: null,
                    render: function (data) { if(data.lab_hour_status==1){  var saat_checked="checked"; }
                        return "<center><input class='form-check-input chs"+data.id+"' "+saat_checked+" type='checkbox' data-id='"+data.id+"' id='saat_durum_guncelle'></center>"; }
                },
                {
                    data: null,
                    render: function (data) { if(data.status==1){  var durum='<b class="text-success">Aktif</b>'; }else { var durum='<b class="text-danger">Pasif</b>'; }
                        return durum; }
                },
                {
                    data: null,
                    render: function (data) { return "<center><i class='fa-thin fa-pen-to-square text-success fa-lg hizmetdüzenle' data-bs-target='#modal-getir' data-bs-toggle='modal' data-id='"+data.id+"'></i></center>"; }
                }
            ],
            "aLengthMenu":[[50,100,250,500,-1],['50 Adet','100 Adet','250 Adet','500 Adet','Tümü']],
            "dom": "<'row'<'col-sm-12 col-md-3 'B><'col-sm-12 col-md-6 tabbaslik'><'col-sm-12 col-md-3'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'lp>>",
            "buttons": [
                {
                    text: '<i class="fas fa-check"></i> Tümünü Seç',
                    className: 'btn btn-warning all-selecet',
                    titleAttr: 'Tümünü Seç',
                    action: function (e, dt, node, config) {

                        if (count_701 == 0) {
                            $('.checkbox123').prop("checked", true);
                            count_701 = 1;
                        } else {
                            $('.checkbox123').prop("checked", false);
                            count_701 = 0;
                        }

                        $('.sil-702').prop("disabled", false);

                    }
                },
                {
                    text: '<i class="fa-thin fa-repeat"></i> Aktif',
                    className: 'btn btn-success sil-702',
                    titleAttr: 'Sil',
                    action: function (e, dt, node, config) {
                        var id = [];
                        $(".checkbox123:checked").off().each(function () {
                            id.push($(this).attr('id'));
                        });
                        var boyut = id.length
                        if (boyut > 0) {
                            $.get("ajax/tanimlar/hizmet-tanimla.php?islem=durum_guncelleaktif",{id:id},function (result){
                                if (result != 2){
                                    alertify.success("İşlem Başarılı");
                                    hizmet_tanim_tablo.ajax.url(hizmet_tanim_tablo.ajax.url()).load();
                                } else {
                                    alertify.warning("Bilinmeyen Bir Hata Oluştu");

                                }
                            });

                        } else {
                            alertify
                                .alert("Lütfen işlem yapmak istediğin hizmeti seçiniz..", function () {
                                    alertify.message('OK');
                                });
                        }
                    }
                },
                {
                    text: '<i class="fa-thin fa-repeat"></i>Pasif',
                    className: 'btn btn-danger sil-702',
                    titleAttr: 'Sil',
                    action: function (e, dt, node, config) {
                        var id = [];
                        $(".checkbox123:checked").off().each(function () {
                            id.push($(this).attr('id'));
                        });
                        var boyut = id.length
                        if (boyut > 0) {
                            $.get("ajax/tanimlar/hizmet-tanimla.php?islem=durum_guncellepasif",{id:id},function (result){
                                if (result != 2){
                                    alertify.success("İşlem Başarılı");
                                    hizmet_tanim_tablo.ajax.url(hizmet_tanim_tablo.ajax.url()).load();
                                } else {
                                    alertify.warning("Bilinmeyen Bir Hata Oluştu");

                                }
                            });

                        } else {
                            alertify
                                .alert("Lütfen işlem yapmak istediğin hizmeti seçiniz..", function () {
                                    alertify.message('OK');
                                });
                        }
                    }
                },
                {
                    text: '<i class="fa-thin fa-plus"></i> Yeni Kayıt',
                    className: 'btn btn-info',
                    titleAttr: 'Sil',
                    action: function (e, dt, node, config) {
                        $('#modal-getir').modal('show');
                        $.get("ajax/tanimlar/hizmet-tanimla.php?islem=hizmetbodyup",{},function (result){
                            $('#modal-tanim-icerik').html(result);
                        });
                    }
                }

            ],
            order:[
                [1,'ASC']
            ]

        });

        $("body").off("click",".hizmetdüzenle").on("click",".hizmetdüzenle",function (){

            var id = $(this).attr("data-id");
            $.get("ajax/tanimlar/hizmet-tanimla.php?islem=hizmetbodyup",{id:id},function (result){
                $('#modal-tanim-icerik').html(result);
                hizmet_tanim_tablo.ajax.url(hizmet_tanim_tablo.ajax.url()).load();
            });
        });

        $("body").off("click","#kosul_kodu_guncelle").on("click","#kosul_kodu_guncelle",function (){
            var id = $(this).attr("data-id");
            $.get("ajax/tanimlar/hizmet-tanimla.php?islem=kosul-kod-guncelle",{id:id},function (result){
               if (result != 2){
                   hizmet_tanim_tablo.ajax.url(hizmet_tanim_tablo.ajax.url()).load();
                   alertify.success("İşlem Başarılı");
               } else {
                   alertify.warning("Bilinmeyen Bir Hata Oluştu");
                   $("#kosul_kodu_guncelle").prop('checked', false);
               }
            });
        });

        $("body").off("click","#day_durum_guncelle").on("click","#day_durum_guncelle",function (){
            var id = $(this).attr("data-id");
            $.get("ajax/tanimlar/hizmet-tanimla.php?islem=day_durum_guncelle",{id:id},function (result){
               if (result != 2){
                   hizmet_tanim_tablo.ajax.url(hizmet_tanim_tablo.ajax.url()).load();
                   alertify.success("İşlem Başarılı");
               } else {
                   alertify.warning("Bilinmeyen Bir Hata Oluştu");
                   $("#day_durum_guncelle").prop('checked', false);
               }
            });
        });

        $("body").off("click","#saat_durum_guncelle").on("click","#saat_durum_guncelle",function (){
            var id = $(this).attr("data-id");
            $.get("ajax/tanimlar/hizmet-tanimla.php?islem=saat_durum_guncelle",{id:id},function (result){
               if (result != 2){
                   hizmet_tanim_tablo.ajax.url(hizmet_tanim_tablo.ajax.url()).load();
                   alertify.success("İşlem Başarılı");
               } else {
                   alertify.warning("Bilinmeyen Bir Hata Oluştu");
                   $("#saat_durum_guncelle").prop('checked', false);
               }
            });
        });

    </script>
<?php }
if ($islem == "hizmetbodyup") {
    $gelen_id=$_GET['id'];
//    $hizmet=singularactive("process_role","id",$gelen_id);
    $hizmet=tek("select * from process_role where id={$gelen_id}");
    ?>
    <div class="modal-content">

        <div class="modal-header py-1 px-3">
            <h5 class="modal-title">Hizmet İşlem</h5>

            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <div class="row" id="modal-print">
                <div class="col-md-12">
                    <div class="card ">
                        <div class="card-header" style="height: 4vh;">
                            <div class="col-12 row">
                                <h5 style="font-size: 13px;">Hizmet Düzenle</h5>
                            </div>
                        </div>
                        <div class="mx-3" >
                            <div class="hizmetupbody mt-1">
                                <form id="formhizmetup" action="javascript:void(0);"  class="form-control">
                                    <div class="row">
                                        <?php //var_dump($hizmet);  ?>
                                        <div class="col-md-6">
                                            <?php if ($hizmet['id']!=''){ ?>
                                        <input type="hidden" class="form-control" name="id"
                                               value="<?php echo $hizmet['id'] ?>" id="basicpill-firstname-input">
                                            <?php } ?>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row mx-2">
                                                <label class="form-label col-md-4" >Hizmet Adı</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control"
                                                           <?php
                                                           if ($hizmet['process_name']!=''){ ?>
                                                               disabled
                                                          <?php }else{ ?>
                                                               name="process_name"
                                                         <?php  }
                                                           ?>
                                                           value="<?php
                                                               echo $hizmet['process_name'];
                                                           ?>" id="basicpill-firstname-input">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row mx-2">
                                                <label for="basicpill-firstname-input" class="form-label col-md-4" >Sut Kodu</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control"
                                                        <?php
                                                        if ($hizmet['official_code']!=''){ ?>
                                                            disabled
                                                        <?php }else{ ?>
                                                            name="official_code"
                                                        <?php  }
                                                        ?>
                                                           value="<?php
                                                               echo $hizmet['official_code'];
                                                           ?>" id="basicpill-firstname-input">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class=" row mt-1">
                                        <div class="col-md-6">
                                            <div class="row mx-2">
                                                <label class="form-label col-md-4" >Hizmet Uyarı</label>
                                                <div class="col-md-8">
                                                    <textarea class="form-control" name="process_description"
                                                              rows="3"><?php echo $hizmet['process_description'] ?></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="row mx-2">
                                                <label class="form-label col-md-4" >Kural Sut Kodu</label>
                                                <div class="col-md-8">

                                                   <textarea class="form-control" name="condition_code"
                                                             rows="3"><?php echo $hizmet['condition_code'] ?></textarea>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-1 row">
                                        <div class="col-md-6">
                                            <div class="row mx-2">
                                                <label class="form-label col-md-4" >Kural Gün</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="lab_day"
                                                           value="<?php
                                                           echo $hizmet['lab_day'];
                                                           ?>" id="basicpill-firstname-input">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer pb-0 pt-0">
                                <?php
                                if ($hizmet['id']==''){
                                    ?>
                                    <button type="button " class="btn up-btn btn-sm  hizmetinsert" data-bs-dismiss="modal"><i class="fas fa-edit" aria-hidden="true"></i>Kaydet</button>

                                <?php }else{ ?>
                                    <button type="button " class="btn up-btn btn-sm  hizmetupdate" data-bs-dismiss="modal"><i class="fas fa-edit" aria-hidden="true"></i> Güncelle</button>
                                    <?php if ($hizmet['status']){ ?>
                                    <button type="button " class="btn sil-btn btn-sm hizmetdelete" data-id="<?php echo $hizmet['id'] ?>" data-bs-dismiss="modal"><i class="fa fa-trash" aria-hidden="true"></i> Pasif Et</button>
                                    <?php } else{  ?>
                                        <button type="button " class="btn sil-btn btn-sm hizmetaktif" data-id="<?php echo $hizmet['id'] ?>" data-bs-dismiss="modal"><i class="fa fa-trash" aria-hidden="true"></i> Aktif Et</button>
                                    <?php } ?>
                                <?php  }
                                ?>
                            </div>

                        </div>
                    </div>

                </div>

                <script>
                    $(document).ready(function () {
                        $(".hizmetinsert").off().on("click", function () {
                            //form reset-->
                            var gonderilenform = $("#formhizmetup").serialize();

                            document.getElementById("formhizmetup").reset();
                            $.ajax({
                                type: 'POST',
                                url: 'ajax/tanimlar/hizmet-tanimla.php?islem=islemhizmetin',
                                data: gonderilenform,
                                success: function (e) {
                                    $("#sonucyaz").html(e);
                                    hizmet_tanim_tablo.ajax.url(hizmet_tanim_tablo.ajax.url()).load();

                                }
                            });
                        });




                        $(".hizmetupdate").off('click').on("click", function () {

                            var gonderilenform = $("#formhizmetup").serialize();
                            $.ajax({
                                type: 'POST',
                                url: 'ajax/tanimlar/hizmet-tanimla.php?islem=islemhizmetup',
                                data: gonderilenform,
                                success: function (e) {
                                    $("#sonucyaz").html(e);
                                    hizmet_tanim_tablo.ajax.url(hizmet_tanim_tablo.ajax.url()).load();
                                }
                            });

                        });


                        $(".hizmetdelete").click(function () {
                            var id =$(this).attr('data-id');
                            alertify.confirm("<div class='alert alert-danger'>İptal Nedeninizi Belirtiniz...</div><input class='form-control' type='text' id='personel_delete_detail' placeholder='İptal Nedeni Giriniz'>", function(){

                                var delete_detail = $('#personel_delete_detail').val();

                                $.ajax({
                                    type: 'POST',
                                    url: 'ajax/tanimlar/hizmet-tanimla.php?islem=islemhizmetdelete',
                                    data: {id:id, delete_detail: delete_detail},
                                    success: function (e) {
                                        $("#sonucyaz").html(e);
                                        hizmet_tanim_tablo.ajax.url(hizmet_tanim_tablo.ajax.url()).load();

                                    }
                                });

                            }, function(){ alertify.warning('İptal işleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"İptal işlemini Onayla"});
                        });

                        $(".hizmetaktif").click(function () {
                            var id =$(this).attr('data-id');
                            $.ajax({
                                type: 'POST',
                                url: 'ajax/tanimlar/hizmet-tanimla.php?islem=islemhizmetaktif',
                                data: {id:id},
                                success: function (e) {
                                    $("#sonucyaz").html(e);
                                    hizmet_tanim_tablo.ajax.url(hizmet_tanim_tablo.ajax.url()).load();

                                }
                            });

                        });

                    });

                </script>
            </div>
        </div>

    </div>

<?php }
elseif ($islem == "islemhizmetup") {
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);

        $sonuc = direktguncelle("process_role", "id", $id, $_POST);
        if ($sonuc) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }

    }
}
elseif ($islem == "islemhizmetdelete") {
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);
        $detay = $_POST['delete_detail'];
        $silme = $_SESSION["id"];

        $vezneguncelle = canceldetail("process_role", "id", $id, $detay, $silme, $tarih);
        if ($vezneguncelle) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }
    }
}

elseif ($islem == "islemhizmetaktif") {
    if ($_POST) {
        $id = $_POST['id'];
        unset($_POST['id']);

        $vezneguncelle =  $sql = backcancel('process_role', 'id', $id);
        if ($vezneguncelle) { ?>
            <script>
                alertify.success('işlemi Başarili');
            </script>
        <?php } else { ?>
            <script>
                alertify.error('işlemi Başarisiz');
            </script>

        <?php }
    }
}
?>



