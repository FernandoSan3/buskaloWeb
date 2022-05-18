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

          <div class="tab-pane active" id="documentacion">
            <div class="side-heading">
              <div class="row">
                <div class="col-md-8">
                  <div class="head-side">
                    <h3>Documentacion</h3>
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
            <table id="example2" class="display nowrap contact-table" style="width:100%">
                <thead>
                    <tr>
                        <th>S no.</th>
                        <th>Perfil</th>
                        <th>Servicio</th>
                        <th>Fecha</th>
                        <th>Archivo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td><img src="{{ url('img/frontend/user.png') }}"></td>
                        <td>RUC</td>
                        <td>2020/05/21</td>
                        <td><button type="button" class="btn sele-btn">Seleccionar</button></td>
                        <td><div class="edit-btns"><i class="fa fa-pencil-square-o"></i><i class="fa fa-trash"></i></div>
                        </td>
                    </tr>
                    <tr>
                      <td>2</td>
                      <td><img src="{{ url('img/frontend/user.png') }}"></td>
                      <td>Acta de constitucion</td>
                      <td>2020/05/21</td>
                      <td><button type="button" class="btn sele-btn">Seleccionar</button></td>
                      <td><div class="edit-btns"><i class="fa fa-pencil-square-o"></i><i class="fa fa-trash"></i></div>
                      </td>
                    </tr>
                    <tr>
                      <td>2</td>
                      <td><img src="{{ url('img/frontend/user.png') }}"></td>
                      <td>Acta de constitucion</td>
                      <td>2020/05/21</td>
                      <td><button type="button" class="btn sele-btn">Seleccionar</button></td>
                      <td><div class="edit-btns"><i class="fa fa-pencil-square-o"></i><i class="fa fa-trash"></i></div>
                      </td>
                    </tr>
                    <tr>
                      <td>2</td>
                      <td><img src="{{ url('img/frontend/user.png') }}"></td>
                      <td>Acta de constitucion</td>
                      <td>2020/05/21</td>
                      <td><button type="button" class="btn sele-btn">Seleccionar</button></td>
                      <td><div class="edit-btns"><i class="fa fa-pencil-square-o"></i><i class="fa fa-trash"></i></div>
                      </td>
                    </tr>
                    <tr>
                      <td>2</td>
                      <td><img src="{{ url('img/frontend/user.png') }}"></td>
                      <td>Acta de constitucion</td>
                      <td>2020/05/21</td>
                      <td><button type="button" class="btn sele-btn">Seleccionar</button></td>
                      <td><div class="edit-btns"><i class="fa fa-pencil-square-o"></i><i class="fa fa-trash"></i></div>
                      </td>
                    </tr>
                    <tr>
                      <td>2</td>
                      <td><img src="{{ url('img/frontend/user.png') }}"></td>
                      <td>Acta de constitucion</td>
                      <td>2020/05/21</td>
                      <td><button type="button" class="btn sele-btn">Seleccionar</button></td>
                      <td><div class="edit-btns"><i class="fa fa-pencil-square-o"></i><i class="fa fa-trash"></i></div>
                      </td>
                    </tr>
                    <tr>
                      <td>2</td>
                      <td><img src="{{ url('img/frontend/user.png') }}"></td>
                      <td>Acta de constitucion</td>
                      <td>2020/05/21</td>
                      <td><button type="button" class="btn sele-btn">Seleccionar</button></td>
                      <td><div class="edit-btns"><i class="fa fa-pencil-square-o"></i><i class="fa fa-trash"></i></div>
                      </td>
                    </tr>
                    <tr>
                      <td>2</td>
                      <td><img src="{{ url('img/frontend/user.png') }}"></td>
                      <td>Acta de constitucion</td>
                      <td>2020/05/21</td>
                      <td><button type="button" class="btn sele-btn">Seleccionar</button></td>
                      <td><div class="edit-btns"><i class="fa fa-pencil-square-o"></i><i class="fa fa-trash"></i></div>
                      </td>
                    </tr>
                    <tr>
                      <td>2</td>
                      <td><img src="{{ url('img/frontend/user.png') }}"></td>
                      <td>Acta de constitucion</td>
                      <td>2020/05/21</td>
                      <td><button type="button" class="btn sele-btn">Seleccionar</button></td>
                      <td><div class="edit-btns"><i class="fa fa-pencil-square-o"></i><i class="fa fa-trash"></i></div>
                      </td>
                    </tr>
                    <tr>
                      <td>2</td>
                      <td><img src="{{ url('img/frontend/user.png') }}"></td>
                      <td>Acta de constitucion</td>
                      <td>2020/05/21</td>
                      <td><button type="button" class="btn sele-btn">Seleccionar</button></td>
                      <td><div class="edit-btns"><i class="fa fa-pencil-square-o"></i><i class="fa fa-trash"></i></div>
                      </td>
                    </tr>
                </tbody>
              </table>
            <div class="col-md-12 text-center">
              <button type="submit" class="btn opp-btn">Aceptar</button>
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