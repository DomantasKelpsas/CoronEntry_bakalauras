<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoronEntry</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.2.0/chart.min.js" integrity="sha512-VMsZqo0ar06BMtg0tPsdgRADvl0kDHpTbugCBBrL55KmucH6hP9zWdLIWY//OTfMnzz6xWQRxQqsUFefwHuHyg==" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
</head>
<body>
<nav class="p-6 bg-purple-100 flex justify-between">     
    <ul class="flex items-center">   
    @if(Route::is('login'))
      <li><a href="/" class="p-3">Home</a></li>
      <li><a href="{{route('login')}}" class="p-3">Login</a></li>
    @else
    <li><a href="/" class="p-3">Home</a></li>
      <li><a href="{{route('stats')}}" class="p-3">Statistics</a></li>
      <li><a href="{{route('usermng')}}" class="p-3">User Management</a></li>
      <li><a href="{{route('stats')}}" class="p-3">EP Management</a></li>
    @endif   
    </ul> 
</nav>
@yield('content')
</body>
</html>