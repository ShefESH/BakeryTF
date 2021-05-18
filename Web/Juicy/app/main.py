from flask import Blueprint, render_template, request, jsonify, flash, make_response, redirect
import jwt, datetime
from functools import wraps


main = Blueprint('main', __name__)

SECRET_KEY=""

@main.route('/')
def index():
    return render_template('index.html')

@main.route('/buy')
def buy():
    if request.args.get('user') == 'admin' and request.args.get('juice') == 'flag':
        flash(u'SESH{Adm1n_Ju1c3}', 'error')
    elif request.args.get('juice') == 'flag':
        flash(u'Not juicy enough', 'error')
    else:
        flash(u'Enjoy your juice', 'error')
    return render_template('index.html')

def token_required(f):
    @wraps(f)
    def decorated(*args, **kwargs):
        token = None

        if 'x-access-token' in request.cookies:
            token = request.cookies['x-access-token']

        if not token:
            return 'You are missing your \'x-access-token\' JWT token! Head over to /register to make one!', 401

        try:
            data = jwt.decode(token, SECRET_KEY)

            user = data['user']
            admin = data['admin']
            if admin == True and user != "admin":
                return jsonify(({'message' : 'This user should not be an admin! Only select admins can have admin set!'}))
        except:
            return jsonify({'message' : 'Token is invalid!'}), 401

        return f(user, admin, *args, **kwargs)

    return decorated

def has_valid_token():
    token = None
    # If the token is set
    if 'x-access-token' in request.cookies:
        token = request.cookies['x-access-token']
    if not token:
        # Return if it is invalid
        return False
    try:
        # Decode the token
        data = jwt.decode(token, SECRET_KEY)
        if data['user'] != '':
            # If the user is not blank, then we have a valid token
            return True
        # else return false, the token is invalid
        return False
    except:
        return False

@main.route('/auth')
@token_required
def authorised(user, admin):
    token = request.args.get('token')
    auth = request.authorization
    if user == "admin" and admin == True:
        return 'SESH{53cur3_k3y_y0ur_jw7}'
    else:
        return 'You are currently logged in. This website is currently under construction<br>Please log in as an admin to see the admin panel.'

@main.route('/register')
def login():
    # If a 'valid' token exists, then redirect to auth to potentially get flag, otherwise allow to login
    if has_valid_token():
        return redirect("/auth")
    return render_template('login.html')

@main.route('/register', methods=['POST'])
def get_login():
    auth = request.authorization
    if request.form['username'] and request.form['password']:
        # We make a token here
        token = jwt.encode({
            'user': request.form['username'],
            'admin': False, # set the admin to be false, we don't want to generate admins!
            'exp': datetime.datetime.utcnow() + datetime.timedelta(hours=1)
        },
        SECRET_KEY) # This secret key is BLANK! This is the vulnerability
        # The token is meant to be encrypted with a secure SECRET_KEY so that the token can't be tampered with

        response = redirect("/auth")
        # Set the token to the browser to be used with other requests
        response.set_cookie('x-access-token', token.decode('UTF-8'))
        return response
    else:
        return make_response('Unable to verify', 403, {'WWW-Authenticate': 'Basic realm: "login required"'})
