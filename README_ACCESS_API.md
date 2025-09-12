# ACCESS School Management System Laravel API

A Laravel wrapper for the ACCESS School Management System API that provides clean, Laravel-friendly methods to interact with student data.

## Installation & Setup

### 1. Environment Configuration

Add the following variables to your `.env` file:

```env
ACCESS_APPLICATION=YourAppName
ACCESS_SCHOOL=YourSchoolName
ACCESS_KEY=your_api_key_here
ACCESS_HASH=your_api_hash_here
ACCESS_URL=https://api.accessphp.net/
ACCESS_SYSTEM_ID=your_system_id
ACCESS_DEBUG=false
```

### 2. Register Service Provider (Optional)

If you want to use dependency injection, add the service provider to your `config/app.php`:

```php
'providers' => [
    // Other providers...
    App\Services\AccessApi\AccessServiceProvider::class,
],
```

## API Endpoints

All endpoints are prefixed with `/api/access/students/{studentId}`:

### Student Information
- `GET /api/access/students/{studentId}/info` - Get basic student information
- `POST /api/access/students/{studentId}/authenticate` - Authenticate student with password

### Academic Data
- `GET /api/access/students/{studentId}/curriculum` - Get student curriculum
- `GET /api/access/students/{studentId}/grades` - Get student grades

### Financial Data
- `GET /api/access/students/{studentId}/assessment` - Get student assessment
- `GET /api/access/students/{studentId}/balance` - Get student balance
- `GET /api/access/students/{studentId}/ledger-history` - Get ledger history
- `POST /api/access/students/{studentId}/assess` - Re-assess student for course fees

## Authentication

All ACCESS API endpoints are protected with Laravel Sanctum authentication. You need to obtain an API token first.

### Getting an API Token

```bash
curl -X POST "http://your-app.com/api/login" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "api@example.com",
    "password": "password",
    "device_name": "My App"
  }'
```

Response:
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "API User",
      "email": "api@example.com"
    },
    "token": "1|abc123...",
    "token_type": "Bearer"
  }
}
```

### Authentication Endpoints

- `POST /api/login` - Login and get API token
- `POST /api/logout` - Logout and revoke current token
- `GET /api/user` - Get current authenticated user
- `GET /api/tokens` - List all user tokens
- `DELETE /api/tokens/{tokenId}` - Revoke specific token
- `DELETE /api/tokens` - Revoke all tokens

## Usage Examples

### Using the API Endpoints

All requests must include the `Authorization` header with your Bearer token:

#### Get Student Information
```bash
curl -X GET "http://your-app.com/api/access/students/9504266/info" \
  -H "Authorization: Bearer YOUR_API_TOKEN"
```

With optional parameters:
```bash
curl -X GET "http://your-app.com/api/access/students/9504266/info?params[]=enroll&params[]=contacts" \
  -H "Authorization: Bearer YOUR_API_TOKEN"
```

#### Authenticate Student
```bash
curl -X POST "http://your-app.com/api/access/students/9504266/authenticate" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -d '{"password": "student_password"}'
```

#### Get Student Grades
```bash
curl -X GET "http://your-app.com/api/access/students/9504266/grades" \
  -H "Authorization: Bearer YOUR_API_TOKEN"
```

### Using the Service Classes Directly

```php
use App\Services\AccessApi\Student;
use App\Services\AccessApi\AccessClient;

// Using default configuration
$student = new Student('9504266');

// Get student information
$info = $student->getInfo(['enroll', 'contacts', 'balance']);

// Authenticate student
$isAuthenticated = $student->authenticate('password123');

// Get grades
$grades = $student->getGrades();

// Get financial information
$assessment = $student->getAssessment();
$balance = $student->getBalance();
$ledgerHistory = $student->getLedgerHistory();

// Re-assess student
$newAssessment = $student->assess();
```

### Using Custom Configuration

```php
use App\Services\AccessApi\AccessClient;
use App\Services\AccessApi\Student;

$customClient = new AccessClient([
    'application' => 'MyCustomApp',
    'school' => 'CustomSchool',
    'key' => 'custom_key',
    'hash' => 'custom_hash',
    'url' => 'https://custom-api.example.com/',
    'debug' => true,
]);

$student = new Student('9504266', $customClient);
$info = $student->getInfo();
```

## Response Format

All API endpoints return JSON responses in this format:

### Success Response
```json
{
    "success": true,
    "data": {
        // Student data here
    }
}
```

### Authentication Response
```json
{
    "success": true,
    "authenticated": true
}
```

### Error Response
```json
{
    "success": false,
    "message": "Error description"
}
```

### Validation Error Response
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "field_name": ["Error message"]
    }
}
```

## Available Student Information Parameters

When calling the `getInfo()` method or `/info` endpoint, you can include these optional parameters:

- `enroll` - Enrollment information with restrictions, toenroll, section
- `numbers` - Student numbers/IDs
- `contacts` - Contact information
- `balance` - Balance information

## Error Handling

The API includes comprehensive error handling:

- **AccessApiException** - Thrown for API-related errors
- **ValidationException** - Thrown for request validation errors
- **HTTP Status Codes** - Proper status codes for different error types

## Security Features

- SHA1 security hashing for all requests
- Unique session IDs for each request
- Environment-based configuration
- Debug mode for development

## Logging

When `ACCESS_DEBUG=true`, all API requests and responses are logged to Laravel's default log channel for debugging purposes.

## Default Test Users

The application comes with seeded test users:

- **API User**: `api@example.com` / `password`
- **Admin User**: `admin@example.com` / `admin123`

## Testing

You can test the API endpoints using tools like Postman, curl, or Laravel's built-in testing features:

```php
// In a Laravel test
public function test_student_info_endpoint()
{
    $user = User::factory()->create();
    $token = $user->createToken('test')->plainTextToken;
    
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->get('/api/access/students/9504266/info');
    
    $response->assertStatus(200)
             ->assertJson(['success' => true]);
}
```

### Generate API Token via CLI

You can also generate tokens directly via Artisan command:

```bash
php artisan access:token api@example.com --name="My API Token"
```

### Complete Authentication Flow Example

```bash
# 1. Login to get token
TOKEN=$(curl -s -X POST "http://localhost:8000/api/login" \
  -H "Content-Type: application/json" \
  -d '{"email": "api@example.com", "password": "password"}' \
  | jq -r '.data.token')

# 2. Use token to access protected endpoints
curl -X GET "http://localhost:8000/api/access/students/9504266/info" \
  -H "Authorization: Bearer $TOKEN"

# 3. Logout when done
curl -X POST "http://localhost:8000/api/logout" \
  -H "Authorization: Bearer $TOKEN"
```

## Security Features

- **Laravel Sanctum Authentication** - Token-based API authentication
- **Rate Limiting** - 60 requests per minute per user
- **Request Logging** - All API requests are logged for monitoring
- **Security Headers** - XSS protection, content type sniffing prevention
- **Token Management** - Create, list, and revoke tokens
- **Environment-based Configuration** - Secure credential management