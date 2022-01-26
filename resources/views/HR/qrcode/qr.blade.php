<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script>
        window.Laravel = <?php echo json_encode(['csrfToken' => csrf_token(),
            'locale' => config('app.locale'),
        ]); ?>;
    </script>

    <script src="https://unpkg.com/html2canvas@1.4.0/dist/html2canvas.js"></script>
    <style>
      button {
        display: inline-block;
        background-color: #7b38d8;
        border-radius: 10px;
        border: 4px double #cccccc;
        color: #eeeeee;
        text-align: center;
        font-size: 28px;
        padding: 20px;
        width: 200px;
        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;
        -o-transition: all 0.5s;
        transition: all 0.5s;
        cursor: pointer;
        margin: 5px;
      }
    </style>
</head>
<body>
    <div style="padding:20px; justify-content: center; text-align:center; margin: auto;">
        <div id="capture" style="padding: 10px; width:200px; height:230px; left: 0; right: 0; display: initial;">
            {!!$qrcode!!}
            <p style="font-weight: 700; font-family:Verdana, Geneva, Tahoma, sans-serif">{{$qr}}</p>
        </div>
        <button onclick="saveimg()">Download</button>
        {{-- {!! QrCodeGenerator::size(300)->generate($qr)!!}
        {!! QrCodeGenerator::format('png')->size(200)->generate($qr, storage_path('qrcode/'.$qr."png"))!!} !!} --}}
    </div>
</body>
<script src="{{ asset('js/app.js') }}" defer></script>
<script>
    function saveimg() {
        html2canvas(document.querySelector("#capture"), {
            allowTaint: false,
            logging:true,
            onrendered: function (canvas) {
                document.body.appendChild(canvas);
            }
        }).then(canvas => {
            var a = document.createElement('a');
            a.href = canvas.toDataURL('image/jpeg').replace('image/jpeg', 'image/octet-stream');
            a.download = 'qrcode.jpg';
            a.click();
        });
    }

</script>
</html>
