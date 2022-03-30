<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Import;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->import                   = new Import;
        $this->whitelist_ip_addresses   = ['::1'];
    }

    function checkAvailability($request)
    {
        // $ip_address   = $request->ip();
        // if (in_array($ip_address, $this->whitelist_ip_addresses)) {
        //     return true;
        // } else {
        //     return false;
        // }

        return true;
    }

    function index(Request $request)
    {
        if(!$this->checkAvailability($request)){
            return view('failed');
        }
        $activities = Activity::orderBy('created_at', 'DESC')->get();
        return view('index', ['activities' => $activities]);
    }

    function checkHistory($person_passport_number, $activities)
    {
        $history          = $this->import::orderBy('created_at', 'DESC')->where('person_passport_number', $person_passport_number)->where('created_at', '>', now()->subDays(7)->endOfDay())->get();
        $final_history    = $this->import::orderBy('created_at', 'DESC')->where('person_passport_number', $person_passport_number)->whereIn('activity_id', $activities)->where('created_at', '>', now()->subDays(7)->endOfDay())->get();

        if ($final_history->count()) {
            return $history;
        } else {
            return false;
        }
    }

    function add(Request $request)
    {
        if(!$this->checkAvailability($request)){
            return view('failed');
        }

        $data                                         = [];
        $request->validate([
            'person_name'                           => 'required|max:191|min:3',
            'person_passport_number'                => 'required|max:191|min:3',
            'activities'                            => 'required|array',
            'description'                           => 'max:1000'
        ]);

        $check                                        = $this->checkHistory($request['person_passport_number'], $request['activities']);

        if ($check) {
            $stocks                                   = '';

            foreach ($check as $key => $c) {
                $activities                           = Activity::orderBy('created_at', 'DESC')->where('id', $c->activity_id)->get();
                foreach ($activities as $a) {
                    $stocks                          .= ($key == 0 ? ' (' : ', (') . $a->name . ' на: ' . date('d.m.Y', strtotime($c->created_at)) . ' г.' . ')';
                }
            }
            $data['status']                           = 'error';
            $data['message']                          = 'Лицето "' . $request['person_name'] . '" с паспорт №: ' . $request['person_passport_number'] . ' е получило помощ изразена в: ' . $stocks;
        } else {
            foreach ($request['activities'] as $a) {
                $activity                             = new Import();
                $activity->person_name                = $request['person_name'];
                $activity->person_passport_number     = $request['person_passport_number'];
                $activity->activity_id                = $a;
                $activity->description                = $request['description'];
                $activity->save();
            }

            $data['status']                           = 'success';
            $data['message']                          = 'Записът беше успешен!';
        }

        return view('result', ['data' => $data]);
    }
}
