# Docker Configuration

> This configuration is still in development.

### Getting Started

Clone this repository into a new, clean directory on your workstation or server.

> `git clone nxlv/alpha`

Next, open a terminal and move to the `docker` directory from the repository.

> `cd docker`

**On your first run ONLY**, build the `platform` image.

> `sudo docker-compose build --force-rm --pull --no-cache`

This will take a while as it will need to compile necessary PHP modules.

Finally, run `docker-compose` to bring the container online.

> `sudo docker-compose up`

This will run Docker in an interactive session, where you will see system logs and activities happening.  To run Docker in the background, add a `-d` command to the command line:

> `sudo docker-compose up -d`

# Logging In

> Please read the information below for directions on how to be able to log-in to ALPHA once you get it up and running in Docker.

Once you get up-and-running, you will arrive at the ALPHA login screen.

By default, **no users are defined**.  You can create your own user to log-in with by using the **Register** link on the login page.
