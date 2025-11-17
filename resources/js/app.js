import Croppie from 'croppie';
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

// document.addEventListener('DOMContentLoaded', () => {
//     let croppie = null;

//     const fileInput = document.querySelector('#avatar');
//     const imagePreview = document.querySelector('#imagePreview');
//     const croppedImage = document.querySelector('#croppedImage');
//     const croppieContainer = document.querySelector('#croppie-container');
//     const hiddenInput = document.querySelector('#cropped_image');

// fileInput.addEventListener('change', function(e) {
//     const file = e.target.files[0];
//     if(!file) return;

//     if(croppie) {
//         croppie.destroy();
//         croppie = null;
//     }

//     croppie = new Croppie(croppieContainer, {
//         viewport: { width: 128, height: 128, type: 'square' },
//         boundary: { width: 160, height: 160 },
//         enableZoom: true,
//         showZoomer: false,
//         mouseWheelZoom: true
//     })

//     if(file) {
//         const reader = new FileReader();

//         reader.onload = function (e) {

//             croppie.bind({url: e.target.result}).catch(function(err) {
//                 console.error('Croppier bind error: ', err);
//             });
//             imagePreview.src = e.target.result;
//         };

//         reader.readAsDataURL(file);
//     }
// });
// })

document.addEventListener('DOMContentLoaded', function () {
    let croppie = null;

    const croppieContainer = document.querySelector('#croppieContainer');
    const cropBtn = document.querySelector('#cropBtn');
    const fileInput = document.querySelector('#avatarInput');
    const hiddenInput = document.querySelector('#croppedImageInput');
    const imagePreview = document.querySelector('#imagePreview');
    const avatarForm = document.querySelector('#avatarForm');

    fileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if(!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            const dataUrl = e.target.result;

            imagePreview.src = dataUrl;

            if(croppie) {
                croppie.destroy();
                croppie = null;
            }

            croppie = new Croppie(croppieContainer, {
                viewport: { width: 128, height: 128, type: 'square' },
                boundary: { width: 160, height: 160 },
                enableZoom: true,
                showZoomer: true,
                mouseWheelZoom: true,
                enableOrientation: true,
            })

            croppie.bind({url: dataUrl, orientation: 1}).catch(function (err) {
                console.log('Croppie bind error: ', err);
            })
        }
        reader.readAsDataURL(file);
    });

    avatarForm.addEventListener("submit", async function (e) {
        const base64 = await croppie.result({
            type: "base64",
            size: { width: 128, height: 128}
        })

        hiddenInput.value = base64;
    })
})
