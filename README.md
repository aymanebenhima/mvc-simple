## Project Structure:

```
.
|-- app
| |-- config
| | |-- app.php
| |-- Controllers
| | |-- Controller.php
| |-- Exceptions
| | |-- NotFoundException.php
| |-- Middleware
| | |-- RedirectIfAdmin.php
| |-- Models
| | |-- Model.php
| |-- Validation
| |-- Validator.php
|-- database
| |-- Connection.php
|-- public
| |-- assets
| |-- robots.txt
| |-- .env
| |-- index.php
|-- routes
| |-- Route.php
| |-- Router.php
| |-- web.php
|-- vendor
|-- views
|-- .env.example
|-- .gitignore
|-- .htaccess
|-- composer.json
```

## Explain important files
### Connection.php
This Connection class is responsible for managing the connection to a database. Here's a brief explanation of each class method:

- __construct: This method is the constructor for the class. It initializes the private properties $dbname, $host, $username, and $password with the provided values.

- getInstance: This method retrieves the singleton instance of the Connection class. It takes the same parameters as the constructor and creates a new instance if it doesn't exist.

- getPDO: This method retrieves the PDO object for the database connection. It returns the existing $pdo property if it exists, or creates a new PDO object using the stored database credentials.

### Controller.php
This class is an abstract base controller that provides some common functionality for other controllers in the application.

- The __construct method initializes a new instance of the class and sets the database connection.
- The view method renders a view by including a specified PHP file and passing optional parameters.
- The getDb method retrieves the database object.

### Model.php
This class is an abstract model that provides basic CRUD (Create, Read, Update, Delete) functionality for interacting with a database table. Here's a summary of each class method:

- __construct: Initializes the class with a database connection object.
- all: Retrieves all records from the table.
- findById: Finds a record in the database by its ID.
- create: Creates a new record in the database table.
- update: Updates a record in the database.
- destroy: Deletes a record from the database table.
- query: Executes a SQL query on the database.

### Route.php
This class is a Route class that handles matching URLs against a given path and executing PHP functions based on the matched route. Here's what each method does:

- __construct($path, $action): Constructs a new instance of the class and sets the path and action properties.
- matches(string $url): Matches the given URL against the path of the current object. It uses regular expressions to match dynamic segments in the path.
- execute(): Executes the PHP function specified in the action property. It creates a new instance of the controller class and calls the specified method, passing any matched dynamic segments as parameters.

### Router.php
This class is a router that handles HTTP routes. Here's what each class method does:

- __construct($url): Initializes the router with a URL.
- get($path, $action): Adds a new GET route to the routes array.
- post($path, $action): Adds a new POST route to the routes array.
- run(): Iterates through the routes and executes the matching route based on the requested URL. If no route matches, it throws a NotFoundException.

### Validator.php
This Validator class is used to validate data based on a set of rules. Here's what each class method does:

- __construct(array $data): Initializes the Validator object with the provided data.
- validate(array $rules): ?array: Validates an array of rules. It iterates through each rule and performs the corresponding validation method based on the rule name.
- required(string $field, string $value): Validates if a field is required. It checks if the value is empty or null and adds an error message if it is.
- email(string $field, string $value): Validates if a field contains a valid email address. It uses the FILTER_VALIDATE_EMAIL filter to check if the value is a valid email address.
- numeric(string $field, string $value): Validates if a field contains a numeric value. It checks if the value is numeric using the is_numeric function.
- unique(string $field, string $value, string $table, string $column): Validates if a value is unique in a given database table and column. It performs a database query to check for uniqueness and adds an error message if the value is not unique.
- date(string $field, string $value): Validates if a field contains a valid date. It uses the DateTime::createFromFormat method to check if the value can be parsed as a valid date.
- min(string $field, string $value, string $rule): Validates if the length of a given value is greater than or equal to a specified limit. It extracts the limit from the rule and checks if the length of the value is less than the limit.
- max(string $field, string $value, string $rule): Validates if the length of a string is less than or equal to a specified limit. It extracts the limit from the rule and checks if the length of the value exceeds the limit.
- getErrors(): ?array: Retrieves the errors, if any, from the class. It returns the array of errors or null if there are no errors.
