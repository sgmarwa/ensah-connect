// espace_notes.js
document.addEventListener('DOMContentLoaded', loadStudents);
document.getElementById('save-button').addEventListener('click', saveNotes);

function loadStudents() {
    var urlParams = new URLSearchParams(window.location.search);
    var filiere = urlParams.get('filiere');

    fetch('fetch_students.php?filiere=' + encodeURIComponent(filiere))
    .then(response => response.json())
    .then(data => {
        displayStudents(data);
    });
}

function displayStudents(students) {
    var table = '<table>';
    table += '<tr><th>Photo</th><th>ID Etudiant</th><th>Nom et Prénom</th><th>Note</th></tr>';
    students.forEach(student => {
        table += '<tr>';
        if (student.photo) {
            table += '<td><img src="data:image/jpeg;base64,' + student.photo + '" alt="Photo de l\'étudiant" width="100" height="100"></td>';
        } else {
            table += '<td>Aucune photo</td>';
        }
        table += '<td>' + student.id_etudiant + '</td>';
        table += '<td>' + student.nomETprenom + '</td>';
        table += '<td><input type="number" name="note" data-id="' + student.id_etudiant + '" min="0" max="20" required></td>';
        table += '</tr>';
    });
    table += '</table>';
    document.getElementById('students-table').innerHTML = table;
}

function saveNotes() {
    var urlParams = new URLSearchParams(window.location.search);
    var filiere = urlParams.get('filiere');
    var module = urlParams.get('module');
    var evaluation = urlParams.get('evaluation');
    var pourcentage = document.getElementById('pourcentage').value;
    var notes = [];
    var valid = true;
    var per = true;

    // Vérification du pourcentage
    if (pourcentage === "" || pourcentage < 0 || pourcentage > 100) {
        per = false;
    }

    // Vérification des notes
    document.querySelectorAll('input[name="note"]').forEach(input => {
        var note = input.value;
        if (note === "" || note < 0 || note > 20) {
            valid = false;
        }
        notes.push({
            id_etudiant: input.getAttribute('data-id'),
            note: note
        });
    });

    if (!valid) {
        alert("Toutes les notes doivent être comprises entre 0 et 20 et les cases ne doivent pas rester vides");
        return;
    }

    if (!per) {
        alert("Le pourcentage doit être compris entre 0 et 100 et sa case ne doit pas rester vide");
        return;
    }

    fetch('save_notes.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            filiere: filiere,
            module: module,
            evaluation: evaluation,
            pourcentage: pourcentage,
            notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Notes enregistrées avec succès');
        } else {
            alert('Erreur lors de l\'enregistrement des notes');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
    });
}
