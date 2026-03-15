#!/bin/bash

if [ -f .env ]; then
    export $(grep -v '^#' .env | xargs)
else
    echo "Error: .env file not found. Please create one based on .env.example."
    exit 1
fi

if [ -z "$REMOTE_USER" ] || [ -z "$REMOTE_HOST" ] || [ -z "$REMOTE_PATH" ]; then
    echo "Error: REMOTE_USER, REMOTE_HOST, and REMOTE_PATH must be set in .env."
    exit 1
fi

echo "Starting deployment to ${REMOTE_USER}@${REMOTE_HOST}..."

rsync -avzP \
    --exclude='.git/' \
    --exclude='node_modules/' \
    --exclude='.DS_Store' \
    --exclude='.env*' \
    --exclude='*.log' \
    --exclude='.cache/' \
    --exclude='bun.lock' \
    --exclude='composer.lock' \
    ./ "${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_PATH}"

echo "Deployment complete!"
