<?php
include "../../../controller/fonksiyonlar.php";
$baglanti = veritabanibaglantisi();
session_start();
ob_start();
yetkisorgula($_SESSION["username"],"islemdetayekle");
?>
<div class="modal-dialog" style=" width: 75%; max-width: 95%; ">

    <!-- modal content-->
    <form class="modal-content"  action="" method="post" >
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <?php
            if ($_GET["islemid"]!= "") {
                echo '<h4 class="modal-title">detay düzenle</h4>';
                $birimbilgisi = tekil("transaction_definitions", "id", $_GET["islemid"]);
                //             var_dump($doktorbilgisi);
                extract($birimbilgisi);
            } else {
                echo '<h4 class="modal-title">detay ekle</h4>';
            }  ?>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-12 row">
                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">durumu  </label>
                        <select name="durum" class="form-control" >
                            <option <?php if ($durum == 0) {echo "selected";} ?> value="0">pasif</option>
                            <option <?php if ($durum == 1) {echo "selected";} ?> value="1">aktif</option>
                        </select>
                    </div>
                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">detay adı</label>
<!--                        --><?php //var_dump($_GET); ?>
                        <input type="text" class="form-control" name="islem_adi" value="<?php echo $islem_adi; ?>" id="basicpill-firstname-input">
                        <?php if ($_GET["islemid"]!= "") { ?>

                <input type="hidden" class="form-control" name="id" value="<?php echo $_GET["islemid"]; ?>" id="basicpill-firstname-input">
            <?php }else{?>
                <input type="hidden" class="form-control" name="islem_id" value="<?php echo $_GET["eklenecekislemid"]; ?>" id="basicpill-firstname-input">
            <?php } ?>

                     </div>
                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">ücreti</label>
                        <input type="number"  class="form-control" name="ucreti" value="<?php echo $ucreti; ?>" id="basicpill-firstname-input">
                        <small style="color: red;">ayrak olarak , kullanın</small>
                    </div>
                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">birimi</label>
                        <input type="number" class="form-control" name="birimi" value="<?php echo $birimi; ?>" id="basicpill-firstname-input">
                    </div>
                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">dr %</label>
                        <input type="number" class="form-control" name="dr_yuzdesi" value="<?php echo $dr_yuzdesi; ?>" id="basicpill-firstname-input">
                    </div>
                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">maaliyet</label>
                        <input type="text" class="form-control" name="maaliyet" value="<?php echo $maaliyet; ?>" id="basicpill-firstname-input">
                    </div>
                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">özel kod</label>
                        <input type="text" class="form-control" name="ozel_kod" value="<?php echo $ozel_kod; ?>" id="basicpill-firstname-input">
                    </div>
                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">dr zorunlu</label>
                        <select name="dr_zorunlu" class="form-control" >
                            <option <?php if ($dr_zorunlu == 0) {echo "selected";} ?> value="0">değil</option>
                            <option <?php if ($dr_zorunlu == 1) {echo "selected";} ?> value="1">zorunlu</option>
                        </select>
                    </div>
                        <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">konsültasyon mu ?</label>
                        <select name="konsultasyon" class="form-control" >
                            <option <?php if ($konsultasyon == 0) {echo "selected";} ?> value="0">hayır</option>
                            <option <?php if ($konsultasyon == 1) {echo "selected";} ?> value="1">evet</option>
                        </select>
                    </div>
                     <div class="col-2">
                         <label for="basicpill-firstname-input" class="form-label">açıklama</label>
                         <input type="text" class="form-control" name="aciklama" value="<?php echo $aciklama; ?>" id="basicpill-firstname-input">
                     </div>

                     <div class="col-2">
                         <label for="basicpill-firstname-input" class="form-label">gss günlük kota?</label>
                         <input type="number" class="form-control" name="gss_gunluk_kota" value="<?php echo $gss_gunluk_kota; ?>" id="basicpill-firstname-input">
                     </div>
                        <div class="col-2">
                         <label for="basicpill-firstname-input" class="form-label">sgk'ya gitsin mi ?</label>
                          <select name="sgk_gitsin" class="form-control" >
                                <option <?php if ($sgk_gitsin == 0) {echo "selected";} ?> value="0">hayır</option>
                                <option <?php if ($sgk_gitsin == 1) {echo "selected";} ?> value="1">evet</option>
                            </select>
                     </div>
                        <div class="col-2">
                         <label for="basicpill-firstname-input" class="form-label">katkı payı mı ?</label>
                          <select name="katki_payi" class="form-control" >
                                <option <?php if ($katki_payi == 0) {echo "selected";} ?> value="0">hayır</option>
                                <option <?php if ($katki_payi == 1) {echo "selected";} ?> value="1">evet</option>
                            </select>
                     </div>
                        <div class="col-2">
                         <label for="basicpill-firstname-input" class="form-label">medula onayı zorunlu mu ?</label>
                          <select name="medula_onayi_zorunlu" class="form-control" >
                                <option <?php if ($medula_onayi_zorunlu == 0) {echo "selected";} ?> value="0">hayır</option>
                                <option <?php if ($medula_onayi_zorunlu == 1) {echo "selected";} ?> value="1">evet</option>
                            </select>
                     </div>
                <div class="col-2">
                    <label for="basicpill-firstname-input" class="form-label">medula kota sayısı</label>
                    <input type="number" class="form-control" name="medula_kota_sayisi" value="<?php echo $medula_kota_sayisi; ?>" id="basicpill-firstname-input">
                </div>
               <div class="col-2">
                                <label for="basicpill-firstname-input" class="form-label">ameliyat grubu</label>
                   <select name="amel_grubu" class="form-control" >
                       <option <?php if ($amel_grubu == 0) {echo "selected";} ?> value="0">hayır</option>
                       <option <?php if ($amel_grubu == 1) {echo "selected";} ?> value="1">evet</option>
                   </select>
                </div>
               <div class="col-2">
                                <label for="basicpill-firstname-input" class="form-label">sağlık kurulu bölümü</label>
                   <select name="saglik_kurulu_bolum" class="form-control" >
                       <option <?php if ($saglik_kurulu_bolum == 0) {echo "selected";} ?> value="0">hayır</option>
                       <option <?php if ($saglik_kurulu_bolum == 1) {echo "selected";} ?> value="1">evet</option>
                   </select>
                </div>
                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">hizmet türü</label>
                        <input type="text" class="form-control" name="hizmet_turu" value="<?php echo $hizmet_turu; ?>" id="basicpill-firstname-input">
                    </div>

                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">sağlıknete gitsinmi?</label>
                        <select name="sagliknet_gitsin" class="form-control" >
                            <option <?php if ($sagliknet_gitsin == 0) {echo "selected";} ?> value="0">hayır</option>
                            <option <?php if ($sagliknet_gitsin == 1) {echo "selected";} ?> value="1">evet</option>
                        </select>
                    </div>

                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">yaş alt limit</label>
                        <input type="number" class="form-control" name="yas_alt_limit" value="<?php echo $yas_alt_limit; ?>" id="basicpill-firstname-input">
                    </div>
                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">yaş üst limit</label>
                        <input type="number" class="form-control" name="yas_ust_limit" value="<?php echo $yas_ust_limit; ?>" id="basicpill-firstname-input">
                    </div>

                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">müstehaklık kontrol</label>
                        <select name="mustehaklik_kontrol" class="form-control" >
                            <option <?php if ($mustehaklik_kontrol == 0) {echo "selected";} ?> value="0">hayır</option>
                            <option <?php if ($mustehaklik_kontrol == 1) {echo "selected";} ?> value="1">evet</option>
                        </select>
                    </div>
                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">günlük toplam kota</label>
                        <input type="number" class="form-control" name="gunluk_top_kota" value="<?php echo $gunluk_top_kota; ?>" id="basicpill-firstname-input">
                    </div>
                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">fark tavanında i̇stisna</label>
                        <select name="fark_tavaninda_istisna" class="form-control" >
                            <option <?php if ($fark_tavaninda_istisna == 0) {echo "selected";} ?> value="0">hayır</option>
                            <option <?php if ($fark_tavaninda_istisna == 1) {echo "selected";} ?> value="1">evet</option>
                        </select>
                    </div>
                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">icd 10 kodu</label>
                        <input type="text" class="form-control" name="icd_on_kodu" value="<?php echo $icd_on_kodu; ?>" id="basicpill-firstname-input">
                    </div>
                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">anestezi zorunlu mu ?</label>
                        <select name="anestezi_zorunlu" class="form-control" >
                            <option <?php if ($anestezi_zorunlu == 0) {echo "selected";} ?> value="0">hayır</option>
                            <option <?php if ($anestezi_zorunlu == 1) {echo "selected";} ?> value="1">evet</option>
                        </select>
                    </div>
                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">i̇şlemin fiyatı değiştirilmesin </label>
                        <select name="islemin_fiyati_degismesin" class="form-control" >
                            <option <?php if ($islemin_fiyati_degismesin == 0) {echo "selected";} ?> value="0">hayır</option>
                            <option <?php if ($islemin_fiyati_degismesin == 1) {echo "selected";} ?> value="1">evet</option>
                        </select>
                    </div>
                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">açıklama zorunlu</label>
                        <select name="aciklama_zorunlu" class="form-control" >
                            <option <?php if ($aciklama_zorunlu == 0) {echo "selected";} ?> value="0">hayır</option>
                            <option <?php if ($aciklama_zorunlu == 1) {echo "selected";} ?> value="1">evet</option>
                        </select>
                    </div>
                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">kdv orani</label>
                        <input type="number" class="form-control" name="kdv_orani" value="<?php echo $kdv_orani; ?>" id="basicpill-firstname-input">
                    </div>
                    <div class="col-2">
                        <label for="basicpill-firstname-input" class="form-label">hakedişte görünsün</label>
                        <select name="hakediste_gorunsun" class="form-control" >
                            <option <?php if ($hakediste_gorunsun == 0) {echo "selected";} ?> value="0">hayır</option>
                            <option <?php if ($hakediste_gorunsun == 1) {echo "selected";} ?> value="1">evet</option>
                        </select>
                    </div>


                    <div class="modal-footer">
            <?php
            if ($_GET["islemid"] != "") { ?>

                <a onclick="return confirm('kullancıyı silmek istediğinize emin misiniz ? ');"       href="?islem=sil&islemid=<?php echo $_GET["islemid"]; ?>"><button type="button" class="btn btn-default"   style=" margin-bottom: 0; margin-left: 5px; background: red; color: white; margin: 0; margin-left: 10px; ">sil</button></a>


                <input class="btn btn-primary w-md justify-content-end" name="islemdetayguncelle" type="submit"   value="düzenle"/>
                <?php
            } else { ?>
                <input class="btn btn-primary w-md justify-content-end" name="islemdetaykaydet" type="submit"   value="kaydet"/>
                <?php
            } ?>
            <button type="button" class="btn btn-default" data-dismiss="modal" style=" margin-bottom: 0; margin-left: 5px; background: red; color: white; margin: 0; margin-left: 10px; ">kapat</button>
        </div>
    </form>
</div>

</div>
</div>