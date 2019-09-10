@extends('layouts.adminLayout.admin_design')
@section('content')

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="{{ url('/admin/dashboard') }}" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Dashboard</a> <a href="{{ url('/admin/view-products') }}" class="current">Products</a> </div>
    <h1>Products</h1>
  </div>
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">
        	@if(Session::has('flash_message_success'))
        		<div class="alert alert-success alert-block">
            		<button type="button" class="close" data-dismiss="alert">×</button> 
            		<strong>{!! session('flash_message_success') !!}</strong>
        		</div>
        	@endif
  		<a href="{{ url('/admin/add-product') }}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Tambah</a>
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Products</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th>Code</th>
                  <th>Name</th>
                  <th>Color</th>
                  <th>Category</th>
                  <th>Description</th>
                  <th>Material & Care</th>
                  <th>Price</th>
                  <th>Image</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
              	@foreach($products as $product)
                <tr class="gradeX">
                  <td>{{ $product->product_code }}</td>
                  <td>{{ $product->product_name }}</td>
                  <td>{{ $product->product_color }}</td>
                  <td>{{ $product->category_name }}</td>
                  <td>{{ $product->description }}</td>
                  <td>{{ $product->care }}</td>
                  <td>{{ $product->price }}</td>
                  <td>
                    @if(!empty($product->image))
                    <img href="#myModal{{ $product->id }}" data-toggle="modal" src="{{ asset('/images/backend_images/products/small/'.$product->image)}}" style="width: 50px">
                    @endif
                  </td>
                  <td class="center">
                  	<a href="{{ url('/admin/edit-product/'.$product->id) }}" class="btn btn-warning btn-mini">Edit</a>
                    <a href="{{ url('/admin/add-attributes/'.$product->id) }}" class="btn btn-primary btn-mini">Attribute</a>
                    <a href="{{ url('/admin/add-images/'.$product->id) }}" class="btn btn-info btn-mini">Foto</a>
                  	<a rel="{{ $product->id }}" rel1="delete-product" <?php //href="{{ url('/admin/delete-product/'.$product->id) }}" ?> href="javascript:" class="btn btn-danger btn-mini deleteRecord" >Delete</a>
                  </td>
                </tr>  
                    <div id="myModal{{ $product->id }}" class="modal hide">
                      <div class="modal-header">
                        <button data-dismiss="modal" class="close" type="button">×</button>
                        <h3>{{ $product->product_name }} Full Details</h3>
                      </div>
                      <div class="modal-body">
                        <p>
                          @if(!empty($product->image))
                          <img src="{{ asset('/images/backend_images/products/small/'.$product->image)}}">
                          @endif
                        </p>
                        <p>Kode Produk : {{ $product->product_code }}</p>
                        <p>Nama Produk : {{ $product->product_name }}</p>
                        <p>Farian Warna: {{ $product->product_color }}</p>
                        <p>Kategori Produk : {{ $product->category_name }}</p>
                        <p>Harga : {{ $product->price }}</p>
                      </div>
                    </div>
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