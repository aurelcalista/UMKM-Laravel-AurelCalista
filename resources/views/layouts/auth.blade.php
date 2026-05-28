<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Login</title>
    <link rel="stylesheet" href="{{asset('css/template-login.css')}}">

<link rel="preconnect" href="https://fonts.googleapis.com">

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    
</head>
<body>
    @yield('content')
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Login Gagal',
            text: "{{ $errors->first() }}",
        });
    });
</script>
@endif

@if(session('status'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: "{{ session('status') }}",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    });
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: "{{ session('error') }}",
        });
    });
</script>
@endif

</body>
</html>