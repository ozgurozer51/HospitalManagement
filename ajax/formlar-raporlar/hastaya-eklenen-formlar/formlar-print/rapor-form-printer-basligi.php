<?php
include "../../../controller/fonksiyonlar.php";  ?>

<table class="table table-border w-100 borer-701" style="border-style: solid;">
    <tbody>
    <tr>
        <td class="borer-701"><img src="assets/img/saglik-bakanligi-logo.png" width="50"></td>
        <td class="borer-701"><?php echo  hastane_adi; ?>  <div id="report-form-name-dom"><?php echo $_POST['form_adi']; ?></div> </td>
    </tr>
    </tbody>
</table>

<table class="table table-striped table-sm w-100 borer-701 nowrap display" id="print-hasta-adi-dom" style="font-size: 13px;">
    <tbody>
    <tr>
        <td>Hasta Adı:</td>
        <td class="hasta-adi-soyadi-print-<?php echo $_POST['uniquid']; ?> fw-bold"></td>
        <td>Hasta TC:</td>
        <td class="hasta-tc-no-print-<?php echo $_POST['uniquid']; ?> fw-bold"></td>
    </tr>

    <tr>
        <td>Protokol No:</td>
        <td class="hasta-protokol-no-print-<?php echo $_POST['uniquid']; ?> fw-bold"></td>
        <td>Birimi:</td>
        <td class="hasta-birimi-print-<?php echo $_POST['uniquid']; ?> fw-bold"></td>
    </tr>

    <tr>
        <td>Hasta Doktoru:</td>
        <td class="hasta-doktoru-print-<?php echo $_POST['uniquid']; ?> fw-bold"></td>
        <td>Tarih:</td>
        <td class="hasta-rapor-tarihi-print-<?php echo $_POST['uniquid']; ?> fw-bold"></td>
    </tr>
    </tbody>
</table>


<script>
    var uniquid = $(".w").find('form:visible').attr("uniquid");

$('.hasta-adi-soyadi-print-'+uniquid).html($('#hasta-adi-soyadi-'+uniquid).val());
$('.hasta-tc-no-print-'+uniquid).html($('#hasta-tc-no-'+uniquid).val());

$('.hasta-protokol-no-print-'+uniquid).html($('#hasta-protokol-no-'+uniquid).val());
$('.hasta-birimi-print-'+uniquid).html($('#hasta-birim-id-'+uniquid).val());

$('.hasta-doktoru-print-'+uniquid).html($('#hasta-doktoru-'+uniquid).val());
$('.hasta-rapor-tarihi-print-'+uniquid).html($('#hasta-rapor-tarihi-'+uniquid).val());
</script>