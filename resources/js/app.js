import './bootstrap';


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


const fileInput = document.querySelector('#avatar');
const imagePreview = document.querySelector('#imagePreview');

fileInput.addEventListener('change', function(e) {
    const file = e.target.files[0];

    if(file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            imagePreview.src = e.target.result;
        };

        reader.readAsDataURL(file);
    }
});

