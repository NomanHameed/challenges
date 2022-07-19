@extends('layouts.admin')

@section('content')
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard v1</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">


        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{$total_challenges}}</h3>

                <p>Total Challanges</p>
              </div>
              <div class="icon">
                <i class="fas fa-running"></i>
              </div>
              <a href="{{route('admin.challenges.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{$user_count}}</h3>

                <p>Total Users</p>
              </div>
              <div class="icon">
                <i class="fa fa-users"></i>
              </div>
              <a href="{{route('admin.users.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <!-- <div class="col-lg-3 col-6">
            
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{$total_payments}} $</h3>

                <p>Total Payments</p>
              </div>
              <div class="icon">
                <i class="fas fa-money-check-alt"></i>
              </div>
              <a href="{{route('admin.payments.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div> -->
          <!-- ./col -->
          <!-- <div class="col-lg-4 col-6">
            
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{$total_participations}}</h3>

                <p>Total Active Participations</p>
              </div>
              <div class="icon">
			          <i class="fas fa-handshake"></i>
                
              </div>
              <a href="{{route('admin.participations.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div> -->

          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-warning" style="background-color: #b2ff07 !important;">
              <div class="inner">
                <h3>{{$total_badges}}</h3>

                <p>Total Badges</p>
              </div>
              <div class="icon">
                <i class="fas fa-medal"></i>
              </div>
              <a href="{{route('admin.badges.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <!-- ./col -->
        </div>
        <!-- /.row -->





        <!-- Small boxes (Stat box) -->
        <div class="row">
          <!-- <div class="col-lg-3 col-6">
            
            <div class="small-box bg-info" style="background-color: #2c00ca82 !important;">
              <div class="inner">
                <h3>{{$total_products}}</h3>

                <p>Total Products</p>
              </div>
              <div class="icon">
                <i class="fab fa-product-hunt"></i>
              </div>
              <a href="{{route('admin.products.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div> -->
          <!-- ./col -->
          
          <!-- ./col -->
          <!-- <div class="col-lg-3 col-6">
            
            <div class="small-box bg-danger" style="background-color: #dc359b !important;">
              <div class="inner">
                <h3>{{$total_orders}}</h3>

                <p>Total Orders</p>
              </div>
              <div class="icon">
                <i class="fas fa-shopping-cart"></i>
              </div>
              <a href="{{route('admin.orders.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div> -->
          <!-- ./col -->
          <div class="col-lg-4 col-6">
            <!-- small box -->
            <div class="small-box bg-success" style="background-color: #6c532cd6 !important;">
              <div class="inner">
                <h3>&nbsp</h3>

                <p>Settings</p>
              </div>
              <div class="icon">
			          <i class="fas fa-th"></i>
                
              </div>
              <a href="{{route('settings')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->


        
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection