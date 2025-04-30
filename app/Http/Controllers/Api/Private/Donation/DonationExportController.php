<?php

namespace App\Http\Controllers\Api\Private\Donation;

use App\Http\Controllers\Controller;

use App\Services\Donation\DonationService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpWord\PhpWord;
//use Barryvdh\DomPDF\Facade\Pdf; // Add at top of controller
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Language;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\JcTable;
use PhpOffice\PhpWord\Style\Cell;
use misterspelik\LaravelPdf\Facades\Pdf;

class DonationExportController extends Controller implements HasMiddleware
{
    protected $donationService;


    public function __construct(DonationService $donationService)
    {
        $this->donationService = $donationService;
    }

    public static function middleware(): array
    {
        return [
            //new Middleware('auth:api'),
            // new Middleware('permission:all_charity_cases', only:['index']),
            // new Middleware('permission:create_charity_case', only:['create']),
            // new Middleware('permission:edit_charity_case', only:['edit']),
            // new Middleware('permission:update_charity_case', only:['update']),
            // new Middleware('permission:destroy_charity_case', only:['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->exportType == 'excel') {

            return $this->exportDonationsToExcel($request);
        } elseif($request->exportType == 'pdf') {

            return $this->exportDonationsToPdf($request);
        }elseif($request->exportType == 'docx') {
            return $this->exportDonationsToDocx($request);
        }
    }

    private function exportDonationsToExcel(Request $request) {

        // Retrieve all charity cases from the service or model
        $allCharityCases = $this->donationService->allDonations();
        // Create a new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set the sheet direction to right to left (for RTL languages like Arabic)
        $sheet->setRightToLeft(true);

        // Set the header row
        $sheet->setCellValue('A1', 'اسم الحالة')
              ->setCellValue('B1', 'الرقم القومى')
              ->setCellValue('C1', 'اسم الزوج')
              ->setCellValue('D1', 'الرقم القومى للزوج')
              ->setCellValue('E1', 'العنوان')
              ->setCellValue('F1', 'التبرع')
              ->setCellValue('G1', 'ملاحظات');

        // Bold header row and apply borders
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:G1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Apply borders to all cells in the range
        $sheet->getStyle('A1:G' . (count($allCharityCases) + 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Set column widths
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);

        // Loop through each charity case and fill data starting from row 2
        $row = 2;
        foreach ($allCharityCases as $case) {
            // Use setCellValueExplicit to treat these numbers as text
            $sheet->setCellValueExplicit('B' . $row, (string) $case->charityCase->national_id, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                  ->setCellValueExplicit('D' . $row, (string) $case->charityCase->pair_national_id, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
                  ->setCellValue('A' . $row, $case->charityCase?->name)
                  ->setCellValue('C' . $row, $case->charityCase->pair_name)
                  ->setCellValue('E' . $row, $case->charityCase->address)
                  ->setCellValue('F' . $row, $case->amount)
                  ->setCellValue('G' . $row, $case->note);

            $row++;
        }

        // Apply autofilter to header row (A1:G1)
        $sheet->setAutoFilter('A1:G1');

        // Handle dynamic filename based on filter dates
        $fileName = 'charity_cases_' . time() . '.xlsx';
        $filePath = 'public/' . $fileName;
        // if ($request['filter']['startDate'] || $request['filter']['endDate']) {
        //     $fileName = 'charity_cases_from_' . $request['filter']['startDate'] . '_to_' . $request['filter']['endDate'] . '.xlsx';
        // } else if ($request['filter']['startDate']) {
        //     $fileName = 'charity_cases_' . $request['filter']['startDate'] . '.xlsx';
        // } else if ($request['filter']['endDate']) {
        //     $fileName = 'charity_cases_to_' . $request['filter']['endDate'] . '.xlsx';
        // }

            // Define file name (you can customize based on dates if needed)

    // Save file
        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path('app/' . $filePath));

        // Generate full URL
        $url = asset('storage/' . $fileName);

        // Return path as JSON
        return response()->json([
            'path' => $url
        ]);
    }

    private function exportDonationsToPdf(Request $request) {

        // Retrieve all charity cases from the service or model
        $allDonations = $this->donationService->allDonations();

        $fileName = 'charity_cases.pdf';


        $fileName = 'charity_cases_' . time() . '.pdf';
        $filePath = 'public/' . $fileName; // This saves under storage/app/public/

        // Generate the PDF
        $pdf = Pdf::loadView('export.donation_pdf', [
            'allDonations' => $allDonations
        ])->save(storage_path('app/' . $filePath));
            //set_option('font', 'Cairo')->setPaper('a4')->
        // Get public URL
        $url = url(Storage::url($filePath));

        return response()->json([
            'path' => $url
        ]);
    }

    private function exportDonationsToDocx(Request $request)
    {
        $cases = $this->donationService->allDonations();

        $phpWord = new PhpWord();

        // Set Arabic language
        $phpWord->getSettings()->setThemeFontLang(new Language('ar-SA'));

        // Set default RTL paragraph style
        $phpWord->setDefaultParagraphStyle([
            'alignment' => Jc::RIGHT,
            'rtl' => true,
            'bidiVisual' => true
        ]);

        $section = $phpWord->addSection();

        // Title (centered, bold, RTL)
        $section->addText(
            'قائمة الحالات',
            ['bold' => true, 'size' => 18, 'name' => 'Arial'],
            ['alignment' => Jc::CENTER, 'rtl' => true]
        );

        // Table with RTL support
        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '999999',
            'alignment' => JcTable::CENTER,
            'bidiVisual' => true, // RTL layout
        ]);

        // Headers in reverse order for RTL
        $headers = [
            'اسم الحالة',
            'الرقم القومى',
            'اسم الزوج',
            'الرقم القومى للزوج',
            'العنوان',
            'التبرع',
            'ملاحظات'
        ];

        $table->addRow();
        foreach ($headers as $header) {
            $table->addCell(2000)->addText(
                $header,
                ['bold' => true, 'name' => 'Arial'],
                ['alignment' => Jc::CENTER, 'rtl' => true, 'bidiVisual' => true]
            );
        }

        // Data rows
        foreach ($cases as $case) {
            $table->addRow();
            $table->addCell(2000)->addText($case->charityCase?->name ?? '', ['name' => 'Arial'], ['rtl' => true, 'bidiVisual' => true]);
            $table->addCell(2000)->addText((string)($case->charityCase?->national_id ?? ''), ['name' => 'Arial'], ['rtl' => true, 'bidiVisual' => true]);
            $table->addCell(2000)->addText($case->charityCase?->pair_name ?? '', ['name' => 'Arial'], ['rtl' => true, 'bidiVisual' => true]);
            $table->addCell(2000)->addText((string)($case->charityCase?->pair_national_id ?? ''), ['name' => 'Arial'], ['rtl' => true, 'bidiVisual' => true]);
            $table->addCell(2000)->addText($case->charityCase?->address ?? '', ['name' => 'Arial'], ['rtl' => true, 'bidiVisual' => true]);
            $table->addCell(2000)->addText((string)$case->amount, ['name' => 'Arial'], ['rtl' => true, 'bidiVisual' => true]);
            $table->addCell(2000)->addText($case->note ?? '', ['name' => 'Arial'], ['rtl' => true, 'bidiVisual' => true]);
        }

        // Filename with timestamp to avoid conflicts
        $fileName = 'charity_cases_' . time() . '.docx';
        $filePath = 'public/' . $fileName;
        $tempPath = storage_path('app/' . $filePath);

        // Save the file
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempPath);

        // Public URL
        $url = url(Storage::url($fileName));

        return response()->json([
            'path' => $url
        ]);
    }



}
