import Cookies from 'js-cookie'

const googleId = Cookies.get('GOOGLE_ID')

let auth2

gapi.load('auth2', function () {
    auth2 = gapi.auth2.init({
        client_id: '699258154622-t9og9u5681snjbtjktobs02lmq4p19ds.apps.googleusercontent.com',
    })

    const btnGoogle = document.getElementById('btnGoogle')
    if (btnGoogle) {
        attachSignin(btnGoogle)
    }
})

function attachSignin(element) {
    auth2.attachClickHandler(element, {}, function(googleUser) {
        const profile = googleUser.getBasicProfile().getName();
        const id_token = googleUser.getAuthResponse().id_token;
        const xhr = new XMLHttpRequest();
        xhr.open('POST', document.body.dataset.loginGoogle);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4)
                window.location.reload()
        }
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('id_token=' + id_token);
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
