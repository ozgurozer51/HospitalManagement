<?php
include "../../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();

date_default_timezone_set('europe/istanbul');
$simdikitarih = date('y/m/d h:i:s');

$islem=$_GET['islem'];


if ($islem=="geneltahsilatlistesi"){ ?>
    <table id="example"
           class="table  table-bordered table-sm table-hover "
           style=" background:white;width: 100%;">
        <thead>
        <tr>
            <th scope="col">makbuz no</th>
            <th scope="col">makbuz tarihi</th>
            <th scope="col">i̇ptal tarihi</th>
            <th scope="col">ödeme türü</th>
            <th scope="col">vezne adı</th>
            <th scope="col">hasta</th>
            <th scope="col">i̇şlem türü</th>

        </tr>
        </thead>
        <tbody>

        <?php $tc_kimlik=$_POST['tc_kimlik'];
        $veznebirim=$_POST['vezne_birim'];
        $veznedar=$_POST['veznedar'];
        $tarih=$_POST['makbuz_zamani'];
        $hastakayit=tek("select * from hasta_kayit where tc_kimlik=$tc_kimlik order by id desc");
        $hastaid=$hastakayit['id'];
        if (empty($veznebirim) || empty($veznedar)){
            $hastalistgetir = "select * from vezne where  (makbuz_zamani like '$tarih%' and hasta_id='$hastaid') ";
        }else if (empty($tc_kimlik) ){
            $hastalistgetir = "select * from vezne where  (makbuz_zamani like '$tarih%' and veznedar='$veznedar')";
        }else{
            $hastalistgetir = "select * from vezne ";
        }

        $hello = verilericoklucek($hastakayit);
        foreach ($hello as $rowa) { ?>

            <tr>
                <td><?php echo $rowa["makbuz_numarasi"] ?></td>
                <td><?php echo $rowa["makbuz_zamani"] ?></td>
                <td><?php echo $rowa["iptal_zamani"] ?></td>
                <td><?php echo islemtanimekgetir("odeme_turu",$rowa["tahsil_turu"]); ?></td>
                <td><?php echo birimgetir($rowa["vezne_birim_kodu"]); ?></td>
                <?php
                $hastakayitbilgi=tekil("hasta_kayit","id",$rowa["hasta_id"]);
                $hastabilgi=tekil("hastalar","tc_kimlik",$hastakayitbilgi['tc_kimlik']);
                ?>
                <td><?php echo $hastabilgi['adi'].' '.$hastabilgi['soyadi']; ?></td>
                <td><?php echo $rowa["islem_tipi"] ?></td>
            </tr>

        <?php } ?>

        </tbody>
    </table>

<?php } ?>