<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Print</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css') {{-- or your compiled Tailwind --}}
    <style>
        @media print {
            @page {
                size: A4 portrait;
                margin: 1.0cm;
            }

            body {
                font-size: 12px;
                color: #000;
                font-family: Arial, Helvetica, sans-serif;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body class="bg-slate-500 text-black h-screen">
    {{ $slot }}

    {{-- Auto print --}}
    <script>
        // window.addEventListener('load', () => {
        //     window.print();
        // });
    </script>
</body>

</html>