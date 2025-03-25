@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center">
		<h1 class="h3">{{translate('All Question and Answers')}}</h1>
	</div>
</div>

<div class="row">
	<div class="@if(auth()->user()->can('add_brand')) col-lg-7 @else col-lg-12 @endif">
		<div class="card">
		    <div class="card-header row gutters-5">
				<div class="col text-center text-md-left">
					<h5 class="mb-md-0 h6">{{ translate('Question and Answers') }}</h5>
				</div>
				<div class="col-md-4">
					<form class="" id="sort_questions_answers" action="" method="GET">
						<div class="input-group input-group-sm">
					  		<input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name & Enter') }}">
						</div>
					</form>
				</div>
		    </div>
		    <div class="card-body">
		        <table class="table aiz-table mb-0">
		            <thead>
		                <tr>
		                    <th>#</th>
		                    <th>{{translate('question')}}</th>
		                    <th>{{translate('answer')}}</th> 
		                    <th class="text-right">{{translate('Options')}}</th>
		                </tr>
		            </thead>
		            <tbody>
		                @foreach($questions_answers as $key => $question_answer)
		                    <tr>
		                        <td>{{ ($key+1) + ($questions_answers->currentPage() - 1)*$questions_answers->perPage() }}</td>
		                        <td>{{ $question_answer->getTranslation('question') }}</td> 
		                        <td>{{ $question_answer->getTranslation('answer') }}</td> 
		                        <td class="text-right"> 
										<a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('questions_answers.edit', ['id'=>$question_answer->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">
											<i class="las la-edit"></i>
										</a> 
										<a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('questions_answers.destroy', $question_answer->id)}}" title="{{ translate('Delete') }}">
											<i class="las la-trash"></i>
										</a> 
		                        </td>
		                    </tr>
		                @endforeach
		            </tbody>
		        </table>
		        <div class="aiz-pagination">
                	{{ $questions_answers->appends(request()->input())->links() }}
            	</div>
		    </div>
		</div>
	</div>
	@can('add_brand')
		<div class="col-md-5">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0 h6">{{ translate('Add New Question and Answers') }}</h5>
				</div>
				<div class="card-body">
					<form action="{{ route('questions_answers.store') }}" method="POST">
						@csrf
						<div class="form-group mb-3">
							<label for="name">{{translate('question')}}</label>
							<input type="text" placeholder="{{translate('question')}}" name="question" class="form-control" required>
						</div> 
						<div class="form-group mb-3">
							<label for="name">{{translate('answer')}}</label>
							<input type="text" placeholder="{{translate('answer')}}" name="answer" class="form-control" required>
						</div>
						<div class="form-group mb-3 text-right">
							<button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	@endcan
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
<script type="text/javascript">
    function sort_questions_answers(el){
        $('#sort_questions_answers').submit();
    }
</script>
@endsection
