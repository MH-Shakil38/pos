<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        #invoice-POS {
            box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
            padding: 2.5mm;
            margin: 0 auto;
            width: 100mm;
            height: 100mm;
            background: #FFF;


            ::selection {
                background: #f31544;
                color: #FFF;
            }

            ::moz-selection {
                background: #f31544;
                color: #FFF;
            }

            h1 {
                font-size: 1.5em;
                color: #222;
            }

            h2 {
                font-size: .9em;
            }

            h3 {
                font-size: 1.2em;
                font-weight: 300;
                line-height: 2em;
            }

            p {
                font-size: .7em;
                color: #666;
                line-height: 1.2em;
            }

            #top,
            #mid,
            #bot {
                /* Targets all id with 'col-' */
                border-bottom: 1px solid #EEE;
            }

            #top {
                min-height: 100px;
            }

            #mid {
                min-height: 71px;
            }

            #bot {
                min-height: 50px;
            }

            #top .logo {
                //float: left;
                height: 60px;
                width: 60px;
                background: url({{ public_path($settings->logo) }}) no-repeat;
                background-size: 60px 60px;
            }

            .clientlogo {
                float: left;
                height: 60px;
                width: 60px;
                background: url(http://michaeltruong.ca/images/client.jpg) no-repeat;
                background-size: 60px 60px;
                border-radius: 50px;
            }

            .info {
                display: block;
                //float:left;
                margin-left: 0;
            }

            .title {
                float: right;
            }

            .title p {
                text-align: right;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            td {
                //padding: 5px 0 5px 15px;
                //border: 1px solid #EEE
            }

            .tabletitle {
                //padding: 5px;
                font-size: .5em;
                background: #EEE;
            }

            .service {
                border-bottom: 1px solid #EEE;
            }

            .item {
                width: 24mm;
            }

            .itemtext {
                font-size: .5em;
            }

            #legalcopy {
                margin-top: 5mm;
            }

            .site_name {}
        }
    </style>
</head>

<body>

    <div id="invoice-POS">

        {{-- <center id="top">
            <div class="logo"></div>
            <div class="info site_name" >
                <h2 style="margin: 0px; font-size: 26px; color: #6a6a6a; padding: 0px">{{ $settings->name }}</h2>
            </div><!--End Info-->
        </center><!--End InvoiceTop--> --}}

        <div id="mid">
            <div class="info">
                <h2
                    style="    margin: 0 auto;
    text-align: center;
    font-size: 58px;
        margin-top: 45px !important;
    font-family: emoji;
    color: #616161;">
                    Abaya Elite</h2>
                <p style="margin: 0px;
    text-align: center;
    font-size: 15px;"> <span>www.facebook.com/abayaelite </p>
                <p style="margin: 5px;
    text-align: center;
    font-size: 18px;">Phone: 01960504000</p>
                <table style="marging-bottom:5px">
                    <tr style="text-align: center">
                        <td class="col-4" style="color: #4e4e4e;
    font-size: 10px;"><strong>Customer Name:</strong>
                            {{ $invoice->customer->name }}</td>
                        <td class="col-4" style="color: #4e4e4e;
    font-size: 10px;"> <strong>Phone:</strong>
                            {{ $invoice->customer->phone }}</td>
                        <td class="col-4" style="color: #4e4e4e;
    font-size: 10px;"> <strong>Location:</strong>
                            {{ $invoice->customer->address }}</td>
                    </tr>
                </table>
                {{-- <p style="margin-top:5px;margin-bottom:2px ">
                    Name : {{ $invoice->customer->name }}</br>
                    Phone : {{ $invoice->customer->phone }}</br>
                    Address : {{ $invoice->customer->address }}</br>
                </p> --}}
            </div>
        </div><!--End Invoice Mid-->

        <div id="bot" style="padding: 20px">
            <div id="table">
                <table>
                    <tr>
                        <td class="col-4"></td>
                        <td class="col-4"><span style="color: #6a6a6a;
                            font-size: 18px;
                            text-align: right;
                            float: right;
                            margin-right: 35px;">Bill Amount</span> </td>
                        <td class="col-4" style="text-align:center"> <strong style="    font-size: 16px;
    color: #626262;"> {{ $invoice->total }}</strong></td>
                    </tr>
                    <tr style="margin-top: 10px">
                        <td class="col-4"><span style="color: #6a6a6a; font-size: 20px;"></span> </td>
                        <td class="col-4"> <span style="color: #6a6a6a;
                            font-size: 18px;
                            text-align: right;
                            float: right;
                            margin-right: 35px;">Delivery Charge</span></td>
                        <td class="col-4" style="text-align:center"> <strong style="    font-size: 16px;
    color: #626262;"> {{ $invoice->shipping->shipping_cost ?? 0 }}</strong></td>
                    </tr>

                    <tr style="margin-top: 10px">
                        <td class="col-4"><strong style="color: #6a6a6a; font-size: 20px;"></strong> </td>
                        <td class="col-4"> <strong style="color: #6a6a6a;
    font-size: 18px;
    text-align: right;
    float: right;
    margin-right: 35px;">Total</strong></td>
                        <td class="col-4" style="text-align:center"> <strong style="    font-size: 16px;
    color: #626262;"> {{ $invoice->total + $invoice->shipping->shipping_cost ?? 0 }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="3"> <span style="font-size: 10px;color: #4d4c4c;">
                            Note:
                            @forelse ($products as $key => $product)
                            {{ str_replace('(,)', '', $product->name) }} qty-{{ $product->quantity }},

                            @empty

                            @endforelse ()
                            </span></td>
                    </tr>

                    <tr>
                        <td colspan="3" style="text-align: center"> <span style="font-size: 16px;
    color: #000000;
    /* border: 1px dashed; */
    padding: 2px;
    font-family: math;border-top:1px solid #ddd;margin-top:5px"> <span>Customer ID:</span> <strong>{{ $invoice->shipping->consignment_id ?? '_____________'}}</strong>, <span>Marcent ID:</span> <strong>47645</strong> </span></td>
                    </tr>
                    <tr>
                        <td colspan="3"> <strong style="font-size: 25px;color:#4d4c4c">----------------------------------------</strong> </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <div style="margin: 0 auto;text-align:center">
        <button id="downloadImage" style="margin: 10px">Download</button>
        <button id="printImage" style="margin: 10px">Print</button>
    </div>

    <script>
        document.getElementById("downloadImage").addEventListener("click", function() {
            const invoice = document.getElementById("invoice-POS");

            html2canvas(invoice, {
                scale: 2
            }).then(canvas => {
                const imgData = canvas.toDataURL("image/png");
                const link = document.createElement("a");
                link.href = imgData;
                link.download = "invoice.png";
                link.click();
            });
        });
    </script>

<script>
    document.getElementById("printImage").addEventListener("click", function() {
        const invoice = document.getElementById("invoice-POS").innerHTML;
        const printWindow = window.open("", "", "width=800,height=600");

        printWindow.document.write(`
            <html>
                <head>
                    <title>Invoice Print</title>
                    <style>
                        body { font-family: Arial, sans-serif; }
                    </style>
                </head>
                <body onload="window.print(); window.close();">
                    ${invoice}
                </body>
            </html>
        `);

        printWindow.document.close();
    });
</script>

    {{-- <button onclick="printInvoice()">Print Invoice</button>

    <script>
        function printInvoice() {
            var printContents = document.getElementById("invoice-POS").innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload(); // Reload to restore original content
        }
    </script> --}}

</body>

</html>
