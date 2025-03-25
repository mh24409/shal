@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('City Information')}}</h5>
</div>

<div class="row">
  <div class="col-lg-8 mx-auto">
      <div class="card">
          <div class="card-body p-0">
              <ul class="nav nav-tabs nav-fill border-light">
    				@foreach (\App\Models\Language::all() as $key => $language)
    					<li class="nav-item">
    						<a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3" href="{{ route('cities.edit', ['id'=>$city->id, 'lang'=> $language->code] ) }}">
    							<img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
    							<span>{{ $language->name }}</span>
    						</a>
    					</li>
  	            @endforeach
    			</ul>
              <form class="p-4" action="{{ route('cities.update', $city->id) }}" method="POST" enctype="multipart/form-data">
                  <input name="_method" type="hidden" value="PATCH">
                  <input type="hidden" name="lang" value="{{ $lang }}">
                  @csrf
                  <div class="form-group mb-3">
                      <label for="name">{{translate('Name')}}</label>
                      <input type="text" placeholder="{{translate('Name')}}" value="{{ $city->getTranslation('name', $lang) }}" name="name" class="form-control" required>
                  </div>

                  <div class="form-group">
                    <label for="country">{{translate('State')}}</label>
                    <select class="select2 form-control aiz-selectpicker " id='state_id' name="state_id" data-toggle="select2" data-placeholder="Choose ..." data-live-search="true">
                        @foreach ($states as $state)
                                <option value="{{ $state->id }}" shippingId ="{{ $state->shipping_id }}" {{$state->id == $city->state_id ? 'selected' : null}}>{{ $state->name }}</option>
                        @endforeach
                    </select>
                </div>


                <div class="form-group">
                    <label for="country">{{translate('Shipping City')}}</label>
                    <select class="select2 form-control aiz-selectpicker" id='shipping_city' name="shipping_city"  data-toggle="select2" data-placeholder="Choose ..." data-live-search="true">
                        <option selected>{{ translate('State Citites') }}</option>
                    </select>
                </div>

                  <div class="form-group mb-3">
                      <label for="name">{{translate('Cost')}}</label>
                      <input type="number" min="0" step="0.01" placeholder="{{translate('Cost')}}" name="cost" class="form-control" value="{{ $city->cost }}" required>
                  </div>


                  <div class="form-group mb-3 text-right">
                      <button type="submit" class="btn btn-primary">{{translate('Update')}}</button>
                  </div>
              </form>
          </div>
      </div>
  </div>
</div>

@endsection

@section('script')

    <script type="text/javascript">

var states_cities = @json($states_cities);


function getStateCities(selectedValue) {
    var state_cities_select = $('#shipping_city');
    state_cities_select.empty();

    if (selectedValue) {
        // Find the cities that belong to the selected state
        var selectedStateCities = states_cities.filter(function(city) {
            return city.state_id == selectedValue;
        });



        if (selectedStateCities && selectedStateCities.length > 0) {
            // Use map and forEach to add cities to the 'shipping_city' select element
            selectedStateCities.map(function(city) {
                var option = document.createElement('option');
                option.value = `${city.name}_${city.id}`;
                option.text = city.name;
                if(city.id == '{{$city->shipping_id}}'){
                    option.selected = true;
                }
                state_cities_select.append(option);
            });
        }
    }
    $(state_cities_select).selectpicker('refresh');
    state_cities_select.trigger('change');
}

    getStateCities('{{$city->state->shipping_id}}');

    document.getElementById('state_id').addEventListener('change', function() {
        var stateSelect = document.getElementById('state_id');
        var selectedOption = stateSelect.options[stateSelect.selectedIndex];
        var selectedValue = selectedOption.getAttribute('shippingId');
        getStateCities(selectedValue);
    });


    </script>

@endsection
