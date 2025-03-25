<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Shipping_Governments;
use Illuminate\Support\Facades\Validator;


class StateController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:manage_shipping_states'])->only('index','edit');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_country = $request->sort_country;
        $sort_state = $request->sort_state;

        $state_queries = State::query();
        if ($request->sort_state) {
            $state_queries->where('name', 'like', "%$sort_state%");
        }
        if ($request->sort_country) {
            $state_queries->where('country_id', $request->sort_country);
        }

        $states = $state_queries->orderBy('status', 'desc')->paginate(15);
        return view('backend.setup_configurations.states.index', compact('states', 'sort_country', 'sort_state'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    public function store(Request $request)
    {

        $state = explode('_',$request->state_name);




        $state = new State;
        $state->name        = $request->name;
        $state->name_ar       = translate($request->name);
        $state->country_id  = $request->country_id;
        $state->status  = 1;

        $state->save();

        flash(translate('State has been inserted successfully'))->success();
        return back();
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $state  = State::findOrFail($id);
        $countries = Country::where('status', 1)->get();

        return view('backend.setup_configurations.states.edit', compact('countries', 'state'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $state = State::findOrFail($id);

        $shipping_state = explode('_',$request->state_name);
        $state->name        = $request->name;
        $state->name_ar       = translate($request->name);
        $state->country_id  = $request->country_id;

        $state->save();

        flash(translate('State has been updated successfully'))->success();
        return back();
    }


    public function destroy($id)
    {
        State::destroy($id);

        flash(translate('State has been deleted successfully'))->success();
        return redirect()->route('states.index');
    }

    public function updateStatus(Request $request)
    {
        $state = State::findOrFail($request->id);
        $state->status = $request->status;
        $state->save();

        if ($state->status) {
            foreach ($state->cities as $city) {
                $city->status = 1;
                $city->save();
            }
        }

        return 1;
    }


    public function updateShippingCompanyStates(Request $request)
    {
        try{
            $apiUrl = 'https://backoffice.turbo-eg.com/external-api/get-government';
            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Accept: application/json',
                'Content-Type: application/json',
            ));

            $response = curl_exec($ch);
            $responseData = json_decode($response, true);
            if(empty($responsData)){
                Shipping_Governments::truncate();
                DB::statement('ALTER TABLE shipping_governments AUTO_INCREMENT = 1');
            }
            foreach ($responseData as $data) {
                $feed = $responseData['feed'];
                foreach ($feed as $item) {
                    $government = new Shipping_Governments();
                    $government->state_id = $item['id'];
                    $government->name = $item['name'];
                    $government->save();
                }
            }
            flash('Shipping Governaments Updated Successfully')->success();
        }catch(Exception $e){
            flash('Could Not Update Shipping Governament')->error();
        }
    }
}
