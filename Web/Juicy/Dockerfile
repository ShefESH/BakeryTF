FROM python:3.9.5-alpine3.13

# Update the container and add gcc
RUN apk update && apk upgrade
RUN apk add build-base

# Create a new user to run juicy as
RUN adduser flask -D
USER flask

# Copy the project over to the container
COPY ./ /home/flask/juicy
WORKDIR /home/flask/juicy

# Add .local/bin to path to stop red error in build
ENV PATH=/home/flask/.local/bin:$PATH
# Install python requirements
RUN pip install -r requirements.txt

# Run the application and give us access to port 5000
ENV FLASK_APP=run.py
EXPOSE 5000
ENTRYPOINT ["/home/flask/.local/bin/flask", "run", "--host", "0.0.0.0"]
