<?php
include "../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
date_default_timezone_set('Europe/istanbul');
$simdikitarih = date('Y-m-d H:i:s');
$dizi = explode(" ", $simdikitarih);
$tarih = $dizi['0'];
$saat = $dizi['1'];
$kullanicid = $_SESSION['id'];
$islem = $_GET['islem'];
if ($islem == "yatakhareketibody") {

    $hastakayit = singular("patient_registration", "protocol_number", $_GET["getir"]);

    if ($hastakayit['mother_tc_identity_number']==''){
        $patients = singular("patients", "id", $hastakayit['patient_id']);
    }else{
        $hastalarid=$hastakayit['patient_id'];
        $patients = tek("SELECT * FROM patients WHERE id='$hastalarid'");
    }
    $protokolno=$hastakayit['protocol_number'];
    $taburcu = singular("patient_discharge", "admission_protocol",$protokolno);
    $izin = singularactive("patient_permission","protocol_number",$protokolno);
    ?>

    <div class="modal-content">
        <div >
            <div class="modal-header text-white py-1 px-3">
                <h5 class="modal-title">Yatak Hareketleri  - <?php echo strtoupper($patients['patient_name']."  ".$patients['patient_surname']); ?></h5>

                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card ">
                            <div class="card-header " style="height: 4vh;">

                                <h5 style="font-size: 13px;">Yatak Hareketleri Listesi</h5>
                            </div>
                            <div class="mx-1">
                                <div class="card-body mx-0 yatakhareketlerilistesi">
                                    <script>
                                        var patients_id = "<?php echo $hastakayit['patient_id']; ?>";
                                        $.get("ajax/yatis/yatak_hareketleri_liste.php?islem=yatakhareketlerilistesi", {patients_id:patients_id}, function (g) {
                                            $('.yatakhareketlerilistesi').html(g);
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php }
elseif($islem=="yatakhareketlerilistesi"){ ?>

    <table class="table border table-bordered border-dark table-hover nowrap display w-100" style="font-size: 13px;"  id="tableyatakhareketleri-<?php echo $_GET["patients_id"]; ?>">
        <thead>
        <tr>
            <th>işlem Tarihi</th>
            <th>Yatış Başlangıç</th>
            <th>Yatış Bitiş</th>
            <th>Önceki Birim</th>
            <th>Önceki Doktor</th>
            <th>Şimdiki Birim</th>
            <th>Şimdiki Doktor</th>
            <th>Yatış Süresi</th>
            <th>Durum</th>
        </tr>
        </thead>
        <tbody>
        <?php $patients_id=$_GET["patients_id"];
        $demo = "select inbound_transfer_service as o_birim,inbound_transfer_doctor as o_doktor,transfer_date as tarih,definition_name as transfer_tur,
       patient_registration.hospitalized_accepted_datetime as baslangic,patient_registration.admission_end_date as bitis,
       patient_discharge.transfer_service as s_birim,patient_discharge.transfer_doctor as s_doktor from patient_discharge
    inner join transaction_definitions on patient_discharge.discharge_type=transaction_definitions.id
    inner join patient_registration on patient_registration.hospitalization_protocol=patient_discharge.admission_protocol
      where patients_id='$patients_id' and definition_name='Başka servise transfer' and patient_discharge.status='1' 
    order by patient_registration.hospitalized_accepted_datetime desc";
        $hello=verilericoklucek($demo);
        foreach ((array) $hello as $row) { ?>

            <tr id="<?php echo $row["id"]; ?>" class="yogumbakimizlembodyguncelle sevkbtn">
                <td><?php echo nettarih($row['tarih']); ?></td>
                <td><?php echo nettarih($row['baslangic']); ?></td>
                <td><?php echo nettarih($row['bitis']); ?></td>
                <td><?php echo birimgetirid($row['o_birim']); ?></td>
                <td><?php echo kullanicigetirid($row['o_doktor']); ?></td>
                <td><?php echo birimgetirid($row['s_birim']); ?></td>
                <td><?php echo kullanicigetirid($row['s_doktor']); ?></td>
                <td><?php echo ikitariharasindakigunfark($row['bitis'] , $row['baslangic']); ?></td>
                <td><?php echo $row['transfer_tur']; ?></td>
            </tr>

        <?php } ?>
        </tbody>
    </table>
    <script type="text/javascript">

        var yatak_hareketleri=$("#tableyatakhareketleri-<?php echo $_GET["patients_id"]; ?>").DataTable({
            "responsive": true,
            order: [[1, 'asc']],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
            "lengthChange": false,
            "info": false,

        });


    </script>

<?php }
