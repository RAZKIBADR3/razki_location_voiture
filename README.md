* Application:
   * Information:
     - Routes:
        * [POST] /api/login_check
        * [GET] /api/cars
        * [GET] /api/cars/{id}
        * [POST] /api/reservations
        * [GET] /api/users/{id}/reservations
        * [PUT] /api/reservations/{id}
        * [DELETE] /api/reservations/{id}
   
   * Configuration:
      - to install modules and packages:
       # composer update
  
      - create JWT keys(public,private):
       # symfony console lexik:jwt:generate-keypair

* Database:
  * Information:
    - Database name:
      * adkomo_location
    - Tabels:
      * user[id, email, roles, password]
      * car[id, nom, prix, model, entreprise, color]
      * reservation[id, car_id, user_id, date_debut, date_fin]
    - login fields:
      * email
      * password
      
  * Configuration:
    - for database user privileges you should have adkomo user with password [adkomoroot2024], alternatively use root user, in config .env:
      * DATABASE_URL="mysql://root@127.0.0.1:3306/adkom_location?serverVersion=8&charset=utf8mb4"

    - create database by:
      # symfony console doctrine:database:create

    - update database (to add tables):
      # symfony console doctrine:schema:update --force

  * Extra:
    - you can use this command to create hashed password for user insert:
      # symfony console security:hash-password
