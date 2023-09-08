<!doctype html>
<html>

<head>
    <title>Pull Out Letter</title>
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
            font-size: 13px;
        }
        .subHeader {
            width: 100%;
            font-size: 13px;
            line-height: 1.1;
        }
        td {
            text-align: center;
        }
    </style>

</head>

<body >


    <content>

    <?php



        if($company == "NBFI"){
            echo '<div>
            <div class="relative w-full">
                <div class="absolute top-0 left-0">
                    <img  src="/var/www/html/PRESTOv1/public/images/NBFIlogo.png" class="w-full" alt="logo">
                </div>
            </div>
        </div>';
        }

        else if($company == "ASC"){
            echo '<div>
            <div class="relative w-full">
                <div class="absolute top-0 left-0">
                    <img  src="/var/www/html/PRESTOv1/public/images/ASClogo.jpg" class="w-full" alt="logo">
                </div>
            </div>
        </div>';
        } elseif ($company == "CMC") {
            echo '<div>
                <div class="relative w-full">
                    <div class="absolute top-0 left-0">
                        <img  src="/var/www/html/PRESTOv1/public/images/CMClogo.jpg" class="w-full" alt="logo">
                    </div>
                </div>
            </div>';
        } elseif ($company == "ASC") {
            echo '<div>
                <div class="relative w-full">
                    <div class="absolute top-0 left-0">
                        <img  src="/var/www/html/PRESTOv1/public/images/AHLClogo.png" class="w-full" alt="logo">
                    </div>
                </div>
            </div>';
        }
            else if($company == "RDS"){
                echo '<div>
                <div class="relative w-full">
                    <div class="absolute top-0 left-0">
                        <img  src="/var/www/html/PRESTOv1/public/images/AHLClogo.png" class="w-full" alt="logo">
                    </div>
                </div>
            </div>';
            }else if($company == "EPC"){
                echo '<div>
                <div class="relative w-full">
                    <div class="absolute top-0 left-0">
                        <img  src="/var/www/html/PRESTOv1/public/images/EPClogo.png" class="w-1/2" alt="logo">
                    </div>
                </div>
            </div>';
            }
        // echo '<img src="'.$src.'" class="h-24" alt="logo">';
    ?>


    <br><br><br><br><br><br><br><br>
    <p style="font-weight: bold">{{ $date }} </p><br>
    <p style="font-weight: bold">{{ $branchName }}</p><br>
    <p style="font-weight: bold">RE:  PULL-OUT LETTER </p><br><br>


    <p>This is a request from our office to kindly allow our Retail Area Supervisor <span style="font-weight: bold">(see below)</span> to perform a pull-out of item(s)/hanger(s)/fixture(s) from your store/warehouse between <span id="formattedDate"></span> {{ $dateStart }}, and {{ $dateEnd }}.</p><br>
    <p style="text-transform: uppercase; font-weight: bold">1.  {{ $name }}</p><br>
        <div class="mx-24">
                <table id="branchMaintenanceTable" class="w-full text-xs text-left text-gray-500 dark:text-gray-400 border-collapse border border-slate-800">
                    <thead class="text-ml text-gray-900 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr class="w-full">
                            <th scope="col" class="py-1 px-3 border border-slate-300">
                                Total No. of Box (Items)
                            </th>
                            <th scope="col" class="py-1 px-3 border border-slate-300">
                                1
                            </th>
                        </tr>
                    </thead>
                </table><br>
                <table id="branchMaintenanceTable" class="w-full text-xs text-left text-gray-500 dark:text-gray-400 border-collapse border border-slate-800">
                    <thead class="text-ml text-gray-900 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr class="w-full">

                            <th scope="col" class="py-1 px-3 border border-slate-300">
                                BRAND NAME
                            </th>
                            <th scope="col" class="py-1 px-3 border border-slate-300">
                                QUANTITY
                            </th>
                            <th scope="col" class="py-1 px-3 border border-slate-300">
                                AMOUNT (PHP)
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $item)
                       <tr class="w-full">
                            <td scope="col" class='py-1 px-3 border border-slate-300'>{{ $item->brand }}</td>
                            <td scope="col" class='py-1 px-3 border border-slate-300'>{{ $item->quantity_total }}</td>
                            <td scope="col" class='py-1 px-3 border border-slate-300'>{{ number_format($item->amount_total, 2, '.', ',') }}</td>
                        </tr>
                        @endforeach

                    </tbody>
                    <tfoot>
                    <tr class="w-full">

                        <th scope="col" class="py-1 px-3 border border-slate-300">
                            TOTAL
                        </th>
                        <th scope="col" class="py-1 px-3 border border-slate-300">
                            {{ $totalQuantity }}
                        </th>
                        <th scope="col" class="py-1 px-3 border border-slate-300">
                        {{ $totalAmount }}
                        </th>
                    </tr>
                    </tfoot>
                </table>
        </div><br>

        <div class="mx-24">
                <table id="branchMaintenanceTable" class="w-full text-xs text-left text-gray-500 dark:text-gray-400 border-collapse border border-slate-800">
                    <thead class="text-ml text-gray-900 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr class="w-full">
                            <th scope="col" class="py-1 px-3 border border-slate-300">
                                Total No. of Box (Fixtures)
                            </th>
                            <th scope="col" class="py-1 px-3 border border-slate-300">
                                1
                            </th>
                        </tr>
                    </thead>
                </table><br>
                <table id="branchMaintenanceTable" class="w-full text-xs text-left text-gray-500 dark:text-gray-400 border-collapse border border-slate-800">
                    <thead class="text-ml text-gray-900 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr class="w-full">

                            <th scope="col" class="py-1 px-3 border border-slate-300">
                                FIXTURES
                            </th>
                            <th scope="col" class="py-1 px-3 border border-slate-300">
                                QUANTITY
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $item)
                       <tr class="w-full">
                            <td scope="col" class='py-1 px-3 border border-slate-300'>{{ $item->brand }}</td>
                            <td scope="col" class='py-1 px-3 border border-slate-300'>{{ $item->quantity_total }}</td>
                        </tr>
                        @endforeach

                    </tbody>
                    <tfoot>
                    <tr class="w-full">

                        <th scope="col" class="py-1 px-3 border border-slate-300">
                            TOTAL
                        </th>
                        <th scope="col" class="py-1 px-3 border border-slate-300">
                            {{ $totalQuantity }}
                        </th>
                    </tr>
                    </tfoot>
                </table>
        </div><br>

        <div class="mx-24">
                <table id="branchMaintenanceTable" class="w-full text-xs text-left text-gray-500 dark:text-gray-400 border-collapse border border-slate-800">
                    <thead class="text-ml text-gray-900 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr class="w-full">
                            <th scope="col" class="py-1 px-3 border border-slate-300">
                                Total No. of Box (Hangers)
                            </th>
                            <th scope="col" class="py-1 px-3 border border-slate-300">
                                1
                            </th>
                        </tr>
                    </thead>
                </table><br>
                <table id="branchMaintenanceTable" class="w-full text-xs text-left text-gray-500 dark:text-gray-400 border-collapse border border-slate-800">
                    <thead class="text-ml text-gray-900 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr class="w-full">

                            <th scope="col" class="py-1 px-3 border border-slate-300">
                                BRAND NAME
                            </th>
                            <th scope="col" class="py-1 px-3 border border-slate-300">
                                DESCRIPTION
                            </th>
                            <th scope="col" class="py-1 px-3 border border-slate-300">
                                QUANTITY
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($data as $item)
                       <tr class="w-full">
                            <td scope="col" class='py-1 px-3 border border-slate-300'>{{ $item->brand }}</td>
                            <td scope="col" class='py-1 px-3 border border-slate-300'>{{ $item->quantity_total }}</td>
                            <td scope="col" class='py-1 px-3 border border-slate-300'>{{ number_format($item->amount_total, 2, '.', ',') }}</td>
                        </tr>
                        @endforeach

                    </tbody>
                    <tfoot>
                    <tr class="w-full">

                        <th scope="col" class="py-1 px-3 border border-slate-300">
                            TOTAL
                        </th>
                        <th scope="col" class="py-1 px-3 border border-slate-300">
                            {{ $totalQuantity }}
                        </th>
                        <th scope="col" class="py-1 px-3 border border-slate-300">
                        {{ $totalAmount }}
                        </th>
                    </tr>
                    </tfoot>
                </table>
        </div><br>

        <div class="mx-24">
                <table id="branchMaintenanceTable" class="w-full text-sm text-left text-gray-500 dark:text-gray-400 border-collapse border border-slate-800">

                    <tbody>
                       <tr>
                            <td class='py-1 px-3 border border-slate-300'>Total No. of Cartons</td>
                            <td class='py-1 px-3 border border-slate-300'>{{ $boxCount}}</td>
                        </tr>

                    </tbody>
                </table>
        </div><br><br>

    <p>Thank you for accommodating our request.</p><br>
    <p>Very truly yours,</p><br><br>
    <?php

    if($company == "NBFI" || $company == "ASC" || $company == "CMC"){
        echo '<p>Ms. Emelyn Castro/Ms. Czareen Legaspi-Yanson</p>
        <p>AVP - Sales Assistant Manager</p>';
    } else {
        echo '<p>Ana Karla Brecio</p>
        <p>AVP- Sales and Merchandising</p>';
    }
    ?>

    </content>
</body>


</html>
