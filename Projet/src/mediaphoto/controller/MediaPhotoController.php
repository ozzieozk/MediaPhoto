<?php

namespace mediaphoto\controller;

use mediaphoto\view\MediaPhotoView;

class MediaPhotoController extends \mf\control\AbstractController {
    public function __construct()
    {
        parent::__construct();
    }

    public function viewHome() {
        MediaPhotoView::addStyleSheet(\mediaphoto\view\MediaPhotoView::$app_url . '/html/assets/css/accueil.css');
        $galleries = \mediaphoto\model\Gallery::select()->orderBy('id', 'desc')->get();
        $vue = new \mediaphoto\view\MediaPhotoView($galleries);
        $vue->render('renderHome');
    }

    public function viewGallery() {
        MediaPhotoView::addStyleSheet(\mediaphoto\view\MediaPhotoView::$app_url . '/html/assets/css/galerie.css');
        MediaPhotoView::addScript(\mediaphoto\view\MediaPhotoView::$app_url . '/html/assets/js/jquery-3.2.1.js');
        MediaPhotoView::addScript(\mediaphoto\view\MediaPhotoView::$app_url . '/html/assets/js/vignette.js');
        if(!isset($this->request->get['id'])) {
            $router = new \mf\router\Router();
            header('Location:' . $router->urlFor('home'));
            exit;
        } else {
            $id = $this->request->get['id'];
            $gallery = \mediaphoto\model\Gallery::select()->where('id', '=', $id)->first();
            $vue = new \mediaphoto\view\MediaPhotoView($gallery);
            $vue->render('renderViewGallery');
        }
    }

    public function viewPhoto() {
        MediaPhotoView::addStyleSheet(\mediaphoto\view\MediaPhotoView::$app_url . '/html/assets/css/detail.css');
        MediaPhotoView::addScript(\mediaphoto\view\MediaPhotoView::$app_url . '/html/assets/js/jquery-3.2.1.js');
        MediaPhotoView::addScript(\mediaphoto\view\MediaPhotoView::$app_url . '/html/assets/js/lightbox.js');
        if(!isset($this->request->get['id'])) {
            $router = new \mf\router\Router();
            header('Location:' . $router->urlFor('home'));
            exit;
        } else {
            $id = $this->request->get['id'];
            $photo = \mediaphoto\model\Photo::select()->where('id', '=', $id)->first();
            $vue = new \mediaphoto\view\MediaPhotoView($photo);
            $vue->render('renderViewPhoto');
        }
    }

    public function viewSearch() {
        MediaPhotoView::addStyleSheet(\mediaphoto\view\MediaPhotoView::$app_url . '/html/assets/css/accueil.css');
        $render = [];
        if(isset($this->request->get['galerie'])) {
            $galleries = \mediaphoto\model\Gallery::select()->where('titre', 'LIKE', '%'.$this->request->get['galerie'].'%')->get();
            $render['galerie'] = $galleries;
        }
        if(isset($this->request->get['photo'])) {
            $photos = \mediaphoto\model\Photo::select()->where('titre', 'LIKE', '%'.$this->request->get['photo'].'%')->get();
            $render['photo'] = $photos;
        }
        $vue = new \mediaphoto\view\MediaPhotoView($render);
        $vue->render('renderViewSearch');
    }

    public function checkSearch() {
        $router = new \mf\router\Router();
        if(isset($this->request->post['submit'])) {
            $filter = [];
            $link = '';
            $i = 0;
            $search = filter_var($this->request->post['search'], FILTER_SANITIZE_SPECIAL_CHARS);

            if(isset($this->request->post['filter-photo'])) {
                $filter[] = $this->request->post['filter-photo'];
            }
            if(isset($this->request->post['filter-galerie'])) {
                $filter[] = $this->request->post['filter-galerie'];
            }

            foreach($filter as $f) {
                // Si premier tour de boucle
                if($i == 0) {
                    $link .= '?' . $f . '=' . $search;
                // Sinon
                } else {
                    $link .= '&' . $f . '=' . $search;
                }
                $i++;
            }

            header('Location:' . $router->urlFor('viewSearch') . $link);
            exit;
        } else {
            $router->executeRoute('home');
        }
    }

    public function viewCreateGallery() {
        MediaPhotoView::addStyleSheet(\mediaphoto\view\MediaPhotoView::$app_url . '/html/assets/css/styleLogin.css');
        MediaPhotoView::addScript(\mediaphoto\view\MediaPhotoView::$app_url . '/html/assets/js/jquery-3.2.1.js');
        MediaPhotoView::addScript(\mediaphoto\view\MediaPhotoView::$app_url . '/html/assets/js/autosearch.js');
        MediaPhotoView::addScript(\mediaphoto\view\MediaPhotoView::$app_url . '/html/assets/js/conf-user.js');
        MediaPhotoView::addScript(\mediaphoto\view\MediaPhotoView::$app_url . '/html/assets/js/block-add-user.js');
        $auth = new \mediaphoto\auth\MediaPhotoAuthentification();
        if(!$auth->logged_in) {
            $router = new \mf\router\Router();
            header('Location:' . $router->urlFor('home'));
            exit;
        } else {
            $vue = new \mediaphoto\view\MediaPhotoView([]);
            $vue->render('renderViewCreateGallery');
        } 
    }

    public function checkCreateGallery() {
        $auth = new \mediaphoto\auth\MediaPhotoAuthentification();
        $router = new \mf\router\Router();
        if(!$auth->logged_in) {
            $router = new \mf\router\Router();
            header('Location:' . $router->urlFor('home'));
            exit;
        } else {
            $post = $this->request->post;
            if(isset($post['submit'])) {
                $title = filter_var($post['galerie-name'], FILTER_SANITIZE_SPECIAL_CHARS);
                $desc = filter_var($post['galerie-desc'], FILTER_SANITIZE_SPECIAL_CHARS);
                $tags = filter_var($post['list-tag'], FILTER_SANITIZE_SPECIAL_CHARS);
                $type = filter_var($post['galerie-conf'], FILTER_SANITIZE_SPECIAL_CHARS);
                $users = filter_var($post['list-user'], FILTER_SANITIZE_SPECIAL_CHARS);
    
                if(empty($title) || empty($tags) || empty($desc) || empty($type)) {
                    $auth->generateMessage('create_gallery_error', array('Veuillez renseigner tous les champs.', 'red'), 'viewCreateGallery');
                } else {
                    $user_id = \mediaphoto\model\User::getLoggedUserId();
                    $tags = explode(',', $tags);
                    $id_tags = [];

                    if(empty($users) && $type == '3') {
                        $auth->generateMessage('create_gallery_error', array('Veuillez partager votre galerie à au moins un utilisateur.', 'red'), 'viewCreateGallery');
                    } else {
                        if(!empty($users)) {
                            $users = explode(',', $users);
                            $id_users = [];
        
                            foreach($users as $u) {
                                $exist = \mediaphoto\model\User::where('nom', '=', $u)->count();
                                if(!$exist) {
                                    $auth->generateMessage('create_gallery_error', array('L\'utilisateur que vous avez spécifié n\'existe pas.', 'red'), 'viewCreateGallery');
                                    exit;
                                } else {
                                    $id_users[] = \mediaphoto\model\User::select('id')->where('nom', '=', $u)->first()->id;
                                }
                            }
        
                            foreach($id_users as $u) {
                                \mediaphoto\model\Share::insert(
                                    ['id_utilisateur' => $u, 'id_galerie' => 0]
                                );
                            }

                            foreach($tags as $t) {
                                $exist = \mediaphoto\model\Tag::where('nom', '=', $t)->count();
                                if(!$exist) {
                                    $lastInsertTagsId = \mediaphoto\model\Tag::insertGetId(
                                        ['nom' => $t]
                                    );
                                    $id_tags[] = $lastInsertTagsId;
                                } else {
                                    $id_tags[] = \mediaphoto\model\Tag::select('id')->where('nom', '=', $t)->first()->id;
                                }
                            }
        
                            foreach($id_tags as $t) {
                                \mediaphoto\model\TagGallery::insert(
                                    ['id_tag' => $t, 'id_galerie' => 0]
                                );
                            }
    
                            $lastInsertId = \mediaphoto\model\Gallery::insertGetId(
                                ['titre' => $title, 'description' => $desc, 'type' => $type, 'auteur' => $user_id]
                            );
                            \mediaphoto\model\TagGallery::where('id_galerie', '=', 0)->update(['id_galerie' => $lastInsertId]);
                            \mediaphoto\model\Share::where('id_galerie', '=', 0)->update(['id_galerie' => $lastInsertId]);
                        
        
                            header('Location:' . $router->urlFor('viewGallery', array('id' => $lastInsertId)));
                            exit;
                        } else {
                            foreach($tags as $t) {
                                $exist = \mediaphoto\model\Tag::where('nom', '=', $t)->count();
                                if(!$exist) {
                                    $lastInsertTagsId = \mediaphoto\model\Tag::insertGetId(
                                        ['nom' => $t]
                                    );
                                    $id_tags[] = $lastInsertTagsId;
                                } else {
                                    $id_tags[] = \mediaphoto\model\Tag::select('id')->where('nom', '=', $t)->first()->id;
                                }
                            }
        
                            foreach($id_tags as $t) {
                                \mediaphoto\model\TagGallery::insert(
                                    ['id_tag' => $t, 'id_galerie' => 0]
                                );
                            }
    
                            $lastInsertId = \mediaphoto\model\Gallery::insertGetId(
                                ['titre' => $title, 'description' => $desc, 'type' => $type, 'auteur' => $user_id]
                            );
                            \mediaphoto\model\TagGallery::where('id_galerie', '=', 0)->update(['id_galerie' => $lastInsertId]);
                        
        
                            header('Location:' . $router->urlFor('viewGallery', array('id' => $lastInsertId)));
                            exit;
                        }
                    }
                }
            } else {
                header('Location:' . $router->urlFor('viewCreateGallery'));
                exit;
            }  
        }
    }

    public function viewPostPhoto() {
        MediaPhotoView::addStyleSheet(\mediaphoto\view\MediaPhotoView::$app_url . '/html/assets/css/styleLogin.css');
        MediaPhotoView::addScript(\mediaphoto\view\MediaPhotoView::$app_url . '/html/assets/js/jquery-3.2.1.js');
        MediaPhotoView::addScript(\mediaphoto\view\MediaPhotoView::$app_url . '/html/assets/js/autosearch.js');
        MediaPhotoView::addScript(\mediaphoto\view\MediaPhotoView::$app_url . '/html/assets/js/block-browse-img.js');
        $auth = new \mediaphoto\auth\MediaPhotoAuthentification();
        if(!$auth->logged_in) {
            $router = new \mf\router\Router();
            header('Location:' . $router->urlFor('home'));
            exit;
        } else {
            $author = \mediaphoto\model\User::getLoggedUserId();
            $galleries = \mediaphoto\model\Gallery::select()->where('auteur', '=', $author)->get();
            $vue = new \mediaphoto\view\MediaPhotoView($galleries);
            $vue->render('renderViewPostPhoto');
        }
    }

    public function checkPostPhoto() {
        $auth = new \mediaphoto\auth\MediaPhotoAuthentification();
        $router = new \mf\router\Router();
        $user_id = \mediaphoto\model\User::getLoggedUserId();
        if(!$auth->logged_in) {
            header('Location:' . $router->urlFor('home'));
            exit;
        } else {
            $post = $this->request->post;
            if(isset($post['submit'])) {
                $title = filter_var($post['galerie-name'], FILTER_SANITIZE_SPECIAL_CHARS);
                $tags = filter_var($post['list-tag'], FILTER_SANITIZE_SPECIAL_CHARS);
                $selected_gallery = filter_var($post['galerie-conf'], FILTER_SANITIZE_SPECIAL_CHARS);

                if($_FILES['image-upload']['size'] == 0 || empty($title) || empty($tags) || empty($selected_gallery)) {
                    $auth->generateMessage('post_photo_error', array('Veuillez renseigner tous les champs.', 'red'), 'viewPostPhoto');
                } else {
                    $target_dir = $this->request->root . '/html/images/';
                    $target_file = $target_dir . basename($_FILES["image-upload"]["name"]);
                    $uploadOk = 1;
                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                    // Taille de l'image en ko + arrondi au supérieur
                    $imageSize = ceil($_FILES["image-upload"]["size"] / 1024);

                    $check = getimagesize($_FILES["image-upload"]["tmp_name"]);
                    if($check !== false) {
                        $uploadOk = 1;
                    } else {
                        $auth->generateMessage('post_photo_error', array('Le fichier que vous nous avez transmis n\'est pas une image.', 'red'), 'viewPostPhoto');
                        exit;
                    }

                    if($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png") {
                        $auth->generateMessage('post_photo_error', array('Désolé, seules les extensions JPG, JPEG et PNG sont autorisées.', 'red'), 'viewPostPhoto');
                        exit;
                    }

                    if($uploadOk == 1) {
                        if(move_uploaded_file($_FILES["image-upload"]["tmp_name"], $target_file)) {
                            $tags = explode(',', $tags);
                            $id_tags = [];

                            foreach($tags as $t) {
                                $exist = \mediaphoto\model\Tag::where('nom', '=', $t)->count();
                                if(!$exist) {
                                    $lastInsertTagsId = \mediaphoto\model\Tag::insertGetId(
                                        ['nom' => $t]
                                    );
                                    $id_tags[] = $lastInsertTagsId;
                                } else {
                                    $id_tags[] = \mediaphoto\model\Tag::select('id')->where('nom', '=', $t)->first()->id;
                                }
                            }
        
                            foreach($id_tags as $t) {
                                \mediaphoto\model\TagPhoto::insert(
                                    ['id_tag' => $t, 'id_photo' => 0]
                                );
                            }

                            $lastInsertId = \mediaphoto\model\Photo::insertGetId(
                                ['titre' => $title, 'chemin' => '/html/images/' . $_FILES["image-upload"]["name"], 'id_utilisateur' => $user_id,
                                 'id_galerie' => $selected_gallery, 'qualite' => 'HD', 'type' => $imageFileType, 'taille' => $imageSize]
                            );
                            \mediaphoto\model\TagPhoto::where('id_photo', '=', 0)->update(['id_photo' => $lastInsertId]);
        
                            header('Location:' . $router->urlFor('viewPhoto', array('id' => $lastInsertId)));
                            exit;
                        }
                    }
                }
            } else {
                header('Location:' . $router->urlFor('viewPostPhoto'));
                exit;
            }
        }
    }

    public function viewMyPictures() {
        $auth = new \mediaphoto\auth\MediaPhotoAuthentification();
        $router = new \mf\router\Router();
        if(!$auth->logged_in) {
            $router = new \mf\router\Router();
            header('Location:' . $router->urlFor('home'));
            exit;
        } else {
          MediaPhotoView::addStyleSheet(\mediaphoto\view\MediaPhotoView::$app_url . '/html/assets/css/show_pic.css');
          $pictures = \mediaphoto\model\Photo::select()->where('id_utilisateur', '=', \mediaphoto\model\User::getLoggedUserId())->limit(5)->get();
          $vue = new \mediaphoto\view\MediaPhotoView($pictures);
          $vue->render('renderViewMyPictures');
        }
    } 
}