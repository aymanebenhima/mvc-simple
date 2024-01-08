## Installation and Usage

Follow these steps to install and use this repository:

1. **Clone the repository**

   First, clone the repository to your local machine:

```bash
   git clone https://github.com/aymanebenhima/mvc-simple.git
```
2. Install dependencies

This project uses Composer to manage dependencies. To install the dependencies, navigate to the project directory and run:
```bash
composer install
```
This command will install all the dependencies defined in the composer.json file.

3. Usage

After installing the dependencies, you can start using the project. Here's a brief explanation of how the routing works:

   The `routes/web.php` file is where you define your routes. Each route is associated with a controller and a method within that controller. The format for defining a route is:

```php
   $router->post('/url/path', 'Namespace\ControllerClass@methodName');
```

For Example the following line:
```php
$router->post('/admin/posts/delete/:id', 'App\Controllers\Admin\PostController@destroy');
```

This defines a route for the URL path /admin/posts/delete/:id. When this route is hit with a POST request, the destroy method in the PostController under the App\Controllers\Admin namespace is called. The :id in the URL path is a placeholder for a parameter that will be passed to the destroy method.

To define a new route, add a new line in the web.php file following the format above.

The line 
```php
$router->get('/posts', 'App\Controllers\BlogController@index');
```
in your web.php file is defining a route in your application.

Here's a breakdown:

**$router->get** is calling the get method on the $router object. This method is used to define a route that responds to HTTP GET requests.

**'/posts'** is the URL path for the route. When someone visits your website at a path that matches /posts, this route will be triggered.

**'App\Controllers\BlogController@index'** is a string that tells the router what code to execute when the route is triggered. This string is in the format ControllerClass@methodName.

**App\Controllers\BlogController** is the fully qualified class name of the controller. This means that there is a BlogController class in the App\Controllers namespace, which should be defined in the BlogController.php file in your workspace.

**@index** means that the index method of the BlogController class should be called when the route is triggered.

## Add views:

In a typical MVC (Model-View-Controller) structure in PHP, views are used to display data received from the controller. Here's a simple way to add views to your project:

1. Create a views directory: Create a new directory named views in your project root.

2. Create a view file: Inside the views directory, create a new directory name post for your view. For example, you might create a index.php file for displaying posts.

3. Add HTML to your view: In your view file, you can add HTML code. You can use PHP tags to insert dynamic content.

```php
<!-- post/index.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Posts</title>
</head>
<body>
    <h1>Posts</h1>
    <?php foreach ($posts as $post): ?>
        <h2><?= $post->title ?></h2>
        <p><?= $post->content ?></p>
    <?php endforeach; ?>
</body>
</html>
```
4. Load the view from your controller: In your controller, you can load the view and pass data to it. Here's how you might do it in your Post and PostController:

```bash
php generate.php Post -mc
```

In this example, the index method fetches all posts from the database, then loads the posts.php view. The view can access the $posts variable because it's in the same scope.

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
|-- generate.php
```
## Generating Models and Controllers

This project includes a script that can generate models and controllers for you. Here's how you can use it:

1. Open a terminal in the project's root directory.

2. Run the `generate.php` script with the name of the class you want to generate and flags to indicate what you want to generate. The class name should be capitalized.

   For example, to generate a `User` model and controller, you would run:

   ```bash
   php generate.php User -mc
   ```   
This command will generate a *User* model in *app/Models/User.php* and a *UserController* in *app/Controllers/UserController.php*.

The -m flag indicates that you want to generate a model, and the -c flag indicates that you want to generate a controller. You can use either flag alone to generate only a model or controller, or use them together to generate both.

After running the command, you should see messages indicating that the model and controller were created successfully.

**Please note that the generate.php script expects the class name to be capitalized. If you provide a class name that's not capitalized, the script will print an error message and exit.**

This should provide clear instructions for anyone who wants to use the generate.php script to generate models and controllers.

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
