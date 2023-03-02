 @if($message = Session::get('success'))
                            <div class="alert alert-success alert-icon-start alert-dismissible fade show">
											<span class="alert-icon bg-success text-white">
												<i class="ph-gear"></i>
											</span>
											<span class="fw-semibold">{{$message}}</span> 
											<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
									    </div>
                            @endif

                             @if($message = Session::get('failed'))
                            <div class="alert alert-danger alert-icon-start alert-dismissible fade show">
											<span class="alert-icon bg-danger text-white">
												<i class="ph-gear"></i>
											</span>
											<span class="fw-semibold">{{$message}}</span> 
											<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
									    </div>
                            @endif