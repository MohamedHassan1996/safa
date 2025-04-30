<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
        <!-- Load Arabic font from Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400..700&display=swap" rel="stylesheet">

        <title>الحالات الخيرية</title>
    <style>
        /*{ font-family: 'DejaVu Sans', sans-serif; } */
        body {
            font-family: 'Noto Naskh Arabic', sans-serif;
            direction: rtl;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
            direction: rtl;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: middle;
            direction: rtl;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">قائمة الحالات الخيرية</h2>

    <table>
        <thead>
            <tr>
                <th>اسم الحالة</th>
                <th>الرقم القومى</th>
                <th>اسم الزوج</th>
                <th>الرقم القومى للزوج</th>
                <th>العنوان</th>
                <th>التبرع</th>
                <th>ملاحظات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allDonations as $case)
            <tr>
                <td>{{ $case->charityCase?->name }}</td>
                <td>{{ $case->charityCase?->national_id }}</td>
                <td>{{ $case->charityCase?->pair_name }}</td>
                <td>{{ $case->charityCase?->pair_national_id }}</td>
                <td>{{ $case->charityCase?->address }}</td>
                <td>{{ $case->amount }}</td>
                <td>{{ $case->note }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
