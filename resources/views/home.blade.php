@extends('layouts.app')
@section('customcss')
<style type="text/css">
    .panel-body .table tbody td:last-child, .panel-body .table thead th:last-child {
        padding-right: 15px;
        display: table-cell;
    }
</style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-6 text-left">
                            <h1>AOI {{$ip}}</h1>
                        </div>
                        <div class="col-md-3">
                            <a class="btn btn-primary" href="{{route('activity_logs')}}" target="_blank"><i class="fas fa-sticky-note"></i> Logs</a>
                        </div>
                        <div class="col-md-3 text-right align-middle">
                            <button class="btn btn-primary" data-toggle="modal" data-target=".machine_add"><i class="fas fa-laptop"></i> Add Machine PC</button>
                        </div>
                    </div>


                </div>

                <div class="panel-body">
                    <table id="table" class="table table-bordered">
                        <thead>
                            <th>#</th>
                            <th>IP Address</th>
                            <th>Machine Name</th>
                            <th>Machine type</th>
                            <th>Date Added</th>
                            <th>Status</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @foreach($machines as $key => $mech)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$mech->ip_address}}</td>
                                    <td>{{$mech->machine_name}}</td>
                                    <td>{{machine_type($mech->machine_type)}}</td>
                                    <td>{{date_con($mech->date_created)}}</td>
                                    <td>{{stats($mech->active_status)}}</td>
                                    <td><button class="btn btn-primary view" data-id="{{$mech->id}}" data-toggle="modal" data-target=".machine_view"> View</button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Add Modal -->
<div class="modal fade machine_add" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="save_machines">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h3 class="modal-title">Add Machine PC</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 pr-1">
                            <div class="form-group">
                                <label>IP Address</label>
                                <input name="ip_address" type="text" class="form-control" required placeholder="IP ADDRESS">
                            </div>
                        </div>
                        <div class="col-md-4 pr-1">
                            <div class="form-group">
                                <label>Machine Name</label>
                                <input name="machine_name" type="text" class="form-control" required placeholder="Machine Name">
                            </div>
                        </div>
                        <div class="col-md-4 pr-1">
                            <div class="form-group">
                                <label>Machine Type</label>
                                <select class="form-control" name="mech_type">
                                    <option selected disabled>Select Machine Type</option>
                                    <option value="1">AOI</option>
                                    <option value="2">QET</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-3 col-md-offset-6">
                            <button type="submit" class="btn btn-primary regmech">Register Machine</button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End modal -->

<!-- Add Modal -->
<div class="modal fade machine_view" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="update_machines">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h3 class="modal-title">Add Machine PC</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3 pr-1">
                            <div class="form-group">
                                <label>IP Address</label>
                                <input type="hidden" name="mech_id" id="mech_id">
                                <input name="ip_address" id="ip_address" type="text" class="form-control" required placeholder="IP ADDRESS">
                            </div>
                        </div>
                        <div class="col-md-3 pr-1">
                            <div class="form-group">
                                <label>Machine Name</label>
                                <input name="machine_name" id="machine_name" type="text" class="form-control" required placeholder="Machine Name">
                            </div>
                        </div>
                        <div class="col-md-3 pr-1">
                            <div class="form-group">
                                <label>Machine Type</label>
                                <select class="form-control" name="mech_type" id="mech_type">
                                    <option selected disabled>Select Machine Type</option>
                                    <option value="1">AOI</option>
                                    <option value="2">QET</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 pr-1">
                            <div class="form-group">
                                <label>Machine Status</label>
                                <select class="form-control" name="mech_status" id="mech_status">
                                    <option selected disabled>Select Machine Status</option>
                                    <option value="1">Active</option>
                                    <option value="2">Deactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-3 col-md-offset-6">
                            <button type="submit" class="btn btn-primary upmech">Update Machine</button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End modal -->
@endsection


@section('customjs')
<script type="text/javascript">
    $('#table').DataTable();

    $('#table').on('click','.view',function() {
            var id = $(this).data('id');
            $.ajax({
                type:'get',
                url:'{{  route('get_detials') }}',
                data:{id:id},
                success:function(data) {
                    $.each(data.info, function(index,value){
                        if(data.valid){
                            $('#mech_id').val(value.id);
                            $('#ip_address').val(value.ip_address);
                            $('#machine_name').val(value.machine_name);
                            $('#mech_type').val(value.machine_type);
                            $('#mech_status').val(value.active_status);
                        }
                    });
                }
            });
        });

    $('.regmech').click(function() {
        event.preventDefault();
        var data = $('#save_machines').serialize();
        $.ajax({
            type:"POST",
            url:"{{route('save_machine')}}",
            data: data,
            success: function(data){
                $.each(data.message, function(index,value){
                    if(data.error){
                        alert(value);
                    }
                    else{
                        alert(value);
                        window.setTimeout(function(){location.reload()},1000)
                    }
                });
            }
        });
    });

    $('.upmech').click(function() {
        event.preventDefault();
        var data = $('#update_machines').serialize();
        $.ajax({
            type:"POST",
            url:"{{route('update_machine')}}",
            data: data,
            success: function(data){
                $.each(data.message, function(index,value){
                    if(data.error){
                        alert(value);
                    }
                    else{
                        alert(value);
                        window.setTimeout(function(){location.reload()},1000)
                    }
                });
            }
        });
    });
</script>
@endsection
