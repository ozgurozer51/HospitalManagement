
<div class="ameliyathanelistesi"><!--Modal Buraya Çekecek--></div>

<script>
    $(document).ready(function () {
        $.get( "ajax/ameliyathane-modul/ameliyathanelistesi.php?islem=ameliyathanelistesi", { },function(e){
            $('.ameliyathanelistesi').html(e);
            
        });

        $('#birime_masa_kayit').on('shown.bs.modal', function () {
            $('#DELETE_APPROVAL').trigger('focus');
        })

        
    });
</script>

<!--Ameliyathane Güncelleme İçin Modal -------------------------------------------------------------------------------->
<div class="modal fade" id="ameliyathanegüncellemodal"   aria-hidden="true"  style="margin-top: 80px !important;">
    <div  class="modal-dialog bd-example-modal-lg modal-lg" role="document">
        <div class="modal-dialog modal-lg ameliyathaneguncellemodalbody">

        </div>
    </div>
</div>


<!--Ameliyathane Sil İçin  Modal -------------------------------------------------------------------------------------->
<div class="modal fade" id="ameliyathanesilmodal"  role="dialog" aria-hidden="true" style="margin-top: 85px !important;">
    <div  class="modal-dialog bd-example-modal-lg modal-lg" role="document">
        <div class="ameliyathanesilbodygetir">

        </div>
    </div>
</div>

<!--Birime Masa Kayıt Et----------------------------------------------------------------------------------------------->
<div class="modal fade" role="dialog" id="birime_masa_kayit" aria-hidden="true" style="margin-top: 85px !important;">
    <div class="modal-dialog" id="personel_ameliyat_takvimi_dom" style="width:80%; max-width: 80%;">
    <div class="birime_kayitli_ameliyat_masalari_goster">

    </div>
    </div>
</div>

<!--Hasta Listesi Hastaya Randevu Başlat------------------------------------------------------------------------------->
<div class="modal fade" id="ameliyat_birim_ekle" aria-hidden="true" style="margin-top: 85px !important;">
    <div class="modal-dialog modal-lg " id='ameliyat_birim_modal_getir'> </div>
</div>