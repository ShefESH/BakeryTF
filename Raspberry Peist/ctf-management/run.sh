#!/bin/bash

sqlite3 raspi.db < seed.sql

export FLASK_APP=app.py;

/home/flask/.local/bin/flask run --host 0.0.0.0 --port 5000