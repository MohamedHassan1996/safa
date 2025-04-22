<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>الحالات الخيرية</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            direction: rtl;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: middle;
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
