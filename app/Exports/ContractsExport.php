<?php

namespace App\Exports;

use App\Models\Contract;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\DB;

class ContractsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Contract::with(['client', 'address']);

        // Apply filters
        if (isset($this->filters['search']) && $this->filters['search']) {
            $query->where('contract_num', 'like', "%{$this->filters['search']}%");
        }

        if (isset($this->filters['mobile']) && $this->filters['mobile']) {
            $query->whereHas('client', function ($q) {
                $q->where('mobile_number', 'like', "%{$this->filters['mobile']}%")
                  ->orWhere('alternate_mobile_number', 'like', "%{$this->filters['mobile']}%");
            });
        }

        if (isset($this->filters['status']) && $this->filters['status'] !== 'all') {
            if ($this->filters['status'] === 'active') {
                // For active status, we want contracts that are truly active (not expired and not superseded)
                $query->where('status', 'active')
                      ->where('end_date', '>', now())
                      ->whereNotExists(function ($subQuery) {
                          $subQuery->select(DB::raw(1))
                              ->from('contracts as newer_contracts')
                              ->whereColumn('newer_contracts.address_id', 'contracts.address_id')
                              ->where('newer_contracts.status', 'active')
                              ->whereColumn('newer_contracts.created_at', '>', 'contracts.created_at');
                      });
            } elseif ($this->filters['status'] === 'expired') {
                // For expired status, we want contracts that are expired OR superseded by newer contracts
                $query->where(function ($q) {
                    $q->where('end_date', '<=', now())
                      ->orWhere(function ($subQ) {
                          $subQ->where('status', 'active')
                               ->whereExists(function ($existsQuery) {
                                   $existsQuery->select(DB::raw(1))
                                       ->from('contracts as newer_contracts')
                                       ->whereColumn('newer_contracts.address_id', 'contracts.address_id')
                                       ->where('newer_contracts.status', 'active')
                                       ->whereColumn('newer_contracts.created_at', '>', 'contracts.created_at');
                               });
                      });
                });
            } else {
                // For cancelled status, use the original logic
                $query->where('status', $this->filters['status']);
            }
        }

        if (isset($this->filters['type']) && $this->filters['type'] !== 'all') {
            $query->where('type', $this->filters['type']);
        }

        if (isset($this->filters['start_date']) && $this->filters['start_date']) {
            $query->where('start_date', '>=', $this->filters['start_date']);
        }

        if (isset($this->filters['end_date']) && $this->filters['end_date']) {
            $query->where('end_date', '<=', $this->filters['end_date']);
        }

        if (isset($this->filters['expiring_months']) && $this->filters['expiring_months']) {
            $months = (int) $this->filters['expiring_months'];
            $today = now()->startOfDay();
            $futureDate = now()->addMonths($months)->endOfDay();
            
            $query->where('end_date', '>=', $today)
                  ->where('end_date', '<=', $futureDate);
        }

        // Note: Renewed contract logic is now handled within the status filtering above

        return $query->latest()->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Contract Number',
            'Client Name',
            'Mobile Number',
            'Alternate Mobile',
            'Address',
            'Contract Type',
            'Status',
            'Start Date',
            'End Date',
            'Duration (Months)',
            'Total Amount (KWD)',
            'Paid Amount (KWD)',
            'Remaining Amount (KWD)',
            'Commission Amount (KWD)',
            'Commission Type',
            'Commission Recipient',
            'Commission Date',
            'Details',
        ];
    }

    /**
     * @param Contract $contract
     * @return array
     */
    public function map($contract): array
    {
        return [
            $contract->contract_num,
            $contract->client->name,
            $contract->client->mobile_number,
            $contract->client->alternate_mobile_number,
            $contract->address->full_address,
            $contract->type,
            $contract->dynamic_status,
            $contract->start_date,
            $contract->end_date,
            $contract->duration_months,
            number_format($contract->total_amount, 2),
            number_format($contract->paid_amount, 2),
            number_format($contract->remaining_amount, 2),
            number_format($contract->commission_amount, 2),
            $contract->commission_type,
            $contract->commission_recipient,
            $contract->commission_date,
            $contract->details,
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 15, // Contract Number
            'B' => 20, // Client Name
            'C' => 15, // Mobile Number
            'D' => 15, // Alternate Mobile
            'E' => 30, // Address
            'F' => 10, // Contract Type
            'G' => 10, // Status
            'H' => 12, // Start Date
            'I' => 12, // End Date
            'J' => 15, // Duration
            'K' => 15, // Total Amount
            'L' => 15, // Paid Amount
            'M' => 15, // Remaining Amount
            'N' => 15, // Commission Amount
            'O' => 15, // Commission Type
            'P' => 20, // Commission Recipient
            'Q' => 15, // Commission Date
            'R' => 30, // Details
        ];
    }
}
