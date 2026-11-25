<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Models\Admin\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PdfReport;
use Auth;
use PDF;
class ReportsController extends Controller
{
    public function index(Request $request)
    {
        switch ($request->get('export_to_pdf')) {
            case '1':
                return $this->_report1($request);
                break;
            case '2':
                return $this->_report2($request);
                break;
            case '3':
                return $this->_report3($request);
                break;
            case '4':
                return $this->_report4($request);
                break;
            case '5':
                return $this->_report5($request);
                break;
            default:
                $report1 = $this->getReport1($request)->get();
                $report2 = $this->getReport2($request)->get();
                $report3 = $this->getReport3($request)->get();
                $report4 = $this->getReport4($request)->get();
                $report5 = $this->getReport5($request)->get();
                $months = $this->getMonths();

                return view('admin.reports.index')->with(
                    [
                        'months' => $months,
                        'report1' => $report1,
                        'report2' => $report2,
                        'report3' => $report3,
                        'report4' => $report4,
                        'report5' => $report5,
                    ]);
                break;
        }
    }

    public function _report1(Request $request)
    {
        // Report title
        $title = 'Reporte de visitas en este mes';

        // For displaying filters description on header
        $meta = [
            'Creado' => date('d-m-Y'),
        ];

        // Do some querying..
        $result = $this->getReport1($request)->get();
        $date = $request->get('report1_current_month');

        // Set Column to be displayed
        $columns = [
            'Tienda' => 'store_name',
            'Marca' => 'brand_name',
            'Número de reservaciones' => 'count'
        ];

        $pdf = PDF::loadView('admin.pdf.report', compact('title', 'meta', 'result', 'columns', 'date'));
        return @$pdf->download(str_replace(' ','',$title).'.pdf');

    }

    public function _report2(Request $request)
    {
        // Report title
        $title = 'Usuarios con más reservaciones';

        // For displaying filters description on header
        $meta = [
            'Creado' => date('d-m-Y'),
        ];

        $result = $this->getReport2($request)->get();

        $date = $request->get('report1_current_month');
        $columns = [
            'Email' => 'email',
            'Nombre' => 'name',
            'Número de reservaciones' => 'count'
        ];


        $pdf = PDF::loadView('admin.pdf.report', compact('title', 'meta', 'result', 'columns', 'date'));
        return @$pdf->download(str_replace(' ','',$title).'.pdf');
    }

    public function _report3(Request $request)
    {
        // Report title
        $title = 'Usuarios con más reservaciones en sucursales';

        // For displaying filters description on header
        $meta = [
            'Creado' => date('d-m-Y'),
            'Horario' => '09:00 a 18:00 hrs'
        ];

        // dd(User::all());
        // Do some querying..
        $result = $this->getReport3($request)->get();
        $date = $request->get('report3_current_month');

        // Set Column to be displayed
        $columns = [
            'Email' => 'email',
            'Nombre' => 'name',
            'Marca' => 'brand_name',
            'Sucursal' => 'store_name',
            'Número de reservaciones' => 'count',
        ];
        $pdf = PDF::loadView('admin.pdf.report', compact('title', 'meta', 'result', 'columns', 'date'));
        return @$pdf->download(str_replace(' ', '', $title) . '.pdf');
    }
    public function _report4(Request $request)
    {
        // Report title
        $title = 'Cantidad de reservas por turno y sucursal';

        // For displaying filters description on header
        $meta = [
            'Creado' => date('d-m-Y'),
            'Horario' => '18:00 a 04:00 hrs'
        ];

        $result = $this->getReport4($request)->get();
        $date = $request->get('report4_current_month');

        // Set Column to be displayed
        $columns = [
            'turn' => 'periodOfDay',
            'Tienda' => 'store_name',
            'Marca' => 'brand_name',
            'Número de reservaciones' => 'count'
        ];

        $pdf = PDF::loadView('admin.pdf.report', compact('title', 'meta', 'result', 'columns', 'date'));
        return @$pdf->download(str_replace(' ','',$title).'.pdf');
    }
    public function _report5(Request $request)
    {
        // Report title
        $title = 'Usuarios que reservaron';

        // For displaying filters description on header
        $meta = [
            'Creado' => date('d-m-Y'),
        ];

        $result = $this->getReport5($request)->get();
        $date = $request->get('report5_current_month');

        // Set Column to be displayed
        $columns = [
            'email'=>'Email',
            'name'=>'Nombre',
            'last_name'=>'Apellido',
            'mobile'=>'Celular',
            'birthday'=>'Fec Nac.',
        ];

        $pdf = PDF::loadView('admin.pdf.report', compact('title', 'meta', 'result', 'columns', 'date'));
        return @$pdf->download(str_replace(' ','',$title).'.pdf');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Query\Builder
     */
    protected function getReport1(Request $request)
    {
        $date = $request->get('report1_current_month', date('m-Y'));
        $current_month = explode('-', $date)[0];
        $current_year = explode('-', $date)[1];

        return DB::table('reservations')
            ->select(
                'stores.name as store_name',
                'brands.name as brand_name',
                DB::raw('sum(reservations.people) as count')
            )
            ->leftJoin('users', 'reservations.user_id', '=', 'users.id')
            ->leftJoin('stores', 'reservations.store_id', '=', 'stores.id')
            ->leftJoin('brands', 'reservations.brand_id', '=', 'brands.id')
            ->groupBy('stores.id', 'brands.id', 'store_name', 'brand_name')
            ->whereNotIn('status', [Reservation::$STATUS_CANCELED, Reservation::$STATUS_MAXIMUM_CAPACITY, Reservation::$STATUS_NOT_ATTENDED,])
            ->whereMonth('reservation_date', '=', $current_month)
            ->whereYear('reservation_date', '=', $current_year)
            ->whereNull('users.role')
            ->orderBy('store_name');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Query\Builder
     */
    protected function getReport2(Request $request)
    {
        $date = $request->get('report2_current_month', date('m-Y'));
        $current_month = explode('-', $date)[0];
        $current_year = explode('-', $date)[1];
        return DB::table('reservations')
            ->select(
                'users.email',
                DB::raw('CONCAT(users.name,\', \' ,users.last_name) as name'),
                DB::raw('sum(reservations.people) as count')
            )
            ->leftJoin('users', 'reservations.user_id', '=', 'users.id')
            ->whereNull('users.role')
            ->whereNotIn('status', [Reservation::$STATUS_CANCELED, Reservation::$STATUS_MAXIMUM_CAPACITY, Reservation::$STATUS_NOT_ATTENDED,])
            ->whereMonth('reservation_date', '=', $current_month)
            ->whereYear('reservation_date', '=', $current_year)
            ->groupBy('users.email', 'users.last_name', 'users.name')
            ->orderBy('count');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Query\Builder
     */
    protected function getReport3(Request $request)
    {
        $date = $request->get('report3_current_month', date('m-Y'));
        $current_month = explode('-', $date)[0];
        $current_year = explode('-', $date)[1];
        return DB::table('reservations')
            ->select(
                'users.email', 'stores.name as store_name', 'brands.name as brand_name',
                DB::raw('CONCAT(users.name,\', \' ,users.last_name) as name'),
                DB::raw('sum(reservations.people) as count')
            )
            ->leftJoin('users', 'reservations.user_id', '=', 'users.id')
            ->leftJoin('stores', 'reservations.store_id', '=', 'stores.id')
            ->leftJoin('brands', 'stores.brand_id', '=', 'brands.id')
            ->whereNull('users.role')
            ->whereNotIn('status', [Reservation::$STATUS_CANCELED, Reservation::$STATUS_MAXIMUM_CAPACITY, Reservation::$STATUS_NOT_ATTENDED,])
            ->whereMonth('reservation_date', '=', $current_month)
            ->whereYear('reservation_date', '=', $current_year)
            ->groupBy('users.email', 'users.last_name', 'store_name', 'brand_name', 'users.name')
            ->orderBy('count');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Query\Builder
     */
    protected function getReport4(Request $request)
    {
        $date = $request->get('report4_current_month', date('m-Y'));
        $current_month = explode('-', $date)[0];
        $current_year = explode('-', $date)[1];
        return DB::table('reservations')
            ->select('stores.name as store_name',
                'brands.name as brand_name',
                DB::raw('CASE WHEN reservation_time < \'18:00:00\' THEN \'Día\' ELSE \'Noche\' END AS periodOfDay'),
                DB::raw('sum(reservations.people) as count')
            )
            ->leftJoin('stores', 'reservations.store_id', '=', 'stores.id')
            ->leftJoin('brands', 'reservations.brand_id', '=', 'brands.id')
            ->groupBy('periodOfDay', 'stores.id', 'brands.id', 'store_name', 'brand_name')
            ->whereNotIn('status', [Reservation::$STATUS_CANCELED, Reservation::$STATUS_MAXIMUM_CAPACITY, Reservation::$STATUS_NOT_ATTENDED,])
            ->whereMonth('reservation_date', '=', $current_month)
            ->whereYear('reservation_date', '=', $current_year)
            ->orderBy('count');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Query\Builder
     */
    protected function getReport5(Request $request)
    {
        //$date = $request->get('report4_current_month', date('m-Y'));
        //$current_month = explode('-', $date)[0];
        //$current_year = explode('-', $date)[1];
        $DB = DB::table('users')
            ->select(
                'email',
                'name',
                'last_name',
                'mobile',
                'birthday'
            )
            ->whereNull('role')
            ->groupBy('email','name','last_name','mobile','birthday')
            ->orderBy('email');

        switch (Auth::user()->role) {
            case Admin::$ROLE_BRAND:
                break;
        }
        return $DB;
    }

    public function getMonths()
    {
        return DB::table('reservations')
            ->select(DB::raw('DATE_FORMAT(reservation_date, \'%m-%Y\') as date'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();
    }
}
