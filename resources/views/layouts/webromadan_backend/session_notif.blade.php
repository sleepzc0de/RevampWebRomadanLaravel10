 @if($message = Session::get('success'))



                                                <div class="alert alert-success alert-dismissible" role="alert">
          <h4 class="alert-heading d-flex align-items-center"><i class="mdi mdi-check-circle-outline mdi-24px me-2"></i>Well done :)</h4>
          <hr>
          <p class="mb-0">{{$message}}</p>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
          </button>
        </div>
@endif

@if($message = Session::get('failed'))

        <div class="alert alert-danger alert-dismissible" role="alert">
          <h4 class="alert-heading d-flex align-items-center"><i class="mdi mdi-check-circle-outline mdi-24px me-2"></i>Error :(</h4>
          <hr>
          <p class="mb-0">{{$message}}</p>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
          </button>
        </div>
@endif
