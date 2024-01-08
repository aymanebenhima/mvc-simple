<?php

// Define the base directory for your controllers and models
define('CONTROLLER_DIR', __DIR__ . '/app/Controllers/');
define('MODEL_DIR', __DIR__ . '/app/Models/');

// Get the name of the class and the flags from the command line arguments
$className = $argv[1];
$flags = $argv[2];

// Check if the first character of the class name is capitalized
if ($className !== ucfirst($className)) {
    echo "Error: Class name should be capitalized.\n";
    exit(1);
}

$lowerClassName = strtolower($className);

// Check if the 'm' flag is set
if (strpos($flags, 'm') !== false) {
    // Create the model file
    $modelFile = fopen(MODEL_DIR . $className . '.php', 'w');
    $modelContent = "<?php\n\nnamespace App\Models;\n\nclass $className extends Model {\n\n}";
    fwrite($modelFile, $modelContent);
    fclose($modelFile);

    echo "Model $className created successfully.\n";
}

// Check if the 'c' flag is set
if (strpos($flags, 'c') !== false) {
    // Create the controller file
    $controllerFile = fopen(CONTROLLER_DIR . $className . 'Controller.php', 'w');
    $controllerContent = <<<EOT
    <?php
    
    namespace App\Controllers;
    
    use App\Controllers\Controller;
    use App\Models\\$className;
    
    class {$className}Controller extends Controller {
        /**
         * Retrieves all {$lowerClassName} from the database and displays them on the index page.
         *
         * @return Some_Return_Value
         */
        public function index()
        {
            \${$lowerClassName}s = (new {$className}(\$this->getDB()))->all();

            return \$this->view('{$lowerClassName}.index', compact('{$lowerClassName}s'));
        }

        /**
         * Show function.
         *
         * Retrieves a {$lowerClassName} from the database using the provided ID and displays it on the {$lowerClassName}.show view.
         *
         * @param int \$id The ID of the {$lowerClassName} to retrieve.
         * @return View The rendered {$lowerClassName}.show view with the retrieved {$lowerClassName}.
         */
        public function show(int \$id)
        {
            \${$lowerClassName} = new {$className}(\$this->getDB());
            \${$lowerClassName} = \${$lowerClassName}->findById(\$id);

            return \$this->view('{$lowerClassName}.show', compact('{$lowerClassName}'));
        }

        /**
         * Creates a new {$lowerClassName}.
         *
         * @return void
         */
        public function create{$className}()
        {

            \${$lowerClassName} = new {$className}(\$this->getDB());

            \$result = \${$lowerClassName}->create(\$_POST);

            if (\$result) {
                return header('Location: /{$lowerClassName}s');
            }
        }

        /**
         * Edit a post by ID.
         *
         * @param int \$id The ID of the {$lowerClassName} to edit.
         * @throws Some_Exception_Class If an error occurs while editing the {$lowerClassName}.
         * @return View The view for the {$lowerClassName} edit form.
         */
        public function edit(int \$id)
        {

            \${$lowerClassName} = (new {$className}(\$this->getDB()))->findById(\$id);

            return \$this->view('{$lowerClassName}.form', compact('{$lowerClassName}'));
        }

        /**
         * Updates a {$lowerClassName} with the given ID.
         *
         * @param int \$id The ID of the post to update.
         * @throws Some_Exception_Class If an error occurs during the update process.
         * @return void
         */
        public function update(int \$id)
        {
            \${$lowerClassName} = new {$className}(\$this->getDB());

            \$result = \${$lowerClassName}->update(\$id, \$_POST);

            if (\$result) {
                return header('Location: /{$lowerClassName}s');
            }
        }

        /**
         * Destroy a {$lowerClassName} by ID.
         *
         * @param int \$id The ID of the {$lowerClassName} to be destroyed.
         * @return void
         */
        public function destroy(int \$id)
        {
            \${$lowerClassName} = new {$className}(\$this->getDB());
            \$result = \${$lowerClassName}->destroy(\$id);

            if (\$result) {
                return header('Location: /{$lowerClassName}s');
            }
        }
    }
    EOT;


    fwrite($controllerFile, $controllerContent);
    fclose($controllerFile);

    echo "Controller {$className}Controller created successfully.\n";
}