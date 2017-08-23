<script type="text/javascript" src="/js/qrcode.min.js"></script>
<h1>{{$code}}</h1>
<p id="qrcode"></p>
<script type="text/javascript">
    new QRCode(document.getElementById("qrcode"), "{{$code}}");
</script>