//Properties
let searchInput = document.querySelector('.form-control');
let article = document.querySelector('#articlelist');


//EventListener
// searchInput.addEventListener('keypress', (event) => {
//     if(event.keyCode == 13) {
//         alert(searchInput.value)
//         if(searchInput.value != null) {
//             alert("je suis dans le 2ème IF");
//             getProductsAndRender(searchInput.value);
//         }
//     }
// });

function getProductsAndRender(option = ''){
    $.ajax({
        url: "/api/products" + '?sort=' + option,
    }).done(function(result) {
        $(article).empty();
        for(let i = 0; i < result.length; i++){
            renderProduct(result[i])
        }
    });
}

function displaySearchedProduct(result){
    $(article).empty();
    for(let i = 0; i < result.length; i++){
        renderProduct(result[i]);
    }
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

$( document ).ready(function() {
    getProductsAndRender();
    // displaySearchedProduct();
});