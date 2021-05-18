from flask import Flask
from flask import request, render_template, flash, session, redirect, url_for
from flask_bootstrap import Bootstrap
import sqlite3
from .conf import secret_key as sk
import hashlib

# define flask variables

app = Flask(__name__)
Bootstrap(app)

app.secret_key = sk

# define routes

@app.route('/')
def index():
    error = request.args.get('error')
    return render_template('index.html', error=error)

@app.route('/admin')
def admin():
    if 'id' in session and 'username' in session:
        if session['id'] == 1:
            return render_template('admin.html', username=session['username'])
    
    print("Not authorised - no session set")
    return redirect(url_for('index', error="Not authorised"))

@app.route('/admin/search', methods=['GET', 'POST'])
def search():
    if 'id' in session and 'username' in session:
        if session['id'] == 1:
            if request.method == 'GET':
                con = sqlite3.connect('raspi.db')
                con.row_factory = sqlite3.Row
                cur = con.cursor()
                cur.execute(f"SELECT * FROM challenges")
                challenges = cur.fetchall()

                # print(challenges)
                
                return render_template('search.html', challenges=challenges)

            elif request.method == 'POST':
                search_term = request.form['search_term']
                con = sqlite3.connect('raspi.db')
                con.row_factory = sqlite3.Row
                cur = con.cursor()
                cur.execute(f"SELECT id, title, description FROM challenges WHERE title LIKE '%{search_term}%'")
                challenges = cur.fetchall()

                return render_template('search.html', challenges=challenges)
        
    print("Not authorised - no session set")
    return redirect(url_for('index', error="Not authorised"))

@app.route('/login', methods=['POST'])
def login():
    username = request.form['username']
    password = hashlib.md5(request.form['password'].encode('utf-8')).hexdigest()
    print(password)
    con = sqlite3.connect('raspi.db')
    con.row_factory = sqlite3.Row
    cur = con.cursor()
    cur.execute(f"SELECT id, username FROM users WHERE username='{username}' AND password='{password}'")
    users = cur.fetchall()
    
    if len(users) > 0:
        session['id'], session['username'] = users[0]
        return redirect(url_for('admin'))
    else:
        print("Wrong username or password")
        return redirect(url_for('index', error="Wrong email or password"))

@app.route('/logout', methods=['POST'])
def logout():
    session.clear()
    print("Session cleared")
    return redirect(url_for('index'))