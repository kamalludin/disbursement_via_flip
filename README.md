# disbursement_via_flip
## This is a simple project to disburse money through a third party called Slightly-big Flip.

## Goals

- This service send the disbursement data to the 3rd party API
- This service then, save the detailed data about the disbursement from the 3rd party, in local database
- This service also have the capability to check the disbursement status, and update the information on database according to the response get


## What is this project doing

- Use PHP Language
- Use MySQL Database
- Not use any framework or any external libraries
- Save the information get from the API response (third party), to local database
- Update the information get from the disbursement status endpoint, to the related transaction in local database. 
The information that must be updated when check the disbursement status are the following: `status`, `receipt`, `time_served`
- Use git when working on the project


## How to run
- Open terminal, go to the directory where you want to save the project
- Clone this repository `git clone https://github.com/kamalludin/disbursement_via_flip.git` (make sure that you have installed git)
- If you have not installed PHP, please install by opening the link [here](https://www.geeksforgeeks.org/how-to-execute-php-code-using-command-line/)
- Run a database migration by typing `php disbursement_via_flip/migrate.php` for database migrations as needed (make sure that you have installed MySQL and have it running)
- Run the project by typing `php -S localhost:8080 -t disbursement_via_flip/`
- Open your Rest Client Application like `Postman`, then try this Restful API


### List of service

## General

Base url for all request is:
`localhost:8080`


### Disbursement Request

This service is to request disbursement via the flip by sending the necessary data, including `bank_code`, `account_number`, `amount` and `remark`.

## Request

```http
POST /disbursementRequest HTTP/1.1
Content-Type: application/x-www-form-urlencoded
```
Attribute:
- `bank_code`
- `account_number`
- `amount`
- `remark`

## Response

```json
Status 200
Content-Type: application/json

{
    "message": "success",
    "data": {
        "disbursement_id": "5f081aeb643f8",
        "status": "PENDING"
    }
}
```

### Check Disbursement Status

This service is for checking the status of disbursement by sending an id received when requesting a disbursement.

## Request

```http
POST /checkDisbursementStatus HTTP/1.1
Content-Type: application/x-www-form-urlencoded
```
Attribute:
- `disbursement_id`

## Response

```json
Status 200
Content-Type: application/json

{
    "message": "success",
    "data": {
        "disbursement_id": "5f081aeb643f8",
        "status": "SUCCESS"
    }
}
```

## Additional
This simple project uses only simple routing with a switch case, can be developed further with a better routing.


#### Happy Coding :)
