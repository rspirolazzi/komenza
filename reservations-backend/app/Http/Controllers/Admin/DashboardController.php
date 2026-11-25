<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Reservation;
use App\Models\Admin\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return redirect()->route('admin.reservations.index');
        $barChartReservationByStore = $this->barChartReservationByStore();
        $stores = Store::all();
        $cols = ['Mes'];
        $rows = [];
        foreach($barChartReservationByStore->groupBy('date') as $date=>$value){
            $rows[$date] = [];
            $rows[$date][0] = $date;
            foreach($stores as $index => $store){
                $rows[$date][] = 0;
            }
        }
        foreach ($stores as $index => $store) {
            $cols[] = $store->name;
            $filtered = $barChartReservationByStore->filter(function ($value, $key) use ($store) {
                return $value->store_id == $store->id;
            });
            if ($filtered->count() > 0) {
                foreach ($filtered->all() as $value) {
                    if (!isset($rows[$value->date])) {
                        $rows[$value->date] = [];
                        $rows[$value->date][0] = $value->date;
                    }
                    $rows[$value->date][$index+1] = (int)$value->count;
                }
            }
        }
        $openReservationStoreToday = $this->getOpenReservationStoreToday();
        $reservationStoreToday = $this->getReservationStoreToday();
        return view('admin.dashboard')
            ->with('barChartReservationByStore', array_values(array_merge([$cols], $rows)))
            ->with('openReservationStoreToday', $openReservationStoreToday)
            ->with('reservationStoreToday', $reservationStoreToday);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function barChartReservationByStore()
    {
        $barChartReservationByStore = DB::table('reservations')
            ->select(
                'store_id',
                DB::raw('DATE_FORMAT(reservations.reservation_date, \'%Y-%m\') as date'),
                DB::raw('sum(reservations.people) as count')
            )
            ->groupBy('store_id', 'date')
            ->whereNotIn('status', [Reservation::$STATUS_CANCELED, Reservation::$STATUS_MAXIMUM_CAPACITY, Reservation::$STATUS_NOT_ATTENDED,])
            ->orderBy('date')
            ->orderBy('store_id')
            ->get();
        return $barChartReservationByStore;
    }

    /**
     * @param false|string $date
     * @return \Illuminate\Support\Collection
     */
    protected function getOpenReservationStoreToday($date = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');
        }
        $barChartReservationByStore = DB::table('stores')
            ->select(
                'stores.name as store_name',
                'stores.max_persons_day',
                'brands.name as brand_name',
                DB::raw('sum(reservations.people) as count')
            )
            ->join('reservations', 'reservations.store_id', '=', 'stores.id')
            ->join('brands', 'reservations.brand_id', '=', 'brands.id')
            ->groupBy('stores.id',
                'brands.id',
                'max_persons_day',
                'stores.name',
                'brands.name')
            ->whereIn('status', [Reservation::$STATUS_OPEN,])
            ->where('reservation_date', $date)
            ->orderBy('store_name')
            //->toSql()
            ->get();
        //echo $barChartReservationByStore;exit;
        return $barChartReservationByStore;
    }

    /**
     * @param false|string $date
     * @return \Illuminate\Support\Collection
     */
    protected function getReservationStoreToday($date = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');
        }
        $barChartReservationByStore = DB::table('stores')
            ->select(
                'stores.name as store_name',
                'brands.name as brand_name',
                DB::raw('sum(reservations.people) as count')
            )
            ->leftJoin('reservations', 'reservations.store_id', '=', 'stores.id')
            ->leftJoin('brands', 'reservations.brand_id', '=', 'brands.id')
            ->groupBy('stores.id', 'brands.id', 'stores.name','brands.name')
            ->whereNotIn('status', [Reservation::$STATUS_CANCELED, Reservation::$STATUS_NOT_ATTENDED,])
            ->where('reservation_date', $date)
            ->orderBy('store_name')
            //->toSql()
            ->get();
        //echo $barChartReservationByStore;exit;
        return $barChartReservationByStore;
    }
}
