
<style>
    .barcode{
        width: 2.50cm;
        height: 5cm;

    }
    @media print {
        @page {
            size: 10cm;,
        margin:0;
        }

        html, body{
            margin: 0;
        }
    }
</style>



<div>
    <svg class="barcode" id="barcode_draw"></svg>
</div>

<div>
    <svg class="barcode" id="barcode_draw"></svg>
</div>

<div>
    <svg class="barcode" id="barcode_draw"></svg>
</div>

<div>
    <svg class="barcode" id="barcode_draw"></svg>
</div>

<script src="assets/jsBarcode.js"></script>


<script>
    JsBarcode("#barcode_draw", "0123456789");

    window.print()
</script>


