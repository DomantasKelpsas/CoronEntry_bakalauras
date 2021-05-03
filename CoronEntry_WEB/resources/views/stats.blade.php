@extends('layouts.app')

@section('content')
<h1 class="p-10 flex justify-center text-4xl">Monthly Statistics</h1>
<div class="bg-gray-100 p-5 m-8">
<canvas id="myChart" class="bg-white rounded" max-width="100%" height="50vh"></canvas>
</div>
<h1 class="p-10 flex justify-center text-3xl">Single User Statistics</h1>

<select id="user-select" name="user-select" class="flex justify-center px-4 py-3 rounded font-medium w-3/12 mx-auto text-center" 
onchange="singleUserStats(this.options[this.selectedIndex].value)">
        <option  class="text-center" value="">--- Select User ---</option>
        @foreach($data['users'] as $user)
            <option value="{{$user->id}}" >{{$user->name}} [{{$user->user_code}}]</option>
        @endforeach
    </select>
    <div id="test"></div>
    


<script>
var data = <?= json_encode($data['chartdata']);?>;
console.log(data);
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul","Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
            label: 'Monthly user count',
            data: data,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {              
                beginAtZero: true,
                ticks:{
                    stepSize: 2
                }
            }
        }
    }
});

function singleUserStats(id){
$.ajax({
    url: '/user-select/'+id,
    success: function(data) {
        console.log(data);
        showSingleUserStats(data);
    }
});
}


function showSingleUserStats(data){
    data.forEach( element => {
        document.getElementById("test").innerHTML += 
              `<h3>${element['date']}</h3>`
    });
}
</script>
@endsection