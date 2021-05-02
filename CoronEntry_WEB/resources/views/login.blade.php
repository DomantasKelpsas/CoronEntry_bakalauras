@extends('layouts.app')

@section('content')
<div class="flex justify-center">
    <div class="w-4/12 bg-gray-50 p-6 rounded-lg">
    @if (session('status'))
        {{session('status')}}  

    @endif
    <h1 class="p-4 flex justify-center text-4xl">Login</h1>
    <form  action="{{ route('login')}}" method="post">
    @csrf
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
    <div>
    <button type="submit" class="bg-blue-500 text-white px-4 py-3 rounded font-medium w-full">Login</button>
    </div>
    </form>
    </div>
</div>
@endsection