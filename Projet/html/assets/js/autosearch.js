let autosearch = {
  modules : {
  }
}
let listTag = [];
$(document).ready(function() {
  autosearch.modules.handlerautosearch = (function(){
    return {
      reqToDB(e){
        let chaine = e.target.value;
        let xhr = new XMLHttpRequest();
        xhr.open('GET', '/ajax/ajax_autosearch.php?chaine=' + chaine);
        xhr.addEventListener('readystatechange', function()
        {
          if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200)
          {
            if(xhr.responseText != ''){
              let valTable = JSON.parse(xhr.responseText);
              valTable.forEach(function(elem)
              {
                elemOption = document.createElement('option');
                elemOption.value = elem;
                document.getElementById('_tagSearch').append(elemOption);
              });
            }
          }
        });
        xhr.send(null);
        $('#tag-add-textbox').unbind("keyup");
      },
      verifIfDeletingText(e){
        let elem = e.target;
        if(elem.value == '')
        {
          document.getElementById('_tagSearch').innerHTML = '';
          $('#tag-add-textbox').keyup(autosearch.modules.handlerautosearch.reqToDB);
        }
      },
      addTagToList(){
        let elem = $('#tag-add-textbox')[0];
        let name = elem.value;
        if(listTag.indexOf(name) < 0)
        {
          listTag.push(name);
          elemC = document.createElement('span');
          elemC.classList.add("removeTag");
          elemC.setAttribute('title', 'Retirer le tag');
          elemC.innerHTML = name;

          $(elemC).on("click", autosearch.modules.handlerautosearch.deleteTagFromList);
          document.getElementById('p-list-tags').append(elemC);
          let strTag = null;
          listTag.forEach(elem => {
            if(!strTag){
              strTag = elem;
            }
            else{
              strTag += ',' + elem;
            }
          });
          document.getElementById('input-tagList').value = strTag;
        }
        else
        {
          alert('Le tag a déjà était ajouté.');
        }
      },
      deleteTagFromList(e){
        var name = e.target.innerHTML;
        var indexTable = listTag.indexOf(name);
        listTag.splice(indexTable, 1);
        var chn = '';
        listTag.forEach(function(elem)
        {
          chn += '<span class="removeTag" title="Retirer le tag">' + elem + '</span>';
        });
        document.getElementById('p-list-tags').innerHTML = chn;
        autosearch.modules.handlerautosearch.addEventClickForListTag();
        let strTag = null;
        listTag.forEach(elem => {
          if(!strTag){
            strTag = elem;
          }
          else{
            strTag += ',' + elem;
          }
        });
        document.getElementById('input-tagList').value = strTag;
      },
      addEventClickForListTag()
      {
        $(".removeTag").on("click", autosearch.modules.handlerautosearch.deleteTagFromList);
      },
      init(){
        $('#tag-add-btn').click(this.addTagToList);
        //$('#tag-add-textbox').keyup(this.reqToDB);
        $('#tag-add-textbox').keypress(this.verifIfDeletingText);
      }
    }
  })();
  autosearch.modules.handlerautosearch.init();
});
