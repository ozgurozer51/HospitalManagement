<?php
include "../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
$q=$_GET["q"];
$protokolno=$_GET["protokolno"];
$TIP=$_GET["TIP"];
$kullanicininidsi=$_SESSION["ID"];
$tcno=$_GET["tcno"];
$KULLANICI_ID=$_SESSION["ID"];
if(strlen($q)<2){
    echo "En az 2 karakter veya rakam girmeniz gerekmektedir";
}else{

    ?>

    <!-- alertifyjs Css -->
    <table id="pakettanimlarinigetir" class="table table-striped table-bordered"    >
        <thead>
        <tr>
            <th>Grup Kodu</th>
            <th>İşlem Grubu</th>
            <th>SGK Grubu</th>
            <th>İşlem</th>
        </tr>
        </thead>

        <tbody>

        <?php

        $sql ="SELECT * FROM ISLEM_TANIMLARI WHERE TANIM_TIPI='ISLEM_GRUBU' AND DURUM='1'   FETCH FIRST 7 ROWS ONLY";
        $stid = oci_parse($baglanti, $sql);
        oci_execute($stid);
        while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
            $TANIMEKI= $row["TANIM_EKI"];
            ?>
            <tr>
                <th   scope="row" id='<?PHP ECHO $row["ID"]; ?>'><?PHP ECHO $row["ID"]; ?></th>
                <td   id='<?PHP ECHO $row["ID"]; ?>'><?PHP ECHO $row["TANIM_ADI"]; ?></td>
                <td   id='<?PHP ECHO $row["ID"]; ?>'><?PHP ECHO islemtanimgetir($row["TANIM_EKI"]); ?></td>
                <th><button type="button"      protokolno="<?php echo $protokolno; ?>" TIP="GRUPISTEMEKLE"   id="<?PHP ECHO $row["ID"]; ?>"    class="gruppaketekle btn btn-primary waves-effect waves-light">Ekle</button></th>

            </tr>
        <?PHP } ?>
        </tbody>
    </table>


    <!-- notification init -->

    <script>

        $(document).ready(function () {

            $(".gruppaketekle").click(function () {
                var protokolno = $(this).attr('protokolno');
                var id = $(this).attr('id');
                var TIP = $(this).attr('TIP');
                $.get("ajax/hastayaistemekle.php", {protokolno: protokolno, id: id, TIP: TIP}, function (getVeri) {

                    $("#hizlipaketlerigetir .close").click();
                    $('.modal-backdrop').remove();

                    // $('#hastaistemlericerik').html(getVeri);
                    $.get( "ajax/hastadetay/hastaistemlerilistele.php", { protokolno:protokolno },function(getVeri){

                        $('#hastaistemlericerik').html(getVeri);

                    });
                });
            });
        });
    </script>
<?php } ?>