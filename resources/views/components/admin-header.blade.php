<style>
.admin-header{display:flex;align-items:center;justify-content:space-between;gap:20px;margin-bottom:28px;padding:0 2px}
.admin-header .brand{display:flex;align-items:center;gap:13px;min-width:0}
.admin-header .brand-logo{width:42px;height:42px;object-fit:contain;display:block;flex:none}
.admin-header .brand-name{font-size:18px;font-weight:850;letter-spacing:-.02em;color:#f8fafc;white-space:nowrap}
.admin-header .profile{position:relative;flex:none}
.admin-header .profile-button{display:flex;align-items:center;gap:9px;border:1px solid #263246;border-radius:12px;background:#111827;color:#e2e8f0;padding:8px 11px;cursor:pointer;font:inherit;transition:.18s}
.admin-header .profile-button:hover,.admin-header .profile.open .profile-button{border-color:#3b82f6;background:#172033}
.admin-header .profile-avatar{width:30px;height:30px;border-radius:9px;background:#2563eb;color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:900}
.admin-header .profile-name{font-size:12px;font-weight:800}
.admin-header .profile-chevron{font-size:9px;color:#64748b;transition:transform .18s}
.admin-header .profile.open .profile-chevron{transform:rotate(180deg)}
.admin-header .profile-menu{position:absolute;right:0;top:calc(100% + 8px);width:150px;padding:6px;background:#111827;border:1px solid #263246;border-radius:12px;box-shadow:0 18px 45px #0008;display:none;z-index:200}
.admin-header .profile.open .profile-menu{display:block}
.admin-header .profile-menu form{margin:0}
.admin-header .logout-item{width:100%;display:flex;align-items:center;gap:9px;border:0;border-radius:8px;background:transparent;color:#cbd5e1;padding:10px 11px;cursor:pointer;text-align:left;font:inherit;font-size:12px;font-weight:750}
.admin-header .logout-item:hover{background:#1e293b;color:#fff}
@media(max-width:760px){
    .admin-header{margin-bottom:18px}
    .admin-header .brand-name{font-size:15px}
    .admin-header .profile-name{display:none}
    .admin-header .profile-button{padding:6px}
}
</style>

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