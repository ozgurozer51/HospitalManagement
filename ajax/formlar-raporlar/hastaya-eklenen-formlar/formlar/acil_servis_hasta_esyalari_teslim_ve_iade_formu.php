<div class="easyui-layout" data-options="fit:true" style="width:100%;height:100%;">

    <div data-options="region:'east', collapsed:true , split:true , hideCollapsedContent:false" title="Hastaya Eklenmiş Form Ve Raporlar" style="width:25%;">
        <?php include "../tablo-template.php"; ?>
    </div>

    <div data-options="region:'center',title:''">
        <form id="ff-<?php echo $uniqid; ?>" uniquid="<?php echo $uniqid; ?>" print-url="<?php echo $_GET['print_url']; ?>" table-name="<?php echo $_GET['name']; ?>" form-adi="<?php echo $_GET['form_adi']; ?>">

            <input class="form-table-name" type="hidden" value-2="<?php echo $_GET['name']; ?>">

            <div class="input-align">

                <div class="easyui-panel" id="rapor-form-hasta-bilgi" title="Muayene Edilenin" style="width:100%;max-width:100%;">
                    <?php include "../hasta-bilgi-template.php"; ?>
                </div>

            </div>

            <div style="display: flex;">
                <div class="easyui-panel" title="OLAY İLE İLGİLİ GENEL BİLGİLER" style="width:100%;max-width:100%;">
                    <div class="ms-2">

                        <input class="easyui-textbox" id="clothing_items" name="clothing_items" labelWidth=250 data-options="label:'Giyin Eşyaları:'"                label-p="Giyin Eşyaları:" style="width:100%">
                        <input class="easyui-textbox" id="precious_items" name="precious_items" labelWidth=250 data-options="label:'Kıymetli Eşyalar:'"              label-p="Kıymetli Eşyalar:" style="width:100%">
                        <input class="easyui-textbox" id="precious_items" name="precious_items" labelWidth=250 data-options="label:'Diğer Eşyalar (Protez vs.):'"    label-p="Diğer Eşyalar (Protez vs.):" style="width:100%">
                        <input class="easyui-textbox" id="receiving_personnel_name" name="receiving_personnel_name" labelWidth=250 data-options="label:'Teslim Alan Personelin Adı:'"    label-p="Teslim Alan Personelin Adı:" style="width:100%">
                        <input class="easyui-textbox" id="delivery_security_guard" name="delivery_security_guard" labelWidth=250 data-options="label:'Teslim Alan Güvenlik Personeli Adı:'"    label-p="Teslim Alan Güvenlik Personeli Adı:" style="width:100%">
                        <input class="easyui-textbox" id="witness_name_1" name="witness_name_1" labelWidth=250 data-options="label:'Şahit Adı 1:'"    label-p="Şahit Adı 1:" style="width:100%">
                        <input class="easyui-textbox" id="witness_name_2" name="witness_name_2" labelWidth=250 data-options="label:'Şahit Adı 2:'"    label-p="Şahit Adı 2:" style="width:100%">
                        <input class="easyui-textbox" id="delivery_security_guard_name" name="delivery_security_guard_name" labelWidth=250 data-options="label:'Teslim Eden Güvenlik Görevlisi Adı:'"    label-p="Teslim Eden Güvenlik Görevlisi Adı:" style="width:100%">
                        <input class="easyui-textbox" id="receiver_name" name="receiver_name" labelWidth=250 data-options="label:'Teslim Alan Kişi Adı:'"    label-p="Teslim Alan Kişi Adı:" style="width:100%">

                    </div>
                </div>

            </div>

        </form>
    </div>
</div>