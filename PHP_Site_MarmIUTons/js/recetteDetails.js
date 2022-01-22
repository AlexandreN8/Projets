// variable récupéré de l'en tête PHP
var nomR = ajax_numRecette;
var nbCom = ajax_nbCom;
var reponse = false;

//Traiter la note
function compterEtoile(){
  mvar = $('.star:checked').next().attr('name');
  return mvar;
}
//----Insérer un commentaire en AJAX
$(document).ready(function () {
  $("#addComment").on('click', function () { // On utilise l'event on click du bouton inserer
    var comment;
    
      comment = $("#mainComment").val();
    note = compterEtoile();


    if (comment.length != "") { // Condition d'acceptation du commentaire
        $.ajax({
            url: "routeur.php?action=commentaireInsert",
            method: "POST",
            dataType: "text",
            data: {
                addComment: 1, // ajout d'1 commentaire
                comment: comment, // on envoie en post les variable comment et nomR
                nomR: nomR,
                note: note
            }, success: function (response) { // Si la fonction reussis on ajoute le commentaire via client
                   nbCom++; // On augmente le nbCom pour pouvoir aussi l'incrémenter dans le php
                   $("#nbCom").text(nbCom + "Commentaires");
                    $(".userComments").prepend(response);
                    $("#mainComment").val("");
                    window.location.replace("routeur.php?action=detailsRecette&numRecette=" + nomR);
                    // window.location.reload();
                    // setInterval('location.reload()', 7000);
                }
           });
    } else
        alert('Votre commentaire doit pas être vide.'); // popu condition non remplie
  });

  getAllComments(0, nbCom);
});


//-----On va recuperer et affiché tout les commentaires de la recette au load---//
function getAllComments(start, max) {
  if (start > max) {
      return;
  }

  $.ajax({
      url: 'routeur.php?action=commentaireAll',
      method: 'POST',
      dataType: 'text',
      data: {
          getAllComments: 1,
          nomR: nomR,
          start: start
      }, success: function (response) {
          $(".userComments").append(response); // code HTML formatté et retourné par le controller
          getAllComments((start+20), max);
      }
  });
}



