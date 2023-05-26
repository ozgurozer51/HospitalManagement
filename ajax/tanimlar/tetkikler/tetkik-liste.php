
<?php
include "../../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
$simdikitarih = date('Y/m/d H:i:s');
$islem=$_GET['islem'];
session_start();
ob_start();
$KULLANICI_ID = $_SESSION['id'];

if($islem=="tetkik-grup-liste"){ ?>

 <table class="table table-bordered nowrap display w-100 " id="TGroup" style="cursor: pointer;" >
     <thead>
     <tr>
         <th>Grup Adı</th>
         <th>Grup Kodu</th>
         <th>Ana ID</th>
         <th>Durumu</th>
         <th>İşlem</th>
     </tr>
     </thead>

     <tbody>
    <?php $sql = verilericoklucek("select * from process_group where parent_id='1' ");
     foreach ($sql as $row) { ?>
         <tr>
             <td class='hizmet-grup-getir' id="<?php echo $row["id"]; ?>"><?php  echo $row["group_name"] ?></td>
             <td id="<?php echo $row["id"]; ?>" ><?php echo $row["id"]; ?></td>
             <td class='hizmet-grup-getir' id="<?php echo $row["id"]; ?>" ><?php echo $row["parent_id"]; ?></td>
             <td id="<?php echo $row["id"]; ?>"  class='hizmet-grup-getir' align="center"><?php  if ($row["status"]=='1'){ ?> <b style="color: green">Aktif</b> <?php }else {?> <b style="color: darkred">Pasif</b>  <?php } ?></td>
             <td>
                 <button  tetkik-id='<?php echo $row["id"]; ?>' type="button" data-bs-target="#tetkik-grup-modal" data-bs-toggle="modal" class="btn btn-success btn-sm tetkikbilgisigetir">  <i class="fa-solid fa-edit"></i></button>
                 <button tetkik-id='<?php echo $row["id"]; ?>'  type="button" class="btn btn-danger btn-sm  tetkikgrup-sil-buton"><i class="fa-solid fa-trash"></i></button>
             </td>
         </tr>
     <?php } ?>
     </tbody>
 </table>

<script>
    $(document).ready(function(){

        $("body").off("click", ".hizmet-grup-getir").on("click", ".hizmet-grup-getir", function(e){
            var tetkikGrupId = $(this).attr('id');
            $.get('ajax/tanimlar/tetkikler/tetkik-liste.php?islem=tetkik_detay_list', { tetkikGrupId:tetkikGrupId },function(getveri){
                $('#tetkik-detay-list').html(getveri);
            });
        });

        $('#TGroup').DataTable({
            "scrollY": '55vh',
            "scrollCollapse": true,
            "scrollX": true,
            "paging": true,
            'Visible': true,
            "select": true,
            "pageLength": 50,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
            "dom":"<'row'<'col-6 columns'B><'col-6 columns'f>r>"+
                "t"+
                "<'row'<'small-6 columns'><'small-6 columns'p>>",
            buttons: [{
                    text: 'Hizmet Grup Ekle',
                    className: 'btn btn-success btn-sm btn-kaydet',
                    attr:  {
                        'data-bs-toggle':"modal",
                        'data-bs-target':"#tetkik-grup-modal",
                    },
                    action: function ( e, dt, button, config ) {
                        var tetkikGrupId = $(this).attr('tetkik-id');
                        $.get( "ajax/tanimlar/tetkikler/tetkik-modal.php?islem=tetkik-grup-modal", { tetkikGrupId:tetkikGrupId },function(getveri){
                            $('#modal-tetkik-grup-icerik').html(getveri);
                        });
                    }
                }],
        });

        $(document).on("click",".tetkikbilgisigetir",function() {
            var tetkikGrupId = $(this).attr('tetkik-id');
            $.get( "ajax/tanimlar/tetkikler/tetkik-modal.php?islem=tetkik-grup-modal", { tetkikGrupId:tetkikGrupId },function(getveri){
                $('#modal-tetkik-grup-icerik').html(getveri);
            });
        });

    });

    $("body").off("click", ".tetkikgrup-sil-buton").on("click", ".tetkikgrup-sil-buton", function(e){
        var id = $(this).attr('tetkik-id');
        alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div>" +
            "<textarea class='form-control' id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Branş Silme Nedeni..'></textarea>", function(){
            var delete_detail = $('#delete_detail').val();
            $.ajax({
                type: 'POST',
                url:'ajax/tanimlar/tetkikler/tetkik-sql.php?islem=tetkik-grup-sil',
                data: {
                    id,
                    delete_detail,
                },
                success: function (e) {
                    $("#sonucyaz").html(e);
                    $.get("ajax/tanimlar/tetkikler/tetkik-liste.php?islem=tetkik-grup-liste", {}, function (e) {
                        $('.tetkik-grup-liste').html(e);
                    });
                    $('.alertify').remove();
                }
            });
        }, function(){ alertify.message('Silme İşleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"Tetkik Grubu Silme İşlemini Onayla"});
    });
</script>

<?php }
    if($islem=="tetkik_detay_list"){ $tetkikGrupId=$_GET["tetkikGrupId"];  ?>
    <div class="card">
        <div class="card-header px-2 text-white fw-bold" style="background-color:#3F72AF;height:39px;">
            <div class="row">
                <div class="col-md-8">Tetkikler</div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered display nowrap w-100" id="tetkikDetaylariTable">
                <thead>
                <tr>
                    <th>İşlem</th>
                    <th>Durum</th>
                    <th>İşlem Adı</th>
                    <th>Özel Kod</th>
                    <th>Hizmet Açıklaması</th>

                </tr>
                </thead>
                <tbody>

                <?php  $sql= verilericoklucek("select * from processes where process_group_id = '$tetkikGrupId'");
                foreach ($sql as $row) { ?>
                    <tr>
                        <td><button  tetkik-id='<?php echo $row["id"]; ?>' type="button" data-bs-target="#tetkik-detay-modal" data-bs-toggle="modal" class="btn btn-success btn-sm tetkikdetaygetir">  <i class="fa-solid fa-edit"></i></button>
                            <button id='<?php echo $row["id"]; ?>'  type="button" class="btn btn-danger btn-sm tetkik-detay-sil-buton"><i class="fa-solid fa-trash"></i></button></td>
                        <td align="center"><?php  if ($row["status"]=='1'){ ?> <b style="color: green">Aktif</b> <?php }else {?> <b style="color: darkred">Pasif</b>  <?php } ?></td>
                        <td  id="<?php echo $row["id"]; ?>"  class='islem-detayiguncelle'><?php echo $row["process_name"]; ?></td>
                        <td  id="<?php echo $row["id"]; ?>"  class='islemdetayiguncelle'><?php echo $row["offical_code"];  parabirimi(); ?> </td>
                        <td  id="<?php echo $row["id"]; ?>"  class='islemdetayiguncelle'><?php echo $row["process_description"]; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>



        <script>
            $(document).ready(function(){
                $('#tetkikDetaylariTable').DataTable({
                    "scrollY": '55vh',
                    "scrollX": true,
                    "paging":true,
                    'Visible': true,
                    "responsive":true,
                    "pageLength": 50,
                    "language": {
                        "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                    },
                    "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    buttons: [
                        {
                            text: 'Tetkik Ekle',
                            className: 'btn btn-success btn-sm btn-kaydet',
                            attr:  {
                                'data-bs-toggle':"modal",
                                'data-bs-target':"#tetkik-detay-modal",
                            },
                            action: function ( e, dt, button, config ) {
                                var tetkikDetayId = $(this).attr('tetkik-id');
                                var tetkikGrupId ="<?php echo $tetkikGrupId; ?>";
                                //alert(tetkikDetayId);
                                $.get( "ajax/tanimlar/tetkikler/tetkik-modal.php?islem=tetkik-detay-modal", { tetkikDetayId:tetkikDetayId,tetkikGrupId:tetkikGrupId },function(getveri){
                                    $("#modal-tetkik-detay-icerik").html(getveri);
                                });
                            }
                        }
                    ],
                });
            });

            $(document).on("click",".tetkikdetaygetir",function() {
                var tetkikDetayId = $(this).attr('tetkik-id');
                var tetkikGrupId ="<?php echo $tetkikGrupId; ?>";
                //alert(tetkikDetayId);
                $.get( "ajax/tanimlar/tetkikler/tetkik-modal.php?islem=tetkik-detay-modal", { tetkikDetayId:tetkikDetayId,tetkikGrupId:tetkikGrupId },function(getveri){
                    $("#modal-tetkik-detay-icerik").html(getveri);
                });
            });

            $("body").off("click", ".tetkik-detay-sil-buton").on("click", ".tetkik-detay-sil-buton", function(e){
                var id = $(this).attr('id');
                var tetkikGrupId ="<?php echo $tetkikGrupId; ?>";
                alertify.confirm("<div class='alert alert-danger'>Silme Nedeninizi Belirtiniz...</div>" +
                    "<textarea class='form-control' id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Branş Silme Nedeni..'></textarea>", function(){
                    var delete_detail = $('#delete_detail').val();
                    $.ajax({
                        type: 'POST',
                        url:'ajax/tanimlar/tetkikler/tetkik-sql.php?islem=tetkik-detay-sil',
                        data: {
                            id,
                            delete_detail,

                        },
                        success: function (e) {
                            $(".sonucyaz").html(e);
                            $.get( "ajax/tanimlar/tetkikler/tetkik-liste.php?islem=tetkik_detay_list", { tetkikGrupId:tetkikGrupId },function(getveri){
                                $('#tetkik-detay-list').html(getveri);
                            });
                            $('.alertify').remove();
                        }
                    });
                }, function(){ alertify.message('Silme İşleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"Tetkik Grubu Silme İşlemini Onayla"});
            });

        </script>



<?php }
