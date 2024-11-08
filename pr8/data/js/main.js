'use strict';

let currentLogin;
let currentPassword;

let logInContent = document.getElementById('log-in');
let registerContent = document.getElementById('register');
registerContent.style.display = "none";
let profileContent = document.getElementById('profile');
let todoContent = document.getElementById('todo');
let errorsContent = document.getElementById('errors');

let btnLogIn = document.getElementById('do-log-in');
let btnGoToSignUp = document.getElementById('go-to-sign-up');
let login = document.getElementById('login');
let password = document.getElementById('password');

let signUpBtnSignUp = document.getElementById('do-sign-up');
let signUpLogin = document.getElementById('sign-up_login');
let signUpPassword = document.getElementById('sign-up_password');
let signUpAvatar = document.getElementById('sign-up_avatar');

btnLogIn.onclick = function () {
    if (login.value.length < 2 || password.value.length < 6) {
        return;
    }
    const xhttp = new XMLHttpRequest();

    xhttp.open("POST", "src/ajax/logIn.php", true);
    xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

    xhttp.onreadystatechange = function () {
        if (xhttp.readyState === 4 && xhttp.status === 200) {
            let returnedPage = xhttp.responseText;
            if (returnedPage.indexOf('class="error"') !== -1) {
                errorsContent.style.display = 'block';
                errorsContent.innerHTML = returnedPage;
            } else {
                hideAllContent();
                todoContent.style.display = 'block';
                todoContent.innerHTML = returnedPage;
                todo();
            }
        }
    }
    currentLogin = login.value;
    currentPassword = password.value;
    xhttp.send("login=" + currentLogin + '&password=' + currentPassword);
}


btnGoToSignUp.onclick = function () {
    hideAllContent();
    registerContent.style.display = 'block';
}

signUpBtnSignUp.onclick = function () {
    if (signUpLogin.value.length < 2 || signUpPassword.value.length < 6) {
        return;
    }
    const xhttp = new XMLHttpRequest();

    xhttp.open("POST", "src/ajax/register.php", true);
    xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

    xhttp.onreadystatechange = function () {
        if (xhttp.readyState === 4 && xhttp.status === 200) {
            let returnedPage = xhttp.responseText;
            if (returnedPage.indexOf('class="error"') !== -1) {
                errorsContent.style.display = 'block';
                errorsContent.innerHTML = returnedPage;
            } else {
                hideAllContent();
                todoContent.style.display = 'block';
                todoContent.innerHTML = returnedPage;
                todo();
            }
        }
    }

    currentLogin = signUpLogin.value;
    currentPassword = signUpPassword.value;

    xhttp.send("login=" + currentLogin + '&password=' + currentPassword + '&avatar=' + signUpAvatar.value);
}

function hideAllContent() {
    logInContent.style.display = 'none';
    profileContent.style.display = 'none';
    todoContent.style.display = 'none';
    errorsContent.style.display = 'none';
    registerContent.style.display = 'none';
}


function todo() {
    let createNewTaskPage = document.getElementById('div-create-new-task');
    createNewTaskPage.style.display = "none";
    let btnCreateNewTask = document.getElementById('btn-create-new-task');
    let btnCreate = document.getElementById('btn-create');
    let contentOfCreateTask = document.getElementById('contentOfCreateTask');
    let btnDelete = document.getElementsByClassName('btn-delete')

    btnCreateNewTask.onclick = function () {
        createNewTaskPage.style.display = "block";
    }

    btnCreate.onclick = function () {
        if (contentOfCreateTask === "") {
            return;
        }
        createNewTaskPage.style.display = "none";

        const xhttp = new XMLHttpRequest();

        xhttp.open("POST", "src/ajax/todo.php", true);
        xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

        xhttp.onreadystatechange = function () {
            if (xhttp.readyState === 4 && xhttp.status === 200) {
                hideAllContent();
                const xhttp2 = new XMLHttpRequest();

                xhttp2.open("POST", "src/ajax/logIn.php", true);
                xhttp2.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

                xhttp2.onreadystatechange = function () {
                    if (xhttp2.readyState === 4 && xhttp2.status === 200) {
                        let returnedPage = xhttp2.responseText;
                        if (returnedPage.indexOf('class="error"') !== -1) {
                            errorsContent.style.display = 'block';
                            errorsContent.innerHTML = returnedPage;
                        } else {
                            todoContent.style.display = 'block';
                            todoContent.innerHTML = returnedPage;
                            todo();
                        }
                    }
                }
                xhttp2.send("login=" + currentLogin + '&password=' + currentPassword);
            }
        }
        xhttp.send("contentCreateTask=" + contentOfCreateTask.value);
    }

    document.addEventListener("click", function (e) {
        if (e.target.id.indexOf('btnDelete') !== -1) {

            let idClickedBtnDelete = Number(e.target.id.slice(9));
            const xhttp = new XMLHttpRequest();

            xhttp.open("POST", "src/ajax/todo.php", true);
            xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

            xhttp.onreadystatechange = function () {
                if (xhttp.readyState === 4 && xhttp.status === 200) {
                    hideAllContent();
                    const xhttp2 = new XMLHttpRequest();

                    xhttp2.open("POST", "src/ajax/logIn.php", true);
                    xhttp2.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')

                    xhttp2.onreadystatechange = function () {
                        if (xhttp2.readyState === 4 && xhttp2.status === 200) {
                            let returnedPage = xhttp2.responseText;
                            if (returnedPage.indexOf('class="error"') !== -1) {
                                errorsContent.style.display = 'block';
                                errorsContent.innerHTML = returnedPage;
                            } else {
                                todoContent.style.display = 'block';
                                todoContent.innerHTML = returnedPage;
                                todo();
                            }
                        }
                    }
                    xhttp2.send("login=" + currentLogin + '&password=' + currentPassword);
                }
            }
            xhttp.send("idDeleteTask=" + idClickedBtnDelete);
        }
    });
}