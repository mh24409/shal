@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Send Newsletter')}}</h5>
            </div>


            
            <div class="card-body">
                <div class="row  justify-content-end py-3">
                     <div class="col-md-6" >
                        <form class="" action="" method="GET"> 
                            <div class="row justify-content-end"> 
                                <div class="col-md-4"> 
                                        <div class=" ">
                                            <button type="submit" class="btn btn-primary">{{ translate('Filter Users') }}</button>
                                        </div>
                                </div>  
                                <div class="col-md-6"> 
                                        <div class="w-100">
                                            <select name="users_type" class="form-control aiz-selectpicker w-100"  > 
                                             <option  > {{translate('Select Users Filter')}}</option> 
                                                <option {{$sort_value == 'returned_customer' ? 'selected' : '' }} value="returned_customer"> {{translate('returnd customer')}} </option> 
                                                 <option {{$sort_value == 'new_customer' ? 'selected' : '' }}  value="new_customer">{{translate('new customers')}} </option> 
                                                  <option {{$sort_value == 'abonded_customer_cart' ? 'selected' : '' }}  value="abonded_customer_cart">{{translate(' Abonded carts')}}</option> 
                                        </select> 
                                    </div>
                    
                                </div>  
                               </div> 
                       </form>
                     </div>
                </div>
                <form class="form-horizontal" action="{{ route('newsletters.send') }}" method="POST" enctype="multipart/form-data">
                	@csrf
                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="name">{{translate('Emails')}} ({{translate('Users')}})</label>
                        <div class="col-sm-10">
                            <select class="form-control aiz-selectpicker" name="user_emails[]" multiple data-selected-text-format="count" data-actions-box="true">
                                @foreach($users as $user)
                                    <option value="{{$user->email}}">{{$user->email}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="name">{{translate('Emails')}} ({{translate('Subscribers')}})</label>
                        <div class="col-sm-10">
                            <select class="form-control aiz-selectpicker" name="subscriber_emails[]" multiple data-selected-text-format="count" data-actions-box="true">
                                @foreach($subscribers as $subscriber)
                                    <option value="{{$subscriber->email}}">{{$subscriber->email}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="subject">{{translate('Newsletter subject')}}</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="subject" id="subject" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="name">{{translate('Newsletter content')}}</label>
                        <div class="col-sm-10">
                            <textarea rows="8" class="form-control aiz-text-editor" data-buttons='[["font", ["bold", "underline", "italic"]],["para", ["ul", "ol"]], ["insert", ["link", "picture"]],["view", ["undo","redo"]]]' name="content" required></textarea>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{translate('Send')}}</button>
                    </div>
                  </form>
              </div>
        </div>
    </div>
</div>

@endsection
