<?php

namespace mf\control;

abstract class AbstractController {
  
  /* Attribut pour stocker l'objet HttpRequest */ 
  protected $request=null; 
  
  /*
   * Constructeur :
   * 
   * CrÃ©e une instance de la classe HttpRequest et la stocke dans l'attribut
   *    $request 
   *
   */
  
  public function __construct(){
      $this->request = new \mf\utils\HttpRequest() ;
  }
  
}