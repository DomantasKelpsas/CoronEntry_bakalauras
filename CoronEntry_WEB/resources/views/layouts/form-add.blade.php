<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{Route::currentRouteName()}}/add" method="POST" id="addForm">
        {{csrf_field()}}
       {{ method_field('POST')}}
       @if(Route::is('epmng'))
          <div class="form-group">
            <label for="name" class="col-form-label">Name:</label>
            <input type="text" class="name" name="name" id="name">
          </div>   
          <div class="form-group">
            <label for="entry-code" class="col-form-label">Code:</label>
            <input type="text" class="entry-code" name="code" id="entry-code">
          </div> 
          @elseif(Route::is('usermng'))
          <!-- <div class="form-group">
            <label for="email" class="col-form-label">Email:</label>
            <input type="text" class="email" name="email" id="email">
          </div>  -->
          <div class="form-group">
            <label for="entry-code" class="col-form-label">Code:</label>
            <select type="text" class="entry-code" name="code" id="entry-code">
           
            @foreach($data['unassigned_users'] as $user)
            <option value="{{$user->user_code}}">{{$user->user_code}} Email: [{{$user->email}}] Name: [{{$user->name}}]</option>
            @endforeach
            </select>
          </div> 
          @endif
          
          <div class="form-group">
            <label for="entry-class" class="col-form-label">Entry Class:</label>
            <select type="text" class="entry-class" name="entry-class" id="entry-class">
            <option value="Low">Low</option>
            <option value="Intermediate">Intermediate</option>
            <option value="High">High</option>
            </select>
          </div>              
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        @if(Route::is('usermng'))
        <button type="submit" class="btn btn-primary" form="addForm">Save User</button>
        @elseif(Route::is('epmng'))
        <button type="submit" class="btn btn-primary" form="addForm">Save EntryPoint</button>
        @endif
      </div>
    </div>
  </div>
</div>