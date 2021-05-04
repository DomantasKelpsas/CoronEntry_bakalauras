@extends('layouts.app')

@section('content')

@extends('layouts.form-add')
@extends('layouts.form-edit')
@extends('layouts.form-delete')
<div class="p-10 border">
<button class="add bg-blue-500 text-white p-2 my-4 rounded font-medium w-1/12" data-toggle="modal" data-target="#addModal">Add EP</button>
<table  id="ep-table">
        <thead class="thead-dark">
        <tr>    
        <th scope="col" class="hidden"></th>   
        <th scope="col">Name</th>        
        <th scope="col">EP Code</th>
        <th scope="col">Entry Class</th>
        <th scope="col"></th>              
        </tr>
        </thead>
        <tbody>
        @foreach($eps as $ep)
        <tr>
        <td class="hidden">{{$ep['id']}}</td>
        <td>{{$ep['name']}}</td>
        <td>{{$ep['entry_code']}}</td>
        <td>{{$ep['entry_class']}}</td>
        <td class="" >
        
            <button class="edit bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded">Edit</button>
            <button class="delete bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">Delete</button>      
       
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