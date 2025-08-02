<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Metal Galery</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body>
    <div class="container-xl mt-3">
        <h1 class="text-center mt-5 mb-5">The Metal Galery</h1>
        <div id="container" class="row row-cols-1 row-cols-sm-2">
        </div>
    </div>
    <template id="cardObra">
        <div class="col">
            <div class="card mb-3">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="" class="img-fluid rounded-start" alt="">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"></h5>
                            <p class="card-text"></p>
                            <p class="card-autor"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
    <script>
        const container = document.getElementById('container');
        const template = document.getElementById('cardObra');

        fetch('class/GaleriaController.php', { method: 'GET' })
        .then(res => res.json())
        .then(obras => {

            obras.forEach(obra => {
                const clone = template.content.cloneNode(true);

                clone.querySelector('img').src = obra.imagen_path || 'placeholder.jpg';
                // clone.querySelector('img').alt = obra.titulo;
                clone.querySelector('.card-title').textContent = obra.titulo;
                clone.querySelector('.card-text').textContent = obra.descripcion;
                clone.querySelector('.card-autor').textContent = obra.autor;

                container.appendChild(clone);
            });
        })
        .catch(err => {
            console.error(err);
            console.error('Ocurrió un error');
        });
    </script>
</body>

</html>