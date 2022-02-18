@extends('layouts.app')
@section('title','Employe')
@section('content')

	<div class="bd-content col-lg-6">
        <h3 id="example">{{isset($data) ?'Edit' :'Create'}} Employe <a href="{{route('employe')}}">Back</a></h3>
    </div>
	<div class="row col-lg-6 align-items-center">
		<form class="form-control" method="POST" action="@if(isset($data)){{route('employe.update',['id'=>$data->id])}}@else{{route('employe.add')}}@endif" id="create_blog" enctype="multipart/form-data">
			@csrf
			<div class="row col-lg-6">
				<label class="form-label" >Company</label>
				<select class="form-control m-input " name="company_id" id="company_id" >
					<option value="0">Select</option>
					@foreach($company as $v)
						<option value="{{$v->id}}"  {{(isset($data) && $data->company_id == $v->id) ? 'selected': ''}}>{{$v->name}}</option>
					@endforeach
				</select>
			</div>
			<div class="row col-lg-6">
				<label>Firstname</label>
				<input type="text"  class="form-control m-input" name="first_name" placeholder="Enter firstname" id="first_name" value="{{(isset($data)) ? $data->first_name:''}}">
				@if($errors->has('first_name')) 
					{{ $errors->first('first_name') }} 
				@endif
				<!--  -->
			</div>
			<div class="row col-lg-6">
				<label class="form-label" >Lastname</label>
				<input type="text" class="form-control m-input" name="last_name" placeholder="Enter lastname" id="last_name" value="{{(isset($data)) ? $data->last_name:''}}">
				@if($errors->has('last_name')) 
					{{ $errors->first('last_name') }} 
				@endif
				<!--  -->
			</div>
			<div class="row col-lg-6">
				<label class="form-label">Email</label>
				<input type="text" class="form-control m-input" name="email_address" placeholder="Enter email address" id="email_address" value="{{(isset($data)) ? $data->email_address:''}}">
				@if($errors->has('email_address'))
					{{$errors->first('email_address')}}
				@endif
			</div>
			<div class="row col-lg-6">
				<label class="form-label" >Position</label>
				<input type="text" class="form-control m-input" name="position" placeholder="Enter position" id="position" value="{{(isset($data)) ? $data->position:''}}">
				@if($errors->has('position'))
					{{$errors->first('position')}}
				@endif
			</div>
			<div class="row col-lg-6">
				<label class="form-label" >City</label>
				<input type="text" class="form-control m-input" name="city" placeholder="Enter city" id="city" value="{{(isset($data)) ? $data->city:''}}">
				@if($errors->has('city'))
					{{$errors->first('city')}}
				@endif
			</div>
			<div class="row col-lg-6">
				<label class="form-label" >Country</label>
				<input type="text" class="form-control m-input" name="country" placeholder="Enter country" id="country" value="{{(isset($data)) ? $data->country:''}}">
				@if($errors->has('country'))
					{{$errors->first('country')}}
				@endif
			</div>
			<div class="row col-lg-6">
				<label class="form-label" >Status</label>

				<div class="form-check">
					<input type="radio" class="form-check-input" name="status" id="status" value="0" {{(isset($data) && $data->status=='0') ? 'checked':''}}>Active<br>
					<input type="radio" class="form-check-input" name="status" id="status" value="1" {{(isset($data) && $data->status=='1') ? 'checked':''}}>In-Active<br>

					
				</div>
			</div>
			<div class="row col-lg-6">
				<input type="submit" class="form-control col-lg-2">
			</div>
		</form>
	</div>
	

@endsection
@section('js')
<script type="text/javascript">
	$(document).ready(function(){
		$('#create_blog').validate({
			rules:{
				company_id:{
					min:1,
				},
				first_name:{
					required:true,
				},
				last_name:{
					required:true,
				},
				email_address:{
					required:true,
				},	
				position:{
					required:true,
				},	
				city:{
					required:true,
				},	
				country:{
					required:true,
				},	
				status:{
					required:true,
				},	
			},
			messages:{
				company_id:{
					min:'Company is required',
				},
				first_name:{
					required:'Firstname required',
				},
				last_name:{
					required:'Lastname required',
				},
				email_address:{
					required:'Email address required',
				},	
				position:{
					required:'Position required',
				},	
				city:{
					required:'City required',
				},	
				country:{
					required:'Country required',
				},	
				status:{
					required:'Status required',
				},	
			}
		})
	});
</script>
@endsection