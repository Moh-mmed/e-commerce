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