@extends('layouts.adminLayout.admin_design')
@section('content')

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="{{ url('/admin/dashboard') }}" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Dashboard</a> <a href="{{ url('/admin/view-categories') }}" class="current">Categories</a> </div>
    <h1>Categories</h1>
  </div>
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">
    		@if(Session::has('flash_message_error'))
            	<div class="alert alert-danger alert-block">
                	<button type="button" class="close" data-dismiss="alert">×</button> 
                	<strong>{!! session('flash_message_error') !!}</strong>
            	</div>
        	@endif
        	@if(Session::has('flash_message_success'))
        		<div class="alert alert-success alert-block">
            		<button type="button" class="close" data-dismiss="alert">×</button> 
            		<strong>{!! session('flash_message_success') !!}</strong>
        		</div>
        	@endif
  		<a href="{{ url('/admin/add-category') }}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Tambah</a>
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Categories</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th>Category Name</th>
                  <th>Category Level</th>
                  <th>Category Description</th>
                  <th>Category URL</th>
                  <th>Status </th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
              	@foreach($categories as $category)
                <tr class="gradeX">
                  <td>{{ $category->name }}</td>
                  <td>{{ $category->parrent_id }}</td>
                  <td>{{ $category->description }}</td>
                  <td>{{ $category->url }}</td>
                  <td>@if($category->status == 1) <a class="btn-primary btn-mini">Enable</a> @elseif($category->status == 0) <a class="btn-danger btn-mini">Disable</a> @endif</td>
                  <td class="center">
                  	<a href="{{ url('/admin/edit-category/'.$category->id) }}" class="btn btn-primary btn-mini">Edit</a>
                  	<a out="{{ $category->id }}" outs="delete-category" <?php //href="{{ url('/admin/delete-category/'.$category->id) }}"?> href="javascript:" class="btn btn-danger btn-mini deleteRecords" >Delete</a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection