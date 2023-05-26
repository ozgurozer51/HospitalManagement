
<?php
include "../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();

date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');

$islem=$_GET['islem'];
//var_dump($_GET);
if($islem=="poliklinikdoktor"){
    $poliklinikid=$_POST['poliklinikid'];

    $bolumgetir = verilericoklucek("select * from users where department='.$poliklinikid'");
       foreach ($bolumgetir as  $value){
        echo '<option value="'.$value['id'].'">'.$value['name_surname'].'</option>';

    }
}

elseif ($islem=="ililce"){
    if($_POST['ilid']!="") {
        $ilid = $_POST['ilid'];
    }else {
        $ilid = $_GET['ilid'];
    }

    $uyrukgetir = verilericoklucek("select * from district where province_number='$ilid'");
    foreach ($uyrukgetir as $rowa){ ?>
        <option <?php if($_GET['secili']==$rowa["id"]){ echo "selected"; } ?> value="<?php echo $rowa["id"]; ?>" ><?php echo $rowa["district_name"]; ?></option>
    <?php }
}
elseif ($islem=="uvangetir"){
    if($_POST['uvanid']!="") {
        $uvanid = $_POST['uvanid'];
    }else {
        $uvanid = $_GET['uvanid'];
    }

    $uyrukgetir = verilericoklucek("select * from users where title='$uvanid'");
    foreach ($uyrukgetir as $rowa){ ?>
        <option  value="<?php echo $rowa["id"]; ?>" ><?php echo $rowa["name_surname"]; ?></option>
    <?php }
}
elseif ($islem=="binakat"){
    if($_POST['binaid']!="") {
        $binaid = $_POST['binaid'];
    }else {
        $binaid = $_GET['binaid'];
    }

    $uyrukgetir = verilericoklucek("select * from transaction_definitions where definition_supplement='$binaid'");
    foreach ($uyrukgetir as $rowa){ ?>
        <option value="<?php echo $rowa["id"]; ?>" ><?php echo $rowa["definition_name"]; ?></option>
    <?php }
}

elseif ($islem=="kasaduzenle"){
    if($_POST['kasaid']!="") {
        $kasaid = $_POST['kasaid'];
    }else {
        $kasaid = $_GET['kasaid'];
    }

    $kasagetir=singular("safe","id","$kasaid"); ?>

    <input type="hidden" name="id"  value="<?php echo $kasaid ?>"/>

    <div class="mb-3">
        <label for="basicpill-firstname-input" class="form-label">kasa adı</label>
        <input type="text" class="form-control"  name="safe_name" value="<?php echo $kasagetir['safe_name'] ?>" id="basicpill-firstname-input">
    </div>

    <div class="mb-3">
        <label for="basicpill-firstname-input" class="form-label">kasa kodu</label>
        <input type="text" class="form-control"  name="kasakodu" value="<?php echo $kasagetir['safe_id'] ?>" id="basicpill-firstname-input">
    </div>

    <div class="mb-3 row">
        <label for="basicpill-firstname-input" class="form-label">kasa durum</label>
        <div class="col-lg-12">

            <select class="form-select "  name="status" >
                <?php $sql = verilericoklucek("select * from transaction_definitions where definition_type='DURUM'");
                foreach ($sql as $item){ ?>
                    <option  <?php if ($kasagetir['status'] == $item['definition_supplement'] ) {
                        echo "selected";
                    } ?> value="<?php echo $item['definition_supplement']; ?>"  ><?php echo $item['definition_name']; ?></option>
                <?php } ?>
            </select>
        </div>

    </div>
    <div class="mb-3 row">
        <label for="basicpill-firstname-input" class="form-label">bölüm</label>
        <div class="col-lg-12">

            <select class="form-select col-lg-6" name="unit">
                <option value="" ></option>
                <?php $sql = verilericoklucek("select * from units where status='1'");
                foreach ($sql as $item){ ?>
                    <option <?php if ($kasagetir['unir'] == $item['id'] ) {
                        echo "selected";
                    } ?> value="<?php echo $item['id']; ?>" ><?php echo $item['department_name']; ?></option>
                <?php }?>
            </select>

        </div>
    </div>
    <div class="mb-3 row">
        <label>bina</label>
        <div class="col-lg-12">

            <select class="form-select col-lg-6"  name="bina" id="bina" >
                <?php $sql = verilericoklucek("select * from transaction_definitions where definition_type='BINA'");
                   foreach ($sql as $item){ ?>
                    <option <?php if ($kasagetir['building'] == $item['id'] ) {echo "selected";  } ?> value="<?php echo $item['id']; ?>" ><?php echo $item['department_name']; ?></option>
                <?php }?>
            </select>

        </div>
    </div>
    <div class="mb-3 row">
        <div class="col-lg-12">
            <label>kat</label>

            <select class="form-select" name="floor" id="kat">
                <?php $sql = verilericoklucek("select * from transaction_definitions where definition_type='KAT'");
                foreach ($sql as $item){ ?>
                    <option <?php if ($kasagetir['floor'] ==$item['id'] ) { echo "selected"; } ?> value="<?php echo $item['id']; ?>" ><?php echo $item['tanim_adi']; ?></option>
                <?php }?>
             </select>

        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#bina").change(function () {
                var binaid =$(this).val();
                $.ajax({
                    type: "post",
                    url: "ajax/vezne/araislemler.php?islem=binakat",
                    data: {"binaid": binaid},
                    success: function (e) {
                        $("#kat").html(e);
                    }
                })
            })

        });
    </script>
<?php }

elseif ($islem=="kasadetayl"){
    if($_POST['kdetayid']!="") {
        $kdetayid = $_POST['kdetayid'];
    }else {
        $kdetayid = $_GET['kdetayid'];
    }  ?>

    <table  id="exampletab" class="table  table-bordered table-sm table-hover " style=" background:white;width: 100%;">
        <thead>
        <tr>
            <th scope="col">giren</th>
            <th scope="col">çıkan</th>
            <th scope="col">kasa</th>
            <th scope="col">ekleyen kullanici</th>
            <th scope="col">ekleme tarihi</th>
            <th></th>
        </tr>
        </thead>
        <tbody id="kasadetaylist1">

        <?php  $hastalistgetir = verilericoklucek("select * from safe_detail where status='1' and safe_id='$kdetayid'");
         foreach ($hastalistgetir as $rowa){ ?>

            <tr>
                <td ><?php echo $rowa["giren"] ?></td>
                <td><?php echo $rowa["cikan"] ?></td>
                <td><?php $kasagetir=singular("kasa","id",$rowa["kasa_id"]); echo $kasagetir["kasaadi"] ?> </td>
                <td class="w-25"><?php echo $rowa["insert_userid"] ?></td>
                <td class="w-25"><?php echo $rowa["insert_datetime"] ?></td>
                <td class="text-center"><button type="button"  data-toggle="modal"  data-target="#kasadetaysil" data-id="<?php echo $rowa["id"] ?>" class="btn btn-danger btn-sm mb-2 btnkasadetaysilme" ><i class="mdi mdi-trash-can px-1" ></i>sil</button></td>
            </tr>
            <?php }  ?>

        </tbody>
    </table>



<?php }


elseif ($islem=="avansupdate"){
    if($_POST['avansid']!="") {
        echo   $avansid = $_POST['avansid'];
    }else {
        echo  $avansid = $_GET['avansid'];
    }

    $avansgetir=singular("personnel_advance","id","$avansid");
    ?>

    <input type="hidden" name="id"  value="<?php echo $avansid ?>"/>
    <div class="mb-3 row">
        <label for="basicpill-firstname-input" class="form-label">personel</label>
        <div class="col-lg-12">

            <select class="form-select "  name="personel_id" >
                <?php $sql = verilericokucek("select * from users ");
                foreach ($sql as $item){ ?>
                    <option  <?php if ($avansgetir['personel_id']==$item['id'] ) {echo "selected"; } ?> value="<?php echo $item['id']; ?>"  ><?php echo $item['name_surname']; ?></option>
                <?php } ?>
            </select>

        </div>

    </div>
    <div class="mb-3 row">
        <label for="basicpill-firstname-input" class="form-label">kasa</label>
        <div class="col-lg-12">

            <select class="form-select "  name="kasa_id" >
                <?php

                $sql = verilericoklucek("select * from kasa where status=1 ");
                foreach ($sql as $item){ ?>
                    <option  <?php if ($avansgetir['kasa_id'] == $item['id'] ) { echo "selected"; } ?> value="<?php echo $item['id']; ?>"  ><?php echo $item['kasaadi']; ?></option>
                <?php }?>
            </select>

        </div>
    </div>

    <div class="mb-3">
        <label for="basicpill-firstname-input" class="form-label">kasa kodu</label>
        <input type="text" class="form-control"  name="avans_tutari" value="<?php echo $avansgetir['avans_tutari'] ?>" id="basicpill-firstname-input">
    </div>

<?php }


elseif ($islem=="avansin"){
    if($_POST) {

        $_POST["insert_datetime"] = $simdikitarih;
        $_POST["insert_userid"] = 1;
        $_POST["status"] = 1;

        $kasekle = direktekle("personnel_advance", $_POST, "avansekle");
        if ($kasekle == 1) {
            echo "veri eklendi";
        } else {
            echo "veri eklenmedi..";
        }

    }
}

elseif ($islem=="avupdate"){
    if($_POST) {
        unset($_POST["avansedit"]);
        $id=$_POST["id"];
        unset($_POST["id"]);
        $_POST["update_datetime"]=$simdikitarih;
        $_POST["update_userid"]=1;
        $kasaguncelle=direktguncelle("personnel_advance","id",$id,$_POST,"avansedit","id");

        if ($kasaguncelle==1){
            echo "güncellendi";
        }else{
            echo "hata olustu.";
        }

    }
}

elseif ($islem=="izinek"){
    if($_POST) {

        $_POST["insert_datetime"] = $simdikitarih;
        $_POST["insert_userid"] = 1;
        $_POST["status"] = 1;
        //var_dump($_POST);
        $kasekle = direktekle("personnel_permissions",$_POST);
        if ($kasekle == 1) {
            echo "veri eklendi";
        } else {
            echo "veri eklenmedi..";
        }
    }
}

elseif ($islem=="izinupdate"){
    if($_POST['izinid']!="") {
        echo   $izinid = $_POST['izinid'];
    }else {
        echo  $izinid = $_GET['izinid'];
    }

    $izingetir=singular("personnel_permissions","id","$izinid");  ?>

    <input type="hidden" name="id"  value="<?php echo $izinid ?>"/>
    <div class="mb-1 mx-1">
        <label for="basicpill-firstname-input" class="form-label">izin adı</label>
        <input type="text" class="form-control" value="<?php echo $izingetir['izin_adi'] ?>"  name="izin_adi" id="basicpill-firstname-input">
    </div>
    <div class="mb-1 mx-1">
        <label for="basicpill-firstname-input" class="form-label">izin alt adı</label>
        <input type="text" class="form-control" value="<?php echo $izingetir['izin_alt_adi'] ?>" name="izin_alt_adi" id="basicpill-firstname-input">
    </div>

    <div class="mb-1 mx-1">
        <label class="form-label">çalışma süresi</label>

        <select class="form-select" name="calisma_suresi">
            <?php  $uyrukgetir = verilericokucek("select * from transaction_definitions where definition_type='is_durumu'");
            foreach ($uyrukgetir as $rowa){ ?>
                <option  <?php if ($izingetir['is_durumu']==$rowa['id'] ) { echo "selected"; } ?> value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["tanim_adi"]; ?></option>
            <?php } ?>
        </select>

    </div>
    <div class="mb-1 mx-1">
        <label for="basicpill-firstname-input" class="form-label">çalışma yıl</label>
        <input type="number" class="form-control" value="<?php echo $izingetir['calisma_yil'] ?>" name="calisma_yil" id="basicpill-firstname-input">
    </div>
    <div class="mb-1 mx-1">
        <label for="basicpill-firstname-input" class="form-label">izin ay</label>
        <input type="number" class="form-control" value="<?php echo $izingetir['izinli_ay'] ?>" name="izinli_ay" id="basicpill-firstname-input">
    </div>
    <div class="mb-1 mx-1">
        <label for="basicpill-firstname-input" class="form-label">izinli hafta</label>
        <input type="number" class="form-control" value="<?php echo $izingetir['izinli_hafta'] ?>" name="izinli_hafta" id="basicpill-firstname-input">
    </div>
    <div class="mb-1 mx-1">
        <label for="basicpill-firstname-input" class="form-label">izinli gün</label>
        <input type="number" class="form-control" value="<?php echo $izingetir['izinli_gun'] ?>" name="izinli_gun" id="basicpill-firstname-input">
    </div>
    <div class="mb-1 mx-1">
        <label for="basicpill-firstname-input" class="form-label">izinli saati</label>
        <input type="number" class="form-control" value="<?php echo $izingetir['izinli_saat'] ?>" name="izinli_saat" id="basicpill-firstname-input">
    </div>
<?php }


elseif ($islem=="izupdate"){
    if($_POST) {
        $id=$_POST["id"];
        unset($_POST["id"]);
        $_POST["update_datetime"]=$simdikitarih;
        $_POST["update_userid"]=1;
        $kasaguncelle=direktguncelle("personnel_permissions","id",$id,$_POST,"id");

        if ($kasaguncelle==1){
            echo "güncellendi";
        }else{
            echo "hata olustu.";
        }

    }
}
elseif ($islem=="kasaek"){
    if($_POST) {

        $_POST["insert_datetime"] = $simdikitarih;
        $_POST["insert_userid"] = 1;
        $_POST["status"] = 1;
        //var_dump($_POST);
        $kasekle = direktekle("kasa",$_POST);
        if ($kasekle == 1) {
            echo "veri eklendi";
        } else {
            echo "veri eklenmedi..";
        }

    }
}

elseif ($islem=="kasaupdate"){
    if($_POST) {
        $id=$_POST["id"];
        unset($_POST["id"]);
        $_POST["update_datetime"]=$simdikitarih;
        $_POST["update_userid"]=1;
        $kasaguncelle=direktguncelle("safe","id",$id,$_POST,"id");
        if ($kasaguncelle==1){
            echo "güncellendi";
        }else{
            echo "hata olustu.";
        }

    }
}

elseif($islem=="birimtipi"){
    $birimtipiid=$_POST['birimtipiid'];

    $bolumgetir = verilericoklucek('select * from units where birim_tipi='.$birimtipiid .'order by depatrment_name');
    foreach ($bolumgetir as $value){
        echo '<option value="'.$value['id'].'">'.$value['depatrment_name'].'</option>';
    }
}
elseif($islem=="birimgetir"){
    $birimid=$_POST['birimid'];

    $bolumgetir = verilericoklucek('select * from users where department='.$birimid .'order by name_surname');
    foreach ($bolumgetir as $value){
       echo '<option value="'.$value['id'].'">'.$value['name_surname'].'</option>';

    }
}

elseif($islem=="binaidgetir"){
    $binano=$_POST['binano'];

    $bolumgetir = verilericoklucek('select * from hospital_building where building_id='.$binano);

    foreach ($bolumgetir as $value){
        echo '<option value="'.$value['id'].'">'.$value['bina_kat'].'</option>';
    }

}


elseif($islem=="katget"){
    $katid=$_POST['katid'];

    $bolumgetir = verilericoklucek('select * from hospital_building where bina_kat_id='.$katid);
          foreach ($bolumgetir as $value){
        echo '<option value="'.$value['id'].'">'.$value['bina_oda'].'</option>';
    }
}


elseif ($islem=="varin"){
    if($_POST) {

        $_POST["insert_datetime"] = $simdikitarih;
        $_POST["insert_userid"] = 1;
        //var_dump($_POST);
        $kasekle = direktekle("personel_vardiya",$_POST);
        if ($kasekle == 1) {
            echo "veri eklendi";
        } else {
            echo "veri eklenmedi..";
        }

    }
}
elseif ($islem=="vardiyadetay"){
    if($_POST['vardiyaid']!="") {
        echo  $vardiyaid = $_POST['vardiyaid'];
    }else {
        echo  $vardiyaid = $_GET['vardiyaid'];
    }

    $vargetir=singular("personel_vardiya","id","$vardiyaid");

    ?>


    <div class="row">

        <div class="col-12 row">
            <div class="col-4">
                <div class="mb-2">
                    <input type="hidden" name="id"  value="<?php echo $vardiyaid ?>"/>
                    <label class="form-label">birim türü</label>
                    <select class="form-select shadow-none" name="birim_tipi"
                            id="birimtipi">
                        <option  selected> --select-- </option>
                        <?php
                        $uyrukgetir = verilericoklucek("select * from transaction_definition where definition_type='birim_tipi'");
                        foreach ($uyrukgetir as $rowa){

                            ?>
                            <option <?php if ($vargetir['birim_tipi'] == $rowa["tanim_eki"]) {
                                echo "selected";
                            } ?>
                                value="<?php echo $rowa["tanim_eki"]; ?>"><?php echo $rowa["tanim_adi"]; ?></option>
                        <?php } ?>
                    </select>
                    <div class="invalid-feedback">please select a valid event category</div>
                </div>
            </div>

            <div class="col-4">
                <div class="mb-2">
                    <label class="form-label">birim </label>
                    <select class="form-select shadow-none"
                            name="birim_id" id="birim" >
                        <?php

                        $uyrukgetir = "select * from units order by depatrment_name";
                        foreach($uyrukgetir as $rowa){ ?>

                            <option <?php if ($vargetir['birim_id'] == $rowa["id"]) {
                                echo "selected";
                            } ?>
                                value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["depatrment_name"]; ?></option>
                        <?php } ?>
                    </select>
                    <div class="invalid-feedback">please select a valid event category</div>
                </div>
            </div>

            <div class="col-4">
                <div class="mb-2">
                    <label class="form-label">personel</label>
                    <select class="form-select shadow-none"  name="kullanici_id" id="personel">

                        <?php $uyrukgetir = verilericoklucek("select * from users where department='{$vargetir['birim_id']}'");
                        foreach ($uyrukgetir as $rowa){?>
                            <option <?php if ($vargetir['kullanici_id'] == $rowa["id"]) {
                                echo "selected";
                            } ?>
                                value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["name_surname"]; ?></option>
                        <?php } ?>

                    </select>
                    <div class="invalid-feedback">please select a valid event category</div>
                </div>
            </div>

            <script type="text/javascript">
                $(document).ready(function () {
                    $("#birimtipi").change(function () {

                        var birimtipiid = $(this).val();
                        $.ajax({
                            type: "post",
                            url: "ajax/vezne/araislemler.php?islem=birimtipi",
                            data: {"birimtipiid": birimtipiid},
                            success: function (e) {
                                $("#birim").html(e);
                            }
                        })
                    })

                    $("#birim").change(function () {
                        var birimid = $(this).val();
                        $.ajax({
                            type: "post",
                            url: "ajax/vezne/araislemler.php?islem=birimgetir",
                            data: {"birimid": birimid},
                            success: function (e) {
                                $("#personel").html(e);
                            }
                        })
                    })

                });
            </script>
        </div>


        <div class="col-12 row">
            <div class="col-4">
                <div class="mb-2">
                    <label class="form-label">bina</label>
                    <select class="form-select shadow-none" name="bina_id" id="bina">
                        <option  selected> --select-- </option>

                        <?php $uyrukgetir = "select * from hasta_bina where bina_adi!=' '";
                         foreach ($uyrukgetir as $rowa){ ?>
                            <option <?php if ($vargetir['bina_id'] == $rowa["id"]) {
                                echo "selected";
                            } ?>
                                value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["bina_adi"]; ?></option>
                        <?php } ?>
                    </select>
                    <div class="invalid-feedback">please select a valid event category</div>
                </div>
            </div>

            <div class="col-4">
                <div class="mb-2">
                    <label class="form-label">kat</label>
                    <select class="form-select shadow-none"
                            name="kat_id" id="kat" >
                        <option  selected> --select-- </option>

                        <?php $uyrukgetir = verilericoklucek("select * from hasta_bina");
                        foreach ($uyrukgetir as $rowa){ ?>
                            <option <?php if ($vargetir['kat_id'] == $rowa["id"]) {
                                echo "selected";
                            } ?>
                                value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["bina_kat"]; ?></option>
                        <?php } ?>
                    </select>
                    <div class="invalid-feedback">please select a valid event category</div>
                </div>
            </div>

            <div class="col-4">
                <div class="mb-2">
                    <label class="form-label">oda</label>
                    <select class="form-select shadow-none"  name="oda_id" id="oda">
                        <option  selected disabled> --select-- </option>

                        <?php $uyrukgetir = "select * from hasta_bina where kat_id='{$vargetir['kat_id']}'";
                      foreach ($uyrukgetir as $rowa){ ?>
                            <option <?php if ($vargetir['oda_id'] == $rowa["id"]) {
                                echo "selected";
                            } ?>
                                value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["bina_oda"]; ?></option>
                        <?php } ?>
                    </select>
                    <div class="invalid-feedback">please select a valid event category</div>
                </div>
            </div>

            <script type="text/javascript">
                $(document).ready(function () {
                    $("#bina").change(function () {
                        var binano = $(this).val();
                        $.ajax({
                            type: "post",
                            url: "ajax/vezne/araislemler.php?islem=binaidgetir",
                            data: {"binano": binano},
                            success: function (e) {
                                $("#kat").html(e);
                            }
                        })
                    })

                    $("#kat").change(function () {
                        var katid = $(this).val();
                        $.ajax({
                            type: "post",
                            url: "ajax/vezne/araislemler.php?islem=katget",
                            data: {"katid": katid},
                            success: function (e) {
                                $("#oda").html(e);
                            }
                        })
                    })

                });
            </script>
        </div>

        <div class="col-12 row">
            <div class="col-4">
                <div class="mb-3">
                    <label class="form-label">vardiya tarihi</label>
                    <input class="form-control" placeholder="insert event name"
                           type="date" name="vardiya_tarihi"  required value="<?php echo $vargetir['vardiya_tarihi'] ?>" />
                    <div class="invalid-feedback">please provide a valid event name</div>
                </div>
            </div>

            <div class="col-4">
                <div class="mb-3">
                    <label class="form-label">vardiya başlama saati</label>
                    <input class="form-control" placeholder="insert event name"
                           type="time" name="vardiya_baslangic_saati"  required value="<?php echo $vargetir['vardiya_baslangic_saati'] ?>" />
                    <div class="invalid-feedback">please provide a valid event name</div>
                </div>
            </div>

            <div class="col-4">
                <div class="mb-3">
                    <label class="form-label">vardiya bitiş saati</label>
                    <input class="form-control" placeholder="insert event name"
                           type="time" name="vardiya_bitis_saati"  required value="<?php echo $vargetir['vardiya_bitis_saati'] ?>" />
                    <div class="invalid-feedback">please provide a valid event name</div>
                </div>
            </div>

            <script type="text/javascript">
                $(document).ready(function () {
                    $("#bina").change(function () {
                        var binano = $(this).val();
                        $.ajax({
                            type: "post",
                            url: "ajax/vezne/araislemler.php?islem=binaidgetir",
                            data: {"binano": binano},
                            success: function (e) {
                                $("#kat").html(e);
                            }
                        })
                    })

                    $("#kat").change(function () {
                        var katid = $(this).val();
                        $.ajax({
                            type: "post",
                            url: "ajax/vezne/araislemler.php?islem=katget",
                            data: {"katid": katid},
                            success: function (e) {
                                $("#oda").html(e);
                            }
                        })
                    })

                });
            </script>
        </div>

    </div>

<?php }



elseif ($islem=="updatevardiya"){
    if($_POST) {
        $id=$_POST["id"];
        unset($_POST["id"]);
        $_POST["update_datetime"]=$simdikitarih;
        $_POST["update_userid"]=1;
        $kasaguncelle=direktguncelle("personel_vardiya","id",$id,$_POST,"id");

        if ($kasaguncelle==1){
            echo "güncellendi";
        }else{
            echo "hata olustu.";
        }

    }
}

elseif($islem=="dovuztipi"){
    $dovuzekkod=$_POST['dovuzekkod'];
    $dovuz=singular("sistem_ayarlari","id","1");
    $dolarfiyat=dolar_fiyat;
    $eurofiyat=euro_fiyat;

    if ($dovuzekkod=='0'){ ?>
        <input class="form-control" type="text" disabled value="<?php echo $dolarfiyat.' $' ?>">
        <input class="form-control" type="hidden" value="<?php echo $dolarfiyat ?>" name="currency_rate" id="dovuz_kuru">
        <?php } elseif($dovuzekkod=='1') { ?>
        <input class="form-control" type="text" disabled value="<?php echo $eurofiyat.' €' ?>">
        <input class="form-control" type="hidden"  value="<?php echo $eurofiyat ?>" name="currency_rate" id="dovuz_kuru">
    <?php } else { ?>
        <input class="form-control" type="text" disabled value="<?php echo 0 .' ₺' ?>">
        <input class="form-control" type="hidden"  value="<?php echo 0 ?>" name="currency_rate" id="dovuz_kuru">
    <?php }

}


elseif ($islem=="vezneekle"){

    $patient_id = $_POST['patient_id'];
    $receipt_number = $_POST['receipt_number'];
    $treasurer_id = $_POST['treasurer_id'];
    $receipt_datetime = $_POST['receipt_datetime'];
    $explanation = $_POST['explanation'];
    $currencey_rate = $_POST['currencey_rate'];
    $patient_service_id = $_POST['patient_serviceid'];
    $currency_type = $_POST['currency_type'];
    $request_type = $_POST['request_type'];
    $teller_entry_exit_information = $_POST['teller_entry_exit_information'];
    $process_type = $_POST['process_type'];
    $receipt_type = $_POST['receipt_type'];
    $receipt_amount = $_POST['receipt_amount'];
    $request_id = $_POST['requestid'];
    $safe_id= $_POST['safe_id'];

    if($_POST and $_POST["islem_turu"]==1) {     // Tam Komplee Ödeme Bölümü

        unset($_POST["islem_turu"]);

        $_POST["teller_entry_exit_information"] = '1';
        $_POST["requestid"] = $_POST["istemidgetir"];
        $_POST["process_type"] = 'giren';
        unset($_POST["tutari"]);
        unset($_POST["id"]);
        unset($_POST["istemidgetir"]);
        $islem_tipi='giren';
        $_POST['receipt_type']=$_GET['odemeturu'];
        unset($_GET['odemeturu']); 
        $vezneekle = direktekle("pay_office",$_POST);
        if ($vezneekle == 1) {
            $id=$_POST["hasta_id"];
            $vezneguncelle=guncelle("update patient_prompts set payment_completed='1' where patient_id='$id'");

            if ($vezneguncelle==1){
                $makbuztutar=$_POST["receipt_amount"];
                $kasaid=$_POST["safe_id"];
                $patient_id=$_POST['hasta_id'];
                $sql =guncelle("insert into safe_detail (amount , safe_id ,  process_type , protocol_id) values ('$makbuztutar' , '$kasaid' ,'$islem_tipi','$protokolid')");
                //$kasadetayekle =direktekle("safe_detail",$_POST); //önceki postlar hata veriyor.
                //var_dump($kasadetayekle);
                if ($sql==1){ ?>
                    <script>
                        alertify.success('İşlem Başarılı');
                    </script>

                    <?php }else{ ?>

                    <script>
                        alertify.danger('Kasa Ekleme Hatası');
                    </script>

                <?php }

            } else { ?>

                <script>
                    alertify.danger('Hasta İstem Hatası');
                </script>

                <?php }

        } else { ?>
            <script>
                alertify.danger('işlem Başarısız veri Eklenemedi!!!');
            </script>

       <?php }

//**********************************************************************************************************************
//**********************************************************************************************************************


    } else {


            unset($_POST["process_type"]);
            unset($_POST['id']);
            unset($_POST['safe_id']);



            $makbuztutar=$_POST["receipt_amount"];
            $islem_tipi="giren";
            unset($_POST["istemidgetir"]);
            $_POST['receipt_type']=$_GET['odemeturu'];
            unset($_GET['odemeturu']);
            $aratutar=$_POST["tutari"];

            $idistem=$_POST["patient_serviceid"];
            unset($_POST["receipt_amount"]);
            unset($_POST["tutari"]);
            unset($_POST["id"]);
            $_POST["receipt_amount"]=$aratutar;
            $_POST["requestid"]=$idistem;


            unset($_POST);

            $_POST['patient_id'] = $patient_id;
            $_POST['receipt_number'] = $receipt_number;
            $_POST['receipt_number'] = $receipt_number;
            $_POST["teller_entry_exit_information"] = '1';
            $_POST["process_type"] = 'giren';
            $_POST["receipt_datetime"] = $receipt_datetime;
            $_POST["request_id"] = $request_id;
            $_POST["currency_type"] = $currency_type;
            $_POST["currencey_rate"] = $currencey_rate;

        $sql = direktekle("pay_office",$_POST);

            if ($sql == 1) {

                if ($makbuztutar-$aratutar==0) {

                    $sql = guncelle("update patient_prompts set payment_completed='1' where protocol_number='$patient_id'");

                    if ($sql==1){

                        $sql =guncelle("insert into safe_detail (price, safe_id ,process_type,patient_id) values ('$aratutar', '$safe_id','giren','$patient_id')");

                        if ($sql==1){ ?>
s
                            <script>
                                alertify.success('İşlem Başarılı');
                            </script>

                            <?php } else { ?>

                            <script>
                                alertify.error('Kasa Ekleme Hatası');
                            </script>

                            <?php } } else{ ?>

                        <script>
                            alertify.error('Hasta İstem Hatası');
                        </script>

                        <?php } } else {

                    $idizi=explode(",",$request_id);

                    foreach($idizi as $item){
                        $sql = guncelle("update patient_prompts set payment_completed='1' where id='$item'");
                    }

                    if ($sql==1){
                        $kasaid=$_POST["safe_id"];
                        $protokolid=$_POST['patient_id'];
                        unset($_POST["receipt_amount"]);

                        $sql = guncelle("insert into safe_detail (price, safe_id,process_type,protocol_id) values ('$aratutar', '$kasaid','$islem_tipi','$protokolid')");

                        if ($sql==1){ ?>
                            <script>
                                alertify.success('İşlem Başarılı');
                            </script>

                            <?php }else{ ?>

                            <script>
                                alertify.error('Kasa Ekleme Hatası');
                            </script>

                            <?php } }else{ ?>

                        <script>
                            alertify.error('Hasta İstem Hatası');
                        </script>

                        <?php } } } else {  ?>

                <script>
                    alertify.error('Veri Eklenemedi');
                </script>

           <?php }  } }



elseif ($islem=="makbuzbilgi"){
    if($_POST) {
        $no=$_POST["makid"];

        $veznebil=singular("patient_prompts","id","$no");

        //var_dump($veznebil);

        ?>
        <form action="javascript:void(0);" id="formvezneiptal">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <input class="form-control px-2" type="hidden" name="id" value="<?php echo $no ?>" id="istemid">
                        <input class="form-control px-2" type="hidden" name="patient_id" value="<?php echo $veznebil['protocol_number']; ?>" id="hastaid">
                        <input class="form-control px-2" type="hidden" name="receipt_amount" value="<?php echo $veznebil['fee']; ?>" >
                    </div>


                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-2 row">
                                <label for="example-text-input" class="col-md-5 col-form-label">makbuz
                                    no</label>
                                <div class="col-md-7" id="makbuznumarasi">

                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3 row">
                                <label for="example-text-input" class="col-md-5 col-form-label">kasa</label>
                                <div class="col-md-7">

                                    <select class="form-select" name="safe_id" required>
                                        <option class="text-white bg-danger" selected disabled>kasa seç</option>
                                        <?php $uyrukgetir = verilericoklucek("select * from safe where status='1'");
                                         foreach ($uyrukgetir as $rowa){ ?>
                                        <option value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["safe_name"]; ?></option>
                                        <?php } ?>
                                    </select>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3 row">
                                <label for="example-text-input" class="col-md-5 col-form-label">vezne
                                    birim</label>
                                <div class="col-md-7">

                                    <select class="form-select" name="vezne_birim_kodu" id="birim7">
                                        <option selected>birim seç</option>
                                        <?php $uyrukgetir = verilericoklucek("select * from units");
                                           foreach ($uyrukgetir as $rowa){ ?>
                                            <option value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["depatrment_name"]; ?></option>
                                        <?php } ?>
                                    </select>

                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-2 row">
                                <label for="example-text-input" class="col-md-5 col-form-label">veznedar</label>
                                <div class="col-md-7">
                                    <select class="form-select" name="veznedar" id="personel7">

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-2 row">
                                <label for="example-text-input" class="col-md-5 col-form-label">neden iptal edildiğini açıklar mısınız ?
                                </label>
                                <div class="col-md-12" >
                                    <textarea class="form-control" type="text"   name="iptal_aciklama" rows="4" cols="50"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        $(document).ready(function () {
                            $("#birim7").change(function () {
                                var birimid = $(this).val();

                                $.ajax({
                                    type: "post",
                                    url: "ajax/vezne/araislemler.php?islem=birimgetir",
                                    data: {"birimid": birimid},
                                    success: function (e) {
                                        $("#personel7").html(e);
                                    }
                                })
                            })

                        })
                    </script>

                    <div class="col-md-12" align="right">

                        <button type="button" class="btn btn-success btn-sm " data-id="3" data-dismiss="modal" id="vezneiptal">
                            <i class="fas fa-book px-1"></i>iptal et
                        </button>

                    </div>


                </div>
            </div>
        </form>
        <script>
            $(document).ready(function () {
                $('.makbuzclick').on('click', function () {
                    var deg=document.getelementbyid("istemid").value;

                    $.ajax({
                        type: "post",
                        url: "ajax/vezne/araislemler.php?islem=maknogetir",
                        data: {"deg": deg},
                        success: function (e) {
                            $("#makbuznumarasi").html(e);
                        }
                    })
                });

            });


            $(document).ready(function () {
                $("#vezneiptal").on("click", function(){
                    //form reset
                    var gonderilenform = $("#formvezneiptal").serialize();
                    //alert(gonderilenform);
                    document.getelementbyid("formvezneiptal").reset();
                    $.ajax({
                        type:'post',
                        url:'ajax/vezne/araislemler.php?islem=veznesilme',
                        data:gonderilenform,
                        success:function(e){
                            $("#sonucyaz").html(e);
                            alertify.message( 'iptal işlemi başarılı');
                        }
                    });

                });

                $('#vezneiptal').on('click', function () {
                    var protokolnovezne=document.getelementbyid("istemid").value;
                    $.ajax({
                        type: "get",
                        url: "ajax/vezne/araislemler.php?islem=maklistgetir",
                        data: {"protokolnovezne": protokolnovezne},
                        success: function (e) {
                            $("#tahsilatlist").html(e);
                        }
                    })
                });
            });

        </script>
        <?php
    }
}


elseif ($islem=="veznelist") {
    if ($_POST) {
        $hastaid=$_POST['deg'];
        $makbuzno = rasgelesifreolustur('8');
        $toplam = 0;
        $uyrukgetir = verilericoklucek("select * from patient_prompts where protocol_number='$hastaid' and payment_completed='0'");
           foreach ($uyrukgetir as $rowa){
            $adet = $rowa['adet'];
            $ucret = $rowa['fee'];
            $toplam += $adet * $ucret;
        }

        ?>
        <!--html tables with student data-->
        <table id="tableid" class="display" style="width:100%">
            <thead>
            <tr>
                <th>işlem no</th>
                <th>hizmet</th>
                <th>gelir kodu</th>
                <th>gelir adı</th>
                <th>miktar</th>
                <th>fiyat</th>
                <th>t. tutar</th>
                <th>ödeme durum</th>
                <th>m.det. turu</th>
                <th>doktor</th>
                <th>personel</th>

            </tr>
            </thead>
            <tbody >
            <?php $hastalistgetir = verilericoklucek("select * from patient_prompts where protocol_number='$hastaid'");
              foreach ($hastalistgetir as $rowa){ ?>
                <tr  <?php if ($rowa["payment_completed"] == 1) { ?>style="pointer-events: none"; <?php } ?> >
                    <td><?php echo $rowa["id"]; ?></td>
                    <td><?php echo $rowa["adi"]; ?></td>
                    <td>ajax</td>
                    <td><?php echo $rowa["name_surname"] ?></td>
                    <td><?php echo $rowa["piece"] ?></td>
                    <td><?php echo $rowa["fee"] ?> ₺</td>
                    <td><?php $toplam1 = $rowa["piece"] * $rowa["fee"];echo $toplam1; ?></td>
                    <td>
                        <?php if ($rowa["payment_completed"] == 1) { ?>
                            <i class="fas fa-check-circle" style="color:green;"></i>
                        <?php } else { ?>

                            <i class="fas fa-times-circle"
                                       style="color:red;"></i>
                        <?php } ?>
                    </td>
                    <td><?php echo $rowa["depatrment_name"] ?>rht</td>
                    <td><?php echo $rowa["doktor_id"] ?></td>
                    <td><?php echo $rowa["istemi_yapan_kullanici_id"] ?></td>
                </tr>

            <?php } ?>

            </tbody>
            <tfoot>
            <tr>
                <td colspan="4" style="text-align: right"><b>ara tutar</b></td>
                <td colspan="2" id=""><b id="resultid"></b> ₺</td>
                <td colspan="2"  style="text-align: right"><b>toplam tutar</b></td>
                <td colspan="2"><h4><?php echo $toplam; ?> ₺</h4> </td>
            </tr>
            </tfoot>
        </table>
        <br/>
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-success btn-sm " data-toggle="modal" data-target="#odemeal" id="odemeler"><i class="fas fa-book px-1"></i>ödeme al</button>
        </div>

        <script>
            // initialization of datatable
            $(document).ready(function () {
                var studenttable = $('#tableid').datatable();

                // activate the 'selected' class
                // on clicking the rows
                $('#tableid tbody').on(
                    'click', 'tr', function () {
                        $(this).toggleclass('selected');
                    });

                $('#tableid tbody').click(function () {

                    // display the total row count
                    // on clicking the button
                    var totalcount
                        = studenttable.rows('.selected')
                        .data();
                    // $("#resultid").show().html(
                    //     "<br /><b>user clicked </b> "
                    //     + totalcount['0']['4'] + ' rows ');
                    const  say=studenttable.rows('.selected').data().length;

                    if(say>0){
                        console.log(totalcount['0']);
                        var bilgiler=0;
                        var id;
                        var araode;
                        var hizmetkodu=[];
                        var hztkod;
                        var hztkd;
                        var araid;
                        var idal=[];
                        for (let i = 0; i <= say; i++) {
                            bilgiler+=parseint(totalcount[i][6]);
                            id=parseint(totalcount[i][0]);
                            hztkd=totalcount[i][1];
                            hizmetkodu.push(hztkd);
                            idal.push(id);
                            console.log(bilgiler);
                            console.log(idal);

                            document.getelementbyid("resultid").innerhtml=bilgiler;
                            document.getelementbyid("araode").innerhtml=bilgiler;

                            hztkod=document.getelementbyid("hizmetkod");
                            hztkod.value=hizmetkodu;

                            araode=document.getelementbyid("araode1");
                            araode.value=bilgiler
                            araid=document.getelementbyid("idal");
                            araid.value=idal;
                        }
                        document.getelementbyid("idal").value = idal;


                    }else{
                        document.getelementbyid("resultid").innerhtml=0;
                        document.getelementbyid("araode").innerhtml=0;
                        document.getelementbyid("araode1").innerhtml=0;
                    }

                });
            });

            $(document).ready(function () {
                $('#odemeler').on('click', function () {
                    //$('#kasaduzenle').modal('show');
                    var deg=document.getelementbyid("istemid").value;
                    $.ajax({
                        type: "post",
                        url: "ajax/vezne/araislemler.php?islem=veznemodall",
                        data: {"deg": deg},
                        success: function (e) {
                            $("#toplamtutar").html(e);
                        }
                    })
                });
                $('#odemeler').on('click', function () {
                    var deg=document.getelementbyid("istemid").value;

                    $.ajax({
                        type: "post",
                        url: "ajax/vezne/araislemler.php?islem=maknogetir",
                        data: {"deg": deg},
                        success: function (e) {
                            $("#makbuznogetir").html(e);
                        }
                    })
                });
            });
        </script>
    <?php   } }

elseif ($islem=="veznemodall") {
        $hastaid=$_POST['deg'];
        $ucret = $rowa['fee'];  ?>

        <h4>toplam tutar: <?php echo hastaborcsorgula($hastaid); ?> ₺</h4>
        <input type="hidden" id="toplamdeger" value="<?php echo hastaborcsorgula($hastaid); ?>">

    <?php }

elseif($islem=="faturatutar"){
    $dovuzekkod=$_POST['dovuzekkod'];
    $tutaral=$_POST['ttutar'];
    $araode=$_POST['araode'];

    $dovuz=singular("sistem_ayarlari","id","1");

    $dolarfiyat=dolar_fiyat;
    $eurofiyat=euro_fiyat;
    if ($dovuzekkod=='0'){
        $birim=$tutaral / dolar_fiyat;
        $tutar=round($birim,2);
        $birim1=$araode / dolar_fiyat;
        $aratutar=round($birim1,2); ?>

        <div class="row">
            <div class="col-md-5 mb-1">
            </div>
            <div class="col-md-4">
                <h4>toplam tutar: <?php echo $tutar; ?> $</h4>
            </div>
            <div class="col-md-3">
                <h4>ara tutar: <?php echo $aratutar; ?> $</h4>
            </div>
        </div>

    <?php  }elseif ($dovuzekkod=='1'){
        $birim=$tutaral / euro_fiyat;
        $tutar=round($birim,2);
        $birim1=$araode / euro_fiyat;
        $aratutar=round($birim1,2);?>

        <div class="row">
            <div class="col-md-5 mb-1" align="right" >
            </div>
            <div class="col-md-4">
                <h4>toplam tutar: <?php echo $tutar ?> €</h4>
            </div>
            <div class="col-md-3">
                <h4>ara tutar: <?php echo $aratutar ?> €</h4>
            </div>
        </div>

    <?php }else{
        $tutar=round($tutaral,2); ?>
        <div class="row">
            <div class="col-md-5 mb-1" align="right" >
            </div>
            <div class="col-md-4">
                <h4>toplam tutar: <?php echo $tutar ?> ₺</h4>
            </div>
            <div class="col-md-3">
                <h4>ara tutar: <?php echo $araode ?> ₺</h4>
            </div>
        </div>

    <?php  }  ?>

    <?php } elseif ($islem=="vezneuyari") {
    if ($_POST) {
        $hastaid=$_POST['deg'];
        $toplam = 0;
        $uyrukgetir = verilericoklucek("select * from patient_prompts where protocol_number='$hastaid' and payment_completed='0'");
         foreach ($uyrukgetir as $rowa){
            $adet = $rowa['piece'];
            $ucret = $rowa['fee'];
            $toplam += $adet * $ucret;
        } ?>

        <?php if($toplam>0){ ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                hastanin <?php echo $toplam; ?> ₺ borcu var!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
            </div>
        <?php } ?>

    <?php } }

elseif ($islem=="maknogetir") { ?>
    <?php   $makbuzno=rasgelesifreolustur('8'); ?>

    <input class="form-control" type="text" disabled value="<?php echo $makbuzno ?>">
    <input class="form-control" type="hidden"  value="<?php echo $makbuzno ?>" name="receipt_number">

<?php   }


elseif ($islem=="veznesilme"){

    if($_POST) {
        var_dump($_POST);
        echo "<br/>";
        echo "*****************";
        $_POST["makbuz_zamani"] = $simdikitarih;
        $_POST["islem_tipi"] = 'cikan';
        $_POST["vezne_giris_cikis_bilgisi"] = '2';
        $_POST["istem_id"] = $_POST["id"];
        $istemid=$_POST["id"];
        unset($_POST["id"]);
        $islem_tipi='cikan';
        //var_dump($_POST);
        $vezneekle = direktekle("pay_offce",$_POST);
        if ($vezneekle == 1) {
            $id=$_POST["hasta_id"];
            $vezneguncelle=guncelle("update patient_prompts set status=2 where id='$istemid'");

            if ($vezneguncelle==1){
                $makbuztutar=$_POST["receipt_amount"];
                $kasaid=$_POST["kasa_id"];
                $ekleyen=1;
                $kasadetayekle =guncelle("insert into safe_detail (tutar, kasa_id, insert_userid, kayit_zamani,islem_tipi,protokol_id) values ('$makbuztutar', '$kasaid', '$ekleyen', '$simdikitarih','$islem_tipi','$id')");

                if ($kasadetayekle==1){
                    echo "ekleme başarılı".$istemid;
                }else{
                    echo  "kasaekleme hatası";
                }

            }else{
                echo "hasta istem hatası.";
            }

        } else {
            echo "veri eklenmedi..";
        }

    }

}

elseif ($islem=="maklistgetir") { ?>

    <div class="tab-content text-muted mt-4 mt-md-0" id="v-pills-tabcontent">
        <div class="row">
            <form action="" method="get">
                <div class="row">
                    <div class="col-lg-9">
                        <h4 class="card-title">vezne tahsilat listesi</h4>
                    </div><!-- end col -->
                    <div class="col-lg-2">
                        <input type="number" class="form-control w-100"
                               placeholder="tc kimlik numarası giriniz."
                               name="protokolnovezne" id="basicpill-firstname-input">
                    </div><!-- end col -->

                    <div class="col-lg-1 mb-2">
                        <input class="btn btn-success btn-sm justify-content-end tikla"
                               name="tchastagetir" type="submit"
                               value="getir"/>
                    </div><!-- end col -->
                </div>
            </form>

        </div>
        <div class="col-md-12" >
            <table id="example" class="table  table-bordered table-sm table-hover "
                   style=" background:white;width: 100%;">
                <thead>
                <tr>
                    <th scope="col">işlem adı</th>
                    <th scope="col">miktar</th>
                    <th scope="col">fiyat</th>
                    <th scope="col">tutar</th>
                    <th scope="col">durum</th>
                    <th scope="col">doktor</th>
                    <th scope="col">kasa</th>
                    <th scope="col">veznedar</th>
                </tr>
                </thead>
                <tbody>

                <?php
                $hastabilgitc=singular("patient_registration","tc_id",$_GET['protokolnovezne']);
                if (isset($hastabilgitc['tc_id'])){
                    $tckimlik =$hastabilgitc['id'];
                }else{
                    $tckimlik =$_GET['protokolnovezne'];
                }

                $hastalistgetir = verilericoklucek("select * from pay_office where hasta_id=$tckimlik");
                  foreach ($hastalistgetir as $rowa){
                    $idler=$rowa['istem_id'];
                    $dizi = explode (",",$idler);

                    for ($i =0; $i < count($dizi); $i++) {
                        $row=singular("patient_prompts","id","$dizi[$i]");  ?>

                        <tr class="makbuzclick" id="<?php echo $dizi[$i] ?>" <?php if ($rowa['islem_tipi']=='cikan') { ?>style="background: aqua";<?php  }else{ ?>style="background:lightpink";<?php  }?> data-toggle="modal" data-target="#makbuziptl">
                            <td><?php echo $row["adi"] ?></td>
                            <td><?php echo $row["piece"] ?></td>
                            <td><?php echo $row["fee"] ?></td>
                            <td><?php  $tplm=$row["piece"]*$row["fee"];echo $tplm; ?></td>
                            <td><?php if ($rowa["status"] == 1) { ?><i class="fas fa-check-circle" style="color:green;"></i><?php } else { ?><i class="fas fa-times-circle" style="color:red;"></i><?php } ?></td>
                            <td><?php echo $dr=kullanicigetir($row["doktor_id"]);echo $dr; ?></td>
                            <td><?php  $kasagtr=kasagetirid($rowa["kasa_id"]);echo  $kasagtr ?></td>
                            <td><?php echo $rowa["veznedar"] ?></td>
                        </tr>

                    <?php } }?>

                </tbody>
            </table>
            <script>
                $(document).ready(function () {

                    $('.makbuzclick').on('click', function () {
                        var makid =$(this).attr('id');

                        $.ajax({
                            type: "post",
                            url: "ajax/vezne/araislemler.php?islem=makbuzbilgi",
                            data: {"makid": makid},
                            success: function (e) {
                                $("#makbuzbody").html(e);
                            }
                        })
                    });

                    $('.makbuzclick').on('click', function () {
                        var deg=document.getelementbyid("istemid").value;

                        $.ajax({
                            type: "post",
                            url: "ajax/vezne/araislemler.php?islem=maknogetir",
                            data: {"deg": deg},
                            success: function (e) {
                                $("#makbuznumarasi").html(e);
                            }
                        })
                    });
                });
            </script>
        </div>
    </div>
<?php   }

elseif($islem=="birimtutar"){
    $birimid=$_POST['birimid'];
    echo $birimid;
    $devirtoplam = 0;
    $devirgiren = 0;
    $devircikan = 0;

    $uyrukgetir = "select * from pay_office where vezne_birim_kodu='$birimid'";
    foreach ($uyrukgetir as $rowa){
        if ($rowa['islem_tipi']=='giren'){
            $devirgiren+=$rowa['receipt_amount'];
        }elseif ($rowa['islem_tipi']=='cikan'){
            $devircikan+=$rowa['receipt_amount'];
        }
        $devirtoplam=$devirgiren-$devircikan;

    } ?>

    <input class="form-control"  type="text" disabled value="<?php echo $devirtoplam ?>">
    <input class="form-control" name="devir_tutar" type="hidden"  value="<?php echo $devirtoplam ?>">

<?php }

elseif ($islem=="kasadevir"){
    if($_POST) {

//        var_dump($_POST);
        $kasekle = direktekle("safe_detail",$_POST);
        if ($kasekle == 1) {
            echo "veri eklendi";
        } else {
            echo "veri eklenmedi..";
        }

    }
}

elseif ($islem=="devirlist"){ ?>

    <?php $hastalistgetir = verilericoklucek("select * from safe_detail where deviralan_kullanici is not null");
      foreach ($hastalistgetir as $rowa){ ?>

        <tr class="kasadevirupdate" data-id="<?php echo $rowa["id"] ?>" data-toggle="modal" data-target="#kasdevirmodal">

            <?php $eden=kullanicigetir($rowa["devreden_kullanici"]);
            $alan=kullanicigetir($rowa["deviralan_kullanici"]);  ?>

            <td><?php echo $rowa["id"]  ?></td>
            <td><?php echo $eden ?></td>
            <td><?php echo $alan ?></td>
            <td><?php echo $rowa["devir_tutar"] ?></td>
            <td><?php echo $rowa["kayit_zamani"] ?></td>

        </tr>
        <script>
            $(document).ready(function(){
                $(".kasadevirupdate").click(function () {
                    var kasdevirid = $(this).data('id');
                    var islem = "kasdevirbody";
                    $.get("ajax/araislemler.php", {islem:islem,kasdevirid: kasdevirid}, function (getveri) {
                        $('#kasadevirbody').html(getveri);
                    });
                });
            });
        </script>

    <?php } ?>

<?php }

elseif ($islem=="kasdevirbody"){
    $no=$_GET["kasdevirid"];
    $kasadevir=singular("safe_detail","id","$no");  ?>

    <form action="javascript:void(0);" id="formveznedevirduzenle">
        <div class="row">
            <div class="col-md-12">
                <input class="form-control" name="id" type="hidden"  value="<?php echo $kasadevir['id'] ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-3 col-form-label">devreden vezne </label>
                            <div class="col-md-8">

                                <select class="form-select" name="devreden_vezne_birim" id="birim3">
                                    <option selected>birim seç</option>
                                    <?php $uyrukgetir = verilericoklucek("select * from units");
                                    foreach ($uyrukgetir as $rowa){ ?>
                                        <option <?php if($rowa["id"] == $kasadevir['devreden_vezne_birim']) echo"selected"; ?>
                                            value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["depatrment_name"]; ?></option>
                                    <?php } ?>
                                </select>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3 row">
                            <label for="example-text-input" class="col-md-3 col-form-label">devreden veznedar</label>
                            <div class="col-md-8">

                                <select class="form-select" name="devreden_kullanici" id="personel3">
                                    <?php $devreden_kullanici=$kasadevir['devreden_kullanici'];
                                    $bolumgetir = verilericoklucek("select * from users where id='$devreden_kullanici'");
                                     foreach ($bolumgetir as $value){ ?>
                                        <option value="<?php echo $value['id']; ?>"><?php echo $value['name_surname']; ?></option>
                                    <?php  } ?>
                                </select>

                            </div>
                        </div>
                    </div>

                    <script>
                        $(document).ready(function () {
                            $("#birim3").change(function () {
                                var birimid = $(this).val();
                                $.ajax({
                                    type: "post",
                                    url: "ajax/vezne/araislemler.php?islem=birimgetir",
                                    data: {"birimid": birimid},
                                    success: function (e) {
                                        $("#personel3").html(e);
                                    }
                                })
                            })

                            $("#birim3").change(function () {
                                var birimid = $(this).val();
                                $.ajax({
                                    type: "post",
                                    url: "ajax/vezne/araislemler.php?islem=birimtutar",
                                    data: {"birimid": birimid},
                                    success: function (e) {
                                        $("#birimtutar1").html(e);
                                    }
                                })
                            })

                        })

                    </script>

                </div>
                <div class="row">

                    <div class="col-md-6">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-3 col-form-label">deviralan vezne</label>
                            <div class="col-md-8">

                                <select class="form-select" name="deviralan_vezne_birim" id="birim2">
                                    <option selected>birim seç</option>
                                    <?php $uyrukgetir = "select * from units";

                                      foreach ($uyrukgetir as $rowa){ ?>
                                        <option <?php if($rowa["id"] == $kasadevir['deviralan_vezne_birim']) echo"selected"; ?>value="<?php echo $rowa['id']; ?>"><?php echo $rowa['depatrment_name']; ?></option>
                                    <?php } ?>

                                </select>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3 row">
                            <label for="example-text-input" class="col-md-3 col-form-label">deviralan veznedar</label>
                            <div class="col-md-8">

                                <select class="form-select" name="deviralan_kullanici" id="personel2">
                                    <?php $deviralan_kullanici=$kasadevir['deviralan_kullanici'];
                                    $bolumgetir = "select * from users where id='$deviralan_kullanici'";

                                      foreach ($bolumgetir as $value){ ?>
                                          <option value="<?php echo $value['id']; ?>"><?php echo $value['name_surname']; ?></option>
                                    <?php  } ?>
                                </select>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">devreden tutar</label>
                            <div class="col-md-8" id="birimtutar1">
                                <input class="form-control" type="text" disabled value="<?php echo $kasadevir['devir_tutar']; ?>" >
                                <input class="form-control" type="hidden" value="<?php echo $kasadevir['devir_tutar'] ?>" >
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-3 col-form-label">tarihi</label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" disabled value="<?php echo $kasadevir['kayit_zamani'] ?>" id="example-text-input">
                                <input class="form-control" type="hidden"  value="<?php echo $kasadevir['kayit_zamani'] ?>" id="example-text-input">
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    $(document).ready(function () {
                        $("#birim2").change(function () {
                            var birimid = $(this).val();
                            $.ajax({
                                type: "post",
                                url: "ajax/vezne/araislemler.php?islem=birimgetir",
                                data: {"birimid": birimid},
                                success: function (e) {
                                    $("#personel2").html(e);
                                }
                            })
                        })

                        $("#kasadevirup").on("click", function(){
                            //form reset
                            var gonderilenform = $("#formveznedevirduzenle").serialize();

                            document.getelementbyid("formveznedevirduzenle").reset();
                            $.ajax({
                                type:'post',
                                url:'ajax/vezne/araislemler.php?islem=kasadevirduzen',
                                data:gonderilenform,
                                success:function(e){
                                    $("#sonucyaz").html(e);
                                    alertify.message( 'kasa devir  güncelle başarılı');
                                }
                            });

                        });

                        $("#kasadevirup").on("click", function(){

                            $.ajax({
                                type:'post',
                                url:'ajax/vezne/araislemler.php?islem=devirlist',
                                data:{},
                                success:function(e){
                                    $("#kasadevirlist").html(e);
                                }
                            });

                        });

                    })
                </script>

                <div class="col-md-12" align="right">
                    <button type="button" class="btn btn-success btn-sm " data-id="3" data-dismiss="modal" id="kasadevirup"><i class="fas fa-book px-1"></i>güncelle</button>
                </div>

            </div>
        </div>
    </form>

    <?php
}

elseif ($islem=="kasadevirduzen"){
    if($_POST) {
        $id=$_POST['id'];
        unset($_POST['id']);
//        var_dump($_POST);
        $vezneguncelle=direktguncelle("safe_detail","id",$id,$_POST);
        if ($vezneguncelle == 1) {
            echo "veri eklendi";
        } else {
            echo "veri eklenmedi..";
        }

    }
}

?>