@csrf
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Container No.</label>
        <div class="input-group input-group-outline">
            <input type="text" class="form-control" name="container_no" value="{{ old('container_no', $container->container_no ?? '') }}" required>
        </div>
        @error('container_no') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Size</label>
        <div class="input-group input-group-outline">
            <input type="text" class="form-control" name="size" value="{{ old('size', $container->size ?? '') }}" placeholder="e.g., 20ft, 40ft">
        </div>
        @error('size') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Agent</label>
    <div class="input-group input-group-outline">
        <input type="text" class="form-control" name="agent" value="{{ old('agent', $container->agent ?? '') }}">
    </div>
    @error('agent') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
</div>
