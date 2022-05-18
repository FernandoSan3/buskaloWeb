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

         <div class="tab-pane active" id="servicios">
            <div class="side-heading">
              <div class="row">
                <div class="col-md-8">
                  <div class="head-side">
                    <h3>Servicios Ofrecidos</h3>
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

            <div class="servicios-sec">
              <div class="row">
                <div class="col-md-4">
                  <div class="servicios-collapse">
                    <div id="accordion" class="accordion">
                      <div class="card mb-0">
                          <div class="card-header collapsed" data-toggle="collapse" href="#collapseOne">
                              <a class="card-title">Guayaquil </a>
                          </div>
                          <div id="collapseOne" class="card-body collapse" data-parent="#accordion">
                            <table>
                              <tr>
                                <td>Tarqui</td>
                                <td><i class="fa fa-plus-circle"></i></td>
                              </tr>
                              <tr>
                                <td>Samborondon</td>
                                <td><i class="fa fa-plus-circle"></i></td>
                              </tr>
                              <tr>
                                <td>Aurora</td>
                                <td><i class="fa fa-plus-circle"></i></td>
                              </tr>
                              <tr>
                                <td>Pascuales</td>
                                <td><i class="fa fa-plus-circle"></i></td>
                              </tr>
                            </table>
                          </div>
                      </div>
                  </div>
                  </div>
                </div>



                <div class="col-md-8">
                  <div class="serv-tabs">
                    <div class="tab-content">
                      <div class="tab-pane active" id="service1">
                        <table id="example3" class="display nowrap contact-table" style="width:100%">
                          <thead>
                              <tr>
                                  <th>S no.</th>
                                  <th>Services</th>
                                  <th>Actions</th>
                              </tr>
                          </thead>
                          <tbody>

                            @if($data)
                            @php $i=1; @endphp
                             @foreach($data as $item)
                              <tr>
                                  <td>{{$i}}</td>
                                  <td>{{$item['service_name']}}</td>
                                  <td>
                                  <button class="delete-modalOfferedService btn btn-danger" data-info="{{$item['id']}}}}">
                                  <span class="glyphicon glyphicon-trash"></span> Delete
                                  </button>
                                  </td>
                              <!-- <td><div class="edit-btns"><i class="fa fa-pencil-square-o"></i>
                                    <i class="fa fa-trash"></i></div></td> -->
                              </tr>

                              @php $i++; @endphp
                            @endforeach
                           @endif
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
      </div>
    </div>
  </div>
  </div>
</div>

@endsection
