<table>
    <thead>
        <tr>
            <th colspan="2" style="font-weight: bold; background-color: #4F46E5; color: white; text-align: center;">System Overview Report</th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #c0c0c0; width: 250px;">Report Metric</th>
            <th style="font-weight: bold; background-color: #c0c0c0; width: 150px;">Total Value</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Total Users</td>
            <td>{{ $reportData['total_users'] ?? 0 }}</td>
        </tr>
        <tr>
            <td>Total Vendors</td>
            <td>{{ $reportData['total_vendors'] ?? 0 }}</td>
        </tr>
        <tr>
            <td>Total Products</td>
            <td>{{ $reportData['total_products'] ?? 0 }}</td>
        </tr>
        <tr>
            <td>Total Orders</td>
            <td>{{ $reportData['total_orders'] ?? 0 }}</td>
        </tr>
        <tr>
            <td>Total Sales ($)</td>
            <td>{{ number_format($reportData['total_sales'] ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td>Pending Payouts ($)</td>
            <td>{{ number_format($reportData['pending_payouts'] ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td>Approved Payouts ($)</td>
            <td>{{ number_format($reportData['approved_payouts'] ?? 0, 2) }}</td>
        </tr>
    </tbody>
</table>

<table><tr><td></td></tr><tr><td></td></tr></table>

<table>
    <thead>
        <tr>
            <th colspan="4" style="font-weight: bold; background-color: #10B981; color: white; text-align: center;">Payout Requests List</th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #e0e0e0;">ID</th>
            <th style="font-weight: bold; background-color: #e0e0e0;">Vendor Name</th>
            <th style="font-weight: bold; background-color: #e0e0e0;">Amount</th>
            <th style="font-weight: bold; background-color: #e0e0e0;">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($payouts ?? [] as $payout)
            <tr>
                <td>{{ $payout->id }}</td>
                <td>{{ $payout->user->name ?? 'N/A' }}</td>
                <td>{{ number_format($payout->amount, 2) }}</td>
                <td>{{ ucfirst($payout->status) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
