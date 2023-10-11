# Currency Converter Project

Welcome to the Currency Converter project! This application allows you to convert currencies based on up-to-date exchange rates.

## Project Overview

This project offers several features and functionalities:

- **User Authentication:** Users can log in securely, providing access to various features within the application.

- **User Management:** Once logged in, users have the capability to create new user accounts.

- **IP Whitelisting:** Users can whitelist specific IP addresses. Only individuals with whitelisted IP addresses are allowed access to the application. Currently, only the IP address '::1' is accepted.

- **On-Demand Rate Updates:** Users can trigger on-demand updates for currency exchange rates. Please note that this process may not be instantaneous or run in the background as of the current implementation.


## About This Project
This project is built using the Symfony PHP framework, making use of various Symfony components and best practices for web development.

## Project Setup
To run this project on your local machine, follow these steps:

1. **Clone the Repository:** Start by cloning this repository to your local machine using the following command:
   ```bash
   git clone https://github.com/SamiOuadrhiri/currency-converter.git

2. **Install Dependencies:** 
Navigate to the project directory and install the required dependencies using Composer:
composer install

3. **Database Setup:**
Unfortunatly i wasn't able to create migrations for this project yet, you can download the .sql file here: [https://www.dropbox.com/scl/fi/88bfqy8uc57jnx93oe0qf/currency_converter.sql?rlkey=otybweslph8jyabafbq8ve3yr&dl=0]

4. **Configure the Environment:**
Create a .env.local file or configure your environment variables according to your local database settings and any other necessary configurations. You can use .env as a template

5. **Run the Application:**
You can start the Symfony development server with the following command:
php bin/console server:start

6. **Access the Application:**
You can access the application by opening your web browser and visiting http://localhost:8000. You should now see the Currency Converter in action.