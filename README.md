# _ with PHP_

#### _A basic web application which , September 9, 2016_

#### By _**Stephen Burden**_

## Description
_This application is . It from user's form inputs._

## Specifications
| Behavior | Input Ex. | Output Ex. |
| --- | --- | --- |
| Add a book to the catalog | "Moby Dick" | "Moby Dick" |
| Select a book from the catalog | "Moby Dick" | "Moby Dick", id |
| Update a book in the catalog | rename "Moby Dick" to "The Great Gatsby" | "The Great Gatsby" |
| Remove a book from the catalog | remove "Moby Dick" | nothing in catalog |
| Search for a book by id | find book 1 | "Moby Dick" |
| Search for a book by title | find "Moby Dick" | "Moby Dick" |
| Add an author | "Herman Melville" | "Herman Melville" |
| Select an author | "Herman Melville" | "Herman Melville", id |
| Update an author | rename "Herman Melville" to "F. Scott Fitzgerald" | "F. Scott Fitzgerald" |
| Remove an author | remove "Herman Melville" | nothing in catalog |
| Add an author to a title | "Moby Dick" by "Herman Melville" | "Moby Dick" - "Herman Melville" |
| Search for a book by author | "Herman Melville" | "Moby Dick"|
| Adjust number of copies of a book in the library | add a second copy of "Moby Dick" | copies: 2|
| Add a title for an author | add "The Great Gatsby" for "F. Scott Fitzgerald" | "The Great Gatsby"|
| Check out a copy of a book | Bill checks out Moby Dick | Bill's Books: Moby Dick, 2016-10-05, "checked out" |
| Check out a copy of a book | Bill checks out Moby Dick | Bill's Books: Moby Dick, 2016-10-05, "checked out" |
| Check books past due |  | Bill Jones, "Moby Dick", 2016-10-05, PAST DUE |

## Setup/Installation Requirements
* _Clone the repository from the link below to your desktop_
* _Run Composer Install to include all dependencies_
* _In Terminal or Command Prompt go to the /web directory and enter the command: php -S localhost:8000_
* _To browse go to http://localhost:8000/ in the browser of your choosing_

## Link
https://github.com/spburden/-php

## Known Bugs
_There are no known bugs with this application._

## Support and contact details
_spburden@hotmail.com_

## Technologies Used
_PHP, Silex, Twig, PHP Unit, HTML, and Bootstrap_

### License
The MIT License (MIT)

Copyright (c) 2016 **_Stephen Burden_**
