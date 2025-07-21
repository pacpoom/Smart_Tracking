<section>
    <header>
        <h5 class="font-weight-bolder">
            Profile Information
        </h5>
        <p class="mt-1 text-sm text-muted">
            Update your account's profile information and email address.
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label class="form-label">Name</label>
            <div class="input-group input-group-outline">
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required autofocus>
            </div>
            @error('name') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <div class="input-group input-group-outline">
                 <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>
             @error('email') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
        </div>

        <div class="d-flex align-items-center gap-4">
            <button type="submit" class="btn btn-dark">Save</button>

            @if (session('status') === 'profile-updated')
                <p class="text-sm text-muted m-0">Saved.</p>
            @endif
        </div>
    </form>
</section>
