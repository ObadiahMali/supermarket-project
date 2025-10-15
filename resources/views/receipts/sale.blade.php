<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Receipt #{{ $sale->id }}</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 26px;
            color: #222;
            text-transform: uppercase;
        }
        .header p {
            margin: 4px 0;
            font-size: 13px;
            color: #555;
        }
        .section {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f8f8f8;
        }
        .totals {
            margin-top: 15px;
            text-align: right;
        }
        .totals p {
            margin: 4px 0;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 13px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Mali's Supermarket</h1>
        <p>Receipt #{{ $sale->id }} &bullet; {{ $sale->created_at->format('Y-m-d H:i') }}</p>
        <p>Cashier: {{ $sale->user->name ?? 'N/A' }} &bullet; Payment: {{ ucfirst($sale->payment_method) }}</p>
    </div>

    <div class="section">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cart as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>{{ number_format($item['price']) }}</td>
                    <td>{{ number_format($item['price'] * $item['quantity']) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="totals">
        <p>Grand Total: UGX {{ number_format($total) }}</p>
        <p>Amount Received: UGX {{ number_format($total + $change) }}</p>
        <p>Change Due: UGX {{ number_format($change) }}</p>
    </div>

    <div class="footer">
        <p>🛍️ Thank you for shopping at Mali's Supermarket!</p>
        <p>We appreciate your business and look forward to serving you again.</p>
        <p>📍 Located in Makindye &bullet; Open daily from 8am to 10pm</p>
    </div>
</body>
</html>