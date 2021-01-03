const form = document.getElementById("new-ad");
const input = form.querySelectorAll(".live");
const adCurr = form.querySelector("select");
const liveCurr = document.querySelector(".live-curr");
input.forEach((element) => {
    element.addEventListener("keyup", () => {
        document.querySelector(element.getAttribute("data-class")).textContent =
            element.value;
    });
});

function getCurr(curr) {
    currencies = {
        "": "",
        USD: "$",
        CAD: "$",
        EUR: "€",
        GBP: "£",
        DZD: "DZD",
    };
    return currencies[curr];
}
// add an event when selecting an option
adCurr.addEventListener("click", () => {
    let currency = adCurr.options[adCurr.selectedIndex].text;
    liveCurr.textContent = getCurr(currency);
});
// BACKEND CODE ===========================================================================================================
let deleteButtons = document.querySelectorAll(".confirm");
const shivronButtons = document.querySelectorAll(".toggle-info ");
// Delete Confirmation Message
deleteButtons.forEach((element) => {
    element.onclick = () => {
        return confirm("Do You Really Want To Delete This Member?");
    };
});

// Dashboard shivron buttons
shivronButtons.forEach((shivronDown) => {
    shivronDown.onclick = () => {
        let element = shivronDown.parentElement.nextElementSibling;
        shivronDown.classList.toggle("selected");
        if (shivronDown.classList.contains("selected")) {
            fade(element);
        } else {
            unfade(element);
        }
    };
});

function fade(element) {
    var op = 1; // initial opacity
    var timer = setInterval(function() {
        if (op <= 0.1) {
            clearInterval(timer);
            element.style.display = "none";
        }
        element.style.opacity = op;
        element.style.filter = "alpha(opacity=" + op * 100 + ")";
        op -= op * 0.1;
    }, 10);
}

function unfade(element) {
    var op = 0.1; // initial opacity
    element.style.display = "block";
    var timer = setInterval(function() {
        if (op >= 1) {
            clearInterval(timer);
        }
        element.style.opacity = op;
        element.style.filter = "alpha(opacity=" + op * 100 + ")";
        op += op * 0.1;
    }, 10);
}