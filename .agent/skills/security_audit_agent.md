# Security Audit Agent

A systematic framework for conducting security audits, identifying vulnerabilities, and recommending remediations following OWASP standards and industry best practices.

## When to Use This Skill

- Performing a pre-deployment security review
- Identifying injection, sensitive data exposure, or access control flaws
- Mapping the attack surface of a new feature or endpoint
- Assessing the risk of identified vulnerabilities
- Auditing authentication and session management logic
- Verifying the implementation of security headers and defenses

## Audit Framework

### 1. Attack Surface Analysis
- **Entry Points**: Map all user inputs (forms, APIs, webhooks, file uploads).
- **Data Flow**: Trace data from input to storage to output.
- **Trust Boundaries**: Identify where untrusted data crosses into internal systems.
- **Privileged Ops**: List operations requiring elevated permissions.

### 2. OWASP Top 10 Review

#### Injection & XSS
- **Parameterization**: Ensure all database queries use placeholders.
- **Escaping**: Verify that all user-supplied data is escaped before being rendered in HTML (Blade `{{ }}`).
- **CSP**: Ensure a strong Content Security Policy is in place to mitigate XSS impact.

#### Authentication & Sessions
- **Hashing**: Verify usage of strong algorithms (bcrypt/Argon2).
- **Tokens**: Check for `HttpOnly`, `Secure`, and `SameSite` flags on cookies.
- **Lockout**: Verify account lockout mechanisms to prevent brute force.

#### Access Control & IDOR
- **Permissions**: Ensure every endpoint has an associated policy or middleware check.
- **IDOR**: Verify that users cannot manipulate `id` fields to access other users' data.

### 3. Risk Assessment
Evaluate each finding based on:
- **Severity**: Critical, High, Medium, or Low.
- **Likelihood**: Ease of exploitation.
- **Impact**: Potential damage to confidentiality, integrity, or availability.

## Vulnerability Reporting

For every vulnerability identified, provide:
- **Title & Severity**: Clear classification.
- **Location**: Specific file, line number, or endpoint.
- **Description**: Technical explanation of the flaw.
- **Impact**: Potential consequences if exploited.
- **Remediation**: Step-by-step fix with code examples.

## Security Headers Checklist
- [ ] **HSTS**: `Strict-Transport-Security` to enforce HTTPS.
- [ ] **CSP**: `Content-Security-Policy` to control script execution.
- [ ] **NOSNIFF**: `X-Content-Type-Options: nosniff`.
- [ ] **X-FRAME**: `X-Frame-Options: DENY` to prevent clickjacking.
- [ ] **X-XSS**: `X-XSS-Protection: 1; mode=block`.

## Remediation Best Practices
- **Defense in Depth**: Don't rely on a single control; implement multiple layers of security.
- **Least Privilege**: Grant only the minimum permissions necessary for a task.
- **Sanitize Early, Escape Late**: Validate and sanitize input as soon as it's received; escape output at the moment of rendering.
