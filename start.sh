#!/bin/sh

# Start Apache in the background
apache2-foreground &

# Start the WebSocket server
php bin/console app:websocket-server