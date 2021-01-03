const form = document.getElementById("update-info");
const username = document.getElementById("username");
const fullname = document.getElementById("fullname");
const email = document.getElementById("email");
let user, cell, full;

usernameValidation();
emailValidation();
fullNameValidation();
form.addEventListener("submit", (e) => {
    if (user === 1 || mail === 1 || full === 1) e.preventDefault();
});

function usernameValidation() {
    username.addEventListener("keyup", () => {
        if (
            username.value.length < 4 ||
            username.value.length > 15 ||
            fullname.value == username.value
        ) {
            username.classList.remove("success");
            username.classList.add("error");
            user = 1;
        } else {
            username.classList.remove("error");
            username.classList.add("success");
            user = 0;
        }
    });
}

function emailValidation() {
    email.addEventListener("keyup", () => {
        if (email.value.length < 3) {
            email.classList.remove("success");
            email.classList.add("error");
            mail = 1;
        } else {
            email.classList.remove("error");
            email.classList.add("success");
            mail = 0;
        }
    });
}

function fullNameValidation() {
    fullname.addEventListener("keyup", () => {
        if (
            fullname.value.length < 4 ||
            fullname.value.length > 25 ||
            fullname.value == username.value
        ) {
            fullname.classList.remove("success");
            fullname.classList.add("error");
            full = 1;
        } else {
            fullname.classList.remove("error");
            fullname.classList.add("success");
            full = 0;
        }
    });
}