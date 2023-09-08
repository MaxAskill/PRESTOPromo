<!doctype html>
<html>

<head>
    <title>Branch Maintenance</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.1.2/tailwind.min.css" />
    <link rel="stylesheet" href="output.css" />
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body{
            background-color:white;
        }
        table {
            border-collapse: collapse;
            border: 1px solid black;
            table-layout: auto;
            width: 30%;
            border: 1px solid black;
        }
        th,td {
            border: 1px solid black;
        }

    </style>

</head>

<body >


    <content>

    <p>Hi <span style="font-weight: bold;">{{ $data['name'] }}</span></p>
    <p>We would like to inform you that the pull-out transaction no.: <span style="font-weight: bold;text-decoration: underline;">{{ $data['transactionID'] }}</span> has been approved and will be process.</p>

    <p>If you have any questions or concerns regarding the transaction, please do not hesitate to contact me thru email or text.</p><br>
    <p>Best regards,</p>
    <p>{{ $data['adminName'] }} </p><br>

    </content>

</body>

</html>
