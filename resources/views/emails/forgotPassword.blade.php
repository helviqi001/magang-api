@extends('emails.template')
@section('title','Atur Ulang Kata Sandi')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Tautan Atur Ulang Kata Sandi</div>
                <div class="card-body">
                  
                    <a href="http://localhost/forgotPassword?otp={{$otp['otp']}}">Klik untuk Mereset Kata Sandi</a>.
                </div>
            </div>
        </div>
    </div>
@endsection