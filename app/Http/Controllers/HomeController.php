<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Import;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    function index()
    {
        $activities = Activity::orderBy('created_at', 'DESC')->get();
        return view('index', ['activities' => $activities]);
    }

    function checkHistory($person_passport_number)
    {
        $history = Import::orderBy('created_at', 'DESC')->where('person_passport_number', $person_passport_number)->where('created_at', '>', now()->subDays(7)->endOfDay())->get();

        if ($history->count()) {
            return $history;
        } else {
            return false;
        }
    }

    function add(Request $request)
    {
        $data                               = [];
        $request->validate([
            'person_name'                       => 'required|max:191|min:3',
            'person_passport_number'            => 'required|max:191|min:3',
            'activities'                        => 'required|array'
        ]);

        $check = $this->checkHistory($request['person_passport_number']);

        if ($check) {
            $date = date('d.m.Y', strtotime($check[0]->created_at)) . ' г.';
            $stocks = '';

            foreach ($check as $key => $c) {
                $activities = Activity::orderBy('created_at', 'DESC')->where('id', $c->activity_id)->get();
                foreach ($activities as $a) {
                    $stocks .= ($key == 0 ? '' : ', ') . $a->name;
                }
            }
            $data['status']                     = 'error';
            $data['message']                    = 'Лицето "' . $request['person_name'] . '" с паспорт №: ' . $request['person_passport_number'] . ' е получило помощ изразена в: ' . $stocks . ' на дата: ' . $date;
        } else {
            foreach ($request['activities'] as $a) {
                $activity                           = new Import();
                $activity->person_name              = $request['person_name'];
                $activity->person_passport_number   = $request['person_passport_number'];
                $activity->activity_id              = $a;
                $activity->save();
            }

            $data['status']                     = 'success';
            $data['message']                    = 'Записът беше успешен!';
        }

        return view('result', ['data' => $data]);
    }
}
