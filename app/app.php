<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Copy.php";
    require_once __DIR__."/../src/Author.php";
    require_once __DIR__."/../src/Book.php";
    require_once __DIR__."/../src/Patron.php";
    date_default_timezone_set('America/Los_Angeles');

    use Symfony\Component\Debug\Debug;
    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    Debug::enable();
    $app = new Silex\Application();
    $app['debug'] = true;

    // //ALTERNATIVE SERVER:
    $server = 'mysql:host=localhost;dbname=library';
    // $server = 'mysql:host=localhost:8889;dbname=library';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
    ));
    $app->register(new Silex\Provider\UrlGeneratorServiceProvider());

    $app->get("/", function() use ($app) {
        $books = Book::getAll();
        $authors = Author::getAll();
        $patrons = Patron::getAll();
        $result_array = array();
        return $app['twig']->render('index.html.twig', array('books' => $books, 'authors' => $authors, 'patrons' => $patrons, 'result_array' =>  $result_array));
    });

    $app->post("/add_book", function() use ($app) {
        $title = $_POST['new_book'];
        $new_book = new Book($title);
        $new_book->save();
        return $app->redirect("/");
    });

    $app->get("/delete_all_books", function() use ($app) {
        Book::deleteAll();
        return $app->redirect("/");
    });

    $app->post("/add_author", function() use ($app) {
        $title = $_POST['new_author'];
        $new_author = new Author($title);
        $new_author->save();
        return $app->redirect("/");
    });

    $app->get("/delete_all_authors", function() use ($app) {
        Author::deleteAll();
        return $app->redirect("/");
    });

    $app->post("/add_patron", function() use ($app) {
        $title = $_POST['new_patron'];
        $new_patron = new Patron($title);
        $new_patron->save();
        return $app->redirect("/");
    });

    $app->get("/delete_all_patrons", function() use ($app) {
        Patron::deleteAll();
        return $app->redirect("/");
    });

    $app->get("/book/{id}", function($id) use ($app) {
        $all_authors = Author::getAll();
        $book = Book::find($id);
        $authors = $book->getBooksAuthors();
        $copies = $book->getCopyInfo();
        return $app['twig']->render('book.html.twig', array('book' => $book, 'authors' => $authors, 'copies' => $copies, 'all_authors' => $all_authors));
    })
    ->bind('book');

    $app->post("/book/{id}/add_author", function($id) use ($app) {
        $author_id = $_POST['author_id'];
        $book = Book::find($id);
        $book->addAuthorbyId($author_id);
        return $app->redirect($app['url_generator']->generate('book', array('id' => $id)));
    });

    $app->get("/author/{id}", function($id) use ($app) {
        $all_books = Book::getAll();
        $author = Author::find($id);
        $author_books = $author->getAuthorsBooks();
        return $app['twig']->render('author.html.twig', array('all_books' => $all_books, 'author' => $author, 'author_books' => $author_books));
    })
    ->bind('author');

    $app->post("/author/{id}/add_book", function($id) use ($app) {
        $book_id = $_POST['book_id'];
        $author = Author::find($id);
        $author->addBookbyId($book_id);
        return $app->redirect($app['url_generator']->generate('author', array('id' => $id)));
    });

    $app->get("/patron/{id}", function($id) use ($app) {
        $patron = Patron::find($id);
        $all_books = Book::getAll();
        $patron_history = $patron->getPatronHistory();
        return $app['twig']->render('patron.html.twig', array('patron_history_entries' => $patron_history, 'patron' => $patron, 'all_books' => $all_books));
    })
    ->bind('patron');

    $app->post("/patron/{id}/checkout", function($id) use($app) {
        $patron = Patron::find($id);
        $book_id = $_POST['book_id'];
        $book = Book::find($book_id);
        $patron->checkoutCopy($book);
        return $app->redirect($app['url_generator']->generate('patron', array('id' => $id)));

    });

    $app->get("/patron/{patronId}/return_copy/{copyId}", function($patronId, $copyId) use($app) {
        $copy = Copy::find($copyId);
        $copy->returnCopy();
        return $app->redirect($app['url_generator']->generate('patron', array('id' => $patronId)));
    });

    $app->post("/search", function() use($app) {
        $title = $_POST['book_title'];
        $result_array = Book::searchTitle($title);
        $books = Book::getAll();
        $authors = Author::getAll();
        $patrons = Patron::getAll();
        return $app['twig']->render('index.html.twig', array('books' => $books, 'authors' => $authors, 'patrons' => $patrons, 'result_array' => $result_array));
    });



    return $app;
?>
