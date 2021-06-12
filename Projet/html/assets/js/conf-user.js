let confUser = {
  modules : {
  }
}
let listUser = [];
$(document).ready(function() {
  confUser.modules.handlerconfUser = (function(){
    return {
      addUserToList(){
        let elem = $('#user-add-textbox')[0];
        let name = elem.value;
        if(listUser.indexOf(name) < 0)
        {
          listUser.push(name);
          elemC = document.createElement('span');
          elemC.classList.add("removeUser");
          elemC.setAttribute('title', 'Retirer l\'utilisateur');
          elemC.innerHTML = name;
          $(elemC).on("click", confUser.modules.handlerconfUser.deleteUserFromList);
          document.getElementById('p-list-users').append(elemC);
          let strUser = null;
          listUser.forEach(elem => {
            if(!strUser){
              strUser = elem;
            }
            else{
              strUser += ',' + elem;
            }
          });
          document.getElementById('input-userList').value = strUser;
        }
        else
        {
          alert('L\'utilisateur a déjà était ajouté.');
        }
      },
      deleteUserFromList(e){
        var name = e.target.innerHTML;
        var indexTable = listUser.indexOf(name);
        listUser.splice(indexTable, 1);
        var chn = '';
        listUser.forEach(function(elem)
        {
          chn += '<span class="removeUser" title="Retirer l\'utilisateur">' + elem + '</span>';
        });
        document.getElementById('p-list-users').innerHTML = chn;
        confUser.modules.handlerconfUser.addEventClickForListUser();
        let strUser = null;
        listUser.forEach(elem => {
          if(!strUser){
            strUser = elem;
          }
          else{
            strUser += ',' + elem;
          }
        });
        document.getElementById('input-userList').value = strUser;
      },
      addEventClickForListUser()
      {
        $(".removeUser").on("click", confUser.modules.handlerconfUser.deleteUserFromList);
      },
      init(){
        $('#user-add-btn').click(this.addUserToList);
      }
    }
  })();
  confUser.modules.handlerconfUser.init();
});
