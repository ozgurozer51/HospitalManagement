<?php
include "../../controller/fonksiyonlar.php"; 
$baglanti = veritabanibaglantisi();
$tc_id=$_GET["tc_id"];
$hastabilgileri=tek("select * from patients where tc_id='$tc_id'");
?>
 
<div class="card p-3" style="margin-top: 5px;">
    <ul class="nav nav-pills mb-3 row" id="pills-tab" role="tablist">
        <li class="nav-item col-xl-2" role="presentation">
            <button class="nav-link w-100 active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#gecmismuayeneleri" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Geçmiş Muayeneleri</button>
        </li>
        <li class="nav-item col-xl-3" role="presentation">
            <button class="nav-link w-100 randevulistesigetir" id="pills-profile-tab" tc="<?php echo $tc_id; ?>" data-bs-toggle="pill" data-bs-target="#randevulistesi" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Hastaya Ait Randevular</button>
        </li>

        <li class="nav-item col-xl-2" role="presentation">
            <button class="nav-link w-100 randevulistesigetir" id="pills-profile-tab" tc="<?php echo $tc_id; ?>" data-bs-toggle="pill" data-bs-target="#randevulistesi" type="button" role="tab"  aria-selected="false">Yeni Kayıtlar</button>
        </li>
    </ul>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="gecmismuayeneleri" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
            <div class="row">
                <table class="table table-bordered table-sm table-hover" style=" background:white;width: 100%;"
                       id="hastane-table">

                    <thead class="table-light">
                    <tr>
                        <th>Protokol No</th>
                        <th>Kayıt Tarihi</th>
                        <th>Birim</th>
                        <th>Doktor</th> 
                    </tr>
                    </thead>
                    <tbody>

                    <?php $say = 0;
                    $hello = verilericoklucek("select patient_registration.id as hastakayitid, patients.patient_name as hastaadi,patients.patient_surname as hastasoyadi ,patients.*,patient_registration.* from patient_registration,patients where patient_registration.tc_id='$tc_id' and baby='0'   and patient_registration.tc_id=patients.tc_id order by patient_registration.id desc");
                    foreach ($hello as $str) {
//var_dump($str);
                        $drbilgisi = singular("users", "id", $str["doctor"]);
                        $birimbilgisi = singular("units", "id", $str["outpatient_id"]);
//                        var_dump($birimbilgisi);
                        ?>
                        <tr>
                            <td><?php echo $str["hastakayitid"]; ?></td>
                            <td><?php echo date('d.m.y', strtotime($str["insert_datetime"])); ?></td>
                            <td><?php echo $birimbilgisi["department_name"]; ?></td>
                            <td><?php echo $drbilgisi["name_surname"]; ?></td>
                             
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="randevulistesi" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
            <div id="hastabazlirandevulistesiicerik">
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#hastane-table').DataTable({
        "responsive": true,
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
        },
       
    
    });
    $(document).ready(function(){

        $(".randevulistesigetir").click(function(){
            var tc_id = $(this).attr('tc_id');
            $.get( "ajax/hastaislemleri/randevulistesi.php", { tc_id:tc_id },function(getVeri){
                $('#hastabazlirandevulistesiicerik').html(getVeri);

            });
        });
    });


</script>

 