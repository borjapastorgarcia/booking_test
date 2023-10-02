# Stayforlong Code challenge

System requirements
===================

* docker
* docker-compose
* Your favourite Web Developing IDE (example PHPStorm)
  
Environment setup
=============
First of all, we will have to download the git project, so we will open a new window in the terminal and we will throw the following command

```bash
git clone https://github.com/borjapastorgarcia/stayforlong_test.git
```

Then, we have to start docker & docker container so, with the help of the makefile file, we will launch the following commands: 

```bash
make up
```
And to download composer packages
```bash
make composer
```
If everything has gone well, we will have our entire environment working perfectly.
To make sure of this, we can go to the following address to see the main screen of a symfony application

http://127.0.0.1:8000/

Tests
=============
To launch the tests, we have the following command
```bash
make test
```

