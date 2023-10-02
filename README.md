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

Also, we can test both endpoints with CLI commands.

For the first one, '/stats' endpoint we have **stayforlong:generate:stats** command. for use it, just write the next command in the terminal:

```bash
php bin/console stayforlong:generate:stats '{request}'
```
Where '{request}' would be our request.
The full command line command will be like this:
```bash
php bin/console stayforlong:generate:max_profit '[
    {
        "request_id": "bookata_XY123",
        "check_in": "2020-01-01",
        "nights": 5,
        "selling_rate": 200,
        "margin": 20
    },
    {
        "request_id": "kayete_PP234",
        "check_in": "2020-01-04",
        "nights": 4,
        "selling_rate": 156,
        "margin": 22
    }
]'
```
![Captura de pantalla 2023-10-03 a las 0 30 57](https://github.com/borjapastorgarcia/stayforlong_test/assets/15001564/244b89d2-2885-4720-80ac-bf06b42f2adc)


For '/maximize' endpoint, we have **stayforlong:generate:max_profit** command. for use it, just write the next command in the terminal:

```bash
php bin/console stayforlong:generate:max_profit '{request}'
```
Where '{request}' would be our request.
The full command line command will be like this:
```bash
php bin/console stayforlong:generate:max_profit '[
    {
        "request_id": "bookata_XY123",
        "check_in": "2020-01-01",
        "nights": 5,
        "selling_rate": 200,
        "margin": 20
    },
    {
        "request_id": "kayete_PP234",
        "check_in": "2020-01-04",
        "nights": 4,
        "selling_rate": 156,
        "margin": 22
    }
]'
```

![Captura de pantalla 2023-10-03 a las 0 29 11](https://github.com/borjapastorgarcia/stayforlong_test/assets/15001564/e17a2c20-3cb7-4cf4-952a-b2a284b05ab3)


Tests
=============
To launch the tests, we have the following command
```bash
make test
```

## Possible improvements

Adding domain events (e.g. when arrives a booking request)

Add more tests

Add value objects where necessary

