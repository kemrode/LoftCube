
var test = document.querySelector('.form-control');

test.addEventListener('keypress', (event) => {
    if(event.keyCode == 13) {
        testKey();
    }
});

function testKey() {
    alert("c'est la bonne touche");
}