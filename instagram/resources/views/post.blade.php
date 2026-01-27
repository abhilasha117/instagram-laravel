<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Post</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
:root { --accent:#ff4d6d; }

body {
    background:#000;
    color:#fff;
    margin:0;
    font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto;
}

.post-container { max-width:420px; margin:auto; }

.post-header {
    display:flex;
    justify-content:space-between;
    padding:12px 14px;
    border-bottom:1px solid #222;
}

.post-image img {
    width:100%;
    aspect-ratio:1/1;
    object-fit:cover;
}

.post-actions {
    display:flex;
    justify-content:space-between;
    padding:10px 14px 6px;
    font-size:20px;
}

.post-actions i { margin-right:14px; cursor:pointer; }

.heart-active {
    color:var(--accent);
    transform:scale(1.2);
    transition:0.2s ease;
}

.post-likes { padding:2px 14px; font-size:15px; font-weight:600; }

.post-caption { padding:6px 14px 10px; color:#ddd; }

.post-time { padding:0 14px 14px; font-size:11px; color:#999; }

.boost-box {
    background:#0f0f0f;
    border-radius:12px;
    padding:14px;
    border:1px solid #222;
}

.dropdown-menu {
    background:#121212;
    border:1px solid #222;
    border-radius:8px;
}

.dropdown-item {
    color:#fff;
    padding:10px 16px;
}

.dropdown-item:hover { background:#1f1f1f; }

#likeDropdown {
    background:#262626;
    border:1px solid #333;
    color:var(--accent);
}

#likeDropdown:disabled { opacity:0.6; }

#boostPreview { color:#aaa; }
#likeFloat {
    position: fixed;
    bottom: 160px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 22px;
    font-weight: 700;
    color: #ff4d6d;
    opacity: 0;
    pointer-events: none;
    z-index: 99999;
}

.like-animate {
    animation: likeFloatAnim 1.2s ease-out forwards;
}

@keyframes likeFloatAnim {
    0% {
        opacity: 0;
        transform: translate(-50%, 20px);
    }
    20% {
        opacity: 1;
    }
    100% {
        opacity: 0;
        transform: translate(-50%, -60px);
    }
}



.like-animate {
    animation:floatUp 1s ease-out forwards;
}

@keyframes floatUp {
    0% { opacity:0; transform:translate(-50%,0); }
    20% { opacity:1; }
    100% { opacity:0; transform:translate(-50%,-40px); }
}
</style>
</head>

<body>

<div class="post-container">
<div class="post-modal" id="postModal">
    <button id="backBtn" class="btn btn-dark" style="
        position: absolute;
        top: 15px;
        left: 20px;
        z-index: 9999;
        padding: 6px 12px;
        font-size: 14px;
        border-radius: 6px;
    ">← Back</button>

    <span class="close-btn" id="closePost">&times;</span>
    <div id="postContent"></div>
</div>

    <div class="post-header">
        <strong>{{ $username }}</strong>
        <i class="fa-solid fa-ellipsis"></i>
    </div>

    <div class="post-image">
        <img src="{{ $post['image'] }}">
    </div>

    <div class="post-actions">
        <div>
            <i class="fa-regular fa-heart"></i>
            <i class="fa-regular fa-comment"></i>
            <i class="fa-regular fa-paper-plane"></i>
        </div>
        <i class="fa-regular fa-bookmark"></i>
    </div>

    <div class="post-likes">{{ number_format($post['likes']) }} likes</div>

    <div class="post-caption">
        <strong>{{ $username }}</strong> {{ $post['caption'] }}
    </div>

    <div class="post-time">2 days ago</div>

    <!-- BOOST -->
    <div class="dropdown w-100 mt-3 px-3">
        <button class="btn w-100 likeDropdown" data-bs-toggle="dropdown">
            Boost ❤️
        </button>

        <ul class="dropdown-menu w-100">
            <li>
                <a class="dropdown-item boost-option" href="#" data-likes="10">❤️ +10 Likes — ₹10</a>
            </li>
            <li>
                <a class="dropdown-item boost-option" href="#" data-likes="50">❤️ +50 Likes — ₹45</a>
            </li>
            <li>
                <a class="dropdown-item boost-option" href="#" data-likes="100">❤️ +100 Likes — ₹80</a>
            </li>
        </ul>
    </div>

    <div class="text-center small mt-2 boostPreview">
        🚀 Boost your post visibility
    </div>

    <div class="likeFloat"></div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.querySelectorAll('.post-container').forEach(container => {
    const likesDiv    = container.querySelector('.post-likes');
    const previewText = container.querySelector('.boostPreview');
    const likeBtn     = container.querySelector('.likeDropdown');
    const likeFloat   = container.querySelector('.likeFloat');

    if (!likesDiv || !previewText || !likeBtn || !likeFloat) return;

    const dropdown = bootstrap.Dropdown.getOrCreateInstance(likeBtn);

    let currentLikes = parseInt(likesDiv.innerText.replace(/\D/g, '')) || 0;

    function showLikeFloat(amount) {
        likeFloat.innerText = `❤️ +${amount} Likes`;
        likeFloat.classList.remove('like-animate');
        void likeFloat.offsetWidth; // trigger reflow
        likeFloat.classList.add('like-animate');
    }

    container.querySelectorAll('.boost-option').forEach(option => {
        option.addEventListener('click', function (e) {
            e.preventDefault();

            const increment = parseInt(this.dataset.likes);

            currentLikes += increment;
            likesDiv.innerText = currentLikes.toLocaleString() + ' likes';

            showLikeFloat(increment);

            // Close the dropdown
            dropdown.hide();

            // UI feedback
            likeBtn.disabled = true;
            likeBtn.innerText = 'Boosting...';
            previewText.innerText = `✅ Boosted +${increment} likes`;

            setTimeout(() => {
                likeBtn.disabled = false;
                likeBtn.innerText = 'Boost ❤️';
                previewText.innerText = '🚀 Boost your post visibility';
            }, 1000);
        });
    });
});
const backBtn = document.getElementById('backBtn');
backBtn.addEventListener('click', () => {
    history.back();
});

</script>
</body>
</html>