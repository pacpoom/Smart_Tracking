<tr>
    <td class="text-center">
        <div class="form-check d-flex justify-content-center">
            <input class="form-check-input menu-checkbox" type="checkbox" name="ids[]" value="{{ $menu->id }}">
        </div>
    </td>
    <td>
        <div class="d-flex align-items-center" style="padding-left: {{ $level * 20 }}px;">
            <i class="material-symbols-rounded text-sm">{{ $menu->icon ?? 'radio_button_unchecked' }}</i>
            <span class="ms-2 text-sm font-weight-bold">{{ $menu->title }}</span>
        </div>
    </td>
    <td><span class="text-xs font-weight-bold">{{ $menu->route }}</span></td>
    <td>
        @if($menu->permission_name)
            <span class="badge badge-sm bg-gradient-info">{{ $menu->permission_name }}</span>
        @endif
    </td>
    {{-- เพิ่มคอลัมน์ Order --}}
    <td class="align-middle text-center">
        <span class="text-secondary text-xs font-weight-bold">{{ $menu->order }}</span>
    </td>
    <td class="align-middle text-center">
        <a href="{{ route('menus.edit', $menu->id) }}" class="btn btn-link text-secondary mb-0" title="Edit">
            <i class="material-symbols-rounded">edit</i>
        </a>
        <button type="button" class="btn btn-link text-danger mb-0" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $menu->id }}" title="Delete">
            <i class="material-symbols-rounded">delete</i>
        </button>
    </td>
</tr>

{{-- Modal for single deletion --}}
<div class="modal fade" id="deleteModal-{{ $menu->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the menu '<strong>{{ $menu->title }}</strong>' and all its children?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Confirm Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@if ($menu->children->isNotEmpty())
    @foreach ($menu->children as $child)
        @include('menus.partials.menu-item', ['menu' => $child, 'level' => $level + 1])
    @endforeach
@endif
