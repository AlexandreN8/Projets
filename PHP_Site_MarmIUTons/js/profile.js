// Barre de recherche de la table admin
$(document).ready(function(){
    $("#srch").on("keyup", function(){
        var val = $(this).val().toLowerCase();
        $("#user-list .input-group").filter(function(){
            $(this).toggle($(this).text().toLowerCase().indexOf(val)>-1)
        });
    });
});

// Afficher onload le modal de la valeur de retour de la modification de role
$(window).on('load', function() {
    $('#success').modal('show');
});
//active tab
$('.navd ul li').click(function(){
    $(this).addClass("active").siblings().removeClass('active');
})


//tab affichage
const tabBtn = document.querySelectorAll('.nav ul li');
const tab = document.querySelectorAll('.tab-bootless');

function tabs(panelIndex){
    tab.forEach(function(node){
        node.style.display = 'none';
    });
    tab[panelIndex].style.display = 'block';
}
tabs(0);

