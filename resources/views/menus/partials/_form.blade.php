<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Title</label>
        <div class="input-group input-group-outline">
            <input type="text" class="form-control" name="title" value="{{ old('title', $menu->title ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label d-flex justify-content-between align-items-end">
            <span>Icon (Material Symbols name)</span>
            <a href="https://fonts.google.com/icons" target="_blank" class="text-secondary text-sm">
                Find Icons
                <i class="material-symbols-rounded text-sm" style="vertical-align: middle;">open_in_new</i>
            </a>
        </label>
        <div class="input-group input-group-outline">
            <input type="text" class="form-control" name="icon" value="{{ old('icon', $menu->icon ?? '') }}" placeholder="e.g., dashboard">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Route Name</label>
        <div class="input-group input-group-outline">
            <input type="text" class="form-control" name="route" value="{{ old('route', $menu->route ?? '') }}" placeholder="e.g., users.index">
        </div>
    </div>
     <div class="col-md-6 mb-3">
        <label class="form-label">Required Permission</label>
        <div class="input-group input-group-outline">
            <select class="form-control" name="permission_name">
                <option value="">None</option>
                @foreach($permissions as $permission)
                    <option value="{{ $permission }}" {{ (old('permission_name', $menu->permission_name ?? '') == $permission) ? 'selected' : '' }}>
                        {{ $permission }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
     <div class="col-md-6 mb-3">
        <label class="form-label">Parent Menu</label>
        <div class="input-group input-group-outline">
            <select class="form-control" name="parent_id">
                <option value="">None (It's a Main Menu)</option>
                @foreach($parentMenus as $parent)
                    <option value="{{ $parent->id }}" {{ (old('parent_id', $menu->parent_id ?? '') == $parent->id) ? 'selected' : '' }}>
                        {{ $parent->title }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Order</label>
        <div class="input-group input-group-outline">
            <input type="number" class="form-control" name="order" value="{{ old('order', $menu->order ?? 0) }}" required>
        </div>
    </div>
</div>
