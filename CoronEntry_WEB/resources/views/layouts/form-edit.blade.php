<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="/" method="POST" id="editForm">
        {{csrf_field()}}
       {{ method_field('PUT')}}
          <div class="form-group">
            <label for="entry-class" class="col-form-label">Entry Class:</label>
            <input type="text" class="entry-class" name="entry-class" id="entry-class">
          </div>     
          @if(Route::is('epmng'))
          <div class="form-group">
            <label for="name" class="col-form-label">Name:</label>
            <input type="text" class="name" name="name" id="name">
          </div>     
          @endif
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        @if(Route::is('usermng'))
        <button type="submit" class="btn btn-primary" form="editForm">Save User</button>
        @elseif(Route::is('epmng'))
        <button type="submit" class="btn btn-primary" form="editForm">Save EntryPoint</button>
        @endif
      </div>
    </div>
  </div>
</div>