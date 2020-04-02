@extends('layouts.app')
@section('customcss')
<style type="text/css">
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
<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="panel panel-default">
        <div class="panel-heading">
          <div class="row">
            <div class="col-md-6 text-left text-justify">
              <h1>AOI {{$ip}}</h1>
            </div>
            <div class="col-md-offset-3 col-md-3 text-right">
              <a href="{{route('listmachine')}}" target="_blank" class="btn btn-primary">List Of Machines</a>
            </div>
          </div>
        </div>
        <div class="panel-body">
          <table class="table table-bordered">
           <thead>
             <th>Machine Name</th>
             <th>Trigger Time</th>
             <th>Status</th>
             <th>First Order</th>
             <th>Second Order</th>
             <th>Third Order</th>
             <th>Machine Setup</th>
             <th>Action</th>
           </thead>
           <tbody id="load_body">
           </tbody>
         </table>
       </div>
     </div>
     <div class="row">
      <div class="col-md-8 col-md-offset-3">
       <form class="form-inline" id="qet_request">
        {{ csrf_field() }}
        <div class="form-group">
          <input type="text" class="form-control" name="request_load" id="request_load" placeholder="Request Load" required>
        </div>
        <div class="form-group">
          <select class="form-control" name="id" required>
            <option selected disabled>Select Machine</option>
            @foreach($machines as $mech)
            <option value="{{$mech->id}}">{{$mech->machine_name}}</option>
            @endforeach
          </select>        
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-default send">Send Request</button>
        </div>
      </form>
    </div>
  </div>
</div>
</div>
</div>
@endsection


@section('customjs')
<script type="text/javascript">
  setInterval(
    function() {
      $.ajax({
        type:"GET",
        url:"{{route('load')}}",
        success: function(response){
          $('#load_body').html(response);
        }
      });
    },1000
    );

  $('#load_body').on('click','.go',function() {
    var id = $(this).data('id');
    $.ajax({
      type:"GET",
      url:"{{route('update')}}",
      data:{id:id},
      success: function(data){
        $.each(data.message, function(index,value){
          if(data.success){
            alert(value);
            window.setTimeout(function(){location.reload()},1000);
          }
        });
      }
    });
  });

  $('.send').click(function() {
    event.preventDefault();
    var data = $('#qet_request').serialize();
    $.ajax({
      type:"POST",
      url:"{{route('machine_order')}}",
      data:data,
      success: function(data){
        $.each(data.message, function(index,value){
          if(data.success){
            alert(value);
            window.setTimeout(function(){location.reload()},1000);
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
