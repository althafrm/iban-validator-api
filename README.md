**Setting up and Running the IBAN Validator API (Laravel):**

1. **Clone the Repository:**

   Clone the repository containing the Laravel API project to your local machine:

   ```bash
   git clone <repository-url>
   cd <api-project-directory>
   ```

2. **Install Dependencies:**

   Navigate to the API project directory and install the required dependencies using Composer:

   ```bash
   cd iban-validator-api
   composer install
   ```

3. **Environment Configuration:**

   Create a copy of the `.env.example` file and rename it to `.env`. Update the environment variables for database credentials:

   ```bash
   cp .env.example .env
   ```

   ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=iban_validator
    DB_USERNAME=root
    DB_PASSWORD=
   ```

4. **Generate Application Key:**

   Generate a unique application key:

   ```bash
   php artisan key:generate
   ```

5. **Database Setup:**

   Create the database tables and seed the initial data:

   ```bash
   php artisan migrate --seed
   ```

6. **Start the Server:**

   Run the development server:

   ```bash
   php artisan serve
   ```

   IBAN Validator API should now be running at `http://localhost:8000`.

7. **API Endpoints:**

    Below are the available API endpoints along with the relevant request bodies those accept:

    User Registration

    - Endpoint: [POST] /api/register
    - Request Body:
    ```json
    {
        "name": "John Doe",
        "email": "john@example.com",
        "password": "secretpassword",
        "password_confirmation": "secretpassword"
    }
    ```

    User Login

    - Endpoint: [POST] /api/login
    - Request Body:
    ```json
    {
        "email": "john@example.com",
        "password": "secretpassword"
    }
    ```

    User Logout

    - Endpoint: [POST] /api/logout
    - Headers:
    ```
    Authorization: Bearer <access_token>
    ```

    List IBANs

    - Endpoint: [POST] /api/iban/list
    - Request Body:
    ```json
    {
        "page": 1,
        "perPage": 10
    }
    ```
    - Headers:
    ```
    Authorization: Bearer <access_token>
    ```

    Validate IBAN

    - Endpoint: [POST] /api/iban/validate
    - Request Body:
    ```json
    {
        "iban": "GB82WEST12345698765432"
    }
    ```
    - Headers:
    ```
    Authorization: Bearer <access_token>
    ```
