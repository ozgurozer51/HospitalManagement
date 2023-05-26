<?php include "../../../controller/fonksiyonlar.php"; ?>
<?php include "rapor-form-printer-basligi.php"; ?>

<table class="table table-border table-sm" id="print-form-ana-icerik-dom">
    <tbody class="print-form-ana-icerik">

    </tbody>
</table>

<script>
    var data = $("#ff-<?php echo $_POST['uniquid']; ?>").serializeArray();

    data.forEach(function (currentElement, index, array) {
    var control_class_radiobutton = $("#"+ currentElement.name).hasClass("easyui-radiobutton");

    if (control_class_radiobutton == true){

        var explain = $("."+currentElement.name).html();
        var value = $("#"+currentElement.name).parent().find(".textbox-label-checked").html();

        if (explain!=undefined){
            $(".print-form-ana-icerik").append("<tr><td class='border-solid'>"+explain+"</td> <td class='border-solid fw-bold'>"+value+"</td></tr>");
        }else if(value!=undefined){
        }

    }else{

        var explain = $("#"+currentElement.name).attr("label-p");
        var value = $("#"+currentElement.name).val();
        if (explain!=undefined){
            $(".print-form-ana-icerik").append("<tr><td class='border-solid'>"+explain+"</td> <td class='fw-bold border-solid'>"+value+"</td></tr>");
        }else if(value==undefined){
        }

    }




    });


</script>