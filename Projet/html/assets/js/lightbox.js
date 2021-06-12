let lightbox = {
  modules : {
  }
}

$(document).ready(function() {
  lightbox.modules.handlerVignette = (function(){
    return {
      deleteAllModal(){
        $('.modal-open').remove();
      },
      createModal(idNewElem, largeImg, isSolo){
        //création des nouveaux elements
        let elem = null;
        if(!isSolo){
          let imgElem = $('<img src="' + largeImg + '"/>');
          let arrowLeft = $('<div class="left"><</div>');
          let arrowRight = $('<div class="right">></div>');
          let closeModal = $('<div class="close">X</div>');

          //création des handlers pour les nouvelles flèches ajoutées
          arrowLeft.click(lightbox.modules.handlerVignette.prevImage);
          arrowRight.click(lightbox.modules.handlerVignette.nextImage);
          closeModal.click(lightbox.modules.handlerVignette.deleteAllModal);

          elem = $('<div class="modal-open" data-id-img="' + idNewElem + '"></div>').append(arrowLeft).append(imgElem).append(arrowRight).append(closeModal);
        }
        else{
          let imgElem = $('<img src="' + largeImg + '"/>');
          let closeModal = $('<div class="close">X</div>');
          closeModal.click(lightbox.modules.handlerVignette.deleteAllModal);

          elem = $('<div class="modal-open" data-id-img="' + idNewElem + '"></div>').append(imgElem).append(closeModal);
        }
        $('#My-lightbox-gallery').after(elem);
      },
      openModal(e){
        lightbox.modules.handlerVignette.deleteAllModal();
        let vignette = $(e.target).closest(".vignette");
        //isSolo vérifie si il y a qu'une seule vignette au total -> ce qui veut dire qu'on affiche le modèle différmeent que si il y en avait plusieurs
        //dans la fonction createModal on affiche pas les flèches
        let isSolo = false;
        if($(vignette).hasClass('solo')){
          isSolo = true;
        }
        let id = $('img', vignette).data('id');
        let largeImg = $('img', vignette).data('img');

        lightbox.modules.handlerVignette.createModal(id, largeImg, isSolo);

      },
      prevImage(e){
        let actualID = $(e.target).closest(".modal-open").data('id-img');
        if(actualID - 1 > 0){
          let nextID = actualID - 1;
          let largeImg = $('#My-lightbox-gallery').find(`[data-id='${nextID}']`).data('img');

          lightbox.modules.handlerVignette.deleteAllModal();
          lightbox.modules.handlerVignette.createModal(nextID, largeImg);
        }
        else{
          let nextID = $('#My-lightbox-gallery .vignette').length;
          let largeImg = $('#My-lightbox-gallery').find(`[data-id='${nextID}']`).data('img');

          lightbox.modules.handlerVignette.deleteAllModal();
          lightbox.modules.handlerVignette.createModal(nextID, largeImg);
        }
      },
      nextImage(e){
        let actualID = $(e.target).closest(".modal-open").data('id-img');
        if(actualID + 1 <= $('#My-lightbox-gallery .vignette').length){
          let nextID = actualID + 1;
          let largeImg = $('#My-lightbox-gallery').find(`[data-id='${nextID}']`).data('img');

          lightbox.modules.handlerVignette.deleteAllModal();
          lightbox.modules.handlerVignette.createModal(nextID, largeImg);
        }
        else{
          let nextID = 1;
          let largeImg = $('#My-lightbox-gallery').find(`[data-id='${nextID}']`).data('img');

          lightbox.modules.handlerVignette.deleteAllModal();
          lightbox.modules.handlerVignette.createModal(nextID, largeImg);
        }
      },
      init(){
        $('.vignette').click(this.openModal);
      }
    }
  })();
  lightbox.modules.handlerVignette.init();

  $("body").keydown(function(e) {
    if($('.modal-open').length){
      if(e.keyCode == 37) {
        $('.modal-open .left').click();
      }
      else if(e.keyCode == 39) {
        $('.modal-open .right').click();
      }
    }
  });
});
