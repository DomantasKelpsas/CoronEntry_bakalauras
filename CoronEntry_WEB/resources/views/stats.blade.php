@extends('layouts.app')

@section('content')
<h1 class="p-10 flex justify-center text-4xl">Monthly Statistics</h1>
<div class="bg-gray-100 p-5 m-8 border-2 rounded">

<input class="m-2 p-2 mb-4 border-2 border-black rounded" type="date">
<input class="m-2 p-2 mb-4 border-2 border-black rounded" type="date">
<button class="secondary-color text-white font-bold py-2 px-4 border border-blue-700 rounded">Proceed</button>

<canvas id="myChart" class="bg-white border-2 rounded" max-width="100%" height="50vh"></canvas>

</div>
<h1 class="p-10 flex justify-center text-3xl">Single User Statistics</h1>

<select id="user-select" name="user-select" class="flex justify-center px-4 py-3 rounded font-medium w-3/12 mx-auto text-center" 
onchange="singleUserStats(this.options[this.selectedIndex].value)">
        <option  class="text-center" value="">--- Select User ---</option>
        @foreach($data['users'] as $user)
            <option value="{{$user->id}}" >{{$user->name}} [{{$user->user_code}}]</option>
        @endforeach
    </select>
    <div id="single-user-stats" class="w-auto m-8 p-8 bg-gray-100 border-2 rounded">
    <table id="single-user-table" class="table w-9/12 thead-dark table-striped border-2 border-black mx-auto">
        <thead class="thead-dark rounded-md">
        <tr class="rounded-md">       
        <th>EntryPoint</th>
        <th>Date Entered</th>
        <th>Date Exit</th>
        </tr>
        </thead>
        <tbody id="single-user-table-body">
        </tbody>
    </table>
    </div>
    



@endsection

@section('script')
<script type="text/javascript">

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
                'rgba(37, 58, 75, 0.5)',
                'rgba(255, 68, 68, 0.5)'
                
            ],
            borderColor: [
                'rgba(37, 58, 75, 1)',
                'rgba(255, 68, 68, 1)'
            ],
            borderWidth: 2
        }]
    },
    options: {
        scales: {
            y: {              
                beginAtZero: true,
                ticks:{
                    stepSize: 4
                }
            }
        }
    }
});

function singleUserStats(id){
$.ajax({
    url: '/user-select/'+id,
    success: function(data) {
    $('#single-user-table').DataTable().clear().destroy();      
    showSingleUserStats(data);
    $('#single-user-table').DataTable();                               
    }
});
}


function showSingleUserStats(data){
    var table = document.getElementById("single-user-table-body");
    table.innerHTML = "";
    data.forEach( element => {                 
        var row = table.insertRow(0);
        var td_ep = row.insertCell(0);
        var td_date = row.insertCell(1);
        var td_exit_date = row.insertCell(2);
        td_ep.innerHTML = element['name'];
        td_date.innerHTML = element['date'];
        td_exit_date.innerHTML = element['exit_date'];
        

    });
}

</script>
@endsection