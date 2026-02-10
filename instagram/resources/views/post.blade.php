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
/* ===== DESKTOP DEFAULT (LAPTOP VIEW) ===== */
body {
    background: #121212;
    margin: 50px;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto;
}

.app-container {
    max-width: 1800px;        /* Instagram desktop width */
    margin: 50px auto;
    background: #000;
    border-radius: 10px;
}

/* Center profile header nicely */
.post-header {
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
<div class="post-wrapper">
<div class="post-container">

    <button id="backBtn" class="btn btn-dark"
        style="position:absolute;top:15px;left:20px;z-index:9999;">
        ← Back
    </button>

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

    <!-- BOOST BUTTONS -->
    <div class="boost-box mt-3 mx-3 text-center">
        <button class="btn btn-dark w-100 mb-2" onclick="openBoostModal('like')">
            ❤️ Boost Likes
        </button>
        <button class="btn btn-dark w-100 mb-2" onclick="openBoostModal('comment')">
            💬 Boost Comments
        </button>
        <button class="btn btn-dark w-100" onclick="openBoostModal('share')">
            ✈️ Boost Shares
        </button>

        <div class="small mt-2 text-muted">
            🚀 Boost your post visibility
        </div>
    </div>
</div>
</div>

<!-- FLOAT TEXT -->
<div class="likeFloat"></div>

<!-- BOOST MODAL -->
<div class="modal fade" id="boostModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white rounded-4">

      <!-- HEADER -->
      <div class="modal-header border-0">
        <h5 class="modal-title fw-semibold" id="boostModalTitle">
          Boost Post
        </h5>
        <button type="button" class="btn-close btn-close-white"
                data-bs-dismiss="modal"></button>
      </div>

      <!-- BODY -->
      <div class="modal-body">
        <input type="hidden" id="boostType">

        <!-- Quantity Input -->
        <div class="mb-3">
          <label class="form-label small text-muted">
            Enter Quantity
          </label>
          <input
            type="number"
            id="boostQuantity"
            class="form-control bg-black text-white border-secondary"
            placeholder="e.g. 1000"
            min="100"
            step="100"
            oninput="calculateBoostAmount()"
          />
          <small class="text-muted">
            Minimum 100 • ₹18 per 1000
          </small>
        </div>

        <!-- Price Box -->
        <div class="p-3 rounded-3 border border-secondary bg-black">
          <div class="d-flex justify-content-between mb-1">
            <span class="text-muted">Base price</span>
            <span>₹18 / 1000</span>
          </div>

          <div class="d-flex justify-content-between fw-semibold">
            <span>Total amount</span>
            <span>₹<span id="boostAmount">0</span></span>
          </div>
        </div>
      </div>

      <!-- FOOTER -->
      <div class="modal-footer border-0">
        <button class="btn btn-success w-100 fw-semibold"
                onclick="confirmBoost()">
          Confirm Boost
        </button>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
/* ===============================
   CONFIG
================================ */
const BASE_PRICE = 18; // ₹18 per 1000
let boostModalInstance = null;

/* ===============================
   OPEN BOOST MODAL
================================ */
function openBoostModal(type) {
    document.getElementById('boostType').value = type;

    const titleMap = {
        like: 'Confirm Likes Boost',
        comment: 'Confirm Comments Boost',
        share: 'Confirm Shares Boost'
    };

    document.getElementById('boostModalTitle').innerText = titleMap[type];

    document.getElementById('boostQuantity').value = '';
    document.getElementById('boostAmount').innerText = '0';

    const modal = new bootstrap.Modal(document.getElementById('boostModal'));
    modal.show();
}

/* ===============================
   CALCULATE TOTAL AMOUNT (LIVE)
================================ */
function calculateBoostAmount() {
    const qtyInput = document.getElementById('boostQuantity');
    const qty = parseInt(qtyInput.value) || 0;

    if (qty <= 0) {
        document.getElementById('boostAmount').innerText = '0';
        return;
    }

    // ₹18 per 1000 logic
    const total = (qty / 1000) * BASE_PRICE;

    document.getElementById('boostAmount').innerText = total.toFixed(2);
}

/* ===============================
   CONFIRM BOOST (API CALL)
================================ */
function confirmBoost() {
    // ✅ CLOSE MODAL IMMEDIATELY
    const modalEl = document.getElementById('boostModal');
    const modalInstance = bootstrap.Modal.getInstance(modalEl);
    modalInstance.hide();

    // OPTIONAL: still call API in background
    const type = document.getElementById('boostType').value;
    const qty  = parseInt(document.getElementById('boostQuantity').value) || 0;
    const amount = (qty / 1000) * BASE_PRICE;

    fetch("{{ route('post.boost', [$post['id'], $username]) }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            type: type,
            quantity: qty,
            amount: amount
        })
    }).catch(() => {
        console.log('API failed, modal already closed');
    });
}


/* ===============================
   FLOATING UI FEEDBACK
================================ */
function showBoostToast(type, qty, amount) {
    let text = '';

    if (type === 'like') {
        text = `❤️ +${qty} Likes`;
        updateLikes(qty);
    } else if (type === 'comment') {
        text = `💬 +${qty} Comments`;
    } else if (type === 'share') {
        text = `✈️ +${qty} Shares`;
    }

    const floatDiv = document.querySelector('.likeFloat');
    if (!floatDiv) return;

    floatDiv.innerText = text;
    floatDiv.classList.remove('like-animate');
    void floatDiv.offsetWidth; // reflow
    floatDiv.classList.add('like-animate');
}

/* ===============================
   UPDATE LIKES (UI ONLY)
================================ */
function updateLikes(increment) {
    const likesDiv = document.querySelector('.post-likes');
    if (!likesDiv) return;

    let currentLikes = parseInt(
        likesDiv.innerText.replace(/\D/g, '')
    ) || 0;

    currentLikes += increment;
    likesDiv.innerText = currentLikes.toLocaleString() + ' likes';
}

/* ===============================
   BACK BUTTON
================================ */
document.getElementById('backBtn')?.addEventListener('click', () => {
    history.back();
});
</script>

</body>
</html>