<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;
use App\Traits\ApiTrait;
use App\Models\ShippingCity;
use Illuminate\Http\Request;
use App\Models\CityTranslation;
use App\Models\Shipping_Governments;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{

    use ApiTrait;

    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:manage_shipping_cities'])->only('index','create','destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_city = $request->sort_city;
        $sort_state = $request->sort_state;
        $cities_queries = City::query();
        if($request->sort_city) {
            $cities_queries->where('name', 'like', "%$sort_city%");
        }
        if($request->sort_state) {
            $cities_queries->where('state_id', $request->sort_state);
        }
        $cities = $cities_queries->orderBy('status', 'desc')->paginate(15);
        $states = State::where('status', 1)->get();
        $states_cities = ShippingCity::get();

        return view('backend.setup_configurations.cities.index', compact('cities', 'states', 'sort_city', 'sort_state','states_cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $shipping_city = explode('_',$request->shipping_city);

        if(!count($shipping_city) == 2 || !isset($shipping_city[0])  || !isset($shipping_city[1])){
            flash(translate('Could Not Insert The State'))->error();
            return back();
        }


        $validator = Validator::make([
            'shipping_name'=>$shipping_city[0],
            'shipping_id'=>$shipping_city[1]
        ],[
            'shipping_name'=>'required|exists:shipping_cities,name',
            'shipping_id'=>'required|exists:shipping_cities,id'
        ]);

        if($validator->fails()){
            flash( translate('Wrong City Values') )->error();
            return back();
        }

        $city = new City;

        $city->name = $request->name;
        $city->cost = $request->cost;
        $city->state_id = $request->state_id;
        $city->shipping_name = $shipping_city[0];
        $city->shipping_id = $shipping_city[1];

        $city->save();

        flash(translate('City has been inserted successfully'))->success();

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function edit(Request $request, $id)
     {
         $lang  = $request->lang;
         $city  = City::findOrFail($id);
         $states = State::where('status', 1)->get();
         $states_cities = ShippingCity::get();
         return view('backend.setup_configurations.cities.edit', compact('city', 'lang', 'states','states_cities'));
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

        $shipping_city = explode('_',$request->shipping_city);

        if(!count($shipping_city) == 2 || !isset($shipping_city[0])  || !isset($shipping_city[1])){
            flash(translate('Could Not Insert The State'))->error();
            return back();
        }


        $validator = Validator::make([
            'shipping_name'=>$shipping_city[0],
            'shipping_id'=>$shipping_city[1]
        ],[
            'shipping_name'=>'required|exists:shipping_cities,name',
            'shipping_id'=>'required|exists:shipping_cities,id'
        ]);

        if($validator->fails()){
            flash( translate('Wrong City Values') )->error();
            return back();
        }

        $city = City::findOrFail($id);
        if($request->lang == env("DEFAULT_LANGUAGE")){
            $city->name = $request->name;
        }

        $city->shipping_name = $shipping_city[0];
        $city->shipping_id = $shipping_city[1];
        $city->state_id = $request->state_id;
        $city->cost = $request->cost;

        $city->save();

        $city_translation = CityTranslation::firstOrNew(['lang' => $request->lang, 'city_id' => $city->id]);
        $city_translation->name = $request->name;
        $city_translation->save();

        flash(translate('City has been updated successfully'))->success();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $city = City::findOrFail($id);
        $city->city_translations()->delete();
        City::destroy($id);

        flash(translate('City has been deleted successfully'))->success();
        return redirect()->route('cities.index');
    }

    public function updateStatus(Request $request){
        $city = City::findOrFail($request->id);
        $city->status = $request->status;
        $city->save();

        return 1;
    }

    public function getStateCities(Request $request,$id){
        $cities = ShippingCity::where('state_id',$id)->get();
        return $cities;
    }


    public function store_shipping_cities_from_company(Request $request){


        try{
            ini_set('max_execution_time', 300);
            $states = Shipping_governments::get();

            foreach($states as $state){
                              // API endpoint URL
        $apiUrl = 'https://backoffice.turbo-eg.com/external-api/get-area/' . $state->state_id;

        // Initialize cURL session
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Content-Type: application/json',
        ));
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        // Execute the cURL session
        $response = curl_exec($ch);

        $response = json_decode($response,true);


        if($response['success'] != 1){

            return $this->apiResponse('failed',null,'Could Not Load State Cities',400);

        }

        foreach($response['feed'] as $data){

            $city = new ShippingCity();

            $city->name = $data['name'];
            $city->city_shipping_id = $data['id'];
            $city->state_id = $state->id;
            $city->save();

        }
            }

        }catch(Exception $e){
            return $this->apiResponse('failed',null,$e->getMessage(),400);
        }

    }
}
