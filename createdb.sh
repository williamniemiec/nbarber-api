#!/bin/bash
set -e

echo "Running the database inicialization script..."
echo "Creating user..."
sudo -u postgres psql -tAc "SELECT 1 FROM pg_roles WHERE rolname='wniemiec'" | grep -q 1 || sudo -u postgres psql -c "CREATE USER wniemiec WITH PASSWORD 'wniemiec'"

echo "Creating database nbarber..." && sudo -u postgres createdb -O wniemiec -E 'UTF-8' "nbarber"
