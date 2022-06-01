var latitude;
var longitude;
var getCity = document.querySelector('.noStyle');

getLocation();

function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
        console.log("Geolocation is not supported by this browser.");
    }
}

function showPosition(position) {
    latitude = position.coords.latitude;
    longitude = position.coords.longitude;
    GetLocalisationOfCity().then((response) => {
       let feature = response.features['0'];
       let context = feature.context;
       console.log(context);
       for(var item of context){
           var idFind = item.id;
           if(idFind.includes("place.")){
               console.log((item.text));
               getCity.value = item.text;
           }
       }
    }).catch((error) => {
        console.error(error);
    });
}

function showError(error) {
    switch(error.code) {
        case error.PERMISSION_DENIED:
            console.log("User denied the request for Geolocation.");
            break;
        case error.POSITION_UNAVAILABLE:
            console.log("Location information is unavailable.");
            break;
        case error.TIMEOUT:
            console.log("The request to get user location timed out.");
            break;
        case error.UNKNOWN_ERROR:
            console.log("An unknown error occurred.");
            break;
    }
}

async function GetLocalisationOfCity() {
    let url = "https://api.mapbox.com/geocoding/v5/mapbox.places/";
    let key =  'pk.eyJ1Ijoia2Vtcm9kZSIsImEiOiJjbDAzbmkycjkwZXB6M25xZGkxNzFtNGFtIn0.H_y4SaibmfnmyxzT9VfOww';
    const call = url + longitude + ',' +latitude +".json?access_token="+key;

    return new Promise((resolve, reject) => {
        xhr = new XMLHttpRequest()
        xhr.open('GET', call);
        xhr.responseType = 'json';
        xhr.onload = () => {
            if(xhr.status >= 200 && xhr.status < 300) {
                resolve(xhr.response);
            } else {
                reject(xhr.statusText);
            }
        };
        xhr.onerror = () => reject(xhr.statusText);
        xhr.send();
    });
}