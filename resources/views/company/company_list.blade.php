@extends('layouts.app')
@section('title','Company')
@section('content')


@if(session()->has('error_msg'))
<label style="color:red">{{session()->get('error_msg')}}</label>
@endif
@if(session()->has('success_msg'))
<label style="color:green">{{session()->get('success_msg')}}</label>
@endif
<div>
	<a href="{{route('home')}}">Home</a>
	<a href="{{route('company.create')}}">Add</a>
</div>
	<table id="table" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
			<th>Id</th>
			<th>Title</th>
			<th>Type</th>

			<th>Action</th>

        </tr>
    </thead>
</table>

@endsection
@section('js')
<script type="text/javascript">
	$(document).ready(function(){
		$("#table").DataTable({
			'serverSide':!0,
			'order':['1','asc'],
			ajax:{
				url:"{{route('company.list')}}",
				method:"post",
				dataType:'json',
				data:{token:"{{csrf_token()}}"},
				headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    }
			},
		 	"aaSorting": [],
			columns:[
				{data:'id',name:'id'},
				{data:'name',name:'name'},	
				{data:'type',name:'type'},				

				{data:'id',name:'id',
					render:function(data){
						return '<a href="{{route("company.edit")}}/'+data+'">Edit</a>&nbsp;<a href="javascript:void(0)" data-user-id='+data+' class="company-delete">Delete</a>';
					}

				},

			],
		});
	});
	$(document).on('click',".company-delete",function(){
		var id=$(this).attr("data-user-id");	
		swal({
			title: 'Are you sure want to delete this?',
	        type: "warning",
	        showCancelButton: true,
	        confirmButtonClass: "btn btn-danger m-btn m-btn--pill m-btn--icon m-btn--air",
	        confirmButtonText: 'Yes',
	        cancelButtonClass: 'btn btn-secondary m-btn m-btn--pill m-btn--icon m-btn--air',
	        cancelButtonText: 'No',
		}).then(function(e){
			if(e==true){
				$.ajax({
	                url:"{{route('company.delete')}}",
	                type:'POST',
	                headers:{ 'X-CSRF-Token' : jQuery('meta[name=csrf-token]').attr('content') },
	                dataType:'json',
	                data:{'id':id,_token: '{{csrf_token()}}'},
	                success:function(response){
	                	var msg = response.msg;
	                    if(response.status=='success'){
				            setTimeout(function() {
				                toastr.options = {
				                    closeButton: true,
				                    progressBar: true,
				                    showMethod: 'slideDown',
				                    timeOut: 4000
				                };
				                toastr.success(msg);
				            }, 1300).then($("#table").DataTable().ajax.reload());
	                    }else{
	                    	setTimeout(function() {
				                toastr.options = {
				                    closeButton: true,
				                    progressBar: true,
				                    showMethod: 'slideDown',
				                    timeOut: 4000
				                };
				                toastr.error(msg);
				            }, 1300);
	                    }
	                },
	                error:function(jqXHR,exception){
	                	var msg = response.msg;
	                	setTimeout(function() {
			                toastr.options = {
			                    closeButton: true,
			                    progressBar: true,
			                    showMethod: 'slideDown',
			                    timeOut: 4000
			                };
			                toastr.error(msg);
			            }, 1300);
	                }
	            });
			}
		});
	});
</script>
@endsection