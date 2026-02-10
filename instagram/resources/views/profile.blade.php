<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
/* ========== RESET ========== */
* { box-sizing: border-box; }

body {
    margin: 0;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto;
    background: #121212;
    color: #fff;
}

/* ========== CONTAINER ========== */
.app-container {
    max-width: 1500px;
    margin: 30px auto;
    background: #000;
    border-radius: 8px;
}

/* TOP BAR */
.top-bar {
    padding: 12px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* PROFILE */
.profile-header { padding: 24px; }

.profile-row {
    display: flex;
    align-items: center;
    margin-top: 8px;
}

.profile-pic {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    object-fit: cover;
}

.stats {
    flex: 1;
    display: flex;
    justify-content: space-around;
    text-align: center;
    font-size: 13px;
}

.stats strong {
    display: block;
    font-size: 15px;
}

.bio {
    margin-top: 10px;
    font-size: 14px;
}

/* GRID */
.grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 6px;
    margin-top: 12px;
    margin-bottom: 60px; /* allow scroll space */
}

.post-thumb {
    aspect-ratio: 1 / 1;
    overflow: hidden;
}

.post-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* MOBILE */
@media (max-width: 768px) {
    .app-container {
        max-width: 100%;
        margin: 0;
        border-radius: 0;
    }
    .profile-header { padding: 0 16px; }
    .grid { gap: 2px; }
}
html, body {
    height: auto;
    min-height: 100%;
}
.grid {
    margin-bottom: 120px;
}


    </style>
</head>
<body>
<div class="app-container">

    <!-- TOP BAR -->
    <div class="top-bar">
        <div><i class="fa-solid fa-lock"></i> {{ $profile['username'] }}</div>
        <div>
            <i class="fa-solid fa-plus me-3"></i>

            <div class="dropdown d-inline">
                <i class="fa-solid fa-bars"
                   data-bs-toggle="dropdown"
                   style="cursor:pointer;"></i>

                <ul class="dropdown-menu dropdown-menu-end"
                    style="background:#121212;border:1px solid #333;">
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                Log out
                            </button>
                        </form>
                    </li>

                    <li>
                        <a href="/history" class="dropdown-item text-light">
                            Order History
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- PROFILE HEADER -->
    <div class="profile-header">
        <div class="profile-row">
            <img src="{{ $profile['profile_pic_url_hd'] }}" class="profile-pic">

            <div class="stats">
                <div><strong>{{ number_format($profile['edge_owner_to_timeline_media']['count']) }}</strong>Posts</div>
                <div><strong>{{ number_format($profile['edge_followed_by']['count']) }}</strong>Followers</div>
                <div><strong>{{ number_format($profile['edge_follow']['count']) }}</strong>Following</div>
            </div>
        </div>

        <div class="bio">
            <strong>{{ $profile['full_name'] }}</strong><br>
            <p>{{ $profile['biography'] }}</p>
        </div>

        <!-- POSTS GRID -->
        @if($posts->count())
        <div class="grid"
             id="postGrid"
             data-username="{{ $profile['username'] }}"
             data-cursor="{{ $cursor }}"
             data-has-next="{{ $hasNext ? '1' : '0' }}">

            @foreach($posts as $post)
                <a href="{{ url('/post/'.$profile['username'].'/'.$post['code']) }}"
                   class="post-thumb"
                   data-post="{{ $post['code'] }}">
                    <img src="{{ $post['image'] }}">
                </a>
            @endforeach

        </div>
        @else
            <p style="color:#888;text-align:center;margin-top:20px;">
                No posts available
            </p>
        @endif

        <!-- LOADER -->
        <div id="loader" style="display:none;text-align:center;padding:20px;">
            <div class="spinner-border text-light"></div>
            <p style="margin-top:8px;color:#aaa;">Loading posts...</p>
        </div>

    </div>
</div>

<script>
let loading = false;

window.addEventListener("scroll", function () {

    const grid = document.getElementById("postGrid");
    const loader = document.getElementById("loader");

    if (!grid) return;
    if (loading) return;

    // use cursor instead of hasNext flag
    if (!grid.dataset.cursor) return;

    const reachedBottom =
        window.innerHeight + window.scrollY >=
        document.body.offsetHeight - 200;

    if (!reachedBottom) return;

    loading = true;
    loader.style.display = "block";

    const username = grid.dataset.username;
    const cursor = grid.dataset.cursor;

    fetch(`/profile/${username}/load-more?cursor=${cursor}`)
        .then(res => res.json())
        .then(data => {

            if (!data.posts || data.posts.length === 0) {
                grid.dataset.cursor = "";
                loader.style.display = "none";
                loading = false;
                return;
            }

            data.posts.forEach(post => {

                if (document.querySelector(`[data-post="${post.code}"]`)) {
                    return;
                }

                const link = document.createElement("a");
                link.href = `/post/${username}/${post.code}`;
                link.className = "post-thumb";
                link.dataset.post = post.code;

                const img = document.createElement("img");
                img.src = post.image;

                link.appendChild(img);
                grid.appendChild(link);
            });

            grid.dataset.cursor = data.next_cursor || "";

            loader.style.display = "none";
            loading = false;
        })
        .catch(() => {
            loader.style.display = "none";
            loading = false;
        });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
