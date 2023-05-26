<?php
include "../../../controller/fonksiyonlar.php";
$islem=$_GET['islem'];

 if($islem=="listeyi-getir"){ ?>
   <div class="user-tanim">
    <div class="card">


        <div class="card-body bg-white">
            <table class="table table-bordered table-sm table-hover w-100" id="kullanici-table">

                <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>kullanıcı tipi</th>
                    <th>tc kimlik</th>
                    <th>ünvanı</th>
                    <th>adı ve soyadı</th>
                    <th>bölümü</th>
                    <th>aktif/pasif</th>
                    <th>i̇şlem</th>
                </tr>
                </thead>
                <tbody>

                <?php  $say=0;
                $demo = "select * from users";
                $ameliyathanegetir = verilericoklucek($demo);
                foreach ($ameliyathanegetir as $row) {
                    $yetkigrupbilgisi=singular("authority_group","id",$row["authority_id"]);  ?>

                    <tr>
                        <td><?php echo $row["id"]; ?></td>
                        <td><?php echo $yetkigrupbilgisi["group_name"]; ?></td>
                        <td><?php echo $row["tc_id"]; ?></td>
                        <td><?php echo islemtanimgetir($row["title"]); ?></td>
                        <td><?php echo $row["name_surname"]; ?></td>
                        <td><?php  echo birimgetirid($row["department"]); ?><?php echo  "[". $row["department"]."]"; ?></td> 
                        <td align="center"  birim-id="<?php echo $row["id"]; ?>" data-bs-target="#modal-getir" title="Durum" data-bs-toggle="modal" class='kullanici-guncelle'><?php if($row["status"]==1){echo "<span style='color:green;'>Aktif</span>"; }else{echo "<span style='color: red;'>Pasif</span>"; } ?></td>
                        <td align="center"><i class="fas fa-edit mx-1 kullanici-guncelle" birim-id="<?php echo $row["id"]; ?>"  title="Düzenle" data-bs-target="#kullanici-tanim-modal" data-bs-toggle="modal"></i><i class="fas fa-trash user-delete-modal"  birim-id="<?php echo $row["id"]; ?>"  title="İptal"></i></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
   </div>

     <div class="modal fade modal-lg" id="kullanici-tanim-modal" aria-hidden="true" style="margin-top: 40px !important;">
         <div class="modal-dialog" id="kullanici-tanim-icerik">
         </div>
     </div>

    <script type="text/javascript">
        $('#kullanici-table').DataTable({
            "responsive": true,
            "scrollY": '60vh',
            "scrollCollapse": true,
            "pageLength": 100,
            "fixedColumns": true,
            "fixedHeader": true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },



            "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                {
                    text: 'Kullanıcı Ekle',
                    className: 'btn btn-success btn-sm btn-kaydet',
                    attr:  {
                        'data-bs-toggle':"modal",
                        'data-bs-target':"#kullanici-tanim-modal",
                    },
                    action: function ( e, dt, button, config ) {

                        $.get( "ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-modal.php?islem=kullanici-tanim",function(get){
                            $('#kullanici-tanim-icerik').html(get);
                        });
                    }
                }
            ],
        });


        $('#kullanici-table').css('height', '100%');

            $("body").off("click", ".kullanici-guncelle").on("click", ".kullanici-guncelle", function(e){
            var getir = $(this).attr('birim-id');
            $.get( "ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-modal.php?islem=kullanici-tanim", { getir:getir },function(getveri){
                $('#kullanici-tanim-icerik').html(getveri);
            });
        });

        $(document).ready(function(){
            function tcsorgula(str) {
                if (str.length==0) {
                    document.getElementById("sorguvarmi").innerhtml="";
                    document.getElementById("sorguvarmi").style.border="0px";
                    return;
                }
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                    if (this.readyState==4 && this.status==200) {
                        var ihsan =this.responseText;
                        if(ihsan){
                            document.getElementById("brans-kaydet").disabled = true;
                            document.getElementById("tcsize").style.background="#ff0000";
                            alertify.alert("dikkat","bu tc kimlik numaralı kullanıcı sistemlerinizde kayıtlı göründüğünden dolayı aynı tc kimlik numarası ile kayıt yapamazsınız!");
                            document.getElementById("tcsize").value = "";
                        }else{
                            document.getElementById("brans-kaydet").disabled = false;
                            document.getElementById("tcsize").style.background="white";
                        }
                    }
                }
                xmlhttp.open("get","ajax/kullanicitcsorgula.php?tc_kimlik="+str,true);
                xmlhttp.send();
            }

            $("body").off("click", ".kullanici-update").on("click", ".kullanici-update", function(e){
                var gonderilenform = $("#formkullanicilar").serialize();

                $.ajax({
                    type:'POST',
                    url:'ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-sql.php?islem=user-update',
                    data:gonderilenform,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $.get("ajax/tanimlar/kullanici-islemleri/kullanici-tanimla.php?islem=listeyi-getir", {}, function (e) {
                            $('.user-tanim:first').html(e);
                        });
                    }
                });
            });

            $("body").off("click", ".kullanici-ekle").on("click", ".kullanici-ekle", function(e){
                var gonderilenform = $("#formkullanicilar").serialize();
                document.getElementById("formkullanicilar").reset();
                $.ajax({
                    type:'POST',
                    url:'ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-sql.php?islem=user-insert',
                    data:gonderilenform,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $.get("ajax/tanimlar/kullanici-islemleri/kullanici-tanimla.php?islem=listeyi-getir", {}, function (e) {
                            $('.user-tanim:first').html(e);
                        });
                    }
                });
            });
        });

        $("body").off("click", ".user-delete-modal").on("click", ".user-delete-modal", function(e){
            var id = $(this).attr('birim-id');
            alertify.confirm("<div class='alert alert-danger');'>Silme Nedeninizi Belirtiniz...</div>" +
                "<textarea class='form-control' id='delete_detail' name='delete_detail' rows='1' placeholder='Silme Nedeni Giriniz.' title='Kullanıcı Silme Nedeni..'></textarea>" , function(){
                var delete_detail = $('#delete_detail').val();

                $.ajax({
                    type: 'POST',
                    url:'ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-sql.php?islem=user-delete',
                    data: {
                        id,
                        delete_detail,
                    },
                    success: function (e) {
                        $(".sonucyaz").html(e);
                        $.get("ajax/tanimlar/kullanici-islemleri/kullanici-tanimla.php?islem=listeyi-getir", {}, function (e) {
                            $('.user-tanim:first').html(e);
                            alertify.success( 'Kullanıcı Pasif Duruma Getirildi..');
                        });
                        $('.alertify').remove();
                    }
                });
            }, function(){ alertify.warning('Silme İşleminden Vazgeçtiniz')}).set({labels:{ok: "Onayla", cancel: "Vazgeç"}}).set({title:"Kullanıcı Silme İşlemini Onayla"});
        });

    </script>


<?php }
