@extends('layouts.webromadan_backend.master_layout')

@section('content')
<div class="content pt-0">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Unauthorized Access</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-danger" role="alert">
                            {{ $exception->getMessage() ?: 'You do not have permission to access this page.' }}
                        </div>
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="ph-house me-2"></i>
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
