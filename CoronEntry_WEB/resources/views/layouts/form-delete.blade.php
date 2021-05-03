<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="/" method="POST" id="deleteForm">
        {{csrf_field()}}
       {{ method_field('DELETE')}}
          <div class="form-group">
           <h1>Please Confirm Delete Action!</h1>
          </div>        
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        @if(Route::is('usermng'))
        <button type="submit" class="btn btn-primary" form="deleteForm">Remove User</button>
        @elseif(Route::is('epmng'))
        <button type="submit" class="btn btn-primary" form="deleteForm">Remove EntryPoint</button>
        @endif
      </div>
    </div>
  </div>
</div>