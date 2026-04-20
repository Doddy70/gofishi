# MySQL & MariaDB Management

A guide for professional management, optimization, and scaling of MySQL and MariaDB databases.

## When to Use This Skill

- Designing and optimizing database schemas
- Improving query performance using indexes and EXPLAIN
- Managing high-availability and replication setups
- Tuning server parameters for production workloads
- Ensuring data integrity and implementing backup strategies
- Identifying bottlenecks via slow query logs

## Core Strategies

### 1. Schema & Indexing
- **Types**: Use precise types (e.g., `TINYINT` for booleans, `BIGINT` for large IDs).
- **Charset**: Always use `utf8mb4` for full Unicode support (including emojis).
- **Clustered Indexes**: Understand that InnoDB clusters data by the Primary Key; choose it carefully.
- **Covering Indexes**: Design indexes that include all columns required by a query to avoid extra table lookups.

### 2. Performance Tuning
- **Buffer Pool**: Set `innodb_buffer_pool_size` to 70-80% of available RAM on dedicated DB servers.
- **Query Optimization**: Use `EXPLAIN` to identify full table scans and non-optimal joins.
- **Avoid SELECT***: Only fetch the columns you need to reduce I/O and memory usage.
- **Slow Queries**: Enable and monitor the `slow_query_log` to find at-risk queries.

### 3. Replication & Scale
- **Master-Slave**: Standard for scaling reads across multiple nodes.
- **GTID**: Use Global Transaction IDs to simplify replication management and failover.
- **Galera Cluster**: (MariaDB specific) For synchronous multi-master clusters.

## Best Practices Checklist

- [ ] Is `innodb_buffer_pool_size` tuned for the available system RAM?
- [ ] Are all frequently filtered columns indexed?
- [ ] Is `utf8mb4` encoding used across all tables and columns?
- [ ] Are slow queries being logged and periodically analyzed?
- [ ] Is a regular automated backup strategy in place (e.g., `mysqldump` or Percona)?
- [ ] Is `strict_sql_mode` enabled to prevent silent data truncation?
- [ ] Are foreign keys used correctly to ensure referential integrity?
- [ ] Is the database connection pool sized correctly for the application?
