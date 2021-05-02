<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
</head>
<body>
<nav class="p-6 bg-white flex justify-between">     
    <ul class="flex items-center">   
      <li><a href="#" class="p-3">Home</a></li>
      <li><a href="{{route('login')}}" class="p-3">Login</a></li>    
    </ul> 
</nav>
@yield('content')
</body>
</html>