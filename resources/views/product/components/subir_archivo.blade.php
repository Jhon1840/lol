<!DOCTYPE html>
<html lang="en">



<body>
    <form action="{{ route('import.products') }}" method="POST" enctype="multipart/form-data"
        onsubmit="return validateForm()">
        @csrf
        <div class="container mt-5">
            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <label for="file_svc" class="form-label">Importar archivo CSV:</label>
                        <input type="file" class="form-control" id="file_svc" name="file_svc" accept=".csv"
                            required>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Importar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        function validateForm() {
            const fileInput = document.getElementById('file_svc');
            if (fileInput.files.length === 0) {
                alert('Por favor, seleccione un archivo CSV para importar.');
                return false;
            }
            return true;
        }
    </script>




</html>
