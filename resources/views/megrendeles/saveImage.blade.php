<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script>
        const dataURL = signaturePad.toDataURL();
        saveImage(dataURL);

        function saveImage(dataURL) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            fetch('/save-image', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        dataURL: dataURL
                    })
                })
                .then(response => {
                    if (response.ok) {
                        console.log('Kép mente');
                        // Sikerüzenet megjelenítése
                        //alert('Az aláírás és az ügyfél sikeresen mentve lett!');
                        // 1 másodperc várakozás után átirányítás
                        /*setTimeout(() => {
                          window.location.href = '/send-mail';
                        }, 1000);*/
                    } else {
                        console.error('Error saving image:', response.statusText);
                    }
                })
                .catch(error => {
                    console.error('Error saving image:', error);
                });
        }
    </script>
</head>

<body>
    <h1>ALAIRAS</h1>
</body>

</html>
