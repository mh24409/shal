@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h1 class="mb-0 h6">{{translate('General Settings')}}</h1>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('business_settings.update') }}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{translate('System Name')}}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="site_name">
                                <input type="text" name="site_name" class="form-control" value="{{ get_setting('site_name') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{translate('site location')}}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="site_location">
                                <input type="text" name="site_location" class="form-control" value="{{ get_setting('site_location') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{translate('whatsapp number')}}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="whatsapp_number">
                                <input type="text" name="whatsapp_number" class="form-control" value="{{ get_setting('whatsapp_number') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{translate('System Logo - White')}}</label>
                            <div class="col-sm-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose Files') }}</div>
                                    <input type="hidden" name="types[]" value="system_logo_white">
                                    <input type="hidden" name="system_logo_white" value="{{ get_setting('system_logo_white') }}" class="selected-files">
                                </div>
                                <div class="file-preview box sm"></div>
                                <small>{{ translate('Will be used in admin panel side menu') }}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{translate('System Logo - Black')}}</label>
                            <div class="col-sm-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose Files') }}</div>
                                    <input type="hidden" name="types[]" value="system_logo_black">
                                    <input type="hidden" name="system_logo_black" value="{{ get_setting('system_logo_black') }}" class="selected-files">
                                </div>
                                <div class="file-preview box sm"></div>
                                <small>{{ translate('Will be used in admin panel topbar in mobile + Admin login page') }}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{translate('System Timezone')}}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="timezone">
                                <select name="timezone" class="form-control aiz-selectpicker" data-live-search="true">
                                    @foreach (timezones() as $key => $value)
                                        <option value="{{ $value }}" @if (app_timezone() == $value)
                                            selected
                                        @endif>{{ $key }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{translate('Admin login page background')}}</label>
                            <div class="col-sm-9">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose Files') }}</div>
                                    <input type="hidden" name="types[]" value="admin_login_background">
                                    <input type="hidden" name="admin_login_background" value="{{ get_setting('admin_login_background') }}" class="selected-files">
                                </div>
                                <div class="file-preview box sm"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{translate('Random Numbers')}}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="first_random_number">
                                <input type="hidden" name="types[]" value="second_random_number">
                                <div class="d-flex justify-content-start">
                                    <input type="number" name="first_random_number" placeholder="{{translate('First number')}}" class="form-control col-6 mx-1" value="{{ get_setting('first_random_number') }}">
                                    <input type="nubmer" name="second_random_number" placeholder="{{translate('Second number')}}" class="form-control col-6 mx-1" value="{{ get_setting('second_random_number') }}">
                                </div>
                                <span class="text-sm text-secondary">{{translate('This numbers is used to randomize today\'s deal sold count')}}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{translate('Tags')}}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="tags">
                                <input type="text" class="form-control aiz-tag-input" name="tags" id="tags" value="{{ get_setting('tags') }}" placeholder="{{ translate('Type to add a tag') }}" data-role="tagsinput">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{translate('Designes')}}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="Designes">
                                <input type="text" class="form-control aiz-tag-input" name="Designes" id="Designes" value="{{ get_setting('Designes') }}" placeholder="{{ translate('Type to add a design') }}" data-role="Designesinput">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{translate('Events')}}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="Events">
                                <input type="text" class="form-control aiz-tag-input" name="Events" id="Events" value="{{ get_setting('Events') }}" placeholder="{{ translate('Type to add a event') }}" data-role="Eventsinput">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{translate('fabric_type')}}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="fabric_type">
                                <input type="text" class="form-control aiz-tag-input" name="fabric_type" id="fabric_type" value="{{ get_setting('fabric_type') }}" placeholder="{{ translate('Type to add a fabric type') }}" data-role="fabric_typeinput">
                            </div>
                         </div>
                         <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{translate('close_type')}}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="close_type">
                                <input type="text" class="form-control aiz-tag-input" name="close_type" id="close_type" value="{{ get_setting('close_type') }}" placeholder="{{ translate('Type to add a close type') }}" data-role="close_typeinput">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{translate('hand_type')}}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="hand_type">
                                <input type="text" class="form-control aiz-tag-input" name="hand_type" id="hand_type" value="{{ get_setting('hand_type') }}" placeholder="{{ translate('Type to add a hand type') }}" data-role="hand_typeinput">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{translate('seasons')}}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="seasons">
                                <input type="text" class="form-control aiz-tag-input" name="seasons" id="seasons" value="{{ get_setting('seasons') }}" placeholder="{{ translate('Type to add a season') }}" data-role="hand_typeinput">
                            </div>
                        </div> 
                        <div class="form-group row">
                        <label class="col-sm-3 col-from-label">{{translate('Flash Deal Timer')}}</label>
                        <div class="col-sm-9">
                            <input type="text" name="count_to" class="form-control" value="{{ get_setting('counter_end') }}">
                        </div>
                       </div>
                        <div class="text-right">
    						<button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
    					</div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
