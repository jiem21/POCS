@extends('layouts.app')
@section('customcss')
<style type="text/css">
    .btn{
        /*width: 10vw;*/
    }
    .fa-spinner{
      webkit-animation: fa-spin 1s infinite linear;
      animation: fa-spin 1s infinite linear;
    }
    .word-wrap {
        word-break: break-all;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }
</style>
@endsection


@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading text-center"><h1>{{$name}}</h1></div>

                <div class="panel-body" style="padding: 30px 5px" id="panel_body">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading text-center"><h1>Machine Setup</h1></div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                            <th>Machine Current Set</th>
                            <td class="word-wrap">{{$setup}}</td>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <form class="form-inline" id="machine_config">
                        {{ csrf_field() }}
                      <div class="form-group">
                          <input type="hidden" name="machine_id" value="{{$id}}">
                          <input type="text" class="form-control" name="setup" id="setup" placeholder="Machine Setup" required>
                      </div>
                      <div class="form-group">
                        <button type="submit" class="btn btn-default update">Update Setup</button>
                      </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('customjs')

@if($status != 3)
<script type="text/javascript">
    setInterval(
        function() {
            var id = '{{$id}}';
            $.ajax({
                type:"GET",
                url:"{{route('qet-load')}}",
                data:{id:id},
                success: function(response){
                    $('#panel_body').html(response);
                }
            });
        },1000
        );
</script>
@endif

<script type="text/javascript">
    setInterval(
        function() {
            var id = '{{$id}}';
            $.ajax({
                type:"GET",
                url:"{{route('qet-load')}}",
                data:{id:id},
                beforeSend:function() {
                    $('.go').prop('disabled',true);
                },
                success: function(response){
                    $('#panel_body').html(response);
                }
            });
        },1000
        );

    
    $('#panel_body').on('click','.go',function() {
        var id = $(this).data('id');
        $.ajax({
            type:"GET",
            url:"{{route('update')}}",
            data:{id:id},
            success: function(data){
                $.each(data.message, function(index,value){
                    if(data.success){
                        alert(value);
                        window.setTimeout(function(){location.reload()},1000)
                    }
                });
            }
        });
    });

    $('.update').click(function() {
        event.preventDefault();
        var data = $('#machine_config').serialize();;
        $.ajax({
            type:"GET",
            url:"{{route('machine_setup')}}",
            data: data,
            success: function(data){
                $.each(data.message, function(index,value){
                    if(data.success){
                        alert(value);
                        window.setTimeout(function(){location.reload()},1000)
                    }
                    else{
                        alert(value);
                    }
                });
            }
        });
    });
</script>
@endsection
