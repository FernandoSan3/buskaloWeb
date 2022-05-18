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

        <div class="tab-pane active" id="creditos">
            <div class="side-heading">
              <div class="row">
                <div class="col-md-8">
                  <div class="head-side">
                    <h3>Creditos</h3>
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

            <div class="credit-header">
              <div class="row">
                <div class="col-md-3">
                  <div class="blnc-total">
                    <h2>Balance</h2>
                    <span>$9.95</span>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="about-blc">
                    <div class="progress">
                      <div class="progress-bar  bar1" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div><span>$20.00</span>
                    </div>
                    <div class="progress">
                      <div class="progress-bar bar2" role="progressbar" style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div><span>$10.00</span>
                    </div>
                    <div class="progress">
                      <div class="progress-bar bar3" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div><span>$5.00</span>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="card-sec">
                    <button type="button" class="btn card-btn">PIN</button>
                    <button type="button" class="btn card-btn">Tarjeta credito</button>
                  </div>
                </div>
              </div>
            </div>

            <table id="example1" class="display nowrap contact-table" style="width:100%">
              <thead>
                  <tr>
                      <th>S no.</th>
                      <th>Servicio</th>
                      <th>Fecha</th>
                      <th>Consumos</th>
                      <th>Recargsa</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                      <td>1</td>
                      <td>Instalacion Cisterna</td>
                      <td>2020/05/21</td>
                      <td>2.56</td>
                      <td>200</div>
                      </td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td>Instalacion planta electrica</td>
                    <td>2020/05/21</td>
                    <td>1.89</td>
                    <td></div>
                    </td>
                  </tr>
                  <tr>
                    <td>3</td>
                    <td>Instalacion Panel Solar</td>
                    <td>2020/05/21</td>
                    <td>5.60</td>
                    <td></div>
                    </td>
                  </tr>
                  <tr>
                    <td>4</td>
                    <td>Carga VISA 1XXXX78</td>
                    <td>2020/05/21</td>
                    <td></td>
                    <td>20.00</div>
                    </td>
                  </tr>
                  <tr>
                    <td>5</td>
                    <td>Instalacion Cisterna</td>
                    <td>2020/05/21</td>
                    <td>2.56</td>
                    <td>200</div>
                  </tr>
                  <tr>
                    <td>6</td>
                    <td>Instalacion Panel Solar</td>
                    <td>2020/05/21</td>
                    <td>5.60</td>
                    <td></div>
                    </td>
                  </tr>
                  <tr>
                    <td>7</td>
                    <td>Instalacion planta electrica</td>
                    <td>2020/05/21</td>
                    <td>1.89</td>
                    <td></div>
                    </td>
                  </tr>
                 <tr>
                    <td>8</td>
                    <td>Carga VISA 1XXXX78</td>
                    <td>2020/05/21</td>
                    <td></td>
                    <td>20.00</div>
                    </td>
                  </tr>
                   <tr>
                      <td>9</td>
                      <td>Instalacion Cisterna</td>
                      <td>2020/05/21</td>
                      <td>2.56</td>
                      <td>200</div>
                      </td>
                  </tr>
                  <tr>
                    <td>10</td>
                    <td>Instalacion planta electrica</td>
                    <td>2020/05/21</td>
                    <td>1.89</td>
                    <td></div>
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