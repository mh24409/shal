<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\City;
use App\Models\State;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request ;
        $address = new Address;
        if ($request->has('customer_id')) {
            $address->user_id   = $request->customer_id;
        } elseif (auth()->user()) {
            $address->user_id   = Auth::user()->id;
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $address->temp_user_id   =  $temp_user_id;

            $address->user_id   = null;
        }
        $address->name       = $request->name;
        $address->address       = $request->address;
        $address->country_id    = $request->country_id;
        $address->state_id      = $request->state_id;
        $address->city_id       = 0;
        $address->longitude     = 'longitude';
        $address->latitude      = 'latitude';
        $address->postal_code   = "20";
        $address->phone         = $request->phone;
        $address->save();

        flash(translate('Address info Stored successfully'))->success();
        return back();
    }
    public function store_address_fron_checkout(Request $request)
    { 
        $address = new Address;
        if ($request->has('customer_id')) {
            $address->user_id   = $request->customer_id;
        } elseif (auth()->user()) {
            $address->user_id   = Auth::user()->id;
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
            $address->temp_user_id   =  $temp_user_id;

            $address->user_id   = null;
        }
        
        $address->name       = $request->name;
        $address->address       = $request->address;
        $address->country_id    = $request->country_id;
        $address->state_id      = $request->state_id;
        $address->city_id       = 0;
        $address->longitude     = 'longitude';
        $address->latitude      = 'latitude';
        $address->postal_code   = "20";
        $address->phone         = $request->phone;
        $address->save();

        $selected_address = $address;
        $address_inputs = view('frontend.checkout_one_Step.address_inputs', compact('selected_address'))->render();
        $user_addresses = view('frontend.checkout_one_Step.user_addresses')->render();
        return response()->json(array('address_inputs' => $address_inputs , 'user_addresses' => $user_addresses , 'address_id' => $address->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['address_data'] = Address::findOrFail($id);
        $data['states'] = State::where('status', 1)->where('country_id', $data['address_data']->country_id)->get();
        $data['cities'] = City::where('status', 1)->where('state_id', $data['address_data']->state_id)->get();

        $returnHTML = view('frontend.partials.address_edit_modal', $data)->render();
        return response()->json(array('data' => $data, 'html' => $returnHTML));
        //        return ;
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
        $address = Address::findOrFail($id);
        $address->name       = $request->name;
        $address->address       = $request->address;
        $address->country_id    = $request->country_id;
        $address->state_id      = $request->state_id;
        $address->city_id       = 0;
        $address->longitude     = 'longitude';
        $address->latitude      = 'latitude';
        $address->postal_code   = "20";
        $address->phone         = $request->phone;

        $address->save();

        flash(translate('Address info updated successfully'))->success();
        return back();
    }


    public function destroy($id)
    {
        $address = Address::findOrFail($id);
        if (!$address->set_default) {
            $address->delete();
            return back();
        }
        flash(translate('Default address can not be deleted'))->warning();
        return back();
    }

    public function getStates(Request $request)
    {
        $states = State::where('status', 1)->where('country_id', $request->country_id)->get();
        $html = '<option value="">' . translate("Select State") . '</option>';

        foreach ($states as $state) {
            $html .= '<option value="' . $state->id . '">' . $state->name . '</option>';
        }

        echo json_encode($html);
    }

    public function getCities(Request $request)
    {
        $cities = City::where('status', 1)->where('state_id', $request->state_id)->get();
        $html = '<option value="">' . translate("Select City") . '</option>';

        foreach ($cities as $row) {
            $html .= '<option value="' . $row->id . '">' . $row->getTranslation('name') . '</option>';
        }

        echo json_encode($html);
    }

    public function set_default(Request $request)
    {
        // return  ;
        foreach (Auth::user()->addresses as $key => $address) {
            $address->set_default = 0;
            $address->save();
        }
        $address = Address::findOrFail($request->address_id);
        $address->set_default = 1;
        $address->save();
        return back();
    }
    public function checkStateToJana(Request $request)
    {
        $founded = false;
        $state_name = State::find($request->state_id)->name;
        $jana_cities = DB::table('jana_cities')->where('city_id', $request->state_id)->count();
        if ($jana_cities > 0) {
            $founded = true;
        }
        return response()->json(array('founded' => $founded, 'state_name' => $state_name));
    }
    public function updateAddressPopup(Request $request)
    {
        $address = Address::findOrFail($request->id);
        return view('frontend.checkout_one_Step.updateAddressFrom', compact('address'))->render();
    }
    public function render_address_input(Request $request)
    {
        $selected_address = Address::findOrFail($request->address_id);
        $returnHTML = view('frontend.checkout_one_Step.address_inputs', compact('selected_address'))->render();
        return response()->json(array('state_id' => $selected_address->state_id, 'html' => $returnHTML));
    }
    public function render_address_to_checkout(Request $request)
    {
        $address = Address::findOrFail($request->id);
        $returnHTML = view('frontend.checkout_one_Step.updateCheckoutAddress', compact('address'))->render();
        return response()->json(array('html' => $returnHTML));
    }
    public function updateFromCheckout(Request $request)
    {
        $address = Address::findOrFail($request->address_id);
        $address->name       = $request->name;
        $address->address       = $request->address;
        $address->country_id    = $request->country_id;
        $address->state_id      = $request->state_id;
        $address->city_id       = 0;
        $address->longitude     = 'longitude';
        $address->latitude      = 'latitude';
        $address->postal_code   = "20";
        $address->phone         = $request->phone;

        $address->save();

        $user_addresses = view('frontend.checkout_one_Step.user_addresses')->render();
        return response()->json(array('user_addresses' => $user_addresses));
    }
}
