<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Import;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function __construct()
    {
        $this->import   = new Import();
        $this->activity = new Activity();
    }

    function index(Request $request)
    {
        $passport_number                = $request['passport_number'];
        $date_from                      = $request['date_from'];
        $date_to                        = $request['date_to'];
        $most_common                    = null;
        $least_common                   = null;
        $imports_list                   = null;

        $imports_count                  = $this->import;
        $people_count                   = $this->import;
        $imports                        = $this->import;

        if($passport_number){
            $imports_list               = $this->import->where('person_passport_number', $passport_number)->get();
            $imports_count              = $imports_count->where('person_passport_number', $passport_number);
            $people_count               = $people_count->where('person_passport_number', $passport_number);
            $imports                    = $imports->where('person_passport_number', $passport_number);
            if($imports_list->count()){
                foreach ($imports_list as $i) {
                    $i->date_dmy        = date('d.m.Y', strtotime($i->created_at)) . ' г.';
                    $i->activity_name   = $this->activity->where('id', $i->activity_id)->first()->name;
                }
            }
        }

        if($date_from || $date_to){
            $imports_count              = $imports_count->whereBetween('created_at', [$date_from, $date_to]);
            $people_count               = $people_count->whereBetween('created_at', [$date_from, $date_to]);
            $imports                    = $imports->whereBetween('created_at', [$date_from, $date_to]);
        }
 
        $imports_count                  = $imports_count->count() ?? '0';
        $people_count                   = $people_count->groupBy('person_passport_number')->get()->count() ?? '0';
        $imports                        = $imports->select('activity_id')->groupBy('activity_id')->orderByRaw('COUNT(*) DESC')->get();

        if($imports->count()){
            $most_common                = $this->activity->where('id', $imports[0]->activity_id)->first()->name;
            $least_common               = $this->activity->where('id', $imports[$imports->count() - 1]->activity_id)->first()->name;
        }
        
        $data = [
            'search' => [
                'passport_number'   => $passport_number,
                'date_from'         => $date_from,
                'date_to'           => $date_to,
            ],
            'imports_count' => $imports_count,
            'people_count'  => $people_count,
            'most_common'   => $most_common,
            'least_common'  => $least_common,
            'imports_list'  => $imports_list
        ];

        return view('statistics', $data);
    }
}
