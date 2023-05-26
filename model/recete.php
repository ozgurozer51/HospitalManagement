<?php
include "../../controller/fonksiyonlar.php";


?>
<script>
    $(document).ready(function() {
        $('#birimlertab').DataTable( {
            "processing": true,
            "responsive":true,
            "scrollY": true,
            "autoWidth": false,
            "scrollX": true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"

            },
            "dom":"<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                {
                    text: 'Reçete Ekle',
                    className: 'btn btn-success btn-sm btn-kaydet',
                    attr:  {
                        'data-bs-toggle':"modal",
                        'data-bs-target':"#recete_modal",
                    },

                    action: function ( e, dt, button, config ) {
                        var getir = $('.hastasecbtn:checked').attr('id');
                        $.get( "ajax/recete/recetemodal.php?islem=modal_recete",{ getir:getir },function(getveri){
                            $('#recete_modal_icerik').html(getveri);
                        });
                    }
                }
            ],
        });

    } );
</script>


<div class="card">

    <div class="modal fade" id="recete_modal" aria-hidden="true" >
        <div class="modal-dialog"  id="recete_modal_icerik" style=" width: 98%; max-width: 98%; "> </div>
    </div>

    <div class="card-body bg-white">

        <table id="birimlertab" class="table table-striped table-bordered w-100 display nawrap" >
            <thead>
            <tr>
                <th>#</th>
                <th>AD SOYAD</th>
                <th>POLİKİNLİK</th>
                <th>HEKİM</th>
                <th>İŞLEM TÜRÜ</th>
                <th>MUAYNE BAŞLAMA ZAMANI</th>
                <th>MUAYNE BİTİŞ ZAMANI</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $say = 0;
            $hasta_kayit=verilericoklucek("select patient_registration.protocol_number as patientregistrationid,patient_registration.*,patients.* from patient_registration inner join patients on patient_registration.tc_id=patients.tc_id where patient_registration.status='1'");
            foreach ($hasta_kayit as $rowa) {
                $process_type=$rowa["process_type"];
                $say++; ?>
                <tr style="cursor: pointer;" id="<?php echo $rowa["patientregistrationid"]; ?>" class="hastasec">
                    <td><input class="form-check-input hastasecbtn" type="radio" id="<?php echo $rowa["patientregistrationid"]; ?>"></td>
                    <td><?php echo hastalaradi($rowa["id"]), " ", hastalarsoyadi($rowa["id"]);?></td>
                    <td><?php echo birimgetirid($rowa["outpatient_id"]); ?></td>
                    <td><?php echo kullanicigetirid($rowa["doctor"]); ?></td>
                    <td><?php echo $rowa["process_type"]; ?></td>
                    <td><?php echo nettarih($rowa["examination_start_time"]); ?></td>
                    <td><?php echo $rowa["examination_finish_time"]; ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    
</div>
<script>
    $(".hastasec").click(function () {
        $(".hastasecbtn").prop("checked", false);
        var getir = $(this).attr('id');
        $('[id="' + getir + '"]').prop("checked", true);
    });
</script>