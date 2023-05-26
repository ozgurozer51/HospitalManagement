

<?php
include "../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();

$islem=$_GET['islem'];

if($islem=="birimtipi"){
    $birimtipiid=$_POST['birimtipiid'];
    echo " <option value=''>birim seçiniz..</option>";
    $bolumgetir = 'select * from units where unit_type='.$birimtipiid .'order by bolum_adi';
    $hello = verilericoklucek($bolumgetir);
    foreach ($hello as $value) {
        var_dump($value);
        echo '<option value="'.$value['id'].'">'.$value['depatrment_name'].'</option>';

    }
}
elseif($islem=="birimgetir"){
    $birimid=$_POST['birimid'];
    echo " <option value=''>kullanıcı seçiniz..</option>";
    $bolumgetir = 'select * from users where department='.$birimid .'order by name_surname';
    $hello = verilericoklucek($bolumgetir);
    foreach ($hello as $value) {
        echo '<option value="'.$value['id'].'">'.$value['name_surname'].'</option>';

    }
}
elseif($islem=="binaidgetir"){
    $binano=$_POST['binano'];
    echo " <option selected disabled class='text-white bg-danger'>Kat seçiniz.</option>";
    $bolumgetir = "select * from hospital_floor where building_id=$binano and status='1' order by floor_name";
    $hello = verilericoklucek($bolumgetir);
    foreach ($hello as $value) {
        echo '<option value="'.$value['id'].'">'.$value['floor_name'].'</option>';

    }
}

elseif($islem=="katget"){
    $katid=$_POST['katid'];
    echo "<option selected disabled class='text-white bg-danger'>Oda seçiniz.</option>";
    $bolumgetir = "select * from hospital_room where floor_id=$katid and status='1' and availability!='1' order by room_name";
    $hello = verilericoklucek($bolumgetir);
    foreach ($hello as $value) {
        $odaturu=islemtanimgetirid($value["room_type"]);?>
        <option value="<?php echo $value['id']?>" <?php if ($odaturu=='izole oda'){ ?> style="background: darkred; color: aliceblue;" <?php } ?> ><?php echo $value['room_name']." "."(".$odaturu.")" ?></option>
    <?php   }
}

elseif($islem=="yatakget"){
    $yatakid=$_POST['yatakid'];
    echo " <option value=''>yatak seçiniz..</option>";
    $bolumgetir = "select * from hospital_bed where room_id=$yatakid and status='1'  order by bed_name";
    $hello = verilericoklucek($bolumgetir);
    foreach ($hello as $value) { ?>
        <option value="<?php echo $value['id'] ?>" <?php if ($value['full_status']==1){ echo "disabled"; }?> > <?php echo $value['bed_name'] ?>  <?php if ($value['full_status']==1){ echo " (dolu)"; }?> </option>';

    <?php  }
}

elseif($islem=="yatakgetbirim"){
    $odaid=$_GET['odaid'];
    echo " <option value=''>yatak seçiniz..</option>";
    $bolumgetir = "select * from hospital_bed where room_id=$odaid and status=1  order by bed_name";
    $hello = verilericoklucek($bolumgetir);
    foreach ($hello as $value) { ?>
        <option value="<?php echo $value['id'] ?>" <?php if ($value['full_status']==1){ echo "disabled"; }?> > <?php echo $value['bed_name'] ?>  <?php if ($value['full_status']==1){ echo " (dolu)"; }?> </option>';

    <?php  }


}

elseif($islem=="tetkikgetir"){
    echo "<option selected disabled class='text-white bg-danger'>Tetkik seçmek için önce test grubu seçiniz.</option>";
    $testgrupid=$_POST['testgrupid'];
    $tetkikgetir =  verilericoklucek("select * from lab_analysis where test_group=$testgrupid and status='1'");
    foreach ($tetkikgetir as $rowa) { ?>
        <option value="<?php echo $rowa["id"]; ?>" ><?php echo $rowa["analysis_name"]; ?></option>
    <?php } ?>
<?php }


elseif($islem=="cinsiyetgetir"){
    $yatakid=$_GET['yatakid'];

    $tckimlik=$_GET['tckimlik'];

    $yatis=tek("select * from yatis where oda_kodu=$yatakid and yatis_talep!='2'");
    $hastalar=singular("patients","tc_id",$yatis['tc_kimlik_no']);
    $hastalar2=singular("patients","tc_id",$tckimlik);
    $cinsiyet=$hastalar['sex'];
    $cinsiyet2=$hastalar2['sex'];
    if ($cinsiyet!=$cinsiyet2 && $cinsiyet=='e'){ ?>

        <div class="alert alert-danger" role="alert">
            seçtiğiniz odada erkek hasta var.
        </div>

    <?php }elseif ($cinsiyet!=$cinsiyet2 && $cinsiyet=='k'){ ?>
        <div class="alert alert-danger" role="alert">
            seçtiğiniz odada kadın hasta var.
        </div>
    <?php   }
}elseif($islem=="sevkturu"){
    $id=$_POST['sevkturuid'];
    $adi=islemtanimgetirid($id);

    if($adi=='iç sevk') { ?>
        <div class="col-lg-12 row">
            <div class="col-lg-6">
                <label class="col-md-6">poliklik</label>
                <select class="form-select" name="sevk_poliklinik" id="poliklinik1">

                    <?php
                    $bolumgetir = 'select * from units';
                    echo $bolumgetir;
                    $hello = verilericoklucek($bolumgetir);
                    foreach ($hello as $rowa) {
                        ?>
                        <option value="<?php echo $rowa["id"]; ?>" ><?php echo $rowa["depatrment_name"]; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-lg-6">
                <label class="col-md-6">doktor</label>
                <select class="form-select" name="sevk_doktor" id="doktor1">

                </select>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#poliklinik1").change(function () {
                    var poliklinikid = $(this).val();
                    $.ajax({
                        type: "post",
                        url: "ajax/poliklinikdoktorsec.php?islem=poliklinikdoktor",
                        data: {"poliklinikid": poliklinikid},
                        success: function (e) {
                            $("#doktor1").html(e);
                        }
                    })
                });

            });
        </script>
    <?php }
    elseif ($adi=='diş sevk'){?>

        <div class="mb-3">
            <label for="basicpill-firstname-input" class="form-label">sevk edilen hastane</label>
            <input type="text" class="form-control" name="sevk_hastane" >
        </div>

    <?php }


}
elseif ($islem=="yatakturugetir"){
    $yatak=$_POST['yatak'];
    $yatakbilgi=singular("hospital_bed","id",$yatak);
    ?>

    <input class="form-control" type="hidden" name="yatisyap[yatak_turu]"
           value="<?php echo $yatakbilgi['bed_type'] ?>" >

<?php }
elseif($islem=="birimdiyet"){
    $birimno=$_POST['birimno']; ?>

    <?php
    $uyrukgetir = "select * from yatis inner join patient_registration on yatis.protokol_no=patient_registration.id where patient_registration.outpatient_id=$birimno";
    $hello = verilericoklucek($uyrukgetir);
    foreach ($hello as $rowa) {
        $hasta=singular("patients", "tc_id",$rowa["tc_kimlik_no"]);
        ?>
        <option
            value="<?php echo $rowa["protokol_no"]; ?>" ><?php echo $hasta["patients_name"]." ".$hasta["patients_surname"]; ?></option>
    <?php } ?>

<?php }

elseif($islem=="refakatgetir"){
    $refid=$_POST['refid']; ?>

    <?php
    $uyrukgetir = "select * from yatis inner join patient_companion on yatis.id=patient_companion.patient_protocol_number where yatis.protokol_no=$refid";
    $hello = verilericoklucek($uyrukgetir);
    foreach ($hello as $rowa) {

        ?>
        <option
            value="<?php echo $rowa["companion_tc"]; ?>" ><?php echo $rowa["companion_name_surname"]; ?></option>
    <?php }


}

elseif($islem=="birimhastadiyet"){
    $birimno=$_POST['birimno']; ?>

    <?php
    $uyrukgetir = "select * from patients inner join patient_registration on patients.tc_id=patient_registration.tc_id where patient_registration.outpatient_id=$birimno";
    $hello = verilericoklucek($uyrukgetir);
    foreach ($hello as $rowa) {
        ?>
        <option
            value="<?php echo $rowa["id"]; ?>" ><?php echo $rowa["patients_name"]." ".$rowa["patients_surname"]; ?></option>
    <?php } ?>

<?php }



elseif($islem=="testgetir"){
    $testgrupid=$_POST['testgrupid']; ?>

    <?php
    $uyrukgetir = "select * from lab_tests  where test_type=$testgrupid";
    $hello = verilericoklucek($uyrukgetir);
    foreach ($hello as $rowa) {
        ?>
        <option
            value="<?php echo $rowa["id"]; ?>" ><?php echo $rowa["test_name"]; ?></option>
    <?php } ?>

<?php }

elseif($islem=="gruptestgetir"){ ?>

    <table class="table  table-bordered table-sm table-hover mt-5" style=" background:white;width: 100%;" id="hbystahlilistem">

        <thead>
        <tr>
            <th></th>
            <th>hbys no</th>
            <th>bileşik adı</th>
        </tr>
        </thead>
        <tbody>

        <?php
        $no=$_POST['testgrupid'];
        $uyrukgetir = "select * from lab_analysis  where  status!='2' and test_group='$no'";
        $hello = verilericoklucek($uyrukgetir);
        foreach ($hello as $rowa) {

            ?>

            <tr  id="<?php echo $rowa["id"] ?>">
                <td><?php echo $rowa["id"] ?></td>
                <td><?php echo $rowa["hbys_no"] ?></td>
                <td><?php echo $rowa["analysis_name"] ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>


    <script type="text/javascript">

        $(document).ready(function () {





            var table = $('#hbystahlilistem').DataTable();

            $('#hbystahlilistem tbody').on('click', 'tr', function () {
                $(this).toggleclass('selected');
            });

            $('#hbystahlilistem tbody').click(function () {
                var totalcount=table.rows('.selected').data();
                var  say=table.rows('.selected').data().length;
                var idal=[];
                var gelenidal=[];


                for (let i = 0; i < say; i++){
                    var idgel=totalcount[i];

                    idal.push(idgel[1]);

                    let text = idal;

                    var myarray = text.tostring().split(",");

                    gelenidal.push(myarray[i]);

                    console.log(gelenidal);
                }

                $("#yazi").val(gelenidal);

            });

            $('.hbysbtn').click(function () {



                var getir =$("#yazi").val();
                $.get( "ajax/laboratuvarbilesikislem.php?islem=hbysistemtahlilekle", { getir:getir },function(getveri){
                    $('#sonucyaz').html(getveri);

                    $.get( "ajax/laboratuvarbilesikislem.php?islem=hbystahlilistemlistesi", { },function(e){
                        $('.hbysliste').html(e);

                    });

                });

            });

            $(document).off().on('click', '.btnremove', function() {
                // var degeral=$(this).data('id');
                $(this).remove();
                // document.getelementbyid("bileiktab1").remove();

            });


        });
    </script>

<?php }


?>




