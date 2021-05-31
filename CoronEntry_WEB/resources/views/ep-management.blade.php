@extends('layouts.app')

@section('content')

@extends('layouts.form-add')
@extends('layouts.form-edit')
@extends('layouts.form-delete')
<div class="p-10 border">
<h1 class="p-10 flex justify-center text-4xl">Entry Point Management</h1>
<form action="{{ route('placetemp-set')}}" method="post">
@csrf
    <input type="number" name="temp" class="border-2 border-black rounded px-4 py-2" value="37" step="0.2" min="30" max="40">
    <button type="submit" class="bg-blue-500 text-white px-4 py-3 rounded font-medium mb-5">Set Temperature</button>
</form>
<button class="add primary-color text-white absolute bottom-10 right-10 rounded-full h-20 w-20" data-toggle="modal" data-target="#addModal">
<i class="fas fa-plus fa-3x"></i></button>
<table class="table w-full thead-light"  id="ep-table">
        <thead class="thead-light">
        <tr>    
        <th scope="col" class="hidden"></th>   
        <th scope="col">Name</th>        
        <th scope="col">EP Code</th>
        <th scope="col">Entry Class</th>
        <th scope="col">User Limit</th>
        <th scope="col">Operations</th>              
        </tr>
        </thead>
        <tbody>
        @foreach($eps as $ep)
        <tr>
        <td class="hidden">{{$ep['id']}}</td>
        <td>{{$ep['name']}}</td>
        <td>{{$ep['entry_code']}}</td>
        <td>{{$ep['entry_class']}}</td>
        <td>{{$ep['max_user_count'] === null ? 'unlimited' : $ep['max_user_count']}}</td>
        <td class="" >
        
        <button class="edit text-2xl text-green-500 hover:text-green-700 text-white font-bold py-3 px-3 mr-2 rounded "><i class="far fa-edit"></i></button>
        <button class="delete text-2xl text-red-500 hover:text-red-700 text-white font-bold py-3 px-3 rounded"><i class="fas fa-trash-alt"></i></button>    
       
        </td>
     
        </tr>
        @endforeach
        </tbody>
    </table>
    </div>

    
@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function (){
        var table = $('#ep-table').DataTable();
//--------------------ADD-----------------------//
        // table.on('click','.add',function(){
        // $tr = $(this).closest('tr');
        // if($($tr).hasClass('child')){
        //     $tr = $tr.prev('parent');
        // }
        // var data = table.row($tr).data();
        // console.log(data);

        // $('#name').val(data[1]);
        // $('#entry-class').val(data[3]);         
        // $('#editForm').attr('action','/epmng/'+data[0]+'/edit');
        // $('#editModal').modal('show');
        // });

//--------------------EDIT-----------------------//
        table.on('click','.edit',function(){

            $tr = $(this).closest('tr');
            if($($tr).hasClass('child')){
                $tr = $tr.prev('parent');
            }

            var data = table.row($tr).data();
            console.log(data);

            $('#name').val(data[1]);           
            $('#entry-class').val(data[3]);
            if(data[4] !='unlimited'){
                $('#userlimit').val(data[4]);
                $("#userlimit-check").removeAttr('checked');
            }
            else{ 
                $('#userlimit').val(null); 
                $('#userlimit').attr("placeholder", "unlimited");
                $("#userlimit-check").attr('checked','checked'); 
            }                   
            $('#editForm').attr('action','/epmng/'+data[0]+'/edit');
            $('#editModal').modal('show');

        });

//--------------------DELETE-----------------------//
        table.on('click','.delete',function(){

        $tr = $(this).closest('tr');
        if($($tr).hasClass('child')){
            $tr = $tr.prev('parent');
        }

        var data = table.row($tr).data();
        console.log(data);
       
        $('#deleteForm').attr('action','/epmng/'+data[0]+'/delete');
        $('#deleteModal').modal('show');

        });

    });
    </script>
@endsection