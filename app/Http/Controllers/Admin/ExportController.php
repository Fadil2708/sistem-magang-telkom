<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ApplicationsExport;
use App\Exports\CertificatesExport;
use App\Exports\EvaluationsExport;
use App\Exports\InternshipsExport;
use App\Exports\LogbooksExport;
use App\Exports\ReportsExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function internships()
    {
        return Excel::download(new InternshipsExport, 'internships.xlsx');
    }

    public function logbooks()
    {
        return Excel::download(new LogbooksExport, 'logbooks.xlsx');
    }

    public function applications()
    {
        return Excel::download(new ApplicationsExport, 'applications.xlsx');
    }

    public function reports()
    {
        return Excel::download(new ReportsExport, 'reports.xlsx');
    }

    public function evaluations()
    {
        return Excel::download(new EvaluationsExport, 'evaluations.xlsx');
    }

    public function certificates()
    {
        return Excel::download(new CertificatesExport, 'certificates.xlsx');
    }
}
