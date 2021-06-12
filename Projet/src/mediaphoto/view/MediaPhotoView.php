<?php

namespace mediaphoto\view;

class MediaPhotoView extends \mf\view\AbstractView {
    public function __construct($data)
    {
        parent::__construct($data);
    }

    private function renderHeader() {
        $auth = new \mediaphoto\auth\MediaPhotoAuthentification();
        $app_url = $app_url = self::$app_url;
        $router = new \mf\router\Router();
        $home = $router->urlFor('home');
        $login = $router->urlFor('viewLogin');
        $signup = $router->urlFor('viewSignup');
        $logout = $router->urlFor('viewLogout');
        $password = $router->urlFor('viewPassword');
        $post_photo_link = $router->urlFor('viewPostPhoto');
        $my_photo_link = $router->urlFor('viewMyPictures');

        if(!$auth->logged_in) {
            $result = <<<HTML
            <nav>
                <div class="nav-link">
                    <a href="${home}">Accueil</a>
                </div>
                <a href="${home}">
                    <img class="logo" src="${app_url}/html/assets/img/logo.png" alt="MediaPhoto" />
                </a>
                <div class="nav-setting">
                    <a href="${login}"><img src="${app_url}/html/assets/img/login.svg" alt="Connexion">
                        <p>Connexion</p>
                    </a>
                    <a href="${signup}"><img src="${app_url}/html/assets/img/signup.svg" alt="Inscription">
                        <p>Inscription</p>
                    </a>
                </div>
            </nav>
            HTML;
        } else {
            $result = <<<HTML
            <nav>
                <div class="nav-link">
                  <a href="${post_photo_link}">
                    <img src="${app_url}/html/assets/img/add-image.png" alt="Poster photo">
                    <p>POSTER UNE PHOTO</p>
                  </a>
                  <a href="${my_photo_link}">
                    <img src="${app_url}/html/assets/img/stack-image.png" alt="Mes photos">
                    <p>Mes photos</p>
                  </a>
                </div>
                <a href="${home}">
                    <img class="logo" src="${app_url}/html/assets/img/logo.png" alt="MediaPhoto" />
                </a>
                <div class="nav-setting">
                    <a href="${password}"><img src="${app_url}/html/assets/img/setting.svg" alt="Paramètres">
                        <p>$_SESSION[user_login]</p>
                    </a>
                    <a href="${logout}"><img src="${app_url}/html/assets/img/disconnect.svg" alt="Déconnexion">
                        <p>Déconnexion</p>
                    </a>
                </div>
            </nav>
            HTML;
        }

        return $result;
    }

    private function renderFooter() {
        return '<br><br>';
    }

    private function renderHome() {
        $auth = new \mediaphoto\auth\MediaPhotoAuthentification();
        $router = new \mf\router\Router();
        $app_url = self::$app_url;
        $galleries = $this->data;
        $search_link = $router->urlFor('checkSearch');

        $result = <<<HTML
        <!-- Début bloc de recherche -->
          <article class="block-search">
              <h1>Bienvenue sur <strong>media photo</strong></h1>
              <form class="form-search" action="${search_link}" method="POST">
                  <div class="input-tb-submit">
                      <input type="text" name="search" placeholder="Rechercher..." />
                      <input name="submit" type="submit" value="OK" />
                  </div>
                  <div class="form-select-filter">
                      <div class="checkbox-group">
                          <input checked type="checkbox" id="filter-photo" name="filter-photo" value="photo">
                          <label for="filter-photo">photo</label>
                      </div>
                      <div class="checkbox-group">
                          <input checked type="checkbox" id="filter-galerie" name="filter-galerie" value="galerie">
                          <label for="filter-galerie">galerie</label>
                      </div>
                  </div>
              </form>
          </article>
          <!-- Fin bloc de recherche -->
        HTML;
                if($auth->logged_in) {
                    $userId = \mediaphoto\model\User::getLoggedUserId();
                    $userGalleries = \mediaphoto\model\Gallery::getUserGalleries($userId);
                    $link_create_gallery = $router->urlFor('viewCreateGallery');

                    $result .= <<<HTML
                    <!-- Début liste de vos galeries -->
                    <article id="content-galerie" class="content-block">
                        <h1>Liste de vos galeries</h1>
                        <div class="block-list">
                    HTML;
                    foreach($userGalleries as $ug) {
                        $galleryId = $ug->id;
                        $titre = $ug->titre;
                        $link = $router->urlFor('viewGallery', array('id' => $galleryId));
                        $nbPhotos = \mediaphoto\model\Photo::where('id_galerie', '=', $galleryId)->count();
                        $get_path = \mediaphoto\model\Photo::select('chemin')->where('id_galerie', '=', $galleryId)->count();
                        if($get_path < 1) {
                            $path = self::$app_url . '/html/images/default.png';
                        } else {
                            $path = self::$app_url . \mediaphoto\model\Photo::select('chemin')->where('id_galerie', '=', $galleryId)->first()->chemin;
                        }

                        $result .= <<<HTML
                        <div class="card">
                            <a href="${link}">
                                <div class="card-body">
                                    <img src="${path}" alt="${titre}">
                                    <p>${titre}</p>
                                </div>
                            </a>
                            <div class="card-footer">
                                <p>${nbPhotos} PHOTOS</p>
                            </div>
                        </div>
                        HTML;
                    }
                    $result .= <<<HTML
                    <div class="card-add">
                      <a href="${link_create_gallery}" title="Créer gallerie">
                          <img src="${app_url}/html/assets/img/add.svg" alt="Créer gallerie" />
                            </a>
                        </div>
                    </div>
                </article>
                <!-- Fin liste de vos galeries -->
                HTML;
                }

        $result .= <<<HTML
        <article id="content-last-post" class="content-block">
            <h1>Dernières publications</h1>
            <div class="block-vignette">
        HTML;

        $limit = 15;
        $photos = \mediaphoto\model\Photo::select()->LIMIT($limit)->orderByDesc('id')->get();

        foreach($photos as $p) {
            $title = $p->titre;
            $path = self::$app_url . $p->chemin;
            $link = $router->urlFor('viewPhoto', array('id' => $p->id));

            $result .= <<<HTML
            <a href="${link}">
                <img src="${path}" alt="${title}" />
                <div class="card-footer">
                    <p>${title}</p>
                </div>
            </a>
            HTML;
        }

        $result .= <<<HTML
        </div>
        </article>
        HTML;

        return $result;
    }

    private function renderViewGallery() {
        $router = new \mf\router\Router();
        $app_url = self::$app_url;

        $gallery = $this->data;
        $id = $gallery->id;
        $title = $gallery->titre;
        $desc = $gallery->description;
        $type = $gallery->type;
        $size = $gallery->taille . ' Ko';
        $author = $gallery->author()->first()->nom_complet;

        $result = <<<HTML
        <article class="block-title-page">
            <h1>Galerie <strong><u>${title}</u></strong></h1>
        </article>
        <article>
            <h3>Description :</h3>
            <p>${desc}</p>
            <hr style="width: 50%;">
            <h3>A propos de la galerie :</h3>
            <p>Créé par : <a href="#">${author}</a></p>
            <p>Taille totale de la galerie : ${size}</p>
        HTML;

        if($type == 3) {
            $get_share = $gallery->partage()->get();
            $share = [];
            foreach($get_share as $s) {
                $share[] = $gallery->getShareUsername($s->id_utilisateur)->nom_complet;
            }
            $share = implode(', ', $share);

            $result .= <<<HTML
            <p>Membre de la galerie : ${share}</p>
            HTML;
        }

        $result .= <<<HTML
        </article>
        <hr style="width: 50%;">
        <article id="content-most-commented" class="content-block">
            <h1>Dernière publications</h1>
            <div class="block-vignette">
        HTML;

        $photos = $gallery->photos()->get();

        foreach($photos as $p) {
            $title = $p->titre;
            $path = self::$app_url . $p->chemin;
            $link = $router->urlFor('viewPhoto', array('id' => $p->id));

            $result .= <<<HTML
            <a href="${link}">
                <img src="${path}" alt="${title}">
                <div class="card-footer">
                    <p>${title}</p>
                </div>
            </a>
            HTML;
        }

        $result .= <<<HTML
        </div>
        <button class="btn-show-more" data-id-galerie="${id}" data-nb-increment="15" data-actual-offset="15">
            VOIR PLUS
            <img src="${app_url}/html/assets/img/right_arrow.svg" alt="Voir plus">
        </button>
        </article>
        HTML;

        return $result;
    }

    private function renderViewPhoto() {
        $router = new \mf\router\Router();
        $photo = $this->data;
        $app_url = self::$app_url;

        $id = $photo->id;
        $title = $photo->titre;
        $path = self::$app_url . $photo->chemin;
        $size = $photo->taille . ' Ko';
        $quality = $photo->qualite;
        $type = $photo->type;

        $author = $photo->author()->first()->nom_complet;
        $gallery = $photo->gallery()->first()->titre;
        $gallery_id = $photo->id_galerie;
        $get_photos = $photo->getGalleryPhoto($gallery_id, $id, 4);
        $gallery_link = $router->urlFor('viewGallery', array('id' => $gallery_id));

        $next_photo_id = $photo->getNextPhoto($gallery_id, $id);
        if($next_photo_id != false) {
            $next_photo_link = $router->urlFor('viewPhoto', array('id' => $next_photo_id->id));
        } else {
            $next_photo_link = false;
        }

        $previous_photo_id = $photo->getPreviousPhoto($gallery_id, $id);
        if($previous_photo_id != false) {
            $previous_photo_link = $router->urlFor('viewPhoto', array('id' => $previous_photo_id->id));
        } else {
            $previous_photo_link = false;
        }

        $result = <<<HTML
        <article class="content-block">
        <h1>${title}</h1>
        <div class="picture-block">
        <div class="showing" id="My-lightbox-gallery">
          <div class="vignette solo">
          <img class="pic" data-img="${path}" data-id="${id}" src="${path}" alt="${title}">
          </div>
          <a href="${previous_photo_link}">
            <img class="left-arrow" src="${app_url}/html/assets/img/left_arrow_pic.svg" alt="Afficher précédent">
          </a>
          <a href="${next_photo_link}">
            <img class="right-arrow" src="${app_url}/html/assets/img/left_arrow_pic.svg" alt="Afficher suivant">
          </a>
        </div>
        <div class="in-galerie">
        <p>Cette photo fait partie de la galerie "${gallery}", voir les autres photos de cette galerie ci-dessous :</p>
        <div class="block-vignette">
        HTML;

        foreach($get_photos as $p) {
            $img_title = $p->titre;
            $img_path = self::$app_url . $p->chemin;
            $img_link = $router->urlFor('viewPhoto', array('id' => $p->id));

            $result .= <<<HTML
            <a href="${img_link}">
            <img src="${img_path}" alt="${img_title}">
            <div class="card-footer">
            <p>${img_title}</p>
            </div>
            </a>
            HTML;
        }

        $result .= <<<HTML
        </div>
        </div>
        <hr style="width: 50%;">
        <div class="more-info-block">
            <h3>A propos de l'image :</h3>
            <p>
            Publiée par : <a href="">${author}</a><br>
            <br>
            Appartenant à la galerie : <a href="${gallery_link}">${gallery}</a><br>
            <br>
            Taille de l'image : ${size}<br>
            <br>
            Qualité : ${quality}<br>
            <br>
            Type : ${type}
            </p>
        </div>
        </article>
        HTML;

        return $result;
    }

    private function renderViewLogin() {
        $router = new \mf\router\Router();
        $checkLogin = $router->urlFor('checkLogin');
        $result = '';

        if(isset($_SESSION['login_error'])) {
            $message = $_SESSION['login_error'][0];
            $color = $_SESSION['login_error'][1];

            $result .= <<<HTML
            <article>
                <div class="alert alert-${color}" role="alert">
                    <h3>Attention !</h3>
                    <p>${message}</p>
                </div>
            </article>
            HTML;
        }

        $result .= <<<HTML
        <h1>Connexion</h1>
        <hr style="width: 70%">
        <form action="${checkLogin}" method="POST">
            <div>
                <label for="name">Nom d'utilisateur :</label>
                <input type="text" name="name" id="name">
            </div>
            <div>
                <label for="password">Mot de passe :</label>
                <input type="password" name="password" id="password">
            </div>
            <button name="submit" type="submit">Se connecter</button>
        </form>
        HTML;
        return $result;
    }

    private function renderViewSignup() {
        $router = new \mf\router\Router();
        $checkSignup = $router->urlFor('checkSignup');
        $site_name = self::$app_title;
        $result = '';

        if(isset($_SESSION['signup_error'])) {
            $message = $_SESSION['signup_error'][0];
            $color = $_SESSION['signup_error'][1];

            $result .= <<<HTML
            <article>
                <div class="alert alert-${color}" role="alert">
                    <h3>Attention !</h3>
                    <p>${message}</p>
                </div>
            </article>
            HTML;
        }

        $result .= <<<HTML
        <h1>Inscription à ${site_name}</h1>
        <hr style="width: 70%">
        <form action="${checkSignup}" method="POST">
            <div>
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" name="username" id="username">
            </div>
            <div>
                <label for="name">Nom complet :</label>
                <input type="text" name="name" id="name">
            </div>
            <div>
                <label for="password">Mot de passe :</label>
                <input type="password" name="password" id="password">
            </div>
            <div>
                <label for="password_confirmation">Confirmer le mot de passe :</label>
                <input type="password" name="password_confirmation" id="password_confirmation">
            </div>
            <button name="submit" type="submit">S'inscrire</button>
        </form>
        HTML;

        return $result;
    }

    private function renderViewPassword() {
        $router = new \mf\router\Router();
        $checkPassword = $router->urlFor('checkPassword');
        $result = '';

        if(isset($_SESSION['password_info'])) {
            $message = $_SESSION['password_info'][0];
            $color = $_SESSION['password_info'][1];

            $result .= <<<HTML
            <article>
                <div class="alert alert-${color}" role="alert">
                    <h3>Attention !</h3>
                    <p>${message}</p>
                </div>
            </article>
            HTML;
        }

        $result .= <<<HTML
        <h1>Modification de votre mot de passe</h1>
        <hr style="width: 70%">
        <div>
            <form action="${checkPassword}" method="POST">
                <div>
                    <label for="currentPassword">Mot de passe actuel :</label>
                    <input type="password" name="currentPassword" id="currentPassword">
                </div>
                <div>
                    <label for="newPassword">Nouveau mot de passe :</label>
                    <input type="password" name="newPassword" id="newPassword">
                </div>
                <div>
                    <label for="newPasswordConfirmation">Confirmer le nouveau mot de passe :</label>
                    <input type="password" name="newPasswordConfirmation" id="newPasswordConfirmation">
                </div>
                <button name="submit" type="submit">Changer de mot de passe</button>
            </form>
        </div>
        HTML;

        return $result;
    }

    private function renderViewSearch() {
        $data = $this->data;

        $result = <<<HTML
        <article id="content-last-post" class="content-block">
            <h1>Résultat de votre recherche</h1>
            <div class="block-vignette">
        HTML;

        if(isset($data['galerie'])) {
            $router = new \mf\router\Router();
            foreach($data['galerie'] as $g) {
                $galleryId = $g->id;
                $title = $g->titre;
                $link = $router->urlFor('viewGallery', array('id' => $galleryId));
                $get_path = \mediaphoto\model\Photo::select('chemin')->where('id_galerie', '=', $galleryId)->count();
                if($get_path < 1) {
                    $path = self::$app_url . '/html/images/default.png';
                } else {
                    $path = self::$app_url . \mediaphoto\model\Photo::select('chemin')->where('id_galerie', '=', $galleryId)->first()->chemin;
                }

                $result .= <<<HTML
                <a href="${link}">
                    <img src="${path}" alt="${title}" />
                    <div class="card-footer">
                        <p>${title}</p>
                    </div>
                </a>
                HTML;
            }
        }

        if(isset($data['photo'])) {
            $router = new \mf\router\Router();
            foreach($data['photo'] as $p) {
                $photoId = $p->id;
                $title = $p->titre;
                $path = self::$app_url . $p->chemin;
                $link = $router->urlFor('viewPhoto', array('id' => $photoId));

                $result .= <<<HTML
                <a href="${link}">
                    <img src="${path}" alt="${title}" />
                    <div class="card-footer">
                        <p>${title}</p>
                    </div>
                </a>
                HTML;
            }
        }

        $result .= <<<HTML
        </div>
        </article>
        HTML;

        return $result;
    }

    private function renderViewCreateGallery() {
        $router = new \mf\router\Router();
        $create_gallery_link = $router->urlFor('checkCreateGallery');
        $result = '';

        if(isset($_SESSION['create_gallery_error'])) {
            $message = $_SESSION['create_gallery_error'][0];
            $color = $_SESSION['create_gallery_error'][1];

            $result .= <<<HTML
            <article>
                <div class="alert alert-${color}" role="alert">
                    <h3>Attention !</h3>
                    <p>${message}</p>
                </div>
            </article>
            HTML;
        }

        $result .= <<<HTML
        <datalist id="_tagSearch"></datalist>
        <h1>Créer une galerie</h1>
        <hr style="width: 70%">
        <form action="${create_gallery_link}" method="POST">
            <div>
                <label for="galerie-name">Saisir un nom pour la gallerie :</label>
                <input type="text" placeholder="Saisir nom..." name="galerie-name" id="galerie-name">
            </div>
            <div>
                <label for="galerie-desc">Description :</label>
                <textarea name="galerie-desc" id="galerie-desc" rows="4"></textarea>
            </div>
            <div>
                <label for="tag-add-textbox">Ajouter/retirer des tags :</label>
                <div class="input-tb-submit tag-add-block">
                    <input type="text" id="tag-add-textbox" placeholder="SAISIR TAG..." list="_tagSearch" />
                    <input type="button" id="tag-add-btn" value="AJOUTER" />
                    <input type="text" style="display: none;" id="input-tagList" name="list-tag" value="">
                </div>
                <div id="block-list-tags">
                    <p id="p-list-tags"></p>
                </div>
            </div>
            <div>
                <label for="galerie-conf">Confidentialité galerie :</label>
                <select id="galerie-conf" name="galerie-conf">
                    <option value="1">Public</option>
                    <option value="2">Privé</option>
                    <option value="3">Partagé</option>
                </select>
            </div>
            <div id="block-add-user" style="display: none;">
                <label for="user-add-textbox">Ajouter/retirer des utilisateurs :</label>
                <div class="input-tb-submit user-add-block">
                    <input type="text" id="user-add-textbox" placeholder="SAISIR UTILISATEUR..." />
                    <input type="button" id="user-add-btn" value="AJOUTER" />
                    <input type="text" style="display: none;" id="input-userList" name="list-user" value="">
                </div>
                <div id="block-list-users">
                    <p id="p-list-users"></p>
                </div>
            </div>
            <input name="submit" id="submit" type="submit" value="Créer galerie" />
        </form>
        HTML;

        return $result;
    }

    private function renderViewPostPhoto() {
        $router = new \mf\router\Router();
        $app_url = self::$app_url;
        $gallery = $this->data;
        $check_post_photo_link = $router->urlFor('checkPostPhoto');
        $result = '';

        if(isset($_SESSION['post_photo_error'])) {
            $message = $_SESSION['post_photo_error'][0];
            $color = $_SESSION['post_photo_error'][1];

            $result .= <<<HTML
            <article>
                <div class="alert alert-${color}" role="alert">
                    <h3>Attention !</h3>
                    <p>${message}</p>
                </div>
            </article>
            HTML;
        }

        $result .= <<<HTML
        <datalist id="_tagSearch"></datalist>
        <h1>Poster une photo</h1>
        <hr style="width: 70%">
        <form action="${check_post_photo_link}" method="POST" enctype="multipart/form-data">
            <div class="block-parcourir-img">
                <label for="image-upload">Parcourir une image :</label>
                <input style="display: none;" type="file" id="image-upload" name="image-upload"
                    accept="image/png, image/jpeg, image/jpg">
                <img src="${app_url}/html/assets/img/add.svg" alt="Parcourir" />
            </div>
            <div>
                <label for="galerie-name">Saisir un nom pour la photo :</label>
                <input type="text" placeholder="Saisir nom..." name="galerie-name" id="galerie-name">
            </div>
            <div>
                <label for="tag-add-textbox">Ajouter/retirer des tags :</label>
                <div class="input-tb-submit tag-add-block">
                    <input type="text" id="tag-add-textbox" placeholder="SAISIR TAG..." list="_tagSearch" />
                    <input type="button" id="tag-add-btn" value="AJOUTER" />
                    <input type="text" style="display: none;" id="input-tagList" name="list-tag" value="">
                </div>
                <div id="block-list-tags">
                    <p id="p-list-tags"></p>
                </div>
            </div>
            <div>
            <label for="galerie-conf">Sélectionner une galerie :</label>
            <select id="galerie-conf" name="galerie-conf">
        HTML;
        foreach($gallery as $g) {
            $id = $g->id;
            $title = $g->titre;

            $result .= <<<HTML
            <option value="${id}">${title}</option>
        HTML;
        }
        $result .= <<<HTML
        </select>
        </div>
        <input name="submit" id="submit" type="submit" value="publier la photo" />
        </form>
        HTML;

        return $result;
    }

    private function renderViewMyPictures() {
        $router = new \mf\router\Router();
        $app_url = self::$app_url;

        $image = $this->data;

        $result = <<<HTML
        <article class="block-title-page">
            <h1>Mes <strong><u>photos</u></strong></h1>
        </article>
        <article class="content-block pictures-list">
        HTML;

        $firstPic = true;
        foreach ($image as $v) {
            if(!$firstPic){//Pour ne pas mettre <hr> au dessus de la première image dans la page
                $result .= '<hr style="width: 50%">';
            }else{
                $firstPic=false;
            }
            $title = $v->titre;
            $path = self::$app_url . $v->chemin;
            $link = $router->urlFor('viewPhoto', array('id' => $v->id));

            $result .= <<<HTML
            <div class="pictures">
                <h1>${title}</h1>
                <a href="${link}">
                <img class="pic" src="${path}" alt="${title} par moi" />
                </a>
            </div>
        HTML;
        }

        $result .= <<<HTML
        </article>
        <footer>
          <div class="block-pagination">
            <a class="previous-page" href="page-1">
              <img src="${app_url}/html/assets/img/left_arrow_pic.svg" alt="Précédent">
            </a>
            <p class="actual-page">1/4</p>
            <a class="next-page" href="page+1">
              <img src="${app_url}/html/assets/img/left_arrow_pic.svg" alt="Suivant">
            </a>
          </div>
        </footer>
        HTML;

      return $result;
    }

    protected function renderBody($selector)
    {
        $header = $this->renderHeader();
        $selecteur = $this->$selector();
        $footer = $this->renderFooter();

        $html = <<<HTML
        <header>${header}</header>
        ${selecteur}
        <footer>${footer}</footer>
        HTML;

        return $html;
    }
}
