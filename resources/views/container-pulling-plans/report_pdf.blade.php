<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Container Pulling Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif; /* Font that supports many characters */
            font-size: 12px;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
        }
        .header p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 40px;
            width: 100%;
        }
        .signature-box {
            width: 45%;
            display: inline-block;
            margin-top: 50px;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Container Pulling Report</h1>
            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($pullingDate)->format('d F Y') }}</p>
            
            <!-- ===== START: Added Shop Display ===== -->
            <p><strong>Shop:</strong> {{ $shop ?? 'All Shops' }}</p>
            <!-- ===== END: Added Shop Display ===== -->
        </div>

        <table>
            <thead>
                <tr>
                    <th class="text-center">Order</th>
                    <th>Container No.</th>
                    <th>Size</th>
                    <th>Location</th>
                    <th>Destination</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plans as $plan)
                <tr>
                    <td class="text-center">{{ $plan->pulling_order }}</td>
                    <td>{{ $plan->containerOrderPlan?->container?->container_no ?? 'N/A' }}</td>
                    <td>{{ $plan->containerOrderPlan?->container?->size ?? 'N/A' }}</td>
                    <td>{{ $plan->containerOrderPlan?->containerStock?->yardLocation?->location_code ?? 'N/A' }}</td>
                    <td>{{ $plan->destination }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No pulling plans for this date.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            <div class="signature-box" style="float: left;">
                <div class="signature-line"></div>
                <p>Driver Signature</p>
            </div>
            <div class="signature-box" style="float: right;">
                <div class="signature-line"></div>
                <p>Security Guard Signature</p>
            </div>
        </div>
    </div>
</body>
</html>