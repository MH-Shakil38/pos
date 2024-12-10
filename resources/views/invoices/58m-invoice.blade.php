<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings->shop_name }}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        @font-face {
            font-family: 'SolaimanLipi';
            src: url('{{ public_path('fonts/SolaimanLipi.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'SolaimanLipi', sans-serif;
            font-size: 16px;
            /* Adjust the font size if needed */
        }

        .invoice-container {
            font-family: 'SolaimanLipi', sans-serif;
            font-size: 14px;
            /* Make sure the font size is readable */
        }

        @font-face {
            font-family: 'SolaimanLipi';
            src: url('storage/fonts/SolaimanLipi.ttf') format('truetype');
        }

        body {
            font-family: 'SolaimanLipi', sans-serif;
            /* background: #f9f9f9; */
        }

        .invoice-container {
            max-width: 100%;
            margin: 0 auto;
        }

        .header,
        .footer {
            text-align: center;
        }

        .logo img {
            width: 150px;
            height: auto;
        }

        .company-details,
        .customer-details {
            margin-bottom: 10px;
        }

        .company-details p,
        .customer-details p {
            margin: 5px 0;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .invoice-table th,
        .invoice-table td {
            border: 1px solid #ddd;
            /* padding: 8px; */
            text-align: center;
        }

        .invoice-table th {
            background-color: #f2f2f2;
        }

        .total-section {
            width: 100%;
            text-align: right;
        }

        .total-section table {
            width: 50%;
            margin-top: 10px;
            border: 0;
            text-align: right;
        }

        .total-section td {
            padding: 0px;
        }
    </style>
</head>


<body style="width:300px;margin:0 auto;">
    <div class="invoice-container" style="">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <img src="{{ public_path($settings->logo) }}" style="margin: -20px;widht:100px;height:70px">
            </div>
            <div class="company-details" style="border-bottom: 1px solid #6d6d6d;line-height:0.8rem;">
                <p><strong>{{ $settings->shop_name }}</strong></p>
                <p>Email: exoticagro@gmail.com</p>
                <p>Phone: 01708436477</p>
                <p>Address: 482, South Goran, Khillgoan, Dhaka,
                    Bangladesh, 1219
                    </p>
            </div>
        </div>



        <!-- Customer Details -->
        <div class="customer-details" style="margin: 0px;font-size:14px;line-height:0.8rem;margin-bottom:10px">
           <p> <strong>Currier number: </strong> {{ $invoice->shipping->currier_number }}</p>
            <p><strong>Name:</strong> {{ $invoice->customer->name }}</p>
            <p><strong>Phone:</strong> {{ $invoice->customer->phone }}</p>
            <p><strong>Address:</strong> {{ $invoice->customer->address }}</p>
        </div>

        <!-- Order Details -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <td>#</td>
                    <td> <strong>Name</strong> </td>
                    <td><strong>QTY</strong></td>
                    <td><strong>Price</strong></td>
                    <td><strong>Total</strong></td>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $key => $product)
                    <tr>
                        <td class="col-1">{{ $key + 1 }}</td>
                        <td class="col-5 text-1" style="font-size: 11px">{{ $product->name }} </td>
                        <td class="col-2 text-center" style="font-size: 14px">{{ $product->quantity }}</td>
                        <td class="col-1 text-center" style="font-size: 12px">{{ $product->price }}/-</td>
                        <td class="col-2 " style="font-size: 12px">{{ $product->total }}/-</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4" style="text-align: right"><strong>Total:</strong></td>
                    <td>{{ $invoice->total }}/-</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: right"><strong>Shipping Cost:</strong></td>
                    <td>{{ $invoice->shipping->shipping_cost }}</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: right"><strong>Paid:</strong></td>
                    <td>{{ $invoice->total - $invoice->due }}/-</td>
                </tr>


                <tr>
                    <td colspan="4" style="text-align: right"><strong>Due:</strong></td>
                    <td>{{ $invoice->due }}/-</td>
                </tr>
            </tbody>
        </table>

        <!-- Total Section -->
        {{-- <div class="" style="text-align: right">
        <table>
            <tr>
                <td>Shipping Cost:</td>
                <td>{{ $order->delivery_cost }}</td>
            </tr>
            <tr>
                <td><strong>Total:</strong></td>
                <td><strong>{{ $order->orders->sum('total') + $order->delivery_cost ?? 0 }}</strong></td>
            </tr>
        </table>
    </div> --}}

        <!-- Footer -->

        <div class="footer" style="border-top: 1px solid #6d6d6d">
            <p style="font-size: 11px">Thank you for your purchase! If you have any questions, feel free to contact us
                at  exoticagro@gmail.com  or 01708436477.</p>
            <p style="font-size: 11px; color: #555; margin-top: 5px;">&copy; {{ date('Y') }}
                {{ $settings->shop_name }}. All rights reserved.</p>
        </div>

        <footer class="text-center mt-4">

            <div class="btn-group btn-group-sm d-print-none"> <a href="javascript:window.print()"
                    class="btn btn-light border text-black-50 shadow-none"><i class="fa fa-print"></i> Print</a> <a
                    href="" class="btn btn-light border text-black-50 shadow-none"><i class="fa fa-download"></i>
                    Download</a> </div>
        </footer>
    </div>

</body>

</html>
