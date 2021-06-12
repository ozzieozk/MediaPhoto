<?php

session_start();

require_once 'vendor/autoload.php';
require_once 'src/mf/utils/AbstractClassLoader.php';
require_once 'src/mf/utils/ClassLoader.php';

$loader = new \mf\utils\ClassLoader('src');
$loader->register();

use mediaphoto\view\MediaPhotoView;
use mediaphoto\auth\MediaPhotoAuthentification;

// URL du site
MediaPhotoView::setAppUrl('http://localhost:8000');
// Titre de la page
MediaPhotoView::setAppTitle('MediaPhoto');

// Importation police
MediaPhotoView::addFont('https://fonts.googleapis.com/css2?family=Lato:wght@300;400&family=Marvel:wght@300;400&display=swap');

// Importation de la feuille de style
MediaPhotoView::addStyleSheet('https://necolas.github.io/normalize.css/8.0.1/normalize.css');
MediaPhotoView::addStyleSheet(MediaPhotoView::$app_url . '/html/assets/css/utils.css');

$config = parse_ini_file('conf/config.ini');
$db = new \Illuminate\Database\Capsule\Manager();
$db->addConnection($config);
$db->setAsGlobal();
$db->bootEloquent();

$router = new \mf\router\Router();

// Routes générales
$router->addRoute('home', '/', '\mediaphoto\controller\MediaPhotoController', 'viewHome', MediaPhotoAuthentification::ACCESS_LEVEL_NONE);
$router->addRoute('viewGallery', '/gallery/', '\mediaphoto\controller\MediaPhotoController', 'viewGallery', MediaPhotoAuthentification::ACCESS_LEVEL_NONE);
$router->addRoute('viewPhoto', '/photo/', '\mediaphoto\controller\MediaPhotoController', 'viewPhoto', MediaPhotoAuthentification::ACCESS_LEVEL_NONE);
$router->addRoute('viewMyPictures', '/my_photos/', '\mediaphoto\controller\MediaPhotoController', 'viewMyPictures', MediaPhotoAuthentification::ACCESS_LEVEL_USER);

// Routes de recherche
$router->addRoute('viewSearch', '/search/', '\mediaphoto\controller\MediaPhotoController', 'viewSearch', MediaPhotoAuthentification::ACCESS_LEVEL_NONE);
$router->addRoute('checkSearch', '/check_search/', '\mediaphoto\controller\MediaPhotoController', 'checkSearch', MediaPhotoAuthentification::ACCESS_LEVEL_NONE);

// Routes création d'une galerie
$router->addRoute('viewCreateGallery', '/create_gallery/', '\mediaphoto\controller\MediaPhotoController', 'viewCreateGallery', MediaPhotoAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('checkCreateGallery', '/check_create_gallery/', '\mediaphoto\controller\MediaPhotoController', 'checkCreateGallery', MediaPhotoAuthentification::ACCESS_LEVEL_USER);

// Routes de publication d'une photo
$router->addRoute('viewPostPhoto', '/post_photo/', '\mediaphoto\controller\MediaPhotoController', 'viewPostPhoto', MediaPhotoAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('checkPostPhoto', '/check_post_photo/', '\mediaphoto\controller\MediaPhotoController', 'checkPostPhoto', MediaPhotoAuthentification::ACCESS_LEVEL_USER);

// Routes modification de mot de passe
$router->addRoute('viewPassword', '/password/', '\mediaphoto\controller\MediaPhotoAuthController', 'changePassword', MediaPhotoAuthentification::ACCESS_LEVEL_USER);
$router->addRoute('checkPassword', '/check_password/', '\mediaphoto\controller\MediaPhotoAuthController', 'checkChangePassword', MediaPhotoAuthentification::ACCESS_LEVEL_USER);

// Routes de connexion
$router->addRoute('viewLogin', '/login/', '\mediaphoto\controller\MediaPhotoAuthController', 'login', MediaPhotoAuthentification::ACCESS_LEVEL_NONE);
$router->addRoute('checkLogin', '/check_login/', '\mediaphoto\controller\MediaPhotoAuthController', 'checkLogin', MediaPhotoAuthentification::ACCESS_LEVEL_NONE);

// Routes d'inscription
$router->addRoute('viewSignup', '/signup/', '\mediaphoto\controller\MediaPhotoAuthController', 'signup', MediaPhotoAuthentification::ACCESS_LEVEL_NONE);
$router->addRoute('checkSignup', '/check_signup/', '\mediaphoto\controller\MediaPhotoAuthController', 'checkSignup', MediaPhotoAuthentification::ACCESS_LEVEL_NONE);

// Route de déconnexion
$router->addRoute('viewLogout', '/logout/', '\mediaphoto\controller\MediaPhotoAuthController', 'logout', MediaPhotoAuthentification::ACCESS_LEVEL_USER);

$router->setDefaultRoute('/');
$router->run();