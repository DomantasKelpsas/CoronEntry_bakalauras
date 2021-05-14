@extends('layouts.app')

@section('content')
<div class="my-20 flex justify-center">
    <div class="w-4/12 bg-gray-50 p-6 rounded-lg">
    @if (session('status'))
        {{session('status')}}  

    @endif
    <h1 class="pb-4 flex justify-center text-4xl">Register</h1>
    <form  action="{{ route('register')}}" method="post">
    @csrf
    <div class="mb-4">
        <label for="name" class="sr-only">Email</label>
        <input type="text" placeholder="Enter Admin Name" name="name" id="name" class="bg-gray-100 border-2 w-full p-4 rounded-lg" value="">
        @error('name')
        <div class="text-red-500 mt-2 text-sm">{{$message}}</div>
        @enderror
    </div>
    <div class="mb-4">
        <label for="email" class="sr-only">Email</label>
        <input type="text" placeholder="Enter Email" name="email" id="email" class="bg-gray-100 border-2 w-full p-4 rounded-lg" value="">
        @error('email')
        <div class="text-red-500 mt-2 text-sm">{{$message}}</div>
        @enderror
    </div>
    <div class="mb-4">
        <label for="password" class="sr-only">Password</label>
        <input type="password" placeholder="Enter Password" name="password" id="password" class="bg-gray-100 border-2 w-full p-4 rounded-lg" value="">
        @error('password')
        <div class="text-red-500 mt-2 text-sm">{{$message}}</div>
        @enderror
    </div>
    <div class="mb-4">
        <label for="password_confirmation" class="sr-only">Password</label>
        <input type="password" placeholder="Confirm Your Password" name="password_confirmation" id="password_confirmation" class="bg-gray-100 border-2 w-full p-4 rounded-lg" value="">
        @error('password')
        <div class="text-red-500 mt-2 text-sm">{{$message}}</div>
        @enderror
    </div>
    <div class="mb-4 py-5 px-3 border-2 border-black rounded-md">
        <h1 class="mb-3 flex justify-center align-center text-lg">Place Details</h1>
        <label for="placename" class="sr-only">Password</label>
        <input type="text" placeholder="Enter Company / Place Name" name="placename" id="placename" class="bg-gray-100 border-2 w-full p-4 rounded-lg" value="">
        @error('placename')
        <div class="text-red-500 mt-2 text-sm">{{$message}}</div>
        @enderror
    </div>
    <div>
    <button type="submit" class="bg-blue-500 text-white px-4 py-3 rounded font-medium w-full mb-5">Register</button>
    <a href="{{route('login')}}" class=""><h2>Already Have Admin account? Login!</h2></a>
    </div>
    </form>
    </div>
</div>
@endsection