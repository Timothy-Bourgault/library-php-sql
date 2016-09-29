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

    $app->get("/", function() use ($app) {
        $books = Book::getAll();
        $authors = Author::getAll();
        $patrons = Patron::getAll();
        return $app['twig']->render('index.html.twig', array('books' => $books, 'authors' => $authors, 'patrons' => $patrons));
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



    return $app;
?>
