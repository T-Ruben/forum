<div class="border border-gray-600">
    <div class="flex gap-1 flex-wrap justify-center bg-gray-400 p-1 w-auto">
        <button type="button" onclick="insertBBCode('b')" class="cursor-pointer px-2 py-1 bg-gray-300 text-black hover:bg-gray-500 rounded">
            <strong>B</strong>
        </button>
        <button type="button" onclick="insertBBCode('i')" class="cursor-pointer px-2 py-1 bg-gray-300 text-black hover:bg-gray-500 rounded">
            <em>I</em>
        </button>
        <button type="button" onclick="insertBBCode('u')" class="cursor-pointer px-2 py-1 bg-gray-300 text-black hover:bg-gray-500 rounded">
            <u>U</u>
        </button>
        <button type="button" onclick="insertBBCode('s')" class="cursor-pointer px-2 py-1 bg-gray-300 text-black hover:bg-gray-500 rounded">
            <s>S</s>
        </button>
        <button type="button" onclick="insertBBCode('URL')" class="cursor-pointer px-2 py-1 bg-gray-300 text-black hover:bg-gray-500 rounded">
            URL
        </button>

    </div>

</div>

<script>
    function insertBBCode(tag) {
    const textarea = document.getElementById('content');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end);
    const before = textarea.value.substring(0, start);
    const after = textarea.value.substring(end);

    let insertion;
    if (selectedText) {
        insertion = `[${tag}]${selectedText}[/${tag}]`;
    } else {
        insertion = `[${tag}][/${tag}]`;
    }


    textarea.value = before + insertion + after;
    textarea.focus();
    textarea.setSelectionRange(start + tag.length + 2, start + insertion.length - tag.length - 3);
}

</script>
