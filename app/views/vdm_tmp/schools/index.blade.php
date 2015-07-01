@include('includes.header_index')
<div id="wrapper">
    <div class="content animate-panel">
        <div class="row">
            <div class="col-lg-12">
                <div class="hpanel">
                    <div class="panel-heading">
                        Vehicles List
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
                                        <table id="example1" class="table table-bordered dataTable">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center;">ID</th>
                                                    <th style="text-align: center;">School ID</th>
                                                    <th style="text-align: center;">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($schoolList as $key => $value)
                                                <tr>
                                                    <td>{{ $key }}</td>
                                                    <td>{{ $value }}</td>

                                                    <!-- we will also add show, edit, and delete buttons -->
                                                    <td> {{ Form::open(array('url' => 'vdmBusRoutes/' . $key, 'class' => 'pull-right')) }}
                                                    {{ Form::hidden('_method', 'DELETE') }}
                                                    {{ Form::submit('Delete Bus Routes', array('class' => 'btn btn-warning')) }}
                                                    {{ Form::close() }} <a class="btn btn-small btn-success" href="{{ URL::to('vdmBusRoutes/'. $value) }}">Show Routes</a><a class="btn btn-small btn-info" href="{{ URL::to('vdmSchool/' . $key . '/edit') }}">Edit School </a></td>
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

