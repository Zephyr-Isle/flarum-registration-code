# zephyrisle/flarum-registration-code

A Flarum extension that enforces one-time registration codes mapped to usernames. This extension provides a secure way to control user registration by requiring pre-assigned registration codes that are linked to specific usernames.

## Features

- **Secure Registration Control**: Require a valid registration code for new user sign-ups
- **Username Mapping**: Each registration code is linked to a specific username for precise access control
- **Admin Management**: Easy-to-use admin interface for managing registration codes
- **CSV Import/Export**: Bulk import and export registration codes via CSV format
- **One-Time Use**: Each code can only be used once, preventing re-registration
- **Usage Tracking**: Track which codes have been used, by whom, and when
- **Auto-Email Generation**: Automatically generates email addresses based on username and forum domain

## Requirements

- Flarum ^2.0
- PHP 8.3 or higher
- MySQL 8.0 or higher / MariaDB 10.6 or higher

## Installation

Install via composer:

```bash
composer require zephyrisle/flarum-registration-code
```

Then enable the extension in the Flarum admin panel.

## Usage

### Creating Registration Codes

1. Navigate to the Admin panel
2. Go to Extensions → Registration Code
3. Enter a username and registration code
4. Click "Add" to create the code

### Importing Registration Codes

1. Prepare a CSV file with the format: `username,code`
2. In the admin panel, click "Import CSV"
3. Either paste the CSV content or upload a CSV file
4. Click "Import" to process the codes

Example CSV format:
```text
username,code
alice,ALICE-001
bob,BOB-001
charlie,CHARLIE-001
```

### Exporting Registration Codes

1. In the admin panel, click "Export CSV"
2. The browser will download a CSV file containing all registration codes

### User Registration

Users must provide a valid registration code during sign-up:
- The code must exist in the system
- The code must not have been used before
- The code must match the username they're registering with
- The username must not already exist in the system

## Security Features

- **Race Condition Protection**: Uses database locking to prevent concurrent use of the same code
- **Username Validation**: Prevents assigning codes to usernames that already exist
- **Foreign Key Constraints**: Ensures data integrity between registration codes and users
- **Admin-Only Access**: All management operations require admin privileges

## Database Schema

The extension creates the following database changes:

### registration_codes table
- `id` - Primary key
- `username` - The username this code is assigned to (indexed)
- `code` - The registration code (unique)
- `used_by` - Foreign key to users table (nullable, indexed)
- `used_at` - Timestamp when the code was used (nullable)
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

### users table
- `registration_code` - Stores the registration code used during registration (nullable)

## Development

### Setup

```bash
composer install
cd js
npm install
npm run build
```

### Building for Production

```bash
cd js
npm run build
```

### Development Mode

```bash
cd js
npm run dev
```

## License

MIT License

## Support

For issues, feature requests, or contributions, please visit the [GitHub repository](https://github.com/Zephyr-Isle/flarum-registration-code).

## Changelog

### 2.0.0
- Updated for Flarum v2 compatibility
- Updated PHP requirement to 8.3+
- Updated to use new ApiResource extender instead of deprecated ApiSerializer
- Updated model to use $casts instead of $dates for Laravel 13 compatibility
- Updated webpack configuration for Flarum v2
- Updated admin settings API for Flarum v2

### 1.0.0
- Initial release
- Basic registration code management
- CSV import/export functionality
- Admin interface
- User registration validation
