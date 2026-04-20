# Laravel Framework

A "web artisan's" guide to building powerful, elegant applications using the PHP Laravel framework.

## When to Use This Skill

- Developing robust MVC web applications
- Building complex API backends with Sanctum or Passport
- Managing database schemas via migrations and Eloquent ORM
- Implementing background processing (Queues) and scheduling
- Creating dynamic templates with the Blade engine
- Enhancing security with built-in authentication and middleware

## Core Architecture

### 1. The Request Lifecycle
Routes (`web.php`/`api.php`) → Middleware → Controller → Service Layer (Optional) → Eloquent Model → Response/View.

### 2. Dependency Injection
Use the Service Container to inject dependencies into controllers or constructors, promoting decoupling and testability.

## Eloquent ORM

### Relationships
- **One to One**: `return $this->hasOne(Profile::class);`
- **One to Many**: `return $this->hasMany(Comment::class);`
- **Many to One**: `return $this->belongsTo(User::class);`
- **Many to Many**: `return $this->belongsToMany(Role::class);`

### Optimization
- **Eager Loading**: Use `with()` to prevent N+1 query problems.
- **Accessors/Mutators**: Use `Attribute` classes to format data on the fly.
- **Scopes**: Encapsulate common query logic (e.g., `scopePublished($query)`).

## Feature Highlights

### Validation & Security
- **Form Requests**: Move validation logic out of controllers into dedicated Request classes.
- **Mass Assignment**: Always define `$fillable` or `$guarded` in models.
- **Authorization**: Use Policies and Gates to control access to resources.

### Blade Templating
- Use `@extends`, `@section`, and `@yield` for layout inheritance.
- Use `@component` or `@include` for reusable UI fragments.
- Directive `@auth` / `@guest` for conditional authenticated content.

### Background & Real-time
- **Queues**: Offload heavy tasks (emails, image processing) using `Job` classes.
- **Events**: Use `Event` and `Listener` to decouple application logic.
- **Notifications**: Send multi-channel alerts (mail, database, slack) with ease.

## Best Practices Checklist
- [ ] Keep controllers "skinny" (minimal logic).
- [ ] Move business logic to **Service Classes**.
- [ ] Encapsulate data fetching in **Eloquent Scopes**.
- [ ] Use **API Resources** to transform model data for responses.
- [ ] Name routes using dot notation (`admin.users.index`).
- [ ] Follow **PSR-12** coding standards.
- [ ] Use **Database Transactions** when performing multiple related writes.
- [ ] Write **Feature Tests** for critical business flows.
