@extends('frontend.layouts.app')

@section('content')
   
<div class="header-profile">


<div id="wrapper" class="toggled left-sidebar">
  <!-- Sidebar -->
  @include('frontend.contractor.profile_sidebar')
  <!-- /#sidebar-wrapper -->

 <!-- Page Content -->
  <div id="page-content-wrapper">
    <div class="container-fluid">
      <div class="right-sidebar ">
        <!-- Tab panes -->
        <div class="tab-content">


         <div class="tab-pane active" id="contactos">
            <div class="side-heading">
                <div class="row">
                  <div class="col-md-8">
                    <div class="head-side">
                      <h3>Contactos</h3>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="search-side">
                      <input type="text" name="" placeholder="Search">
                      <i class="fa fa-search"></i>
                    </div>
                  </div>
                </div>
              </div>
            <div class="contract-contact">
             
                {{ html()->form('POST', route('frontend.contractor.workers.create'))->class("contact-info-fill")->attribute('enctype', 'multipart/form-data')->open() }}
                <div class="form-row">
                  <div class="form-group col">
                    <input type="file" class="form-control" id="inputAddress" name="profile_pic" placeholder="Profile Pic">
                  </div>

                  <div class="form-group col">
                    <input type="text" class="form-control" name="username" id="inputAddress" placeholder="username">
                  </div>

                   <div class="form-group col">
                    <input type="text" class="form-control" name="email" id="inputAddress" placeholder="email-id">
                  </div>

                  <div class="form-group col">
                    <input type="text" class="form-control" name="mobile_number" id="inputAddress" placeholder="Mobile_number">
                  </div>

                   <div class="form-group col">
                    <input type="text" class="form-control" name="address" id="inputAddress" placeholder="address">
                  </div>
                 <!--  <div class="form-group col">
                    <select id="inputState" class="form-control">
                      <option selected>Tipo Identificacion</option>
                      <option>Tipo Identificacion</option>
                    </select>
                  </div> -->
                 <!--  <div class="form-group col">
                    <input type="text" class="form-control" id="inputAddress" placeholder="Identificacion">
                  </div> -->
                  
                 
                  <div class="form-group col">
                    <button type="submit" class="btn select-btn">Add new</button>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="add-more">
                    <i class="fa fa-plus-circle"></i>
                  </div>
                </div>

             {{ html()->form()->close() }}

              <table id="example" class="display nowrap contact-table" style="width:100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Profile</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>mobile Number</th>
                        <th>Address</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i=1;
                foreach ($workers as $key => $worker) 
                { ?>

                       <tr>
                       <td>{{$i}}</td>
                        <?php $pic= 'img/frontend/user.png';
                       if(isset($worker) && !empty($worker->profile_pic)){$pic= 'img/worker/profile/'.$worker->profile_pic;}?>
                        <td><img src="{{ url($pic) }}"></td>
                        <td>{{$worker->username}}</td>
                        <td>{{$worker->email}}</td>
                        <td>{{$worker->mobile_number}} <i class="fa fa-phone"></i></td>
                        <td>{{$worker->address}}</td>
                        <td><div class="edit-btns"><i class="fa fa-pencil-square-o"></i>
                          <i class="fa fa-trash"></i></div>
                        </td>
                    </tr>

                <?php $i++;} ?> 
                   
                </tbody>
                
              </table>
            </div>
          </div>

          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</div>

@endsection
