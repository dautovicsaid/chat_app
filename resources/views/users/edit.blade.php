@extends('layouts.user')

@section('css')
    <style>
        img {
            cursor: pointer;
        }
    </style>
@endsection
@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit profile</div>
                    <div class="card-body ">
                        <form class="" action="{{route('users.update',['user' =>$user])}}"
                              enctype="multipart/form-data" method="post">
                            <div class="row">
                                <div class="col-4 offset-1 ">
                                    <div class="form-group mb-3">
                                        <div class="circular--portrait">
                                            <img src="{{$user->profile_picture}}" id="image">
                                        </div>
                                        <input class="d-none" type="file" name="profile_picture" id="profile_picture"
                                        >

                                    </div>
                                </div>
                                <div class="col-4 offset-1 text-center mt-5">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group mb-3">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Name"
                                               value="{{$user->name}}">
                                        @error('name') {{$message}} @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <input type="text" class="form-control" id="email" name="email"
                                               placeholder="Email"
                                               value="{{$user->email}}">
                                        @error('email') {{$message}} @enderror
                                    </div>
                                    <button class="btn btn-primary">Update</button>
                                </div>
                        </form>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{asset('js/user.js')}}"></script>
    @endsection
