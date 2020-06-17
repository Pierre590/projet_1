import Cookies from 'js-cookie'
import axios from './axios'

const googleId = Cookies.get('GOOGLE_ID')

let auth2 //declaration variable js auth2

gapi.load('auth2', function () {   //chargement du sdk api js google
    auth2 = gapi.auth2.init({ //initialise client id
        client_id: '699258154622-t9og9u5681snjbtjktobs02lmq4p19ds.apps.googleusercontent.com', //id de mon compte dev google
    })

    const btnGoogle = document.querySelectorAll('[login-google]') //recuperation des deux boutons google
    if (btnGoogle.length) {
        btnGoogle.forEach(element => { //boutons ds element
            attachSignin(element)
        })
    }
})

function attachSignin(element) {  //personnalisation btouton google
    auth2.attachClickHandler(element, {}, function(googleUser) { //attachclickhandler = fenetre google avec les comptes
        const profile = googleUser.getBasicProfile().getName();   //recup profile, nom
        const id_token = googleUser.getAuthResponse().id_token; //recup de le client id google /compte email utilisé
        axios.post(document.body.dataset.loginGoogle, { // appel post a la methode /google
            id_token
        }).then(() => {
            window.location.reload()
        })
    }, console.error);
}

const btnLogout = document.getElementById('btnLogout')

if (btnLogout) {
    btnLogout.addEventListener('click', function () {
        auth2.signOut().then(function () {
            window.location.href= document.body.dataset.logout
        });
    })
}
