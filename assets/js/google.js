import Cookies from 'js-cookie'
import axios from './axios'

const googleId = Cookies.get('GOOGLE_ID')

let auth2

gapi.load('auth2', function () {
    auth2 = gapi.auth2.init({
        client_id: '699258154622-t9og9u5681snjbtjktobs02lmq4p19ds.apps.googleusercontent.com',
    })

    const btnGoogle = document.querySelectorAll('[login-google]')
    if (btnGoogle.length) {
        btnGoogle.forEach(attachSignin)
    }
})

function attachSignin(element) {
    auth2.attachClickHandler(element, {}, function(googleUser) {
        const profile = googleUser.getBasicProfile().getName();
        const id_token = googleUser.getAuthResponse().id_token;
        axios.post(document.body.dataset.loginGoogle, {
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
