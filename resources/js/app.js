import './bootstrap';
import { Bars3Icon, BellIcon, XMarkIcon } from '@heroicons/vue/24/outline';

// Time
window.getRelativeTime = function(date) {
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
    if (isToday) return `Today at ${timeString}`;
    if (isYesterday) return `Yesterday at ${timeString}`;
    if (diffDays < 7) return `${diffDays} day${diffDays !== 1 ? 's' : ''} ago`;
    if (diffDays < 30) return `${Math.floor(diffDays / 7)} week${Math.floor(diffDays / 7) !== 1 ? 's' : ''} ago`;

    return date.toLocaleDateString(undefined, {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.post-time:not([x-data])').forEach(el => {
        const time = el.dataset.time;
        if (time) el.textContent = getRelativeTime(new Date(time));
    });
});

// Fixed position reload post
document.querySelectorAll('.formReload').forEach(form => {
    form.addEventListener('click', function (e) {
        sessionStorage.setItem('scrollPosition', window.scrollY);
    })
})

// Fixed position reload reply
document.querySelectorAll('.replyReload').forEach(reply => {
    reply.addEventListener('click', function (e) {
        sessionStorage.setItem('scrollPosition', window.scrollY = document.body.scrollHeight);
    })
})

window.addEventListener('scroll-to-form', () => {
    document.getElementById('postForm')?.scrollIntoView({
        behavior: 'smooth',
        block: 'center'
    });
});


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

// Posting

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
