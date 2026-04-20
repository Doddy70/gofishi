# REST API Design

A comprehensive guide for designing scalable, maintainable, and developer-friendly RESTful APIs based on industry standards.

## When to Use This Skill

- Architecting new API endpoints
- Standardizing existing API structures
- Implementing resource-based routing
- Designing response formats and error handling
- Implementing filtering, sorting, and pagination
- Documenting APIs for frontend or third-party integration

## Resource-Based Routing

### Core Rules
- **Nouns over Verbs**: Use resources as nouns (`/orders`), never verbs (`/getOrders`).
- **Pluralization**: Standardize on plural nouns for collections (`/users`).
- **Hierarchy**: Represent relationships through path nesting (`/users/123/comments`).
- **Kebab-case**: Use lowercase with hyphens for URLs (`/user-profiles`).

### URL Examples
| Action | HTTP Method | Endpoint |
|--------|-------------|----------|
| List Resources | GET | `/products` |
| Get Single Resource | GET | `/products/{id}` |
| Create Resource | POST | `/products` |
| Replace Resource | PUT | `/products/{id}` |
| Update Resource | PATCH | `/products/{id}` |
| Delete Resource | DELETE | `/products/{id}` |

## Success & Error Patterns

### Status Codes
- **200 OK**: Request succeeded.
- **201 Created**: Resource created successfully (return the new resource).
- **204 No Content**: Action succeeded, nothing to return (common for DELETE).
- **400 Bad Request**: Invalid input or logic error.
- **401 Unauthorized**: Missing or invalid authentication token.
- **403 Forbidden**: Authenticated but lacks permission.
- **404 Not Found**: Resource does not exist.
- **422 Unprocessable Entity**: Validation errors on specific fields.
- **500 Internal Server Error**: Uncaught exception or server failure.

### Error Response Format (RFC 7807)
```json
{
  "type": "https://example.com/errors/validation-failed",
  "title": "Validation Failed",
  "status": 422,
  "detail": "The provided email is already in use.",
  "errors": {
    "email": ["Must be a valid email address", "Email is already taken"]
  }
}
```

## Advanced Features

### Filtering & Sorting
- **Filtering**: Use query params (`/users?role=admin&status=active`).
- **Sorting**: Use a `sort` param with `-` prefix for descending (`/users?sort=-created_at,name`).

### Pagination
- **Offset-based**: Simple but slow for large datasets (`/posts?limit=20&offset=40`).
- **Cursor-based**: Best for dynamic feeds/infinite scroll (`/posts?limit=20&after=cursor_id`).

### Versioning
- **Header**: `Accept: application/vnd.api+json;version=2` (Cleanest metadata).
- **URI**: `/api/v2/users` (Most visible and cache-friendly).

## Best Practices
1. **Consistency**: Use the same case (camelCase or snake_case) across all JSON payloads.
2. **ISO 8601**: Always use UTC ISO 8601 strings for dates (`2024-04-20T10:00:00Z`).
3. **Idempotency**: Ensure GET, PUT, and DELETE can be called multiple times with the same result.
4. **Security**: Always use HTTPS, implement rate limiting, and protect with modern auth (JWT/OAuth2).
5. **Partial Updates**: Use `PATCH` with atomic updates rather than replacing the whole resource with `PUT`.
