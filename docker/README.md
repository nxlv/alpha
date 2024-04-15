# Docker Configuration

> This configuration is still in development.

### Getting Started

Clone this repository into a new, clean directory on your workstation or server.

> `git clone nxlv/alpha`

Next, open a terminal and move to the `docker` directory from the repository.

> `cd docker`

Finally, run `docker-compose` to bring the container online.

> `sudo docker-compose up --build`

This will run Docker in an interactive session, where you will see system logs and activities happening.  To run Docker in the background, add a `-d` command to the command line:

> `sudo docker-compose up -d --build`

### Notes

The following things are still not functional:

- MariaDB/PGSQL Database Drivers in PHP
- File permissions are not set correctly and may cause issues

