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
.admin-project-search{display:inline-flex;align-items:center;gap:7px;margin-left:auto}
.admin-project-search input{width:210px;height:38px;padding:0 12px;border:1px solid #263246;border-radius:10px;background:#0d1424;color:#e2e8f0;outline:none;font:inherit;font-size:12px}
.admin-project-search input::placeholder{color:#64748b}
.admin-project-search input:focus{border-color:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,.12)}
.admin-project-search button{height:38px;padding:0 13px;border:0;border-radius:10px;background:#2563eb;color:#fff;cursor:pointer;font:inherit;font-size:12px;font-weight:800}
.admin-project-search button:hover{background:#1d4ed8}
@media(max-width:760px){
    .admin-header{margin-bottom:18px}
    .admin-header .brand-name{font-size:15px}
    .admin-header .profile-name{display:none}
    .admin-header .profile-button{padding:6px}
    .admin-project-search{width:100%;margin:0}
    .admin-project-search input{flex:1;width:auto}
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
    const init = () => {
        const profile = document.getElementById('profile');
        const profileButton = document.getElementById('profileButton');

        if (profile && profileButton) {
            profileButton.addEventListener('click', event => {
                event.stopPropagation();
                const open = profile.classList.toggle('open');
                profileButton.setAttribute('aria-expanded', open ? 'true' : 'false');
            });

            document.addEventListener('click', () => {
                profile.classList.remove('open');
                profileButton.setAttribute('aria-expanded', 'false');
            });
        }

        if (window.location.pathname === '/admin/projects') {
            const tabs = document.querySelector('.tabs');
            if (tabs && !tabs.querySelector('.admin-project-search')) {
                const params = new URLSearchParams(window.location.search);
                const currentSearch = params.get('search') || '';
                const form = document.createElement('form');
                form.className = 'admin-project-search';
                form.method = 'GET';
                form.action = '/admin/projects';
                form.innerHTML = `<input type="hidden" name="tab" value="all"><input type="search" name="search" value="${currentSearch.replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;')}" placeholder="Cari project..." aria-label="Cari project"><button type="submit">Cari</button>`;
                tabs.appendChild(form);
            }
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init, { once: true });
    } else {
        init();
    }
})();
</script>