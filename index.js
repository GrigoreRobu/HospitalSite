
function openLogPopup() {
    document.getElementById("loginPopup").style.visibility = "visible";
}
function closeLogPopup() {
    document.getElementById("loginPopup").style.visibility = "hidden";
}
function openInregPopup() {
    document.getElementById("inregPopup").style.visibility = "visible";
}
function closeInregPopup() {
    document.getElementById("inregPopup").style.visibility = "hidden";
}
function valideaza(event) {
    let username, password;
    const usernameRegex = /^[A-Za-z0-9]{8,}$/;
    const passwordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    if (event.target.id === "loginForm") {
        username = document.getElementById("loginUsername").value;
        password = document.getElementById("loginPass").value;
    } else if (event.target.id === "inregForm") {
        username = document.getElementById("inregUsername").value;
        password = document.getElementById("inregPass").value;
    }
    if (!usernameRegex.test(username)) {
        alert("Numele trebuie sa aiba cel putin 8 caractere.");
        event.preventDefault();
        return false;
    }
    if (!passwordRegex.test(password)) {
        alert("Parola trebuie sa aiba cel putin 8 caractere, o litera mare, o litera mica, un simbol special.");
        event.preventDefault();
        return false;
    }
    return true;
}

document.getElementById("loginForm").addEventListener("submit", valideaza);
document.getElementById("inregForm").addEventListener("submit", valideaza);
