 @extends('frontend.layouts.app')

@section('content')

<div class="header-profile">


<div id="wrapper" class="toggled left-sidebar">
  <!-- Sidebar -->
  @include('frontend.company.profile_sidebar')
  <!-- /#sidebar-wrapper -->

 <!-- Page Content -->
  <div id="page-content-wrapper">
    <div class="container-fluid">
      <div class="right-sidebar ">
        <!-- Tab panes -->
        <div class="tab-content">

        <div class="tab-pane active" id="mensajes">
            <div class="side-heading">
                <div class="row">
                  <div class="col-md-8">
                    <div class="head-side">
                      <h3>Mensajes</h3>
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
            <div class="mensajes-sec">
              <div class="row">
                <div class="col-md-6">
                  <div class="media ">
                    <p>15h30</p>
                    <div class="media-body msg-list p-3">
                      <a href="Mensajes.html">
                        <h6 class="orange">Nuevo</h6>
                        <h4>Maria Paz Espinoza</h4>
                        <span>jueves, 28 de Abril</span>
                        <div class="msg-icon">
                        <img src="{{ url('img/frontend/star.png') }}">
                        </div>
                      </a>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="media ">
                    <p>15h30</p>
                    <div class="media-body msg-list p-3">
                      <a href="Mensajes.html">
                        <h6 class="orange">Nuevo</h6>
                        <h4>Maria Paz Espinoza</h4>
                        <span>jueves, 28 de Abril</span>
                        <div class="msg-icon">
                        <img src="{{ url('img/frontend/star.png') }}">
                        </div>
                      </a>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="media ">
                    <p>15h30</p>
                    <div class="media-body msg-list rd-msg p-3">
                      <a href="Mensajes.html">
                        <h6 class="orange">Nuevo</h6>
                        <h4>Maria Paz Espinoza</h4>
                        <span>jueves, 28 de Abril</span>
                        <div class="msg-icon">
                          <img src="{{ url('img/frontend/star1.png') }}">
                        </div>
                      </a>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="media ">
                    <p>15h30</p>
                    <div class="media-body msg-list rd-msg p-3">
                      <a href="Mensajes.html">
                      <h6 class="orange">Nuevo</h6>
                      <h4>Maria Paz Espinoza</h4>
                      <span>jueves, 28 de Abril</span>
                      <div class="msg-icon">
                        <img src="{{ url('img/frontend/star1.png') }}">
                      </div>
                    </a>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="media ">
                    <p>15h30</p>
                    <div class="media-body msg-list rd-msg p-3">
                      <a href="Mensajes.html">
                      <h6 class="orange">Nuevo</h6>
                      <h4>Maria Paz Espinoza</h4>
                      <span>jueves, 28 de Abril</span>
                      <div class="msg-icon">
                        <img src="{{ url('img/frontend/star1.png') }}">
                      </div>
                    </a>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="media ">
                    <p>15h30</p>
                    <div class="media-body msg-list rd-msg p-3">
                      <a href="Mensajes.html">
                        <h6 class="orange">Nuevo</h6>
                        <h4>Maria Paz Espinoza</h4>
                        <span>jueves, 28 de Abril</span>
                        <div class="msg-icon">
                          <img src="{{ url('img/frontend/star1.png') }}">
                        </div>
                      </a>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="media ">
                    <p>15h30</p>
                    <div class="media-body msg-list rd-msg p-3">
                      <a href="Mensajes.html">
                        <h6 class="orange">Nuevo</h6>
                        <h4>Maria Paz Espinoza</h4>
                        <span>jueves, 28 de Abril</span>
                        <div class="msg-icon">
                          <img src="{{ url('img/frontend/star1.png') }}">
                        </div>
                      </a>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="media ">
                    <p>15h30</p>
                    <div class="media-body msg-list rd-msg p-3">
                      <a href="Mensajes.html">
                        <h6 class="orange">Nuevo</h6>
                        <h4>Maria Paz Espinoza</h4>
                        <span>jueves, 28 de Abril</span>
                        <div class="msg-icon">
                          <img src="{{ url('img/frontend/star1.png') }}">
                        </div>
                      </a>
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
