@extends('layouts.admin')

@section('content')
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Product</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit Product</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Product
              </h3>
              <!-- tools box -->
              <div class="card-tools">
                <button type="button" class="btn btn-tool btn-sm" data-card-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                 
                <button type="button" class="btn btn-tool btn-sm" data-card-widget="remove" data-toggle="tooltip"
                        title="Remove">
              </div>
              <!-- /. tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body pad">
              <div class="mb-3">
			  
				<form action="{{route('admin.products.update',$product->id)}}" method="POST">
				@method('PUT')
				<input type="hidden" name="_token" value="{{csrf_token()}}">

					<div class="form-group">
						<label for="exampleInputEmail1">Product Name</label>
						<input type="text" name="name" value="{{$product->name}}" class="form-control" id="exampleInputEmail1" placeholder="Page Title">
					</div>

          <label for="exampleInputEmail1">Product Description</label>
					<textarea name="description" class="textarea" placeholder="Place some text here"
						style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
            {{$product->description}}
					</textarea>
					
					<div class="form-group">
						<label for="exampleInputEmail1">Product Price ($)</label>
						<input type="number" value="{{$product->price}}" name="price" class="form-control"  placeholder="Price in dollar">
					</div>
					
          <div class="form-group">
					  <label for="exampleInputFile">Product Image</label>
					  <input type="file" id="exampleInputFile" name="prof_pic">
					  <p class="help-block">Upload your Product Image.</p>			
						@if ($product->product_image)
							<img src="{{ asset(@$product->product_image) }}" width="200px" height="200px">
						@endif
					</div>
					
          
          <div class="card card-outline card-info">
              <div class="card-header">
                <h3 class="card-title">Add Product Attribute</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                   <!-- Repeater Html Start -->
                  <div id="repeater">
                    <!-- Repeater Heading -->
                    <div class="repeater-heading">                       
                        <button class="btn btn-primary repeater-add-btn" type="button" style="float:right">
                            Add
                        </button>
                    </div>
                    <div class="clearfix"></div>
                    <!-- Repeater Items -->
                    
                    <div class="items" >
                        <!-- Repeater Content -->
                        <div class="item-content">

                            <div class="form-group" data-group="test">
                                <label for="exampleInputEmail1">Attribute Type</label>
                                <select name="attr_type[]" class="form-control select2" class="col-lg-4" data-skip-name="true">
                                        <option value="size" selected >Size</option>
                                    </select>
                            </div>

                            <div class="form-group">
                                <label for="inputEmail" class="col-lg-2 control-label">Attribute Name</label>                        
                                <input type="text" value="" class="form-control" name="attr_name[]" id="inputName" placeholder="Name" data-skip-name="true">             
                            </div>
                            
                            <div class="form-group">
                                <label for="inputEmail" class="col-lg-2 control-label">Attribute Value</label>
                                <input type="text" class="form-control" name="attr_value[]" id="inputEmail" placeholder="Value" data-skip-name="true" >                              
                            </div>

                        </div>
                        <!-- Repeater Remove Btn -->
                        <div class="pull-right repeater-remove-btn">
                            <button class="btn btn-danger remove-btn" type="button">
                                Remove
                            </button>
                        </div>

                        <div class="clearfix"></div>
                    </div>

                    @foreach($product_attributes as $attribute)
                    <div class="items" >
                        <!-- Repeater Content -->
                        <div class="item-content">

                            <div class="form-group" data-group="test">
                                <label for="exampleInputEmail1">Attribute Type</label>
                                <select name="attr_type[]" class="form-control select2" class="col-lg-4" data-skip-name="true">
                                        <option value="size" selected >Size</option>
                                    </select>
                            </div>

                            <div class="form-group">
                                <label for="inputEmail" class="col-lg-2 control-label">Attribute Name</label>                        
                                <input type="text" class="form-control" value="{{$attribute->attr_name}}" name="attr_name[]" id="inputName" placeholder="Name" data-skip-name="true">             
                            </div>
                            
                            <div class="form-group">
                                <label for="inputEmail" class="col-lg-2 control-label">Attribute Value</label>
                                <input type="text" class="form-control" value="{{$attribute->attr_value}}" name="attr_value[]" id="inputEmail" placeholder="Value" data-skip-name="true" >                              
                            </div>

                        </div>
                        <!-- Repeater Remove Btn -->
                        <div class="pull-right repeater-remove-btn">
                            <button class="btn btn-danger remove-btn" type="button">
                                Remove
                            </button>
                        </div>

                        <div class="clearfix"></div>
                    </div>
                    @endforeach

                </div>
                <!-- Repeater End -->
              </div>
              <!-- /.card-body -->
            </div>




					<div class="row">
						<div class="col-12">
						  <input type="submit" value="Update" class="btn btn-success float-right">
						</div>
					 </div>
				</form> 
				
              </div>
            </div>
          </div>
        </div>
        <!-- /.col-->
      </div>
      <!-- ./row -->
    </section>
    <script src="{{ asset('dist/js/repeater.js') }}"></script>
	<script>
$("#repeater").createRepeater({
            showFirstItemToDefault: false,
        });
	</script>
    <!-- /.content -->
@endsection