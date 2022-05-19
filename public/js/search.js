//Properties
let searchInput = document.querySelector('.form-control');

//EventListener
searchInput.addEventListener('keypress', (event) => {
    if(event.keyCode == 13) {
        testKey();
    }
});

//Public Methods
function testKey() {
    let textValue = searchInput.value;
    if(textValue != null) {

    }
}

function getProductsSearching(){

}


function renderProduct($product){
    $('<div class="col-lg-4 col-sm-6 articleitem" id="article-pattern">' +
        '                                <div class="b-article">' +
        '                                    <div class="v-img">' +
        '                                        <a href="/product/'+ $product.id + '">' +
        '                                            <img src="/storage/'+ $product.picture +'" alt=""></a>' +
        '                                    </div>' +
        '                                    <div class="v-desc">' +
        '                                        <a href="/product/'+ $product.id + '">'+ $product.name+'</a>' +
        '                                    </div>' +
        '                                    <div class="v-desc-global">' +
        '                                    <p>'+ $product.description.slice(0, 20) + '...</p>' +
        '                                    </div>' +
        '                                    <div class="v-views">' +
        '                                        '+$product.views+' vues' +
        '                                    </div>' +
        '                                </div>' +
        '</div>')
        .appendTo($('#articlelist'));
}
/*
* LOGIQUE
*
* LORS DE LA SAISIE UTILISATEUR
* REQUETE TYPE GET AVEC LA SAISIE COMME PARAMETRE
* SI RETOUR OK
* AFFICHER LA LISTE DES ELEMENTS
* SINON
* AFFICHER PAGE ERREUR NOT FOUND
*
 */