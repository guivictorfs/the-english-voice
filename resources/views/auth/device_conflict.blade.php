@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0 text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>Atenção!
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger">
                        <p class="mb-2">
                            <strong>Sua conta foi acessada em outro dispositivo.</strong>
                        </p>
                        <div class="mt-3">
                            <a href="{{ route('login') }}" class="btn btn-primary me-2">
                                <i class="fas fa-sign-in-alt me-1"></i> Fazer login novamente
                            </a>
                            <a href="{{ route('password.request') }}" class="btn btn-danger">
                                <i class="fas fa-key me-1"></i> Redefinir senha
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
