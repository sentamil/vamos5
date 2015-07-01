@include('includes.header_index')
<div id="wrapper">
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        vehicleId - {{$vehicleId}}
                    </div>
                    <div class="panel-body">
                        <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="col-sm-6">
                                        <div id="example2_filter" class="dataTables_filter"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="example1" class="table table-bordered dataTable"  style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center;">ID</th>
                                                    <th style="text-align: center;">Stop Name</th>
                                                    <th style="text-align: center;">Delivery Time</th>
                                                    <th style="text-align: center;">Mobile Nos</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($stopNames as $key => $value)
                                                <tr>
                                                    <td>{{ $key }}</td>
                                                    <td>{{ $value }}</td>
                                                    <td>{{$deliveryTime[$key]}}</td>
                                                    <!-- we will also add show, edit, and delete buttons -->
                                                  <td>
                                                   {{$mobileNos[$key]}}
                                                
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
            </div>
            @include('includes.js_index')
            </body>
            </html>

