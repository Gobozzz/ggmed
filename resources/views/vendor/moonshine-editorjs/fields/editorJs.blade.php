<div class="box">
    <x-moonshine::form.textarea
        :attributes="$attributes->merge([
            'name' => $attributes['name'],
            'data-type'=>'editor-js',
            'id' => $attributes['name'],
            'class' => 'hidden',
        ])->except('x-bind:id')"
    >{!! $value ?? '' !!}</x-moonshine::form.textarea>
    <div id="editorjs"></div>
</div>
<script>
    const editorJsConf = @php echo json_encode(config('moonshine-editor-js')['toolSettings']) @endphp;
</script>

@vite(['resources/js/editorJs/field.js', 'resources/css/editorJs/field.css'])
