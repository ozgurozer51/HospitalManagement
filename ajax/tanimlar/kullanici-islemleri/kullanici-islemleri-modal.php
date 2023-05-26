<?php
include "../../../controller/fonksiyonlar.php";

$islem = $_GET['islem'];

if($islem=="kullanici-bilgisi-getir"){ ?>
<div class="modal-dialog" style=" width: 90%; max-width: 95%; ">
    <form class="modal-content"  action="" method="post" >
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <?php  if($_GET["doktorbilgisi"]) {
                echo '<h4 class="modal-title">kullanıcı düzenle</h4>';
                $doktorbilgisi = singular("users", "id", $_GET["doktorbilgisi"]);
                extract($doktorbilgisi);
            }else {
                echo '<h4 class="modal-title">kullanıcı ekle</h4>';
            }  ?>

        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12 row">
                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">aktif/pasif</label>
                            <div class="col-md-8">
                                <select name="status" class="form-control" >
                                    <option <?php if($status==1){echo "selected"; } ?> value="1">aktif</option>
                                    <option <?php if($status==0){echo "selected"; } ?>  value="0">pasif</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">tipi</label>
                            <div class="col-md-8">
                                <select name="authority_id" required class="form-control" >
                                    <option value="">seçim yapınız</option>
                                    <?php  $bolumgetir = "select * from authority_group";
                                    $sql=verilericoklucek($bolumgetir);
                                    foreach ($sql as $rowa) {   ?>
                                        <option <?php if($authority==$rowa["id"]){echo "selected"; } ?> value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["group_name"]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">t.c no</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" required onkeyup="tcsorgula(this.value)" name="tc_id" value="<?php echo $tc_id; ?>"  id="tcsize">
                                <div id="sorguvarmi"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">adı soyadı</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="name_surname" value="<?php echo $name_surname; ?>" id="basicpill-firstname-input">
                                <input type="hidden" class="form-control" required name="doktor_id" value="<?php echo $_GET["doktorbilgisi"]; ?>" id="basicpill-firstname-input">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">şifresi</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" required name="user_password" value="<?php echo $user_password; ?>" id="basicpill-firstname-input">

                            </div>
                        </div>
                    </div>


                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">ç. listesi</label>
                            <div class="col-md-8">
                                <select name="work_list" class="form-control" >
                                    <option  <?php if($work_list==1){echo "selected"; } ?>  value="1">aktif</option>
                                    <option  <?php if($work_list==0){echo "selected"; } ?>  value="0">pasif</option>
                                </select>
                                <small style="color:red;">çalışma listesi</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">otom. atama</label>
                            <div class="col-md-8">
                                <select name="auto_assign_list" class="form-control" >
                                    <option  <?php if($auto_assign_list==1){echo "selected"; } ?>  value="1">aktif</option>
                                    <option  <?php if($auto_assign_list==0){echo "selected"; } ?>  value="0">pasif</option>
                                </select>
                                <small style="color:red;">çalışma listesi</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">tescil no</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="sb_registration_number"  value="<?php echo $sb_registration_number; ?>" id="basicpill-firstname-input">
                                <small style="color:red;">s.b. tescil numarası</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">uzman no</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="surveyorship_registry_number"  value="<?php echo $surveyorship_registry_number; ?>" id="basicpill-firstname-input">
                                <small style="color:red;">uzmanlık tescil no </small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">dr branşı</label>
                            <div class="col-md-8">
                                <select name="dr_bras_code"  required class="form-control" >
                                    <option value="">seçim yapınız</option>
                                    <?php  $bolumgetir = "select * from branch where status='1' order by branch_name asc";
                                    $sql=verilericoklucek($bolumgetir);
                                    foreach ($sql as $rowa) {
                                        ?>
                                        <option  <?php if($bolumu==$rowa["id"]){echo "selected"; } ?> value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["branch_name"]; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">açıklama</label>
                            <div class="col-md-8">
                                <textarea type="text" class="form-control" name="description"   id="basicpill-firstname-input"><?php echo $description; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">rap ünvan</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="report_title"  value="<?php echo $report_title; ?>"  id="basicpill-firstname-input">
                                <small style="color:red;">rapordaki ünvanı </small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">uzm. bö.</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="report_expert_department" value="<?php echo $report_expert_department; ?>"  id="basicpill-firstname-input">

                                <small style="color:red;">rapordaki bölümü</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">dr hakediş</label>
                            <div class="col-md-8">
                                <select name="dr_credit_arrival" class="form-control" >
                                    <option <?php if($dr_credit_arrival==0){echo "selected"; } ?> value="0">almasın</option>
                                    <option <?php if($dr_credit_arrival==1){echo "selected"; } ?> value="1">alsın</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">randevuda</label>
                            <div class="col-md-8">
                                <select name="appointment_arrival" class="form-control" >
                                    <option <?php if($appointment_arrival==0){echo "selected"; } ?> value="0">görünmesin</option>
                                    <option <?php if($appointment_arrival==1){echo "selected"; } ?> value="1">görünsün</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">kotası</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="quota"  value="<?php echo $quota; ?>"  id="basicpill-firstname-input">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">randevu</label>
                            <div class="col-md-8">
                                <select name="internet_dating" class="form-control" >
                                    <option <?php if($internet_dating==0){echo "selected"; } ?> value="0">pasif</option>
                                    <option <?php if($internet_dating==1){echo "selected"; } ?> value="1">aktif</option>
                                </select>
                                <small style="color:red;">i̇nternet randevusu </small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">doktor tipi</label>
                            <div class="col-md-8">

                                <select name="doctor_type" class="form-control" >
                                    <?php  $sqla = "select * from transaction_definitions where definition_type = 'DOKTOR_TIPI' order by id";
                                    $sql=verilericoklucek($sqla);
                                    foreach ($sql as $rowa) { ?>
                                        <option <?php if($doctor_type==$rowa["id"]){echo "selected"; } ?>  value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["definition_name"]; ?></option>
                                    <?php } ?>
                                </select>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">fark tipi </label>
                            <div class="col-md-8">
                                <select name="type_difference_fee_to_be_charged" class="form-control" >
                                    <?php
                                    $sqla = "select * from transaction_definitions where definition_type = 'ALINACAK_FARK_TIPI' order by id";
                                    $sql=verilericoklucek($sqla);
                                    foreach ($sql as $rowa) { ?>
                                        <option <?php if($type_difference_fee_to_be_charged==$rowa["id"]){echo "selected"; } ?>  value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["definition_name"]; ?></option>
                                    <?php } ?>
                                </select>

                                <small style="color:red;">alınacak fark tipi </small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">fark ücreti</label>
                            <div class="col-md-8">
                                <input type="number" class="form-control" name="type_difference_fee_to_be_charged" value="<?php echo $type_difference_fee_to_be_charged; ?>"  id="basicpill-firstname-input">
                                <small style="color:red;">alınacak fark ücreti</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">snet kul.</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="snet_user_name" value="<?php echo $snet_user_name; ?>"  id="basicpill-firstname-input">
                                <small style="color:red;">snet kullanıcı adı </small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">şifresi</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="snet_user_password"  value="<?php echo $snet_user_password; ?>"  id="basicpill-firstname-input">
                                <small style="color:red;">snet şifresi </small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">ehu</label>
                            <div class="col-md-8">
                                <select name="orderda_hem_unapproved" class="form-control" >
                                    <option <?php if($orderda_hem_unapproved==1){echo "selected"; } ?>  value="1">aktif</option>
                                    <option <?php if($orderda_hem_unapproved==0){echo "selected"; } ?>  value="0">pasif</option>
                                </select>
                                <small style="color:red;">orderda ehu onaysız </small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">e-imza</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="e_signature"  value="<?php echo $e_signature; ?>"  id="basicpill-firstname-input">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">e-imza</label>
                            <div class="col-md-8">
                                <input type="date" class="form-control" name="e_signature_beginning_datetime" value="<?php echo $e_signature_beginning_datetime; ?>"  id="basicpill-firstname-input">
                                <small style="color:red;">başlangıç tarihi</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">e-imza </label>
                            <div class="col-md-8">
                                <input type="date" class="form-control" name="e_signature_end_datetime" value="<?php echo $e_signature_end_datetime; ?>"  id="basicpill-firstname-input">
                                <small style="color:red;">bitiş tarihi</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label"> şifre</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control"   value="<?php echo $e_signature_password; ?>"  name="e_signature_password" id="basicpill-firstname-input">
                                <small style="color:red;">e-i̇mza şifre</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">şifre</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control"  value="<?php echo $e_prescriptions_password; ?>"  name="e_prescriptions_password" id="basicpill-firstname-input">
                                <small style="color:red;">e-reçete şifre</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">firma</label>
                            <div class="col-md-8">
                                <select name="e_signature_company" class="form-control" >
                                    <?php $sqla = "select * from transaction_definitions where definition_type = 'E_IMZA_FIRMA' order by id";
                                    $sql=verilericoklucek($sqla);
                                    foreach ($sql as $rowa) {  ?>
                                        <option <?php if($e_signature_company==$rowa["id"]){echo "selected"; } ?>  value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["department_name"]; ?></option>
                                    <?php } ?>
                                    <small style="color:red;">e-i̇mza firması</small>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">e-posta</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="email"  value="<?php echo $email; ?>"  id="basicpill-firstname-input">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">dr kısa adı</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control"  value="<?php echo $dr_short_name; ?>"  name="dr_short_name" id="basicpill-firstname-input">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="mb-2 row">
                            <label for="example-text-input" class="col-md-4 col-form-label">oturum sayısı</label>
                            <div class="col-md-8">
                                <input type="number" class="form-control"  value="<?php echo $session_num; ?>"  name="session_num" id="basicpill-firstname-input">
                                <small style="color:red;">aynı anda açabileceği </small>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <div class="modal-footer">
            <?php  if($_GET["doktorbilgisi"]!="") { ?>
                <a onclick="return confirm('kullancıyı silmek istediğinize emin misiniz ? ');"       href="?islem=kullaniciislem&islemid=<?php echo $_GET["doktorbilgisi"]; ?>"><button type="button" class="btn btn-default"   style=" margin-bottom: 0; margin-left: 5px; background: red; color: white; margin: 0; margin-left: 10px; ">sil</button></a>
                <input class="btn btn-primary w-md justify-content-end" name="doktorguncelle" type="submit"   value="düzenle"/>
            <?php }else{ ?>
                <input class="btn btn-primary w-md justify-content-end" id="kaydetbutonu" name="doktorkaydet" type="submit"   value="kaydet"/>
            <?php } ?>
            <button type="button" class="btn btn-default" data-dismiss="modal" style=" margin-bottom: 0; margin-left: 5px; background: red; color: white; margin: 0; margin-left: 10px; ">kapat</button>
        </div>
    </form>
</div>

</div>

<script>
    function tcsorgula(str) {
        if (str.length==0) {
            document.getelementbyid("sorguvarmi").innerhtml="";
            document.getelementbyid("sorguvarmi").style.border="0px";
            return;
        }
        var xmlhttp=new xmlhttprequest();
        xmlhttp.onreadystatechange=function() {
            if (this.readystate==4 && this.status==200) {
                var ihsan =this.responsetext;
                if(ihsan){
                    document.getelementbyid("kaydetbutonu").disabled = true;
                    document.getelementbyid("tcsize").style.background="#ff0000";
                    alertify.alert("dikkat","bu tc kimlik numaralı kullanıcı sistemlerinizde kayıtlı göründüğünden dolayı aynı tc kimlik numarası ile kayıt yapamazsınız!");
                    document.getelementbyid("tcsize").value = "";

                }else{
                    document.getelementbyid("kaydetbutonu").disabled = false;
                    document.getelementbyid("tcsize").style.background="white";

                }
            }
        }
        xmlhttp.open("get","ajax/kullanicitcsorgula.php?tc_kimlik="+str,true);
        xmlhttp.send();
    }
</script>


<?php } if ($islem=="grup-yetki-tanim"){
    $bina=singular("staff_group","group_id",$_GET["getir"]);?>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #3F72AF">
                <?php if ($_GET['getir']){ ?>
                    <h4 class="modal-title ">Grup Yetki Düzenle</h4>
                <?php  }else{ ?>
                    <h4 class="modal-title ">Grup Yetki Kaydet</h4>
                <?php } ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form   id="formbina" action="javascript:void(0);" >
                <div class="modal-body">

                            <div class="row">
                                <div class="col-md-6">

                                    <table class="table table-bordered w-100 display nowrap table-hover"  id="usertablo">
                                        <thead class="table-light">
                                        <tr>
                                            <th>İd</th>
                                            <th>Yetki Adı</th>
                                            <th>Açıklama</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php  $say=0;
                                        if ($_GET['getir']){
                                            $sql= verilericoklucek("select * from staff_group where group_id='$_GET[getir]' and status='1' ");
                                            $sql2= "select * from authority_definition where status='1'";
                                        }else{
                                            $sql2= "select * from authority_definition where status='1'";
                                        }
                                        $sonuc= verilericoklucek($sql2);
                                        foreach ($sonuc as $row) { ?>
                                            <tr  <?php foreach ($sql as $item){ if($row['id']== $item['authority_id']){ ?> style="background-color:rgb(57, 180, 150); color: white;"  id="kayitli-users" userupid="<?php echo $item['authority_id'] ?>" <?php }} ?>class="SECIM_YAP" user-id="<?php echo $row['id']; ?>">
                                                <td><?php echo $row['id'] ?></td>
                                                <td ><?php echo $row["authority"] ?></td>
                                                <td ><?php echo $row["explanation"] ?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>

                                    <div id="kullanici-biriktir"></div>
                                </div>

                                <div class="col-md-6">
                                    <label for="example-text-input" class="col-form-label">Grup Adı</label>
                                    <input class="form-control" type="text" name="group_name" value="<?php echo $bina["group_name"]?>" id="example-text-input">
                                    <?php if ($_GET['getir']){ ?>
                                        <input class="form-control" type="hidden" name="id" value="<?php echo  $_GET['getir'];?>">
                                    <?php } ?>
                                </div>
                            </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" class="btn-close" data-bs-dismiss="modal" style=" margin-bottom: 0; margin-left: 5px; background: red; color: white; margin: 0; margin-left: 10px; ">Kapat</button>
                    <?php if ($_GET["getir"]){ ?>
                        <input class="btn btn-success  grup-user-update btn-sm" style="margin-bottom:4px"  type="submit" data-bs-dismiss="modal" value="Düzenle"/>
                    <?php  }else{ ?>
                        <input type="submit" class="btn  btn-update grup-user-insert btn-sm btn-success"  data-bs-dismiss="modal"  value="Kaydet"/>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $("body").off("click", ".grup-user-update").on("click", ".grup-user-update", function(e){
                var gonderilenform = $("#formbina").serialize();
                $.ajax({
                    type:'POST',
                    url:'ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-sql.php?islem=grup-user-update',
                    data:gonderilenform,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $('.yetki-grup-tanim:first').load("ajax/tanimlar/kullanici-islemleri/yetki-grup-tanim.php");
                    }
                });
            });

            $("body").off("click", ".grup-user-insert").on("click", ".grup-user-insert", function(e){
                var gonderilenform = $("#formbina").serialize();
                document.getElementById("formbina").reset();
                $.ajax({
                    type:'POST',
                    url:'ajax/tanimlar/kullanici-islemleri/kullanici-islemleri-sql.php?islem=grup-user-ekle',
                    data:gonderilenform,
                    success:function(e){
                        $('#sonucyaz').html(e);
                        $('.yetki-grup-tanim:first').load("ajax/tanimlar/kullanici-islemleri/yetki-grup-tanim.php");
                    }
                });
            });
        });

        setTimeout(function() {
            var ID = [];
            $("[id='kayitli-users']").each(function(i){
                ID.push($(this).attr('userupid'));
            });

            for(let i=0;i<ID.length;i++){
                $('#kullanici-biriktir').append("<input type='hidden' id='" + ID[i] + "' name='usersid[]'  value='" + ID[i] + "' />");
            }
        }, 500);

        $("body").off("click", ".SECIM_YAP").on("click", ".SECIM_YAP", function(e){
            var userid = $(this).attr('user-id');
            if ($(this).css('background-color') != 'rgb(57, 180, 150)') {
                $(this).addClass("text-white");
                $(this).css("background-color", "rgb(57, 180, 150)");
                $('#kullanici-biriktir').append("<input type='hidden' id='" + userid + "' name='usersid[]'  value='" + userid + "' />");
            }else{
                $(this).css("background-color", "rgb(255, 255, 255)");
                $(this).addClass("text-dark");
                $(this).removeClass("text-white");
                $('#' + userid).remove();
            }
        });


        setTimeout(function() {
            $('#usertablo').DataTable({
                "responsive": true,
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
                },
                "lengthChange": false,
                "info": false
            });

        }, 450);

    </script>

<?php }if($islem=="online-kullanici-listesi"){ ?>

    <div class="modal-dialog  modal-lg"  >
        <div class="modal-content"  action="" method="post" >
            <div class="modal-header">
                <button type="button" class="close bg-danger text-white border-danger" data-dismiss="modal">X</button>
                <h4 class="modal-title">sistemde olan kullanıcılar</h4>
            </div>
            <div class="modal-body">

                <table id="online-users-table" class="table table-striped table-bordered" style=" background:white;width: 100%;">
                    <thead style=" background:white;width: 100%;">
                    <tr style=" background:white;width: 100%;">
                        <th>id</th>
                        <th>kullanıcı tc kimlik</th>
                        <th>kullanıcı adı soyadı</th>
                        <th>giriş tarihi</th>
                        <th>i̇şlem</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                    $sql = 'select * from users_session_logs where desistence_datetime is null order by id';
                    $hello=verilericoklucek($sql);
                    foreach ($hello as $row){
                        $kullanicibilgisi=singular("users","id",$row["userid"]);   ?>
                        <tr>
                            <td><?php echo $row["id"]; ?></td>
                            <td><?php echo $kullanicibilgisi["tc_id"]; ?></td>
                            <td><?php echo $kullanicibilgisi["name_surname"]; ?></td>
                            <td><?php echo nettarih($row["session_datetime"]); ?></td>
                            <td>    <button  islemid="<?php echo $row["id"]; ?>"  islem="oturumsonlandir"  type="button" class="oturumusonlandir btn btn-danger waves-effect waves-light w-sm">oturumu sonlandır</button></td>
                        </tr>
                    <?php } ?>

                    </tbody>
                </table>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal" style=" margin-bottom: 0; margin-left: 5px; background: red; color: white; margin: 0; margin-left: 10px; ">kapat</button>
            </div>
        </div>
    </div>
    </div>

    <script>
        $(".oturumusonlandir").click(function(){
            var islemid = $(this).attr('islemid');
            var islem = $(this).attr('islem');
            $.get( "ajax/yetki/onlinekullanicilar.php", { islemid:islemid,islem:islem },function(getveri){
                $('#yetkibilgisiicerik').html(getveri);
                alertify.alert("oturum sonlandırma başarılı");
            });
        });

        $('#online-users-table').DataTable({
            "scrollY": true,
            "scrollX": true,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.11.3/i18n/tr.json"
            },
        });
    </script>

<?php } else if($islem=="kullanici-tanim"){

$kullanici=singular("users","id",$_GET["getir"]);
extract($kullanici);  ?>
<div class="modal-dialog" style="max-width: 70%;">
    <form class="modal-content" id="formkullanicilar" action="javascript:void(0);">
        <div class="modal-header text-white" >
            <?php  if($kullanici) {
                echo '<h4 class="modal-title">Kullanıcı Düzenle</h4>';  ?>
                <input type="hidden" class="form-control" required name="id" value="<?php echo $_GET["getir"]; ?>"/>
            <?php }else {
                echo '<h4 class="modal-title">Kullanıcı Ekle</h4>'; }  ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">

            <div class="row">
                <div class="col-2">
                    <label for="tc_id" class="col-form-label">T.C. No</label>
                </div>
                <div class="col-10">
                    <input type="text" class="form-control" required onkeyup="tcsorgula(this.value)" name="tc_id" value="<?php echo $tc_id; ?>" id="tcsize">
                    <div id="sorguvarmi"></div>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-2">
                    <label class="col-form-label">Adı</label>
                </div>
                <div class="col-10">
                    <input type="text" class="form-control" name="person_name" value="<?php echo $name_surname; ?>" id="basicpill-firstname-input">
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-2">
                    <label class="col-form-label">Soyadı</label>
                </div>
                <div class="col-10">
                    <input type="text" class="form-control" name="person_surname" value="<?php echo $name_surname; ?>" id="basicpill-firstname-input">
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-2">
                    <label class="col-form-label">Şifresi</label>
                </div>
                <div class="col-10">
                    <input type="text" class="form-control" required name="user_password" value="<?php echo $user_password; ?>" id="basicpill-firstname-input">
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-2">
                    <label class="form-label">Kullanıcı Adı</label>
                </div>
                <div class="col-10">
                    <input class="form-control" type="text" name="username"/>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-2">
                    <label class="col-form-label">Snet Kul.</label>
                </div>
                <div class="col-10">
                    <input type="text" class="form-control" name="snet_user_name" value="<?php echo $snet_user_name; ?>">
                    <small style="color:red;">Snet kullanıcı adı </small>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-2">
                    <label class="col-form-label">Şifresi</label>
                </div>
                <div class="col-10">
                    <input type="text" class="form-control" name="snet_user_password" value="<?php echo $snet_user_password; ?>">
                    <small style="color:red;">Snet şifresi </small>
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <?php if ($_GET["getir"]){ ?>
                <input class="btn btn-success btn-sm kullanici-update" type="button" data-bs-dismiss="modal" value="Düzenle"/>
            <?php  }else{ ?>
                <input type="button" class="btn btn-success btn-sm kullanici-ekle" id="brans-kaydet" data-bs-dismiss="modal" value="Kaydet"/>
            <?php } ?>
            <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Kapat</button>
        </div>

    </form>
</div>

<?php } ?>
