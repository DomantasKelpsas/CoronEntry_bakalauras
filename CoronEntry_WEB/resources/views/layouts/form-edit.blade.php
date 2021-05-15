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
            <select type="text" class="entry-class" name="entry-class" id="entry-class">
            <option value="Low">Low</option>
            <option value="Intermediate">Intermediate</option>
            <option value="High">High</option>
            </select>
          </div>     
          @if(Route::is('epmng'))
          <div class="form-group">
            <label for="name" class="col-form-label">Name:</label>
            <input type="text" class="name" name="name" id="name">
          </div>
          <div class="form-group">
            <label for="userlimit" class="col-form-label">Max User Count:</label>
            <input type="number" class="userlimit" name="userlimit" id="userlimit" min="0">
            <label for="userlimit-check" class="col-form-label">Unlimited</label>
            <input type="checkbox" class="userlimit-check" name="userlimit-check" id="userlimit-check">
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