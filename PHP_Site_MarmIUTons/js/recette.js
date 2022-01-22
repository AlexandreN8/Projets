
function toggleFilter(divClicked) {
    recettes = document.querySelectorAll(".column-noboot");
    numFiltre = divClicked.getAttribute('name');    

    recettes.forEach(function(node){
        console.log(node.getAttribute('name') + " : " + numFiltre);
        if(node.getAttribute('name') == numFiltre){
            node.style.display = "flex";
        }else{
            node.style.display = "none";
        }  
    });
 }

