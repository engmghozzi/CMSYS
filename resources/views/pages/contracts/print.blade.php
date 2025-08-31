<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contracts Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        
        .filters {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .filters h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #333;
        }
        
        .filter-item {
            display: inline-block;
            margin-right: 20px;
            margin-bottom: 5px;
        }
        
        .filter-label {
            font-weight: bold;
            color: #555;
        }
        
        .summary {
            background-color: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .summary h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #1976d2;
        }
        
        .summary-stats {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }
        
        .stat-item {
            text-align: center;
            margin: 5px;
        }
        
        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #1976d2;
        }
        
        .stat-label {
            font-size: 10px;
            color: #666;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        
        th {
            background-color: #f5f5f5;
            font-weight: bold;
            font-size: 11px;
        }
        
        td {
            font-size: 10px;
        }
        
        .status-active {
            color: #28a745;
            font-weight: bold;
        }
        
        .status-expired {
            color: #dc3545;
            font-weight: bold;
        }
        
        .status-cancelled {
            color: #6c757d;
            font-weight: bold;
        }
        
        .amount {
            text-align: right;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Contracts Report</h1>
        <p>Generated on {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    @if(!empty($filters) && array_filter($filters))
        <div class="filters">
            <h3>Applied Filters:</h3>
            @if(isset($filters['search']) && $filters['search'])
                <div class="filter-item">
                    <span class="filter-label">Search:</span> {{ $filters['search'] }}
                </div>
            @endif
            @if(isset($filters['mobile']) && $filters['mobile'])
                <div class="filter-item">
                    <span class="filter-label">Mobile:</span> {{ $filters['mobile'] }}
                </div>
            @endif
            @if(isset($filters['status']) && $filters['status'] !== 'all')
                <div class="filter-item">
                    <span class="filter-label">Status:</span> {{ ucfirst($filters['status']) }}
                </div>
            @endif
            @if(isset($filters['type']) && $filters['type'] !== 'all')
                <div class="filter-item">
                    <span class="filter-label">Type:</span> {{ $filters['type'] }}
                </div>
            @endif
            @if(isset($filters['start_date']) && $filters['start_date'])
                <div class="filter-item">
                    <span class="filter-label">Start Date ≥:</span> {{ $filters['start_date'] }}
                </div>
            @endif
            @if(isset($filters['end_date']) && $filters['end_date'])
                <div class="filter-item">
                    <span class="filter-label">End Date ≤:</span> {{ $filters['end_date'] }}
                </div>
            @endif
            @if(isset($filters['expiring_months']) && $filters['expiring_months'])
                <div class="filter-item">
                    <span class="filter-label">Expiring in:</span> {{ $filters['expiring_months'] }} month(s)
                </div>
            @endif
        </div>
    @endif

    <div class="summary">
        <h3>Summary Statistics</h3>
        <div class="summary-stats">
            <div class="stat-item">
                <div class="stat-value">{{ $contracts->count() }}</div>
                <div class="stat-label">Total Contracts</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $contracts->where('dynamic_status', 'active')->count() }}</div>
                <div class="stat-label">Active</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $contracts->where('dynamic_status', 'expired')->count() }}</div>
                <div class="stat-label">Expired</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ $contracts->where('dynamic_status', 'cancelled')->count() }}</div>
                <div class="stat-label">Cancelled</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ number_format($contracts->sum('total_amount'), 2) }} KWD</div>
                <div class="stat-label">Total Amount</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ number_format($contracts->sum('paid_amount'), 2) }} KWD</div>
                <div class="stat-label">Total Paid</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">{{ number_format($contracts->sum('remaining_amount'), 2) }} KWD</div>
                <div class="stat-label">Total Remaining</div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Contract #</th>
                <th>Client</th>
                <th>Mobile</th>
                <th>Address</th>
                <th>Type</th>
                <th>Status</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Duration</th>
                <th class="amount">Total Amount</th>
                <th class="amount">Paid</th>
                <th class="amount">Remaining</th>
                <th class="amount">Commission</th>
            </tr>
        </thead>
        <tbody>
            @forelse($contracts as $contract)
                <tr>
                    <td>{{ $contract->contract_num }}</td>
                    <td>{{ $contract->client->name }}</td>
                    <td>{{ $contract->client->mobile_number }}</td>
                    <td>{{ Str::limit($contract->address->full_address, 30) }}</td>
                    <td>{{ $contract->type }}</td>
                    <td class="status-{{ $contract->dynamic_status }}">
                        {{ ucfirst($contract->dynamic_status) }}
                    </td>
                    <td>{{ $contract->start_date }}</td>
                    <td>{{ $contract->end_date }}</td>
                    <td>{{ $contract->duration_months }}m</td>
                    <td class="amount">{{ number_format($contract->total_amount, 2) }}</td>
                    <td class="amount">{{ number_format($contract->paid_amount, 2) }}</td>
                    <td class="amount">{{ number_format($contract->remaining_amount, 2) }}</td>
                    <td class="amount">{{ number_format($contract->commission_amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" style="text-align: center; padding: 20px; color: #666;">
                        No contracts found matching the criteria.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>This report was generated on {{ now()->format('Y-m-d H:i:s') }} from the Contract Management System.</p>
    </div>
</body>
</html>
