# Check it out
This is and open source library to make your life easier and happier mmmm... nop! is just an excercise, check it out!

In order to get started spin up the containers, and install project dependencies.

```bash
$ make init
```
###Check application health
http://localhost:8080/health-check

###Run tests
```bash
#outside docker container
$ make test

#inside fpm container
$ make run-tests
```

###Exercise 1 - Cron file
Open cron.txt and insert one scheduled command by line. If you dont wan't to waste your time waitting for a command execution, looking your life passing away, I recommend to execute commands with * * * * *
```bash
Example:
#After 1 min you can see one papagallo in your project root directory.
* * * * * touch /app/papagallo.txt 
```

###Exercise 2 - Book storage
Store and find book

```bash
Create book:
#PUT with resource (id) instead POST to avoid multiple creations)
PUT Request to /books/{id}
 
Find book:
GET Request to /books/{id}

```


