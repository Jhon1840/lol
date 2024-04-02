import '../sass/tabler.scss';
import './bootstrap';
import './tabler-init';

// Asignar la función uploadCsv al objeto global window
window.uploadCsv = function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const csvFile = document.getElementById('csvFile').files[0];
    if (!csvFile) {
        alert('Por favor, seleccione un archivo .csv');
        return;
    }

    const formData = new FormData();
    formData.append('csvFile', csvFile);
    formData.append('_token', csrfToken);

    fetch('/upload-csv', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        // Aquí puedes mostrar un mensaje de éxito o realizar alguna otra acción
        toggleUploadForm(); // Ocultar el formulario de subida después de enviar el archivo
    })
    .catch(error => {
        console.error('Error:', error);
        // Aquí puedes mostrar un mensaje de error
    });
}

// Definir la función toggleUploadForm en el ámbito global
window.toggleUploadForm = function() {
    var uploadForm = document.getElementById("uploadForm");
    if (uploadForm.style.display === "none") {
        uploadForm.style.display = "block";
    } else {
        uploadForm.style.display = "none";
    }
}
