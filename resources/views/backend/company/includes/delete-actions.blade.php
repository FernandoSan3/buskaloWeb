    <div class="btn-group" role="group" aria-label="@lang('labels.backend.access.users.user_actions')">
        <a href="{{ route('admin.company.restore', $user->id) }}" name="confirm_item" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Restore">
            <i class="fas fa-sync"></i>
        </a>

        <a href="{{ route('admin.company.delete-permanently', $user->id) }}" name="confirm_item" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="@lang('buttons.backend.access.users.delete_permanently')">
            <i class="fas fa-trash"></i>
        </a>
    </div>
