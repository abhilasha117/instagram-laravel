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
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial;
    background: #121212;
    color: #fff;
}

/* ========== DESKTOP DEFAULT (NORMAL BROWSER) ========== */
.app-container {
    max-width: 420px;
    margin: 30px auto;
    background: #000;
    border: 1px solid #333;
    border-radius: 12px;
    overflow: hidden;
}

/* TOP BAR */
.top-bar {
    padding: 12px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* PROFILE */
.profile-header {
    padding: 0 16px;
}

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
    gap: 2px;
    margin-top: 12px;
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

/* MODAL */
.post-modal {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.95);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.post-modal img {
    max-width: 90%;
    max-height: 90%;
}

.close-btn {
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 32px;
    cursor: pointer;
}

/* ========== MOBILE VIEW (ONLY WHEN INSPECT / SMALL WIDTH) ========== */
@media (max-width: 768px) {

    body {
        background: #000;
    }

    .app-container {
        max-width: 100%;
        margin: 0;
        border: none;
        border-radius: 0;
    }

    .profile-pic {
        width: 85px;
        height: 85px;
    }
}
/* ===== DESKTOP DEFAULT (LAPTOP VIEW) ===== */
body {
    background: #121212;
    margin: 0;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto;
}

.app-container {
    max-width: 1500px;        /* Instagram desktop width */
    margin: 30px auto;
    background: #000;
    border-radius: 8px;
}

/* Center profile header nicely */
.profile-header {
    padding: 24px;
}

/* Make grid larger on desktop */
.grid {
    gap: 6px;
}

/* ===== MOBILE VIEW ONLY ===== */
@media (max-width: 768px) {

    body {
        background: #000;
    }

    .app-container {
        max-width: 100%;
        margin: 0;
        border-radius: 0;
    }

    .profile-header {
        padding: 0 16px;
    }

    .grid {
        gap: 2px;
    }
}


    </style>
</head>

<body>
<div class="app-container">

    <div class="top-bar">
        <div><i class="fa-solid fa-lock"></i> {{ $profile['username'] }}</div>
        <div>
            <i class="fa-solid fa-plus me-3"></i>
            <div class="dropdown d-inline">
    <i class="fa-solid fa-bars"
       data-bs-toggle="dropdown"
       aria-expanded="false"
       style="cursor:pointer; font-size:18px;"></i>

    <ul class="dropdown-menu dropdown-menu-end"
        style="background:#121212; border:1px solid #333;">
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="dropdown-item text-danger">
                    Log out
                </button>
            </form>
        </li>
    </ul>
</div>

        </div>
    </div>

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
        @if($posts->count())
<div class="grid">
@foreach($posts as $post)
<a href="{{ url('/post/'.$profile['username'].'/'.$post['code']) }}"
   class="post-thumb">
    <img src="{{ $post['image'] }}">
</a>

@endforeach
</div>

@else
<p style="color:#888;text-align:center;margin-top:20px;">
    No posts available
</p>
@endif


    </div>
</div>


<script>

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
