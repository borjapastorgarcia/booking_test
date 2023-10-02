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
  
Application
=============
In this application, we have two endpoints:
 - /stats => Given a list of booking requests, return the average, minimum, and maximum profit per night
 - /maximize => Given a list of booking requests, return the best combination of requests that maximizes total profits.

To test it, we have two .http files, called **stats_api.http** and **maximize_api.http** respectively.

We can execute both files doing a request from the 'play' button of our IDE, getting through the console the response


![Captura de pantalla 2023-10-02 a las 21 09 23](https://github.com/borjapastorgarcia/stayforlong_test/assets/15001564/5f5b49a6-10aa-454d-9c5b-addbb88680f5)
![Captura de pantalla 2023-10-02 a las 21 11 37](https://github.com/borjapastorgarcia/stayforlong_test/assets/15001564/e64bb71d-1e62-486c-aefd-a7cf1689956d)

Tests
=============
To launch the tests, we have the following command
```bash
make test
```

