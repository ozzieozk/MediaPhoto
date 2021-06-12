<?php

namespace mediaphoto\controller;

use mediaphoto\view\MediaPhotoView;

class MediaPhotoAuthController extends \mf\control\AbstractController {
    public function __construct()
    {
        parent::__construct();
    }

    public function login() {
        MediaPhotoView::addStyleSheet(\mediaphoto\view\MediaPhotoView::$app_url . '/html/assets/css/styleLogin.css');
        $auth = new \mediaphoto\auth\MediaPhotoAuthentification();
        if($auth->logged_in) {
            $router = new \mf\router\Router();
            header('Location:' . $router->urlFor('home'));
            exit;
        } else {
            $view = new \mediaphoto\view\MediaPhotoView([]);
            return $view->render('renderViewLogin');
        }
    }

    public function checkLogin() {
        MediaPhotoView::addStyleSheet(\mediaphoto\view\MediaPhotoView::$app_url . '/html/assets/css/styleLogin.css');
        $auth = new \mediaphoto\auth\MediaPhotoAuthentification();
        if($auth->logged_in) {
            $router = new \mf\router\Router();
            header('Location:' . $router->urlFor('home'));
            exit;
        } else {
            if(isset($this->request->post['submit'])) {
                $name = $this->request->post['name'];
                $password = $this->request->post['password'];
                if(empty($name) || empty($password)) {
                    $auth->generateMessage('login_error', array('Veuillez renseigner tous les champs.', 'red'), 'viewLogin');
                } else {
                    try {
                        $name = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS);
                        $password = filter_var($password, FILTER_SANITIZE_SPECIAL_CHARS);
    
                        $auth->loginUser($name, $password);
    
                        // Suppression du message d'erreur stocké en mémoire
                        unset($_SESSION['login_error']);
    
                        $router = new \mf\router\Router();
                        header('Location:' . $router->urlFor('home'));
                        exit;
                    } catch (\mf\auth\exception\AuthentificationException $e) {
                        $auth->generateMessage('login_error', array($e->getMessage(), 'red'), 'viewLogin');
                    }
                }
            } else {
                $router = new \mf\router\Router();
                header('Location:' . $router->urlFor('viewLogin'));
                exit;
            }
        }
    }

    public function signup() {
        MediaPhotoView::addStyleSheet(\mediaphoto\view\MediaPhotoView::$app_url . '/html/assets/css/styleLogin.css');
        $auth = new \mediaphoto\auth\MediaPhotoAuthentification();
        if($auth->logged_in) {
            $router = new \mf\router\Router();
            header('Location:' . $router->urlFor('home'));
            exit;
        } else {
            $view = new \mediaphoto\view\MediaPhotoView([]);
            return $view->render('renderViewSignup');
        }
    }

    public function checkSignup() {
        MediaPhotoView::addStyleSheet(\mediaphoto\view\MediaPhotoView::$app_url . '/html/assets/css/styleLogin.css');
        $auth = new \mediaphoto\auth\MediaPhotoAuthentification();
        if($auth->logged_in) {
            $router = new \mf\router\Router();
            header('Location:' . $router->urlFor('home'));
            exit;
        } else {
            if(isset($this->request->post['submit'])) {
                $username = $this->request->post['username'];
                $name = $this->request->post['name'];
                $password = $this->request->post['password'];
                $password_confirmation = $this->request->post['password_confirmation'];
                if(empty($username) || empty($name) || empty($password) || empty($password_confirmation)) {
                    $auth->generateMessage('signup_error', array('Veuillez renseigner tous les champs.', 'red'), 'viewSignup');
                } else {
                    try {
                        $username = filter_var($username, FILTER_SANITIZE_SPECIAL_CHARS);
                        $name = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS);
                        $password = filter_var($password, FILTER_SANITIZE_SPECIAL_CHARS);
                        $password_confirmation = filter_var($password_confirmation, FILTER_SANITIZE_SPECIAL_CHARS);

                        if($auth->passwordConfirmation($password, $password_confirmation)) {
                            $auth->createUser($username, $name, $password);

                            // Suppression du message d'erreur stocké en mémoire
                            unset($_SESSION['signup_error']);
                            
                            $router = new \mf\router\Router();
                            header('Location:' . $router->urlFor('home'));
                            exit;
                        } else {
                            $auth->generateMessage('signup_error', array('Les mots de passe ne correspondent pas.', 'red'), 'viewSignup');
                        }
                    } catch(\mf\auth\exception\AuthentificationException $e) {
                        $auth->generateMessage('signup_error', array($e->getMessage(), 'red'), 'viewSignup');
                    }
                }
            } else {
                $router = new \mf\router\Router();
                header('Location:' . $router->urlFor('viewSignup'));
                exit;
            }
        }
    }

    public function logout() {
        $auth = new \mediaphoto\auth\MediaPhotoAuthentification();
        $auth->logout();
        $router = new \mf\router\Router();
        header('Location:' . $router->urlFor('home'));
        exit;
    }

    public function changePassword() {
        MediaPhotoView::addStyleSheet(\mediaphoto\view\MediaPhotoView::$app_url . '/html/assets/css/styleLogin.css');
        $auth = new \mediaphoto\auth\MediaPhotoAuthentification();
        if(!$auth->logged_in) {
            $router = new \mf\router\Router();
            header('Location:' . $router->urlFor('viewLogin'));
            exit;
        } else {
            $view = new \mediaphoto\view\MediaPhotoView([]);
            return $view->render('renderViewPassword');
        }
    }

    public function checkChangePassword() {
        MediaPhotoView::addStyleSheet(\mediaphoto\view\MediaPhotoView::$app_url . '/html/assets/css/styleLogin.css');
        $auth = new \mediaphoto\auth\MediaPhotoAuthentification();
        if(!$auth->logged_in) {
            $router = new \mf\router\Router();
            header('Location:' . $router->urlFor('viewLogin'));
            exit;
        } else {
            if(isset($this->request->post['submit'])) {
                $currentPassword = filter_var($this->request->post['currentPassword']);
                $newPassword = filter_var($this->request->post['newPassword']);
                $newPasswordConfirmation = filter_var($this->request->post['newPasswordConfirmation']);

                if(empty($currentPassword) || empty($newPassword) || empty($newPasswordConfirmation)) {
                    $auth->generateMessage('password_info', array('Veuillez renseigner tous les champs.', 'red'), 'viewPassword');
                } else {
                    if($auth->verifyCurrentPassword($_SESSION['user_login'], $currentPassword)) {
                        if($auth->passwordConfirmation($newPassword, $newPasswordConfirmation)) {
                            $auth->changePassword($_SESSION['user_login'], $newPassword);

                            $auth->generateMessage('password_info', array('Votre mot de passe a bien été modifié.', 'green'), 'viewPassword');
                        } else {
                            $auth->generateMessage('password_info', array('Les mots de passe ne correspondent pas.', 'red'), 'viewPassword');
                        }
                    } else {
                        $auth->generateMessage('password_info', array('Le mot de passe actuel est incorrect.', 'red'), 'viewPassword');
                    }
                }
            } else {
                $router = new \mf\router\Router();
                header('Location:' . $router->urlFor('viewPassword'));
                exit;
            }
        }
    }
}