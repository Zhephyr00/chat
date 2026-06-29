<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk OceanChat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #edf4f8;" class="d-flex align-items-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow border-0 p-4 rounded-3 text-center">
                    <h3 style="color: #006699;" class="mb-3">OceanChat</h3>
                    <p class="text-muted small">Sebelum mulai mengobrol, masukkan nama kamu terlebih dahulu.</p>
                    <form action="{{ url('/set-nama') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <input type="text" name="nama" class="form-control text-center" placeholder="Nama Kamu..." required autocomplete="off" autofocus>
                        </div>
                        <button type="submit" class="btn text-white w-100" style="background-color: #006699;">Masuk Obrolan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
