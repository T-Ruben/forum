import Quill from 'quill';
import './bootstrap';


// Time
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.post-time').forEach(el => {
        const time = el.dataset.time;

        if (!time) return;

        const date = new Date(time);

            el.textContent = getRelativeTime(date);

});

function getRelativeTime(date) {
    const now = new Date();
    const diffMs = now - date;
    const diffSecs = Math.floor(diffMs / 1000);
    const diffMins = Math.floor(diffSecs / 60);
    const diffHours = Math.floor(diffMins / 60);
    const diffDays = Math.floor(diffHours / 24);

    const timeString = date.toLocaleTimeString(undefined, {
        hour: 'numeric',
        minute: '2-digit'
    });

    const isToday = now.toDateString() === date.toDateString();

    const yesterday = new Date(now);
    yesterday.setDate(now.getDate() - 1);
    const isYesterday = yesterday.toDateString() === date.toDateString();

    if (diffSecs < 10) return 'A moment ago';
    if (diffSecs < 60) return 'A few seconds ago';
    if (diffMins < 60) return `${diffMins} minute${diffMins !== 1 ? 's' : ''} ago`;
    if (isToday) {
        return `Today at ${timeString}`;
    };

    if (isYesterday) {
        return `Yesterday at ${timeString}`;
    }

    if (diffDays < 7) return `${diffDays} day${diffDays !== 1 ? 's' : ''} ago`;
    if (diffDays < 30) return `${Math.floor(diffDays / 7)} week${Math.floor(diffDays / 7) !== 1 ? 's' : ''} ago`;

    return date.toLocaleDateString(undefined, {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
     });
}
});

// Fixed position reload post
document.querySelectorAll('.formReload').forEach(form => {
    form.addEventListener('submit', function (e) {
        sessionStorage.setItem('scrollPosition', window.scrollY);
    })
})


window.addEventListener('load', function () {
    const scrollPosition = sessionStorage.getItem('scrollPosition');
    if (scrollPosition !== null) {
        window.scrollTo(0, parseInt(scrollPosition));
        sessionStorage.removeItem('scrollPosition');
    }
})

// Avatar change
const avatarChange = document.querySelector('#avatarChange');
if(avatarChange) {
avatarChange.addEventListener('click', () => {
    const avatarEditor = document.querySelector('#avatarEditor');
    const editorContent = document.querySelector('#editorContent');
    const closeBtn = document.querySelector('#closeBtn');

    avatarEditor.classList.replace('hidden', 'flex');

    closeBtn.addEventListener('click', () => {
       avatarEditor.classList.replace('flex', 'hidden');
    })

    const outsideClickHandler = (event) => {
        if (!editorContent.contains(event.target) && event.target !== document.querySelector('#avatarChange')) {

            avatarEditor.classList.replace('flex', 'hidden');

            document.removeEventListener('click', outsideClickHandler);
        }
    };
    setTimeout(() => {
        document.addEventListener('click', outsideClickHandler);
    }, 0);

        const fileInput = document.querySelector('#avatar');
    const imagePreview = document.querySelector('#imagePreview');

fileInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if(!file) return;

    if(file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            imagePreview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});
});
};

// Textarea auto height

if(document.querySelector('#content')){
document.querySelector('#content').addEventListener('input', function () {
    this.style.height = 'auto';
    this.style.height = this.scrollHeight + 'px';
})}

// Quill

const toolbarOptions = [
  ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
  ['link', 'image', 'video'],

  [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
  [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
  [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent

  [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
  [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

  [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
  [{ 'font': [] }],
  [{ 'align': [] }],

  ['clean']                                         // remove formatting button
];

document.addEventListener('DOMContentLoaded', () => {
const editorDiv = document.querySelector('#editor-container')
const hiddenInput = document.querySelector('#content');
const form = document.querySelector('#postForm');

if (!editorDiv || !hiddenInput || !form) return;

if(!editorDiv ) {
    return;
}

const quill = new Quill('#editor-container', {
    theme: 'snow',
    syntax: true,
    modules: {
        toolbar: '#toolbar-container',
    },
    placeholder: 'Write your reply...',
});

if(hiddenInput.value.trim().length > 0) {
    quill.root.innerHTML = hiddenInput.value;
}

form.addEventListener('submit', () => {
        let html = quill.root.innerHTML.trim();

        if (html === '<p><br></p>') {
            html = '';
        }

        hiddenInput.value = html;
})

})
