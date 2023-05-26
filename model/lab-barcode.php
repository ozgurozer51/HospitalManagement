<button id="print-button">Barkdo Yazdır</button>


<script src="assets/jsBarcode.js"></script>

<script>
    $("#print-button").off().on("click", function () {

        let barcode = window.open("https://system.ihars.com/lab_barcode_print.php");
        setTimeout(function(){
            barcode.close()
        }, 2000);
    });

</script>

