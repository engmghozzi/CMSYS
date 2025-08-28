# Contract Expiration System

## Overview

The Contract Expiration System automatically manages contract lifecycles by detecting when contracts reach their end date and updating their status accordingly. This ensures that your HVAC business maintains accurate contract statuses and can proactively manage renewals.

## Features

### 🚀 **Automatic Expiration**
- **Daily Checks**: Automatically runs every day to check for expired contracts
- **Status Updates**: Changes contract status from 'active' to 'expired'
- **Comprehensive Logging**: All expiration actions are logged with detailed information
- **Transaction Safety**: Uses database transactions to ensure data integrity

### 📊 **Smart Contract Management**
- **End Date Detection**: Identifies contracts that have passed their end date
- **Client Information**: Shows client and address details for expired contracts
- **Overdue Calculation**: Calculates how many days contracts are overdue
- **Bulk Processing**: Handles multiple expired contracts efficiently

### 🛡️ **Safety Features**
- **Dry Run Mode**: Test expiration logic without making changes
- **Confirmation Prompts**: Requires user confirmation before processing
- **Error Handling**: Gracefully handles failures and provides detailed error messages
- **Rollback Protection**: Automatically rolls back changes if errors occur

## Commands

### 1. **Main Expiration Command**

```bash
# Expire contracts that have reached their end date
php artisan contracts:expire

# Test mode - show what would be expired without making changes
php artisan contracts:expire --dry-run

# Force expiration (useful for testing)
php artisan contracts:expire --force

# Use a specific date for testing
php artisan contracts:expire --date=2025-01-15
```

### 2. **Testing Command**

```bash
# Test the expiration system and see contract statuses
php artisan test:contract-expiration
```

## How It Works

### **Daily Schedule**
The system is configured to run automatically every day via Laravel's task scheduler:

```php
// In app/Console/Kernel.php
protected function schedule(Schedule $schedule): void
{
    $schedule->command('contracts:expire')->daily();
}
```

### **Expiration Logic**
1. **Query Active Contracts**: Finds all contracts with status 'active'
2. **Check End Dates**: Identifies contracts where `end_date <= current_date`
3. **Update Status**: Changes status from 'active' to 'expired'
4. **Log Actions**: Records all changes in the logs table
5. **Update Timestamps**: Sets `updated_by` to system user

### **Logging Details**
Every expiration action is logged with:
- **Action Type**: 'status_change'
- **Description**: "Contract automatically expired due to end date (YYYY-MM-DD)"
- **Old Values**: Previous status (usually 'active')
- **New Values**: New status ('expired')
- **User**: System user (ID: 1)
- **Timestamp**: When the expiration occurred

## Usage Examples

### **Basic Expiration**
```bash
# Check for expired contracts
php artisan contracts:expire

# Output will show:
# 🚀 Contract Expiration Check Started...
# Checking contracts against date: 2025-01-15
# 📋 Found 3 contract(s) that should be expired:
# [Table showing contract details]
# Do you want to proceed with expiring these contracts? (yes/no)
```

### **Dry Run Testing**
```bash
# See what would happen without making changes
php artisan contracts:expire --dry-run

# Output will show:
# 🔍 DRY RUN MODE - No contracts were actually updated.
# Run without --dry-run to actually expire the contracts.
```

### **Testing with Specific Date**
```bash
# Test expiration logic for a past date
php artisan contracts:expire --date=2024-12-31 --dry-run
```

### **Contract Status Testing**
```bash
# See all contract statuses and expiration information
php artisan test:contract-expiration

# Output will show:
# 🧪 Testing Contract Expiration System...
# Current date: 2025-01-15
# 🚨 EXPIRED CONTRACTS (2):
# ⚠️  EXPIRING SOON (1):
# ✅ ACTIVE CONTRACTS (15):
```

## Database Schema

The system works with the existing `contracts` table:

```sql
contracts:
- id (primary key)
- contract_num (unique contract number)
- client_id (foreign key to clients table)
- address_id (foreign key to addresses table)
- start_date (contract start date)
- duration_months (contract duration in months)
- end_date (calculated end date)
- status (enum: 'active', 'expired', 'cancelled')
- total_amount (contract total value)
- created_by (user who created the contract)
- updated_by (user who last updated the contract)
- created_at, updated_at (timestamps)
```

## Configuration

### **System User ID**
The command uses user ID 1 for system updates. If you need to change this:

```php
// In app/Console/Commands/ExpireContracts.php
$contract->update([
    'status' => 'expired',
    'updated_by' => 1 // Change this to your system user ID
]);
```

### **Scheduling Frequency**
To change how often the command runs:

```php
// In app/Console/Kernel.php
protected function schedule(Schedule $schedule): void
{
    // Run every hour instead of daily
    $schedule->command('contracts:expire')->hourly();
    
    // Run at specific time
    $schedule->command('contracts:expire')->dailyAt('09:00');
    
    // Run multiple times per day
    $schedule->command('contracts:expire')->twiceDaily(9, 17);
}
```

### **Expiration Thresholds**
The system considers contracts expired when:
- `end_date <= current_date`
- `status = 'active'`

You can modify this logic in the command if needed.

## Monitoring and Maintenance

### **Daily Monitoring**
1. **Check Command Output**: Review daily expiration logs
2. **Monitor Logs Table**: Check for expiration-related entries
3. **Review Expired Contracts**: Look for renewal opportunities

### **Weekly Tasks**
1. **Review Expiring Soon**: Check contracts expiring within 30 days
2. **Payment Status**: Verify payment status of expired contracts
3. **Renewal Planning**: Plan renewal campaigns for expiring contracts

### **Monthly Tasks**
1. **Performance Review**: Check expiration command success rates
2. **Data Cleanup**: Consider archiving old expired contracts
3. **System Health**: Verify scheduler is running correctly

## Troubleshooting

### **Common Issues**

#### **Command Not Running**
```bash
# Check if scheduler is running
php artisan schedule:work

# Check schedule list
php artisan schedule:list

# Test command manually
php artisan contracts:expire --dry-run
```

#### **No Contracts Being Expired**
```bash
# Check contract data
php artisan tinker
>>> App\Models\Contract::where('status', 'active')->get(['id', 'contract_num', 'end_date', 'status'])

# Verify end dates are in the past
>>> App\Models\Contract::where('status', 'active')->where('end_date', '<=', now())->count()
```

#### **Permission Errors**
```bash
# Check if logs table is accessible
php artisan tinker
>>> App\Models\Log::count()

# Verify user ID 1 exists
>>> App\Models\User::find(1)
```

### **Debug Commands**
```bash
# Test expiration logic
php artisan test:contract-expiration

# Check specific date
php artisan contracts:expire --date=2025-01-15 --dry-run

# Force expiration for testing
php artisan contracts:expire --force --dry-run
```

## Integration with Other Systems

### **Logging System**
- All expirations are automatically logged
- Logs include old vs new status values
- IP address and user agent are recorded
- Logs can be viewed at `/logs` in your application

### **Contract Management**
- Expired contracts can be renewed
- Status changes trigger business logic
- Payment tracking continues after expiration
- Visit scheduling considers contract status

### **Client Communication**
- Expired contracts can trigger notifications
- Renewal reminders can be automated
- Client portal shows contract status
- Reports include expiration information

## Best Practices

### **1. Regular Monitoring**
- Check expiration logs daily
- Review expiring contracts weekly
- Monitor system performance monthly

### **2. Proactive Management**
- Send renewal reminders before expiration
- Review payment status regularly
- Plan renewal campaigns in advance

### **3. Data Quality**
- Ensure end dates are accurate
- Verify contract durations
- Keep client information updated

### **4. System Maintenance**
- Test expiration logic regularly
- Monitor command performance
- Keep scheduler running

## Future Enhancements

### **Planned Features**
- **Email Notifications**: Automatic client notifications
- **Renewal Workflows**: Streamlined renewal process
- **Advanced Reporting**: Expiration analytics dashboard
- **Mobile Alerts**: Push notifications for expiring contracts

### **Integration Opportunities**
- **CRM Systems**: Export expiration data
- **Accounting Software**: Sync payment status
- **Calendar Systems**: Schedule renewal meetings
- **Communication Tools**: Automated messaging

## Support

For issues or questions about the contract expiration system:

1. **Check Logs**: Review the logs table for errors
2. **Test Commands**: Use dry-run mode to test logic
3. **Verify Data**: Check contract dates and statuses
4. **Review Scheduler**: Ensure commands are running

### **Useful Commands**
```bash
# Test the system
php artisan test:contract-expiration

# Check for expired contracts
php artisan contracts:expire --dry-run

# View scheduled tasks
php artisan schedule:list

# Start scheduler manually
php artisan schedule:work
```

---

**Note**: This system ensures your contracts are always up-to-date and provides a solid foundation for contract lifecycle management. Regular monitoring and proactive management will maximize its effectiveness.
