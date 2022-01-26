<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .container {

        }

        .header {
            background-color: #0d1033;
            color: #fff
        }

        .header_content {
            width: 400px;
            padding: 10px
        }

        .header_content_1 {
            /* width: 400px; */
            padding: 10px;
            margin-left: 0;
            margin-top: -100;
        }

        .header_content h1 {
            font-style: italic;
        }

        .asde_logo {
            width: 110px;
            float: right;
        }

        .side {
            text-align: right;
        }

        .side span {
            font-size: 11px;
        }

        .info_proposal table {
            width: 100%;
        }

        .info_proposal table thead th{
            text-align: left;
        }
        .brand-section{
           background-color: #0d1033;
           padding: 10px 40px;
        }
        .logo{
            width: 50%;
        }

        .row{
            display: flex;
            flex-wrap: wrap;
        }
        .col-6{
            width: 50%;
            flex: 0 0 auto;
        }
        .text-white{
            color: #fff;
        }
        .company-details{
            float: right;
            text-align: right;
        }
        .body-section{

        }
        .heading{
            font-size: 20px;
            margin-bottom: 08px;
        }
        .sub-heading{
            color: #262626;
            margin-bottom: 05px;
        }
        table{
            background-color: #fff;
            width: 100%;
            border-collapse: collapse;
        }
        table thead tr{
            border: 1px solid #111;
            background-color: #f2f2f2;
        }
        table td {
            vertical-align: middle !important;
            text-align: center;
        }
        table th, table td {
            padding-top: 08px;
            padding-bottom: 08px;
        }

        table tbody tr td {
            font-size: 11px;
        }
        table thead th {
            font-size: 13px;
        }
        .table-bordered{
            box-shadow: 0px 0px 5px 0.5px gray;
        }
        .table-bordered td, .table-bordered th {
            border: 1px solid #dee2e6;
        }
        .text-right{
            justify-content: right;
        }
        .w-20{
            width: 20%;
        }
        .float-right{
            float: right;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- <img src="{{ public_path('/img/logo.png') }}" style="width: 100px; height: 100px"> --}}
        <div class="header">
            <div class="header_content">
                <h1>PAYROLL</h1>
            </div>
            {{-- <div class="header_content_1 side">
                <span>ASDEC BUILDERS CORPORATION</span><br/>
                <span>225 BANLAT ROAD TANDANG SORA, QUEZON CITY</span><br/>
                <span>TEL NO. 3455-5115 * TELEFAX NO. 7000-9800 * CEL NOS. (Sun) 0925-864-5310 (Globe) 09661430147</span><br/>
                <span>EMAIL ADD: <span class="invoice_company_email">asdec_mae@yahoo.com</span> * <span class="invoice_company_email">asdecbuilders@yahoo.com</span></span>
            </div> --}}
        </div>
        <hr>
        <table>
            <thead>
                <tr>
                    <th>NAME</th>
                    <th>Date</th>
                    <th>Salary</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$name}}</td>
                    <td>{{$date}}</td>
                    <td>{{$salary}}</td>
                </tr>
            </tbody>
        </table>
        <table>
            <thead>
                <tr>
                    <th>FROM</th>
                    <th>TO</th>
                    <th>RATE</th>
                    <th>HOURS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$from}}</td>
                    <td>{{$to}}</td>
                    <td>{{$rate}} per day</td>
                    <td>{{$hours}}</td>
                </tr>
            </tbody>
        </table>
        <hr>
        <div class="body-section">
            <h3 class="heading">Record</h3>
            <table class="table-bordered">
                <thead>
                    <tr>
                        <th>DATE</th>
                        <th>STATUS</th>
                        <th>RATE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>{{$item->date}}</td>
                            <td>{{$item->status}}</td>
                            <td>{{$item->salary}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
