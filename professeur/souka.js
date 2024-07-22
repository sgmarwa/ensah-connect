// souka.js
// Fonction pour charger les données des étudiants à partir du script PHP en fonction de la filière sélectionnée
function loadStudents() {
    // Récupérer le nom de la filière à partir de l'URL
    var urlParams = new URLSearchParams(window.location.search);
    var filiereNom = urlParams.get('filiere');
    
    // Effectuer une requête fetch pour récupérer les données des étudiants
    fetch('get_students.php?filiere=' + encodeURIComponent(filiereNom))
    .then(response => response.json())
    .then(data => {
        displayStudents(data); // Afficher les données des étudiants dans la table
    })
    .catch(error => console.error('Error:', error));
}

// Fonction pour afficher les étudiants dans un tableau
function displayStudents(students) {
    var table = '<table>';
    table += '<tr><th>ID Etudiant</th><th>Nom et Prénom</th><th>Action</th></tr>';
    students.forEach(student => {
        table += '<tr>';
        table += '<td>' + student.id_etudiant + '</td>';
        table += '<td>' + student.nomETprenom + '</td>';
        table += '<td><button class="btn-absence" data-id="' + student.id_etudiant + '">Marquer ABSENT</button></td>';
        table += '</tr>';
    });
    table += '</table>';
    document.getElementById('absence-table').innerHTML = table;

    // Ajouter des écouteurs d'événements aux boutons d'absence
    var absenceButtons = document.querySelectorAll('.btn-absence');
    absenceButtons.forEach(button => {
        button.addEventListener('click', function() {
            markAttendance(this.getAttribute('data-id'));
        });
    });
}

function markAttendance(studentId) {
    var urlParams = new URLSearchParams(window.location.search);
    var moduleNom = urlParams.get('module');
    var typeSeance = urlParams.get('type_seance');

    var button = document.querySelector('button[data-id="' + studentId + '"]');
    button.textContent = "ABS";
    button.disabled = true;

    fetch('mark_attendance.php?module=' + encodeURIComponent(moduleNom) + '&id=' + studentId + '&type_seance=' + encodeURIComponent(typeSeance) + '&action=ABS')
    .then(response => response.json())
    .then(data => {
        console.log("Response data:", data);
        if (data.success) {
            console.log(data.message);
            alert(data.message); // Afficher le message du serveur
        } else {
            console.error(data.message);
            alert('Erreur: ' + data.message); // Afficher le message d'erreur du serveur
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors du marquage de l\'absence'); // Afficher un message d'erreur général
    });
}

// Charger les données des étudiants lorsque la page est chargée
document.addEventListener('DOMContentLoaded', loadStudents);