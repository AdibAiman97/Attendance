#!/bin/sh
set -e

# Debug: Log available environment variables (remove in production)
echo "=== Environment Variables Check ===" > /tmp/env-check.log
env | grep -E "^(DB_|RENDER_)" >> /tmp/env-check.log || echo "No DB_ or RENDER_ variables found" >> /tmp/env-check.log
echo "=== End Check ===" >> /tmp/env-check.log

# Start Apache - it will inherit environment variables if they exist
exec apache2-foreground

