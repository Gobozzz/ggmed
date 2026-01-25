@props([
    'files' => []
])
<div class="flex items-center">
    @foreach($files as $file)
        <video class="w-full" style="max-width: 150px;min-width: 150px; height: 150px;border-radius: 8px;"
               src="{{ $file['full_path'] }}"
               controls muted
               preload="metadata"></video>
    @endforeach
</div>
