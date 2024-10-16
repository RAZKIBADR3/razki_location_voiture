* for database
  * information:
    - name => adkomo_location
    - tabels =>
        user[id, email, roles, password]
        car[id, nom, prix, model, entreprise, color]
        reservation[id, car_id, user_id, date_debut, date_fin]
      
  * configuration
    - for database user privileges you should have adkomo user with password [adkomoroot2024], alternatively use root user, in config .env
      * DATABASE_URL="mysql://root@127.0.0.1:3306/adkom_location?serverVersion=8&charset=utf8mb4"

    - create database by
      # symfony console doctrine:database:create

    - update database (to add tables)
      # symfony console doctrine:schema:update --force
  
