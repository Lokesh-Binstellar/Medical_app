<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 8px;
            border: 1px solid #000;
            text-align: left;
        }

        .table-borderless td,
        .table-borderless th {
            border: none !important;
            padding: 0;
        }

        .no-margin {
            margin: 0;
        }
    </style>
</head>

<body>
    <!-- Logo -->
    <table class="table-borderless" style="margin-bottom: 10px;">
        <tr>
            <td style="text-align: left;">
                @php
                    $logo = base64_encode(file_get_contents(public_path('gomeds.png')));
                @endphp

                <img src="data:image/png;base64,{{ $logo }}" alt="Pharmacy Logo" height="60">

            </td>
        </tr>
    </table>

    <!-- Invoice ID and Date (reduced spacing) -->
    <table class="table-borderless">
        <tr>
            <td>
                <p class="no-margin"><strong>Invoice #{{ $order->order_id }}</strong></p>
            </td>
            <td style="text-align: right;">
                <p class="no-margin"><strong>Date:</strong> {{ $order->created_at->format('d M Y') }}</p>
            </td>
        </tr>
    </table>

    @if ($order->pharmacy)
        <table style="margin-top: 10px; margin-bottom: 20px;">
            <tr>
                <td><strong>Pharmacy Name:</strong> {{ $order->pharmacy->pharmacy_name }}</td>
                <td style="text-align: right;"><strong>Pharmacy Contact:</strong> {{ $order->pharmacy->phone }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Drug License No.:</strong> {{ $order->pharmacy->license }}</td>
            </tr>
        </table>
    @endif

    @if ($order->patient)
        <div style="margin-top: 20px;border: 1px solid #000; padding: 10px; border-radius: 5px;">
            <strong>Patient Details:</strong><br>
            Name: {{ preg_replace('/\s+/', ' ', trim($order->patient->name)) }}<br>
            Birth Date: {{ \Carbon\Carbon::createFromFormat('d/m/Y', $order->patient->birth_date)->format('d-m-Y') }}<br>
            Gender: {{ ucfirst($order->patient->gender) }}
        </div>
    @endif




    <table style="width: 100%; margin-top: 20px;" class="table-borderless">
        <tr>
            <!-- Invoice To (Left) -->
            <td style="width: 50%; vertical-align: top;">
                <strong>Invoice To:</strong><br>
                {{ $order->customer->firstName }} {{ $order->customer->lastName }}<br>
                {{ $order->delivery_address }}<br>
                {{ $order->customer->mobile_no }}<br>
                @if (!empty($order->customer->email))
                    {{ $order->customer->email }}
                @endif
            </td>

            <!-- Delivery & Payment Info (Right) -->
            <td style="width: 50%; vertical-align: top; text-align: right;">
                <strong>Delivery Method:</strong><br>
                {{ ucfirst(str_replace('_', ' ', $order->delivery_options)) }}<br><br>

                <strong>Payment Method:</strong><br>
                {{ ucfirst(str_replace('_', ' ', $order->payment_option)) }}
            </td>
        </tr>
    </table>



    @php
        $products = json_decode($order->product_details, true);
    @endphp

    <!-- Medicine Table + Price Summary Merged -->
    <h3 style="margin-bottom: 5px;">Ordered Medicines</h3>
    <table>
        <thead>
            <tr>
                <th>Medicine</th>
                <th>MRP</th>
                <th>Discount</th>
                <th>Net Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                @php
                    $netPrice = $product['mrp'] - $product['discount'];
                @endphp
                <tr>
                    <td>{{ $product['medicine_name'] }}</td>
                    <td>₹{{ number_format($product['mrp'], 2) }}</td>
                    <td>₹{{ number_format($netPrice, 2) }}</td>
                    <td>₹{{ number_format($product['discount'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Items Price:</strong></td>
                <td>₹{{ number_format($order->items_price, 2) }}</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Platform Fees:</strong></td>
                <td>₹{{ number_format($order->platform_fees, 2) }}</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Delivery Charges:</strong></td>
                <td>₹{{ number_format($order->delivery_charges, 2) }}</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Total Price:</strong></td>
                <td><strong>₹{{ number_format($order->total_price, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>
    <!-- Footer -->
    <div style="position: fixed; bottom: 20px; left: 0; width: 100%; text-align: center; font-size: 12px; color: #555;">
        &copy; {{ date('Y') }} Gomeds 24|7. All rights reserved.
    </div>


</body>

</html>