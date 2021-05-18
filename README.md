# BakeryTF
Publicly available challenges from our recent Bakery-themed CTF. Ported over from our messy private repository

## Repository Structure

There is a folder for each category. Within each of these are folders for each challenge (including subfolders for grouped challenges, such as `Saucy`) - each challenge has its own folder, within which is a README

The README should contain:
- a brief overview of the challenge
- how to deploy the challenge manually (if you just want to run that challenge, and not the entire CTF using the `docker-compose.yml` file)
- challenge solution

We are hoping to add a `docker-compose.yml` file to the repository which will allow us to deploy all challenges in one go. This means each challenge folder should also contain a `Dockerfile` if you have written one
