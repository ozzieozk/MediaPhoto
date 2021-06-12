let vignette = {
  modules : {
  }
}

$(document).ready(function() {
  vignette.modules.handlerVignette = (function(){
    return {
      ajaxLoadding(_galerieBlock, _idGalerie, _nbElemToLoad, _actualOffset, _positionY, _btn){
        $.ajax({
           url : './../../ajax/ajax_loading_content.php',
           type : 'POST',
           data : { idGalerie: _idGalerie, nbElemToLoad: _nbElemToLoad, actualOffset: _actualOffset },
           dataType : 'html'
        })
        .done(function( html ) {
          $(_galerieBlock).append(html);
          setTimeout(function(){ $(document).scrollTop(_positionY); }, 30);
          $(_btn).data('actual-offset', _actualOffset + _nbElemToLoad);
        });
      },
      showMore(e){
        let positionY = $(document).scrollTop();
        let btn = $(e.target);
        let galerieBlock = $(btn).parent('article').find(".block-vignette");
        let idGalerie = $(btn).data('id-galerie');
        let nbElemToLoad = $(btn).data('nb-increment');
        let actualOffset = $(btn).data('actual-offset');

        let content = vignette.modules.handlerVignette.ajaxLoadding(galerieBlock, idGalerie, nbElemToLoad, actualOffset, positionY, btn);
      },
      init(){
        $('.btn-show-more').click(this.showMore);
      }
    }
  })();
  vignette.modules.handlerVignette.init();
});
