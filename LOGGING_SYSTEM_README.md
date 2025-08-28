# Enhanced Logging System for Laravel HVAC App

## Overview

This enhanced logging system provides comprehensive tracking of all create, update, and delete actions in your Laravel HVAC application. Every action is automatically logged with detailed information about what changed, who made the change, and when it occurred.

## Features

### 🚀 Automatic Logging
- **Model Events**: Automatically logs all `created`, `updated`, and `deleted` events
- **User Tracking**: Records which user performed each action
- **Detailed Changes**: Tracks old vs new values for updates
- **IP & User Agent**: Records client information for security

### 📊 Comprehensive Data
- **Action Types**: create, update, delete, login, logout, view, grant, revoke, role_change, bulk actions
- **Model Types**: All major models (User, Client, Contract, Machine, Payment, Address, Role, Feature, Visit)
- **Change Details**: Shows exactly what fields were modified
- **Timestamps**: Precise timing of all actions

### 🎯 Smart Filtering
- Filter by action type, model type, user, and date range
- Search through logs efficiently
- Pagination for large datasets

## How It Works

### 1. Automatic Model Logging

The `Loggable` trait automatically logs all model events:

```php
use App\Traits\Loggable;

class Client extends Model
{
    use HasFactory, Loggable;
    // ... rest of model
}
```

**Supported Models:**
- ✅ Client
- ✅ Contract  
- ✅ Machine
- ✅ Payment
- ✅ User
- ✅ Address
- ✅ Role
- ✅ Feature
- ✅ Visit

### 2. Event-Based Logging

Login/logout actions are automatically logged:

```php
// Automatically logged when users log in/out
use App\Services\LogService;

LogService::logLogin($user);
LogService::logLogout($user);
```

### 3. Manual Logging

For custom actions, use the LogService:

```php
use App\Services\LogService;

// Log custom actions
LogService::logAction('custom_action', 'Description', $model);

// Log permission changes
LogService::logPermissionChange($user, 'feature_name', true);

// Log role changes
LogService::logRoleChange($user, $oldRole, $newRole);

// Log bulk actions
LogService::logBulkAction('bulk_update', 'User', [1,2,3], 'Description');
```

## Database Schema

The `logs` table stores:

```sql
- id (primary key)
- user_id (who performed the action)
- action_type (create, update, delete, etc.)
- model_type (which model was affected)
- model_id (specific record ID)
- description (human-readable description)
- old_values (JSON of previous values)
- new_values (JSON of new values)
- ip_address (client IP)
- user_agent (browser/client info)
- created_at (when action occurred)
- updated_at (last update)
```

## Usage Examples

### Basic Model Operations

```php
// Creating a client automatically logs the action
$client = Client::create([
    'name' => 'John Doe',
    'email' => 'john@example.com'
]);
// Logs: "Created new Client: John Doe"

// Updating a client automatically logs changes
$client->update(['email' => 'john.doe@example.com']);
// Logs: "Updated Client john@example.com" with old vs new values

// Deleting a client automatically logs the deletion
$client->delete();
// Logs: "Deleted Client john@example.com" with all deleted data
```

### Custom Logging

```php
// Log specific field changes
LogService::logFieldChange($client, 'status', 'active', 'inactive');

// Log system actions
LogService::logSystemAction('maintenance', 'Database backup completed');

// Log bulk operations
LogService::logBulkAction('bulk_delete', 'Client', [1,2,3], 'Deleted inactive clients');
```

### Viewing Logs

Access the logs interface at `/logs` to see:

- **Timeline View**: Chronological list of all actions
- **Detailed Changes**: Side-by-side comparison of old vs new values
- **User Activity**: Track what each user has done
- **Filtering**: Search by action type, model, user, or date
- **Export**: Download logs for analysis

## Configuration

### Adding Logging to New Models

1. Add the trait to your model:
```php
use App\Traits\Loggable;

class NewModel extends Model
{
    use HasFactory, Loggable;
}
```

2. The model will automatically log all CRUD operations.

### Customizing Logged Fields

Modify the `getEssentialFields()` method in the `Loggable` trait to control which fields are logged:

```php
protected static function getEssentialFields($data)
{
    // Add your custom field filtering logic here
    $allowedFields = [
        'name', 'email', 'status', 'amount',
        // Add more fields as needed
    ];
    
    // ... filtering logic
}
```

### Adding New Action Types

1. Add constants to the `Log` model:
```php
const ACTION_CUSTOM = 'custom';
```

2. Add to the `getActionTypes()` method:
```php
public static function getActionTypes()
{
    return [
        // ... existing types
        self::ACTION_CUSTOM => 'Custom Action',
    ];
}
```

## Testing

Test the logging system with:

```bash
php artisan test:logging
```

This creates sample log entries to verify everything works correctly.

## Security Considerations

- **Sensitive Data**: The system automatically excludes password fields and sensitive data
- **IP Logging**: Client IPs are logged for security auditing
- **User Tracking**: All actions are tied to authenticated users
- **Data Retention**: Consider implementing log rotation for production

## Performance

- **Efficient Queries**: Uses database indexes for fast filtering
- **Pagination**: Large log datasets are paginated
- **Selective Logging**: Only essential fields are logged to minimize storage
- **Background Processing**: Consider using queues for high-volume logging

## Troubleshooting

### Common Issues

1. **Logs not appearing**: Check if the `Loggable` trait is properly imported
2. **Missing user info**: Ensure user is authenticated before logging
3. **Performance issues**: Check database indexes on the logs table

### Debug Commands

```bash
# Check if logging is working
php artisan test:logging

# View recent logs
php artisan tinker
>>> App\Models\Log::latest()->take(5)->get()
```

## Future Enhancements

- **Real-time Logs**: WebSocket integration for live updates
- **Log Analytics**: Dashboard with charts and insights
- **Export Formats**: CSV, Excel, PDF export options
- **Advanced Filtering**: Full-text search and complex queries
- **Log Retention Policies**: Automatic cleanup of old logs
- **Audit Reports**: Scheduled reports for compliance

## Support

For issues or questions about the logging system:

1. Check the logs table for any error entries
2. Verify all required models have the `Loggable` trait
3. Ensure the database migrations have been run
4. Check the Laravel logs for any PHP errors

---

**Note**: This logging system provides comprehensive audit trails for compliance and debugging purposes. Ensure your data retention policies align with your business requirements and legal obligations.
