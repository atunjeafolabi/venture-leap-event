# Events API

## Overview
This solution implements a REST API for logging events into a local database. Additionally, events are paginated and can be filtered by passing an event `type` query string.

## Installation:
- Clone the project: ```git clone https://github.com/atunjeafolabi/venture-leap-event.git```
- Create a mysql database named ```venture-leap-event```
- Rename ```.env.example``` to ```.env``` and fill it with the database credentials (username and password)
- From the project root directory, run `composer install`
- Run migrations ```php bin/console doctrine:migrations:migrate```
- Input some related sample data into the database tables `events` and `types` in order to have something to work with
- Run local dev server: ```symfony server:start``` 

## Usage:
The following endpoints are available and can easily be accessed as follows: 

### Get list of events

#### Request

`GET v1/events`

    curl -i -H 'Accept: application/json' http://localhost:8000/v1/events

#### Response

    {
        "currentPageNumber": 1,
        "totalCount": 10,
        "items": [
            {
                "id": 1,
                "details": "First test event details..",
                "timestamp": {
                    "date": "2022-11-08 12:20:21.000000",
                    "timezone_type": 3,
                    "timezone": "UTC"
                },
                "type": {
                    "id": 1,
                    "name": "info"
                }
            },
            {
                "id": 2,
                "details": "Second test event details..",
                "timestamp": {
                    "date": "2022-11-08 12:20:21.000000",
                    "timezone_type": 3,
                    "timezone": "UTC"
                },
                "type": {
                    "id": 1,
                    "name": "info"
                }
            },
            {
                "id": 3,
                "details": "third danger event details",
                "timestamp": {
                    "date": "2022-11-08 12:20:21.000000",
                    "timezone_type": 3,
                    "timezone": "UTC"
                },
                "type": {
                    "id": 2,
                    "name": "danger"
                }
            },
            {
                "id": 7,
                "details": "lorem details",
                "timestamp": {
                    "date": "2022-11-10 08:00:11.000000",
                    "timezone_type": 3,
                    "timezone": "UTC"
                },
                "type": {
                    "id": 6,
                    "name": "warning"
                }
            }
        ]
    }
    
    If no event was found, the JSON below is returned;
    
    {
        "currentPageNumber": 1,
        "totalCount": 0,
        "items": []
    }

##### Query strings    
An event can be filtered by type as follows:
``` 
v1/events?type=info
```

Additionally, page parameter can be sent for pagination:
``` 
v1/events?type=info&page=1
```

## Create a new event

#### Request

`POST v1/events`

    curl -X POST -H "Content-Type: application/json" -d '{
        "details": "First test event details",
        "type": "info"
     }' http://localhost:8000/v1/events


#### Response

    A 201 created response code is retured with a url
     to the newly created event resource.
     The body of the response in empty.

### Tech Stack

- PHP 7
- Symfony 5 framework
- Mysql

### Bundles / packages
To speed up development, the following bundles were used to handle tasks like pagination, database interaction:
- knplabs/knp-paginator-bundle
- symfony-bundles/json-request-bundle
- doctrine/orm

### Future Works
- Validation still needs to be added when creating or updating an event.
- More refactoring can still be done on the codebase
- Seeders can be added for easily loading sample data into the database

### Issues
- Kindly let me know if any issues are encountered.
