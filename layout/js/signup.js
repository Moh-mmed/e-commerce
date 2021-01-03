const form = document.getElementById("signup");
const username = document.getElementById("username");
const password = document.getElementById("password");
const fullname = document.getElementById("fullname");
const email = document.getElementById("email");

const eyeicon = document.getElementById("fa-eye");
let user, cell, full, pass;

usernameValidation();
emailValidation();
fullNameValidation();
passValidation();
form.addEventListener("submit", (e) => {
    if (user === 1 || mail === 1 || full === 1 || pass === 1) e.preventDefault();
});

function usernameValidation() {
    username.addEventListener("keyup", () => {
        if (username.value.length < 4 || username.value.length > 15) {
            username.classList.remove("success");
            username.classList.add("error");
            username.parentElement.querySelector(".length").classList.add("active");
            user = 1;
        } else {
            username.classList.remove("error");
            username.classList.add("success");
            username.parentElement
                .querySelector(".length")
                .classList.remove("active");
            user = 0;
        }
    });
}

function passValidation() {
    password.addEventListener("keyup", () => {
        if (password.value.length < 6) {
            password.classList.remove("success");
            password.classList.add("error");
            password.parentElement.querySelector(".length").classList.add("active");
            pass = 1;
        } else {
            password.classList.remove("error");
            password.classList.add("success");
            password.parentElement
                .querySelector(".length")
                .classList.remove("active");
            pass = 0;
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
        if (fullname.value == username.value) {
            fullname.classList.remove("success");
            fullname.classList.add("error");
            fullname.parentElement.querySelector(".same").classList.add("active");
            full = 1;
        } else {
            fullname.classList.remove("error");
            fullname.classList.add("success");
            fullname.parentElement.querySelector(".same").classList.remove("active");
            full = 0;
        }
    });
}

// show password

eyeicon.addEventListener("onmouse", () => {
    eyeicon.style.color = "black";
});

eyeicon.onmouseenter = () => {
    password.setAttribute("type", "text");
};
eyeicon.onmouseleave = () => {
    password.setAttribute("type", "password");
};