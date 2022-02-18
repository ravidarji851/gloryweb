@extends('layouts.app')
@section('title','Company')
@section('content')
<div class="bd-content col-lg-6">
        <h3 id="example">{{isset($data) ?'Edit' :'Create'}} Company <a href="{{route('company')}}">Back</a></h3>
</div>

<div class="row col-lg-6 align-items-center">
	<form class="form-control" method="POST" action="@if(isset($data)){{route('company.update',['id'=>$data->id])}}@else{{route('company.add')}}@endif" id="create_company" enctype="multipart/form-data">
		@csrf
		
		<div class="row col-lg-6">
			<label class="form-label" >Name</label>
			<input  class="form-control m-input" type="text" name="name" placeholder="Enter name" id="name" value="{{(isset($data)) ? $data->name:''}}">
			@if($errors->has('name')) 
				{{ $errors->first('name') }} 
			@endif
			<!--  -->
		</div>
		<div class="row col-lg-6">
			<label class="form-label" >Website</label>
			<input  class="form-control m-input" type="text" name="website" placeholder="Enter website" id="website" value="{{(isset($data)) ? $data->website:''}}">
			@if($errors->has('website')) 
				{{ $errors->first('website') }} 
			@endif
		</div>
		<div class="row col-lg-6">
			<label class="form-label" >Description</label>
			<textarea   class="form-control m-input" name="description" placeholder="Enter description" id="description">
				{{(isset($data)) ? $data->description:''}}
			</textarea>
			@if($errors->has('description')) 
				{{ $errors->first('description') }} 
			@endif
		</div>
	
		<div class="row col-lg-6">
			<label class="form-label" >Type</label>

			<div class="form-check">
				<input type="radio" name="type" class="form-check-input" id="type" value="0" {{(isset($data) && $data->type=='0') ? 'checked':''}}>PUBLIC <br>
				<input type="radio" name="type" class="form-check-input" id="type" value="1" {{(isset($data) && $data->type=='1') ? 'checked':''}}>PRIVATE <br>
				<input type="radio" name="type" class="form-check-input" id="type" value="1" {{(isset($data) && $data->status=='2') ? 'checked':''}}>Registerd <br>
			</div>
		</div>
		<div class="row col-lg-6">
			<input type="submit" class="form-control">
		</div>
	</form>
</div>
	

@endsection
@section('js')
<script type="text/javascript">
	$(document).ready(function(){
		$('#create_company').validate({
			rules:{
				name:{
					required:true,
				},
				type:{
					required:true,
				},
				website:{
					required:true,
				},	
				description:{
					required:true,
				},	
				
			},
			messages:{
				name:{
					required:'Name required',
				},
				type:{
					required:'Type required',
				},
				website:{
					required:'Website required',
				},	
				description:{
					required:'Description required',
				},	
			}
		})
	});
</script>
@endsection