// Image preview
function previewFile(input) {
  var preview = input.nextElementSibling.firstElementChild;
  var file = input.files[0];
  var reader = new FileReader();

  reader.onloadend = function() {
    preview.src = reader.result;
  }

  if (file) {
    reader.readAsDataURL(file);
  } else {
    preview.src = "~/../images/pic.jpg";
  }

  
}


//toggle classe bouton de selection categorie
function toggleCat(){
   $('.categorie-plat').click(function(){
      $(this).addClass("active").siblings().removeClass('active');
      // Event permettant de changer le name de la div selectionné pour le recuperer ensuite
      $(this).attr("name", "categoriePlatActive").siblings().attr("name", "categoriePlat");
  })
}

//toggle class bouton de la selection tag
$(document).ready(function() {
  var index = 0;
   $('.tagBtn').click(function() {
     $(this).toggleClass('active');
     // On modifie le nom des tags pour distinguer les choix de l'utilisateur
     if($(this).hasClass('active')){
       $(this).attr("name", "tagActive " + index);
       index++;
     }else{
      $(this).attr("name", "tag");
      index--;
     }
   });
 });

//Recherche dans le select ingredients grace a la fonction pré défini "chosen"
$("#searchIng").chosen();
//Recherche dans le select ustensiles grace a la fonction pré défini "chosen"
$("#searchUst").chosen();

// Afficher la saisie ingredient
function addLiIngredient(){
  var nomIngredient =document.getElementById('searchIng').value;
  var quantite = document.getElementsByClassName('qte')[0].valueAsNumber;
  var type = document.getElementById('typeM').value;

  //Compter le nombre d'element de la liste pour suprimer dynamiquement les ellement de $_SESSION
  var nbLi = $('#ingList li').length;

  var dataS = quantite + type + " - " + nomIngredient;
    // On utilise ajax pour creer dynamiquement des li sans intervention du serveur
  $.ajax({
    type:"POST",
    method:"POST",
    url:"routeur.php?action=addLiIngredient", // On va ajouter ces valeurs à la session, on les detruiras une fois la recette soumise
    data:{
      nomIngredient:nomIngredient, 
      quantite:quantite, 
      typeMesure:type
    },
    cache:false,
    success: function(html){
      //Vérification que les 3 champs sont bien remplis sinon popup
      if (nomIngredient == "" || Number.isNaN(quantite) || type == "") {
        alert("Veuillez remplir les 3 champs.");
      }else{
        // On crée le html associé aux choix de l'utilisateur
        html = '<li>';
        html += dataS;
        html += '<button type="button" name="remove" class="' + nbLi + ' remove"><i class="fas fa-minus"></i></button></li>';
        $('#ingList').append(html);
      }
    }
  });
  return false;
}
$(document).on('click', '.remove', function(){
  var position = $(this).attr('class').split(/[ ]+/);
  var name = $(this).attr('name');
  var ingredient = $(this).closest('li').text();
  var numRecette = "";
  if(typeof ajax_numRecette == 'undefined'){
    numRecette = ""
  }else{
    numRecette = ajax_numRecette;
  }
  $(this).closest('li').remove();

  // On utilise ajax pour envoyer en POST sans refresh du serveur, la position de suppression dans la liste
  $.ajax({
     type:"POST",
     method:"POST",
     url:"routeur.php?action=delLiIngredient",
     data:{
       position:position[0]-1,
       name:name,
       ingredient:ingredient,
       numRecette:numRecette
      },
     cache:false,
     error: function(XMLHttpRequest, textStatus, errorThrown){
      alert("Status: " + textStatus); 
      alert("Error: " + errorThrown); 
     }
    });
});


/*
  Même fonction qu'au dessus mais pour la tab ustensile cette fois
*/
function addLiUstensile(){
  var nomUstensile =document.getElementById('searchUst').value;
  var qteUstensile = document.getElementsByClassName('qteUst')[0].valueAsNumber;

  //Compter le nombre d'element de la liste pour suprimer dynamiquement les ellement de $_SESSION
  var nbLi = $('#ustList li').length;

  var dataS = qteUstensile + " - " + nomUstensile;

    // On utilise ajax pour creer dynamiquement des li sans intervention du serveur
  $.ajax({
    type:"POST",
    method:"POST",
    url:"routeur.php?action=addLiUstensile", // On va ajouter ces valeurs à la session, on les detruiras une fois la recette soumise
    data:{nomUstensile:nomUstensile, qteUstensile:qteUstensile},
    cache:false,
    success: function(html){
      //Vérification que les 3 champs sont bien remplis sinon popup
      if (nomUstensile == "" || Number.isNaN(qteUstensile)) {
        alert("Veuillez remplir les 2 champs.");
      }else{
        // On crée le html associé aux choix de l'utilisateur
        html = '<li>';
        html += dataS;
        html += '<button type="button" name="remove2" class="' + nbLi + ' remove2"><i class="fas fa-minus"></i></button></li>';
        $('#ustList').append(html);
      }
    }
  });
  return false;
}
$(document).on('click', '.remove2', function(){
  var position = $(this).attr('class').split(/[ ]+/);
  var name = $(this).attr('name');
  var ustensile = $(this).closest('li').text();
  var numRecette = "";
  if(typeof ajax_numRecette == 'undefined'){
    numRecette = "x"
  }else{
    numRecette = ajax_numRecette;
  }
  $(this).closest('li').remove();

  // On utilise ajax pour envoyer en POST sans refresh du serveur, la position de suppression dans la liste
  $.ajax({
     type:"POST",
     method:"POST",
     url:"routeur.php?action=delLiUstensile",
     data:{
      position:position[0]-1, // sert dans la création avant qu'elle soit en bd
      name:name, // va nous permettre de determiner d'ou viens le post (creation ou update)
      ustensile:ustensile, // nous permet de retrouver l'objet en question
      numRecette:numRecette // nécéssaire pour les intérations avec la BD
      },
     cache:false,
     error: function(XMLHttpRequest, textStatus, errorThrown){
      alert("Status: " + textStatus); 
      alert("Error: " + errorThrown); 
     }
    });
});
// Selection des tab ingredient/ustensile
//active tab
$('.nav-noboot ul li').click(function(){
  $(this).addClass("active").siblings().removeClass('active');
})


//tab affichage
const tabBtn = document.querySelectorAll('.nav-noboot ul li');
const tab = document.querySelectorAll('.tab');

function tabs(panelIndex){
  tab.forEach(function(node){
      node.style.display = 'none';
  });
  tab[panelIndex].style.display = 'block';
}
tabs(0);

 // Afficher onload le modal de la valeur de retour de la modification de role
 $(window).on('load', function() {
   if(booleanModal){
    $('#staticBackdrop').modal('show');
   }
});
