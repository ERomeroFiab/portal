@if ( \Session::has('error') )
   <div class="alert alert-danger alert-dismissible fade show">
      <ul>
         <li>{!! \Session::get('error') !!}</li>
      </ul>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
         <span aria-hidden="true">&times;</span>
      </button>
   </div>
@endif

@if (\Session::has('success'))
   <div class="alert alert-success alert-dismissible fade show">
      <ul>
         <li>{!! \Session::get('success') !!}</li>
      </ul>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
         <span aria-hidden="true">&times;</span>
      </button>
   </div>
@endif

@if (\Session::has('info'))
   <div class="alert alert-info alert-dismissible fade show">
      <ul>
         <li>{!! \Session::get('info') !!}</li>
      </ul>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
         <span aria-hidden="true">&times;</span>
      </button>
   </div>
@endif