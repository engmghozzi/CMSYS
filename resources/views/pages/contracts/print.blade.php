<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contracts Report</title>
         <style>
         @page {
             size: landscape;
             margin: 10mm;
         }
         
         body {
             font-family: Arial, sans-serif;
             font-size: 10px;
             line-height: 1.2;
             color: #333;
             margin: 0;
             padding: 10px;
         }
        
                 .header {
             text-align: center;
             margin-bottom: 15px;
             border-bottom: 2px solid #333;
             padding-bottom: 10px;
         }
         
         .header h1 {
             margin: 0;
             font-size: 18px;
             color: #333;
         }
         
         .header p {
             margin: 3px 0 0 0;
             color: #666;
             font-size: 9px;
         }
        
                 .filters {
             background-color: #f8f9fa;
             padding: 8px;
             border-radius: 3px;
             margin-bottom: 10px;
         }
         
         .filters h3 {
             margin: 0 0 5px 0;
             font-size: 10px;
             color: #333;
         }
        
                 .filter-item {
             display: inline-block;
             margin-right: 15px;
             margin-bottom: 3px;
             font-size: 8px;
         }
         
         .filter-label {
             font-weight: bold;
             color: #555;
         }
        
                 .summary {
             background-color: #e3f2fd;
             padding: 8px;
             border-radius: 3px;
             margin-bottom: 10px;
         }
         
         .summary h3 {
             margin: 0 0 5px 0;
             font-size: 10px;
             color: #1976d2;
         }
        
                 .summary-stats {
             display: table;
             width: 100%;
             table-layout: fixed;
         }
         
         .stat-item {
             display: table-cell;
             text-align: center;
             padding: 3px;
             vertical-align: top;
         }
         
         .stat-value {
             font-size: 12px;
             font-weight: bold;
             color: #1976d2;
             display: block;
         }
         
         .stat-label {
             font-size: 8px;
             color: #666;
             display: block;
         }
        
                 table {
             width: 100%;
             border-collapse: collapse;
             margin-top: 15px;
             table-layout: fixed;
         }
         
         th, td {
             border: 1px solid #ddd;
             padding: 4px 2px;
             text-align: left;
             vertical-align: top;
             overflow: hidden;
             text-overflow: ellipsis;
             white-space: nowrap;
         }
         
         th {
             background-color: #f5f5f5;
             font-weight: bold;
             font-size: 9px;
         }
         
         td {
             font-size: 8px;
         }
         
         /* Column widths for landscape layout */
         th:nth-child(1), td:nth-child(1) { width: 8%; }  /* Contract # */
         th:nth-child(2), td:nth-child(2) { width: 12%; }  /* Client */
         th:nth-child(3), td:nth-child(3) { width: 8%; }  /* Mobile */
         th:nth-child(4), td:nth-child(4) { width: 15%; }  /* Address */
         th:nth-child(5), td:nth-child(5) { width: 5%; }   /* Type */
         th:nth-child(6), td:nth-child(6) { width: 6%; }    /* Status */
         th:nth-child(7), td:nth-child(7) { width: 7%; }    /* Start Date */
         th:nth-child(8), td:nth-child(8) { width: 7%; }   /* End Date */
         th:nth-child(9), td:nth-child(9) { width: 6%; }    /* Duration */
         th:nth-child(10), td:nth-child(10) { width: 8%; } /* Total Amount */
         th:nth-child(11), td:nth-child(11) { width: 6%; }  /* Paid */
         th:nth-child(12), td:nth-child(12) { width: 8%; }  /* Remaining */
         th:nth-child(13), td:nth-child(13) { width: 6%; }  /* Commission */
        
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
             margin-top: 15px;
             text-align: center;
             font-size: 8px;
             color: #666;
             border-top: 1px solid #ddd;
             padding-top: 5px;
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
                    <span class="filter-label">Start Date ≥:</span> {{ \App\Helpers\DateHelper::formatDate($filters['start_date']) }}
                </div>
            @endif
            @if(isset($filters['end_date']) && $filters['end_date'])
                <div class="filter-item">
                    <span class="filter-label">End Date ≤:</span> {{ \App\Helpers\DateHelper::formatDate($filters['end_date']) }}
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
                    <td>{{ $contract->start_date ? \App\Helpers\DateHelper::formatDate($contract->start_date) : '' }}</td>
                    <td>{{ $contract->end_date ? \App\Helpers\DateHelper::formatDate($contract->end_date) : '' }}</td>
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
         <p>This report was generated on {{ \App\Helpers\DateHelper::formatDate(now()) }} from the Contract Management System by {{ auth()->user()->name }} ({{ auth()->user()->email }}).</p>
     </div>
</body>
</html>
