This file describes the changes done to the original codebase in order to complete the task. The changes are described
in the following sections:

1. [Changes to project structure](#changes-to-project-structure)
2. [Changes to `api.php`](#changes-to-apiphp)
3. [Changes to `index.php`](#changes-to-indexphp)

## Changes to project structure
The following changes were done to the project structure in order to complete the task:
1. Created a new structure for the project based on a DDD architecture to separate the different layers of the
application and centralize the logic for each layer
Observations: A DDD architecture was chosen because it allows for a better separation of concerns and a more organized
structure for the application. However, a DDD architecture might have more layers, the goal was to keep it simple and
well organized

2. The structure is as follows:
  a. `src` - Contains the source code for the application
    i. `Application` - Contains the application services. The application services are responsible for handling the data
    from incoming requests using the domain entities and value objects
    ii. `Domain` - Contains the domain entities and value objects. The domain entities are the core of the application.
    iii. `Infrastructure` - Contains the infrastructure code for the application. The infrastructure code is responsible
    for useful tools and services that are used by the application but, not part of the core business logic.

## Changes to `api.php`
The following changes were done to `api.php` in order to complete the task:
1. Refactored the code to use a switch statement to handle the different request scenarios
2. Centralized the logic for handling the different request scenarios in separate classes. The classes are located in
the `src/Application` and `src/Infrastructure` directories.

The performance issues found in the original codebase were:
1. The code was not organized in a way that made it easy to understand and maintain
2. Inefficient `strpos` Search:
The code uses `strpos` in a loop to match the prefix search with a `strtolower` function.
This could be replaced by a `stripos` function that is case-insensitive and returns the position of the first occurrence
of a substring in a string as it is searching for the prefix.

The security issues found in the original codebase were:
1. Input Validation and Sanitization:
The $_GET['title'] and $_GET['prefixsearch'] inputs are used directly without any validation or sanitization.
This could allow injection attacks or unintended behavior if the inputs are malformed or malicious.
2. No Rate Limiting or Input Length Validation:
The prefixsearch parameter could be abused with overly long strings, leading to denial-of-service (DoS) attacks by
overloading the loop or the string search mechanism.

## Changes to `index.php`
The following changes were done to `index.php` in order to complete the task:
1. Refactored the code separating the HTML from the PHP logic. The HTML was moved to a separate file located in the
`templaate` directory. This directory also contains the CSS and JS files for the application.
2. Used the centralized logic for handling the different request scenarios in the classed created for the `api.php` file.
3. Moved the `wordCount` function to the `src/Domain/Article` class to centralize the logic as it already had the
   directory specified, avoiding the use of the global variable

The performance issues found in the original codebase were:
1. The code was not organized in a way that made it easy to understand and maintain
2. The code was mixing HTML and PHP logic, making it harder to read and maintain
3. The code was using global variables to store the data, which could lead to unexpected behavior and make the code
   harder to test
4. The wordCount function could be inefficient for large texts, as it was using an `explode` function to split the text
   into words and then count them. This could be replaced by a more efficient algorithm that counts the words directly
   from the text.

The security issues found in the original codebase were:
1. Input Validation and Sanitization:
The $_GET['title'] and $_GET['body] inputs are used directly without any validation or sanitization.
2. The wordCount function could be abused with overly long strings, leading to denial-of-service (DoS) attacks by
   overloading the function with a large text.
3. The wordCount function was not taking the file extension into account, which could lead to unexpected behavior if the
   file was not a text file and could have a malicious code.

### Note
There was not time to implement the following TODOs:
// TODO F: Implement a simple unit test to ensure the correctness of different parts
// of the application.

