# Juicy

Several challenges based on a deliberately bad replica of the Juice Shop platform. We wanted to make a throwback to our earlier sessions with this one.

## Running the project

### Docker
The easiest way to run this project would be to use docker. 
After installing docker and changing to the Juicy directory, simply run:

`docker build -t juicy:latest . && docker run --rm -it juicy:latest`

You can then connect to the server using the ip from the terminal on port 5000

### Manually running Flask
If you want to build the project manually, make sure python3 and pip3 are installed and run:

`pip install -r requirements.txt`

Then to run the project run:

`FLASK_APP=run.py flask run --host 0.0.0.0 --port 5000`

Then connect to the website on localhost:5000

## Completing Juicy

### Juicy Part 1

<details>

<summary>Spoilers</summary>

Once clicking 'buy' on each of the juices, you will notice that one of them has an extra field that appears in the URL.

After setting this extra field to be 'admin', you will gain a flag for the challenge

</details>

### Juicy Part 2

<details>

<summary>Spoilers</summary>

There is a registration button on the main screen.

You will notice that after registering (With any credentials, it doesn't validate anything) you will be redirected to a `/auth` page.

This page can only be accessed after registering in. You can see that if you delete your cookies and head to `/auth` you get an error saying to have a JWT token set in `x-access-token`.

In order to exploit the token, you must forge it to mimic an account of an admin. To do this, head over to [jwt.io](https://jwt.io) and paste in your `x-access-token` into the text area. You should see that there is a `user`, `admin` and `exp` field.

You may notice that the token says `Invalid Signature` at the bottom left, 
that will likely be because there is a `secret key` set within the blue settings
of jwt.io. Remove this key and you should see that the token becomes signed. 
This is the vulnerability with the application - The token should be signed with 
a key that is not known to the client so the client can't forge their own tokens.
If you would like to read more about JWT tokens, head over to the [jwt.io/introduction](https://jwt.io/introduction/) website.

The `exp` field denotes when the token will expire in the epoch format.

The `user` and `admin` fields denote the username and admin attributes respectively. We will need to forge these in order to get an admin token.

Setting the token to contain `admin: true` and `user: 'admin'` will allow you to login to the website once you set the new token in your browser.

Simple! You've not got the second Juicy flag :D

</details>