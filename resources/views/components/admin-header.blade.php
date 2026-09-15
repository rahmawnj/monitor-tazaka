<div class="top admin-header">
    <div class="brand">
        <img class="brand-logo" src="https://tazaka.co.id/assets/logo-tazaka-PPqkEZXu.png" alt="Logo Tazaka">
        <div>
            <div class="brand-name">Tazaka Elektrik Teknologi</div>
        </div>
    </div>
    <div class="profile" id="profile">
        <button type="button" class="profile-button" id="profileButton" aria-expanded="false">
            <span class="profile-avatar">A</span>
            <span class="profile-name">Admin</span>
            <span class="profile-chevron">▼</span>
        </button>
        <div class="profile-menu">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-item"><span>↪</span> Logout</button>
            </form>
        </div>
    </div>
</div>
<script>
(() => {
    const profile = document.getElementById('profile');
    const profileButton = document.getElementById('profileButton');
    if (!profile || !profileButton) return;
    profileButton.addEventListener('click', event => {
        event.stopPropagation();
        const open = profile.classList.toggle('open');
        profileButton.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    document.addEventListener('click', () => {
        profile.classList.remove('open');
        profileButton.setAttribute('aria-expanded', 'false');
    });
})();
</script>