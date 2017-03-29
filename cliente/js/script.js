//creamos una función para crear las cookies

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toGMTString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

//creamos una para leerlas

function readCookie(name) {
    var name_igual = name + "=";
    var ca = document.cookie.split(';');
    
    for(var i=0;i < ca.length;i++) { 
        var c = ca[i]; 
        
        while (c.charAt(0)==' ') c = c.substring(1,c.length); 
        
        if (c.indexOf(name_igual) == 0) return c.substring(name_igual.length,c.length); 
    } 
    
    return false; 
}


function accesible_font() {
    var haycookie = readCookie("accesiblefont");
    if(!haycookie) {
        setCookie("accesiblefont", 14, 3600);
    } else {
       /* document.getElementsByTagName("body")[0].setAttribute("style", "font: " + haycookie + "px 'segoe ui', 'lucida sans unicode', 'lucida grande', lucida, sans-serif;");
        document.getElementsByTagName("header")[0].setAttribute("height", (haycookie*6) + "px");
        document.getElementsByTagName("legend")[0].setAttribute("style", "font-size: " + haycookie + "px;");
        document.getElementsByClassName("infocab")[0].setAttribute("style", "font-size: " + haycookie + "px;");
        document.getElementsByClassName("resultados")[0].setAttribute("style", "font-size: " + haycookie + "px;");
        */
        accesible_font_size(haycookie);
    } 
}

function accesible_font_size(valor) {
    document.getElementsByTagName("body")[0].setAttribute("style", "font: " + valor + "px 'segoe ui', 'lucida sans unicode', 'lucida grande', lucida, sans-serif;");
    document.getElementsByTagName("header")[0].setAttribute("height", (valor*6) + "px");
    document.getElementsByTagName("legend")[0].setAttribute("style", "font-size: " + valor + "px;");
    document.getElementsByClassName("infocab")[0].setAttribute("style", "font-size: " + valor + "px;");
    document.getElementsByClassName("resultados")[0].setAttribute("style", "font-size: " + valor + "px;");
    
    elementos = document.getElementsByTagName("input");
    for (var i = elementos.length - 1; i >= 0; i--) {
        if(elementos[i].getAttribute("type") != "button") {
            elementos[i].setAttribute("style", "font: " + valor + "px 'segoe ui', 'lucida sans unicode', 'lucida grande', lucida, sans-serif;");
        }
    }

    setCookie("accesiblefont", valor, 3600);
}
