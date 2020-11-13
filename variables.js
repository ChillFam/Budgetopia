function login(oFormElement){
    var xhr = new XMLHttpRequest();
    xhr.onload = function() {
        var response = (xhr.responseText);
        
        if (response.localeCompare("Incorrect Password") != 0 && response.localeCompare("Account Does Not Exist") != 0){
             localStorage.setItem('login', document.getElementById('username').value);
            var end1 = response.search(",G:");
            var end2 = response.search(",FN:");
            var end3 = response.search(",LN:");
            var end4 = response.search(",CO:");
            var end5 = response.search(",CI:");
            var end6 = response.search(",CP:");
            
            var twitch = response.substring(2, end1);
            var gatag = response.substring(end1 + 3, end2);
                              

            var firstname = response.substring(end2 + 4, end3);
              
            var lastname = response.substring(end3 + 4, end4);
            
            var country = response.substring(end4 + 4, end5);
          
            var city = response.substring(end5 + 4, end6);
            var cp = response.substring(end6 + 4, response.length);
            
           
            
            localStorage.setItem('gametag', gatag);
            localStorage.setItem('twitch', twitch);
             localStorage.setItem('firstname', firstname);
             localStorage.setItem('lastname', lastname);
             localStorage.setItem('country', country);
             localStorage.setItem('city', city);
             localStorage.setItem('cp', cp);
               window.location.href = "https://www.lootstreak.com/lobbies";
        }
        else{
             if (response.localeCompare("Account Does Not Exist") == 0){
                 document.getElementById("incorrectpassword").style.visibility = "hidden";
                 document.getElementById("invalidemail").style.visibility = "visible";
                 document.getElementById("logwin").style.height = "387px";
                 document.getElementById("pswlabel").style.top = "160px";
                 document.getElementById("pswinput").style.top = "180px";
                 document.getElementById("rememberme").style.top = "240px";
                 document.getElementById("submitbutton").style.top = "280px";
                 document.getElementById("txt1").style.top = "345px";
             }
            else{
                 document.getElementById("pswlabel").style.top = "150px";
                 document.getElementById("pswinput").style.top = "170px";
                 document.getElementById("invalidemail").style.visibility = "hidden";
                 document.getElementById("incorrectpassword").style.visibility = "visible";
                 document.getElementById("logwin").style.height = "387px";
                 document.getElementById("rememberme").style.top = "240px";
                 document.getElementById("submitbutton").style.top = "280px";
                 document.getElementById("txt1").style.top = "345px";
            }
        }
      

    }
    xhr.open(oFormElement.method, oFormElement.action, true);
    xhr.send(new FormData(oFormElement));

    return false;

    }