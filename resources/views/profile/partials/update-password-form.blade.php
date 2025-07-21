<section>
    <header>
        <h5 class="font-weight-bolder">
            Update Password
        </h5>
        <p class="mt-1 text-sm text-muted">
            Ensure your account is using a long, random password to stay secure.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4">
        @csrf
        @method('put')

        <div class="mb-3">
            <label class="form-label">Current Password</label>
            <div class="input-group input-group-outline">
                <input type="password" name="current_password" class="form-control" autocomplete="current-password">
            </div>
            @error('current_password', 'updatePassword') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">New Password</label>
            <div class="input-group input-group-outline">
                <input type="password" name="password" class="form-control" autocomplete="new-password">
            </div>
             @error('password', 'updatePassword') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <div class="input-group input-group-outline">
                <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
            </div>
        </div>

        <div class="d-flex align-items-center gap-4">
            <button type="submit" class="btn btn-dark">Save</button>

            @if (session('status') === 'password-updated')
                <p class="text-sm text-muted m-0">Saved.</p>
            @endif
        </div>
    </form>
</section>
