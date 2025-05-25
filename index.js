function openLogPopup() {
    document.getElementById("loginPopup").style.visibility = "visible";
}
function closeLogPopup() {
    document.getElementById("loginPopup").style.visibility = "hidden";
}

function valideazaUserPass(event) {
    const usernameRegex = /^[A-Za-z0-9]{1,}$/;
    const passwordRegex = /^[A-Za-z\d@$!%*?&]{1,}$/;
    username = document.getElementById("username").value;
    password = document.getElementById("password").value;

    if (!usernameRegex.test(username)) {
        alert("Login invalid");
        event.preventDefault();
        return false;
    }
    if (!passwordRegex.test(password)) {
        alert("Login invalid");
        event.preventDefault();
        return false;
    }
    return true;
}
document.getElementById("loginForm").addEventListener("submit", valideazaUserPass);

function validSpecializare(event) {
    const nume = document.querySelector('input[name="nume_specializare"]').value.trim();
    if (nume.length < 2 || nume.length > 50) {
        alert("Numele trebuie sa aiba minim 2 caractere si maxim 50.");
        event.preventDefault();
        return false;
    }
    return true;
}

function validServiciu(event) {
    const nume = document.querySelector('input[name="nume"]').value.trim();
    const descriere = document.querySelector('input[name="descriere"]').value.trim();
    const pret = parseFloat(document.querySelector('input[name="pret"]').value);
    const specializare = document.querySelector('select[name="id_specializari"]').value;

    if (nume.length < 2 || nume.length > 50) {
        alert("Numele trebuie sa aiba minim 2 caractere si maxim 50.");
        event.preventDefault();
        return false;
    }

    if (descriere.length < 5 || descriere.length > 255) {
        alert("Descrierea trebuie sa aiba maxin 255 caractere si minim 5");
        event.preventDefault();
        return false;
    }

    if (isNaN(pret) || pret <= 0 || pret > 10000) {
        alert("Pretul trebuie sa fie >=0 si <10000");
        event.preventDefault();
        return false;
    }

    if (!specializare) {
        alert("Selecteaza o specializare.");
        event.preventDefault();
        return false;
    }

    return true;
}

function validMedic(event) {
    const nume = document.querySelector('input[name="nume_medic"]').value.trim();
    const prenume = document.querySelector('input[name="prenume_medic"]').value.trim();
    const specializare = document.querySelector('select[name="id_specializari"]').value;
    const descriere = document.querySelector('input[name="descriere_medic"]').value.trim();

    if (nume.length < 2 || nume.length > 50) {
        alert("Numele trebuie sa aiba minim 2 caractere si maxim 50.");
        event.preventDefault();
        return false;
    }

    if (prenume.length < 2 || prenume.length > 50) {
        alert("Prenumele trebuie sa aiba minim 2 caractere si maxim 50.");
        event.preventDefault();
        return false;
    }

    if (!specializare) {
        alert("Selectati o specializare");
        event.preventDefault();
        return false;
    }

    if (descriere.length < 5 || descriere.length > 1000) {
        alert("Descrierea trebuie sa aiba minim 5 caractere si maxim 1000");
        event.preventDefault();
        return false;
    }

    return true;
}
document.addEventListener('DOMContentLoaded', function () {
    const addSpecializareForm = document.querySelector('form[name="add_specializare_form"]');
    if (addSpecializareForm) {
        addSpecializareForm.addEventListener('submit', valideazaSpecializare);
    }

    const addServiciuForm = document.querySelector('form[name="add_serviciu_form"]');
    if (addServiciuForm) {
        addServiciuForm.addEventListener('submit', validServiciu);
    }

    const addMedicForm = document.querySelector('form[name="add_medic_form"]');
    if (addMedicForm) {
        addMedicForm.addEventListener('submit', validMedic);
    }

    document.querySelectorAll('form.edit-serviciu-form').forEach(form => {
        form.addEventListener('submit', validServiciu);
    });

    document.querySelectorAll('form.edit-medic-form').forEach(form => {
        form.addEventListener('submit', validMedic);
    });
});