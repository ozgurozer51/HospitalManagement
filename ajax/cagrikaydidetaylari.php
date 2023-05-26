<?php
include "../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();

?>

<div class="modal-dialog modal-lg">
    <!-- modal content-->
    <div class="modal-content">
        <div class="modal-header"> 
            <h4 class="modal-title">Çağırma Geçmişi</h4>
        </div>

        <div class="modal-body">
            <div class="ihsan" style=" height: 20em; overflow: auto; ">
                <table class="table table-bordered border-primary mb-0">

                    <thead>
                    <tr>
                        <th>çağrılma sayısı</th>
                        <th>çağrılma saati</th>
                        <th>çağıran kullanıcı</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $say=1;
                    $hastaistemlerigetir = "select * from patient_registration_call_history where tc_id='{$_GET["tc_kimlik"]}' and call_unit='{$_GET["poliklinikid"]}' and call_date='$gunceldate'";

                    $bilgigetir = verilericoklucek($hastaistemlerigetir);
                    foreach ($bilgigetir as $rowa) {
                        ?>
                        <tr>
                            <td><?php echo $say; ?></td>
                            <td><?php echo $rowa["call_time"]; ?></td>
                            <td><?php $sounc=singular("users","id",$rowa["call_userid"]); echo $sounc["name_surname"]; ?></td>
                        </tr>
                        <?php $say++; } ?>
                    </tbody>

                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">kapat</button
        </div>
    </div>

</div>