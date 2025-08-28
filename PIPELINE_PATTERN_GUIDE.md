# Laravel Pipeline Pattern Guide

## What I've Implemented

I've created a complete Pipeline implementation for your authentication system with the following components:

### 1. Phone Verification Pipeline
- **ValidatePhoneExistence**: Checks if phone exists in database
- **ValidateUserStatus**: Ensures user account is active
- **GenerateOtpCode**: Creates OTP using your OtpService
- **SendOtpNotification**: Handles sending OTP (SMS/email)
- **PhoneVerificationPipeline**: Orchestrates the entire flow

### 2. Login Pipeline
- **ValidateUserCredentials**: Finds user by email/phone
- **ValidatePassword**: Verifies password hash
- **ValidateUserStatus**: Checks account status
- **GenerateAuthToken**: Creates authentication token
- **LoginPipeline**: Manages complete login process

## Benefits of Using Pipelines

### 1. **Single Responsibility Principle**
Each pipeline step has one specific job:
```php
// Instead of one large method doing everything
public function login(LoginRequest $request) {
    // 50+ lines of validation, authentication, token generation...
}

// Now each step is focused and testable
class ValidatePassword {
    public function handle(array $data, Closure $next) {
        // Only handles password validation
    }
}
```

### 2. **Easy to Test**
```php
// Test individual pipeline steps
$step = new ValidatePassword();
$result = $step->handle($data, function($data) { return $data; });
```

### 3. **Flexible and Reusable**
```php
// Reuse steps in different pipelines
$adminLoginPipeline = [
    ValidateUserCredentials::class,
    ValidatePassword::class,
    ValidateAdminPermissions::class, // Different validation
    GenerateAuthToken::class,
];

$userLoginPipeline = [
    ValidateUserCredentials::class,
    ValidatePassword::class,
    ValidateUserStatus::class, // Different validation
    GenerateAuthToken::class,
];
```

### 4. **Easy to Modify Flow**
```php
// Add new step without touching existing code
$enhancedPipeline = [
    ValidateUserCredentials::class,
    CheckLoginAttempts::class,        // NEW: Rate limiting
    ValidatePassword::class,
    ValidateUserStatus::class,
    LogLoginActivity::class,          // NEW: Audit logging
    GenerateAuthToken::class,
];
```

## Advanced Pipeline Patterns

### 1. Conditional Pipeline Steps
```php
class ConditionalStep {
    public function handle(array $data, Closure $next) {
        if ($data['user']->requires_2fa) {
            // Only execute if 2FA is required
            return $this->handle2FA($data, $next);
        }
        
        return $next($data);
    }
}
```

### 2. Pipeline with Data Transformation
```php
class TransformUserData {
    public function handle(array $data, Closure $next) {
        $data['user'] = $data['user']->load(['profile', 'permissions']);
        $data['formatted_user'] = [
            'id' => $data['user']->id,
            'name' => $data['user']->name,
            'avatar' => $data['user']->profile->avatar ?? null,
        ];
        
        return $next($data);
    }
}
```

### 3. Pipeline with Side Effects
```php
class LogUserActivity {
    public function handle(array $data, Closure $next) {
        // Continue pipeline first
        $result = $next($data);
        
        // Then log activity (side effect)
        Log::info('User logged in', [
            'user_id' => $data['user']->id,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        
        return $result;
    }
}
```

## Your Updated Controller

Your LoginController is now much cleaner:

```php
// Before: Large methods with mixed concerns
public function login(LoginRequest $request): JsonResponse {
    // 40+ lines of validation, authentication, etc.
}

// After: Clean, focused, and maintainable
public function login(LoginRequest $request): JsonResponse {
    return $this->loginPipeline->execute([
        'credentials' => $request->validated()
    ]);
}
```

## Error Handling in Pipelines

I've implemented proper error handling using Laravel's `HttpResponseException`:

```php
if (!$user) {
    throw new HttpResponseException(
        ApiResponse::error('Phone number not found', 404)
    );
}
```

This allows the pipeline to stop execution and return the error response immediately.

## Best Practices

### 1. **Keep Steps Small and Focused**
- Each step should do one thing well
- Easy to test and maintain
- Can be reused across different pipelines

### 2. **Use Dependency Injection**
```php
class ValidatePhoneExistence {
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}
}
```

### 3. **Pass Data Through Pipeline Context**
```php
// Add data to context for next steps
$data['user'] = $user;
$data['otp_code'] = $otpCode;
return $next($data);
```

### 4. **Handle Errors Gracefully**
```php
try {
    return $this->pipeline->send($data)->through($steps)->then($callback);
} catch (HttpResponseException $e) {
    return $e->getResponse();
}
```

## Next Steps for Your Application

1. **Create User Registration Pipeline**
2. **Add Password Reset Pipeline**
3. **Implement Email Verification Pipeline**
4. **Add Rate Limiting Pipeline Steps**
5. **Create Admin Authentication Pipeline**

## Testing Your Pipelines

```php
// Test individual steps
public function test_validate_phone_existence() {
    $step = app(ValidatePhoneExistence::class);
    $data = ['phone' => '1234567890'];
    
    $this->expectException(HttpResponseException::class);
    $step->handle($data, function($data) { return $data; });
}

// Test complete pipeline
public function test_phone_verification_pipeline() {
    $pipeline = app(PhoneVerificationPipeline::class);
    $response = $pipeline->execute(['phone' => '1234567890']);
    
    $this->assertEquals(200, $response->getStatusCode());
}
```

Your authentication system is now much more modular, testable, and maintainable using the Pipeline pattern!
