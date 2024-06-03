<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $yearDefault = Carbon::now()->year;
        $statisticOrderStatus = Order::GetCountOrderByStatus($yearDefault)->get();

        $defaultMonthInYear = $this->handleChartMonthByYear($yearDefault);
//        get Year
        $yearSelect = [];
        $year = Carbon::now()->year;
        $countYear =env('COUNT_YEAR');
        $yearDown =  + $year - $countYear;
        for ($i = $yearDown ; $i <= $year ; $i++)
        {
            $yearSelect[] = $i;
        }

        $yearSelect = collect($yearSelect)->sortDesc()->values();
        return view('admin.dashboard.dashboard', compact('yearSelect' ,'defaultMonthInYear' ,'statisticOrderStatus'));
    }

    public function changeStatusOrderAjax(Request $request)
    {
        $typeChart = $request->input('type');
        $year = (int) $request->input('year');
        $statisticOrderStatus = Order::GetCountOrderByStatus($year)->get();

        if ($typeChart == 'chart')
        {
            $chartMonthInYear = $this->handleChartMonthByYear($year);
            return response()->json(['data' => $chartMonthInYear]);
        }

        return response()->json(['data' => $statisticOrderStatus]);
    }

    /**
     * @param $year
     * @return array
     */

    private function handleChartMonthByYear($year)
    {
        $defaultMonthInYear = [];
        $totalMonthInYear = 12;
        for ($i=1 ; $i<= $totalMonthInYear ; $i++)
        {
            $defaultMonthInYear[] = ['label'=> "Tháng ".$i, 'y'=> 0 ,'key'=>$i,'total_price' => 0];
        }
        $dataTotalOrderByMonth = Order::GetDataOrderByMonthAndYear($year)->get();
        $data = collect($dataTotalOrderByMonth)->keyBy('month_order');
        foreach ($defaultMonthInYear as $key => $value)
        {
            if (isset($data[$value['key']]))
            {
                $priceTotalMonth = $data[$value['key']]['doanh_thu_thang'] ?? 0;
                $defaultMonthInYear[$key]['y'] = $data[$value['key']]['count_month_order'];
                $defaultMonthInYear[$key]['label'] = "Tháng ".$data[$value['key']]['month_order'];
                $defaultMonthInYear[$key]['total_price'] = number_format($priceTotalMonth, 0, '', ',');
            }
        }
        return $defaultMonthInYear;
    }
}
