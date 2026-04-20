# Security & Penetration Testing

A strategic guide for identifying vulnerabilities, mitigating risks, and ensuring the robust security of web applications.

## When to Use This Skill

- Conducting security audits on new or existing features
- Implementing defenses against OWASP Top 10 vulnerabilities
- Performing static (SAST) and dynamic (DAST) security analysis
- Reviewing authentication and authorization flows
- Hardening server and application configurations
- Securing third-party dependencies and APIs

## OWASP Top 10 Focus

### 1. Broken Access Control
Ensure users cannot access data or perform actions outside their intended permissions.
- **Check**: Vertical privilege escalation (User acting as Admin) and Horizontal escalation (User A accessing User B's data).
- **Mitigation**: Use centralized authorization logic (Laravel Policies/Gates), avoid IDOR (Insecure Direct Object References).

### 2. Injection (SQLi, XSS, Command Injection)
Filter and sanitize all data that enters or leaves the application.
- **SQLi**: Always use parameterized queries (Eloquent/PDO) instead of raw string concatenation.
- **XSS**: Escape output properly (Blade does this by default with `{{ }}`). Use `strip_tags()` or similar for user-generated content when necessary.

### 3. Cryptographic Failures
Protect sensitive data in transit and at rest.
- **Mitigation**: Use HTTPS (TLS 1.2+), hash passwords using bcrypt/argon2, and encrypt sensitive PII (Personally Identifiable Information).

### 4. Security Misconfiguration
Ensure the environment is hardened.
- **Check**: Default credentials, unnecessary services enabled, detailed error messages visible in production (`APP_DEBUG=true`).

## Security Testing Workflow

1. **SCA (Software Composition Analysis)**: Scan dependencies for known vulnerabilities (`npm audit`, `composer audit`).
2. **SAST (Static Analysis)**: Review source code for patterns that suggest security flaws.
3. **DAST (Dynamic Analysis)**: Test the running application for vulnerabilities using proxies or scanners (e.g., OWASP ZAP).
4. **Manual Penetration Testing**: Deep dive into business logic to find flaws that automated tools miss (e.g., bypassing payment logic).

## Best Practices Checklist

- [ ] Is `APP_DEBUG` set to `false` in production?
- [ ] Are all database queries using parameterized inputs?
- [ ] Are user roles and permissions validated on every sensible request?
- [ ] Is sensitive data encrypted and passwords securely hashed?
- [ ] Are third-party packages updated to the latest secure versions?
- [ ] Are security headers (CSP, X-Frame-Options, STS) implemented?
- [ ] Is CSRF protection enabled on all state-changing requests?
- [ ] Does the application handle sensitive file uploads securely (scan for scripts)?
