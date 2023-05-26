<?php
include "../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
oturumkaydikontrol();

$hastakayitbilgileri = singular("patients", "tc_id", $_POST["tc_id"]); 
$simdikitarih = date('Y/m/d H:i:s');
$simdi = explode(" ", $simdikitarih);
$simditarih = $simdi['0'];
$simdiyil = substr($simditarih, 0, 4);
$simdial = explode("/", $simdi['0']);
$tarihal = $simdial['2'] . "-" . $simdial['1'] . "-" . $simdial['0'];
$today_start_time = date('Y-m-d') . " 00:00:00";
$today_end_time = date('Y-m-d') . " 23:59:00";
$dogum = explode("-", $hastakayitbilgileri['birth_date']);
$dogumyil = $dogum['0'];
$yas = $simdiyil - $dogumyil;

if($_GET["islem"]=="poliklinik-kayit"){
    $doktorlimit = singular("users", "id", $_POST["doctor"]);
    $drkotasi=$doktorlimit["quota"]; //KOTA DEĞİŞECEK
    $drkayitlisayisi=drhastasayisigetir("patient_registration","doctor",$_POST["doctor"],"insert_datetime",$today_start_time);
    $limityetkisi=yetkisorgula($_SESSION["id"],"limitdolandrkayitacma");

    //hastanın x süresi boyunca aynı polikliniğe kayıt açılırsa kontrol olarak geçilsin.
    $ilkkezmigeliyor=tek("select * from patient_registration where tc_id='{$_POST['tc_id']}' and outpatient_id='{$_POST["outpatient_id"]}'");

    if($ilkkezmigeliyor){
        $gunsorgula =dahaoncekikayitsorgula($_POST["outpatient_id"],$_POST['tc_id']);
//        var_dump($gunsorgula);
        if($gunsorgula>kontrol_gecerlilik_suresi){
            $_POST["process_type"]="muayene";
        }else  {
            $_POST["process_type"]="kontrol";
        }
    }else{
        $_POST["process_type"]="muayene";
    }
    if($drkayitlisayisi>$drkotasi and $limityetkisi==""){ ?>

        <script>
            alertify.alert(<?php echo $dil["yetki_uyarisi"]; ?>,<?php echo $dil["bu_doktor_gunluk_bakacagi_maximum_sayi"]; ?>);
        </script>

        <?php }else{

        $kayıtsayisi = polhastasayisigetir("patient_registration", "doctor", $_POST["doctor"], "insert_datetime", "$nettarih");
        $_POST["row_number"] = $kayıtsayisi + 1;
        $_POST["registration_time"] = $netsaat;
        $kayitvarmi =dahaoncekikayitsorgula($_POST["outpatient_id"],$_POST['tc_id']);

        if($kayitvarmi){
            $aynigunsorgu=yetkisorgula($_SESSION["id"],"aynigunaynipolkayit");

            if($aynigunsorgu){
                $protokonogetir = tek("select * from patient_registration  order by protocol_number DESC");
                $yeniprotokol=$protokonogetir["protocol_number"]+1;
                $_POST["protocol_number"]=$yeniprotokol;
                $_POST["patient_id"]=$hastakayitbilgileri["id"];
                $kayitet=     direktekle("patient_registration",$_POST,"hastakayit");
//                var_dump($protokonogetir);
                if($kayitet){ echo "1."; }
            } else { ?>
            <script>
                // alert("ihsan");
                alertify.alert(<?php echo $dil["yetki_uyarisi"]; ?>,<?php echo $dil["ayni_gun_ayni_hastaya_kayit_olusturma_yetkisi"]; ?>);
            </script>
        <?php } }else{
            $protokonogetir = tek("select * from patient_registration   order by protocol_number DESC");
            $yeniprotokol=$protokonogetir["protocol_number"]+1;
            $_POST["protocol_number"]=$yeniprotokol;
            $_POST["patient_id"]=$hastakayitbilgileri["id"];
            $kayitet=direktekle("patient_registration",$_POST,"hastakayit");
//            var_dump($protokonogetir);
            if($kayitet){
                echo "2.";
            }
        }

    }
     exit();
}else{ ?>

<form   id="poliklinik-kayit-form" action="javascript:void(0);" style="display:flex;">

 <div class="easyui-panel" title="Poliklinik Seçiniz:" style="width: 33%; height: 100%;">

<input type="hidden" name="tc_id" closed="false" value="<?php echo $_GET["tc_id"]; ?>"/>

     <input name="outpatient_id" id="outpatient_id_set" type="hidden">

    <ul class="easyui-datalist" id="poliklinik-datalist"  lines="true" style="width:100%;height:400px;">
    <?php $yetkilioldugumbirimler = birimyetkiselect($_SESSION["id"]);
    $sql = sql_select("select * from units where unit_type= '0' $yetkilioldugumbirimler order by department_name asc ");
    foreach ($sql as $item) { ?>
        <li value="<?php echo $item["id"]; ?>"><?php echo $item["department_name"]; ?></li>
    <?php } ?>
    </ul>

 </div>

<div class="easyui-panel"  title="Doktor Seçiniz:" style="width: 34%; height: 100%;">

    <ul class="easyui-datalist" name="doctor" id="doktor-datalist" lines="true" style="width:100%;height:400px;">
        <li>
            Önce Poliklinik Bilgisi Giriniz...
        </li>
    </ul>

</div>


 <div class="easyui-panel" title="Diğer Bilgiler:" style=" width: 34%; height: 100%;">

<select class="easyui-combobox" label="Vaka Türü:" labelWidth="100" name="reason_arrival" id="gelis_sebebi" style="width: 100%;">
    <?php $sql = sql_select("select * from transaction_definitions where definition_type='GELIS_SEBEBI'");
    foreach ($sql as $rowa) { ?>
        <option value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["definition_name"]; ?></option>
    <?php } ?>
</select>

<select class="easyui-combobox" label="Hasta Trürü:" labelWidth="100" id="patient_type" name="patient_type" required style="width: 100%;">
    <option class="bg-danger text-white" selected disabled>Seçiniz</option>
    <?php $sql = sql_select("select * from transaction_definitions where definition_type='HASTA_TURU'");
    foreach ($sql as $rowa) { ?>
        <option value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["definition_name"]; ?></option>
    <?php } ?>
</select>

<select class="easyui-combobox" label="Acil Geliş Şekli" labelWidth="100" id="emergency_arrival_type" name="emergency_arrival_type" required style="width: 100%;">
    <option class="bg-danger text-white" selected disabled>Seçiniz</option>
    <?php $sql = sql_select("select * from transaction_definitions where definition_type='ACIL_GELIS_SEKLI'");
    foreach ((array)$sql as $rowa) { ?>
        <option value="<?php echo $rowa["id"]; ?>"><?php echo $rowa["definition_name"]; ?></option>
    <?php } ?>
</select>

  <select class="easyui-combobox" label="Triaj Kodu:" labelWidth="100" id="trage_code" name="trage_code" required style="width: 100%;">
      <option class="bg-danger text-white" selected disabled>Seçiniz</option>
      <?php $sql = sql_select("select * from transaction_definitions where definition_type='TRIAJ_KODU'");
      foreach ($sql as $item){ ?>
      <option value="<?php echo $item['definition_code']; ?>"><?php echo $item['definition_name']; ?></option>
      <?php } ?>
  </select>

<select class="easyui-combobox" label="Kabul Şekli:"  labelWidth="100" id="patient_admission_type" name="patient_admission_type" title="Hasta Kabul Şekli" required style="width: 100%;">
    <option class="bg-danger text-white" selected disabled>Seçiniz</option>
    <?php $sql = sql_select("select * from transaction_definitions where definition_type='HASTA_KABUL_SEKLI'");
    foreach ($sql as $item){ ?>
        <option value="<?php echo $item['id']; ?>"><?php echo $item['definition_name']; ?></option>
    <?php } ?>
</select>

<select class="easyui-combobox" label="İzole Et:" labelWidth="100" style="width: 100%;">
    <option class="bg-danger text-white" disabled selected>Seçiniz</option>
    <option value="1">Evet</option>
    <option value="2">Hayır</option>
</select>

<footer style="float: right;">
  <button class="easyui-linkbutton poliklinik-kaydet" id="poliklinik-kaydet"  value="Kayıt Oluştur">Kayıt Oluştur</button>
</footer>

 </div>
     </form>

<script type="text/javascript">
    $(document).ready(function () {

        function selectOptionsByNameContains(elementId, str){
            var ele = document.getElementById(elementId);
            var n = ele.options.length;
            for(var i = 0; i < n; i++){
                var text = ele.options[i].innerHTML
                if(text.includes(str)){
                    $("#" + elementId).children().eq(i).attr('selected', 'selected');
                }
            }
        }

        $("#gelis_arac").change(function () {
            var gelis_arac = $(this).val();
            $.get("ajax/kayitsirasindaverisorgula.php?islem=gelisaracsorgula", {veri: gelis_arac}, function (e) {
                if(e){ var el=document.queryselector("#aracsorgulaicerik")
                    el.style.csstext = "display:block;" }
                $('#aracsorgulaicerik').html(e);
            });
        });

        $("#poliklinik-kaydet").on("click", function(){ // buton idli elemana tıklandığında
            var gonderilenform = $("#poliklinik-kayit-form").serialize(); // idsi gonderilenform olan formun içindeki tüm elemanları serileştirdi ve gonderilenform adlı değişken oluşturarak içine attı
            var poliklinik = $('#poliklinik-datalist').datalist('getSelected');
            var doktor= $('#doktor-datalist').datalist('getSelected');
            var doctor = doktor.id;
            gonderilenform += "&doctor=" + doctor;

            $.ajax({
                url:'ajax/hastaislemleri/poliklinikkayit.php?islem=poliklinik-kayit', // serileştirilen değerleri ajax.php dosyasına
                type:'POST', // post metodu ile 
                data:gonderilenform, // yukarıda serileştirdiğimiz gonderilenform değişkeni 
                success:function(gonderVeri){ // gonderme işlemi başarılı ise e değişkeni ile gelen değerleri aldı
                    if(gonderVeri==1){
                        $("#hasta-kayit-liste").load("ajax/hasta-kayit-kabul/hasta-kayit-kabul-liste.php?islem=kayit-olan-hastalar");
                        alertify.alert("Kayıt bilgisi","Kayıt işlemi başarılı "); 
                        document.getElementById("gonderilenform").reset();

                    }else{
                        alertify.alert("Uyarı","Kayıt işleminde hata oluştu. Hata detayı : " + gonderVeri);
                    }
                }
            });

            $('.window-poliklinik').dialog('close');

        });


        $('#poliklinik-datalist').datalist({
            onClickRow: function(index, row) {
               var poliklinik_id = row.value;

                $('#outpatient_id_set').val(poliklinik_id);

                $('#doktor-datalist').datalist({
                    valueField: 'id',
                    textField: 'name_surname',
                    url:'ajax/hastaislemleri/hasta-kayit-detay.php?islem=polikliniklere-kayitli-doktorlar&poliklinik_id='+poliklinik_id+'',
                });
            }
        });

        $('#doktor-datalist').datalist({
            onClickRow: function(index, row) {
                var id = $(row).attr('id');

            }
        });

    });
</script>   
<?php } ?>