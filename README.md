# Conference Web API
A backend web API built for a graded Software Architecture module during my time at Northumbria University. 

I acheived [grade]

## Project details
The API was built in PHP using Visual Studio Code, and all testing was done using Postman.

It was ran on a provided web server, using WinSCP to upload the files. As I did not own the web server, I cannot provide a link.

The API acts as an interface to an SQLite database which contains information about an academic conference, linked here: (linked here: https://programs.sigchi.org/chi/2023). The database includes author details and affiliations, content details, awards, and more.

There are 5 endpoints which, together, enable the creation, deletion, and modification of awards, giving and taking awards to content, and searching content and authors. These endpoints are:
- /assignment-1/Author
  - GET parameters: author_id / content_id / search (by author name) / page
- /assignment-1/Award
  - POST parameters: name (unique award name)
  - PATCH parameters: award_id + name (unique award name)
  - DELETE parameters: award_id
- /assignment-1/Content
  - GET parameters: content_id / author_id / search (by content name or abstract) / page
- /assignment-1/Developer
- /assignment-1/Manager
  - POST parameters: content_id + award_id
  - DELETE parameters: content_id

The API also has basic built-in security, multi-parameter support, clean-URL's, and intuitive exception handling with consistent HTTP response codes.

## Marking Criteria
This criteria is taken from the assignment brief, and was followed rigorously throughout development.

- Your code must be Object-Oriented.
- Your code must use a ‘front controller’ pattern and an .htaccess file should be used to enforce a single point of entry. No other design patterns are required but the code must be well structured and organised.
- Your code must use an autoloader and an exception handler.
- You must use an appropriate coding style following the recommendations in PHP FIG PSR-1 and PSR-12 as well as the guidance on the module. You must follow the PHP-FIG PSR-5 PHPDoc draft standard when commenting code. Tags from section 5 of PSR-19 should also be used in the comments where appropriate.
- There must not be any redundant code, including commented-out code.
- You must not use third-party libraries or build tools such as Composer.
- The REST API must use the SQLite database supplied.
- A unique key should be used to secure the API. This must be supplied in the authorisation headers.
- Parameter and input data should be sanitised.
- Requests to the REST API must be via HTTP. Endpoints should be restricted to the request methods stated for each. In addition, OPTIONS requests must be supported.
- The API must use ‘clean URLs’ (file extensions such as .php should not appear in URLs).
- Parameters included in requests must be supplied either as part of the URL query, within the request body as form-data, or as authorisation headers.
- It must be possible to combine parameters.
- Endpoints and parameters must not be case sensitive.
- Response data must be in valid JSON format.
- Appropriate HTTP status codes must be included in each response.
- An endpoint must have a uniform response (must always structure data in the same way and use the same key names for each response).
- The REST API must respond to invalid or rejected requests appropriately.
