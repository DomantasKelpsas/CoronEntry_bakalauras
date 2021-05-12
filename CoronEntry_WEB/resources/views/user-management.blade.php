@extends('layouts.app')

@section('content')

@extends('layouts.form-add')
@extends('layouts.form-edit')
@extends('layouts.form-delete')

<div class="p-10">
<h1 class="p-10 flex justify-center text-4xl">User Management</h1>
<button class="add bg-blue-500 text-white absolute bottom-15 right-20 rounded-full h-20 w-20" data-toggle="modal" data-target="#addModal">
<i class="fas fa-plus fa-3x"></i></button>
<table class="table w-full thead-light" id="user-table">
        <thead class="thead-light">
        <tr>    
        <th scope="col" class="hidden"></th>   
        <th scope="col">Name</th>
        <th scope="col">Email</th>
        <th scope="col">User Code</th>
        <th scope="col">Entry Class</th>
        <th scope="col">Operations</th>              
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
        <tr>
        <td class="hidden">{{$user['id']}}</td>
        <td>{{$user['name']}}</td>
        <td>{{$user['email']}}</td>
        <td>{{$user['user_code']}}</td>
        <td>{{$user['entry_class']}}</td>
        <td class="" >
        
            <button class="edit bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-3 mr-2 rounded"><i class="far fa-edit"></i></button>
            <button class="delete bg-red-500 hover:bg-red-700 text-white font-bold py-3 px-3 rounded"><i class="fas fa-trash-alt"></i></button>      
       
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
        var table = $('#user-table').DataTable();

        table.on('click','.edit',function(){

            $tr = $(this).closest('tr');
            if($($tr).hasClass('child')){
                $tr = $tr.prev('parent');
            }

            var data = table.row($tr).data();
            console.log(data);

            $('#entry-class').val(data[4]);
            $('#editForm').attr('action','/usermng/'+data[0]+'/edit');
            $('#editModal').modal('show');

        });


        table.on('click','.delete',function(){

        $tr = $(this).closest('tr');
        if($($tr).hasClass('child')){
            $tr = $tr.prev('parent');
        }

        var data = table.row($tr).data();
        console.log(data);
       
        $('#deleteForm').attr('action','/usermng/'+data[0]+'/delete');
        $('#deleteModal').modal('show');

        });

    });
    </script>
@endsection