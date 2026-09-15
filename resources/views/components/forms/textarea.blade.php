@props(['label', 'name', 'value' => null, 'placeholder' => '', 'toolbar' => true])

<div class="field">
    <label for="{{ $name }}">{{ $label }}</label>
    <div class="wysiwyg" data-wysiwyg>
        @if($toolbar)
            <div class="wysiwyg-toolbar" role="toolbar" aria-label="{{ $label }} formatting">
                <button type="button" data-command="bold"><strong>B</strong></button>
                <button type="button" data-command="italic"><em>I</em></button>
                <button type="button" data-command="underline"><u>U</u></button>
                <button type="button" data-command="insertUnorderedList">• List</button>
                <button type="button" data-command="insertOrderedList">1. List</button>
                <button type="button" data-command="removeFormat">Clear</button>
            </div>
        @endif
        <div
            id="{{ $name }}_editor"
            class="wysiwyg-editor"
            contenteditable="true"
            role="textbox"
            aria-multiline="true"
            data-placeholder="{{ $placeholder }}"
        >{!! old($name, $value) !!}</div>
        <input type="hidden" id="{{ $name }}" name="{{ $name }}" value="{{ old($name, $value) }}">
    </div>
    @error($name)<small class="error">{{ $message }}</small>@enderror
</div>

@once
<style>
    .wysiwyg{border:1px solid #263246;border-radius:10px;overflow:hidden;background:#0b1222;transition:.2s}
    .wysiwyg:focus-within{border-color:#38bdf8;box-shadow:0 0 0 3px #38bdf81c}
    .wysiwyg-toolbar{display:flex;gap:4px;align-items:center;padding:7px;border-bottom:1px solid #263246;background:#111a2b;flex-wrap:wrap}
    .wysiwyg-toolbar button{border:0;border-radius:6px;background:transparent;color:#a7b3c7;padding:6px 9px;cursor:pointer;font-size:12px}
    .wysiwyg-toolbar button:hover{background:#1e293b;color:#fff}
    .wysiwyg-editor{min-height:150px;padding:12px;color:#fff;outline:0;line-height:1.6;overflow:auto}
    .wysiwyg-editor:empty:before{content:attr(data-placeholder);color:#526078;pointer-events:none}
    .wysiwyg-editor ul,.wysiwyg-editor ol{padding-left:24px}
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-wysiwyg]').forEach((wrapper) => {
            const editor = wrapper.querySelector('.wysiwyg-editor');
            const input = wrapper.querySelector('input[type="hidden"]');

            const sync = () => {
                input.value = editor.innerHTML;
            };

            wrapper.querySelectorAll('[data-command]').forEach((button) => {
                button.addEventListener('mousedown', (event) => event.preventDefault());
                button.addEventListener('click', () => {
                    editor.focus();
                    document.execCommand(button.dataset.command, false, null);
                    sync();
                });
            });

            editor.addEventListener('input', sync);
            sync();
        });
    });
</script>
@endonce
