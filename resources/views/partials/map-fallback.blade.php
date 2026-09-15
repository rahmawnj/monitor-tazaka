<script>
(function () {
    function getProjects() {
        return Array.isArray(window.__monitorProjects) && window.__monitorProjects.length
            ? window.__monitorProjects
            : (typeof initialProjects !== 'undefined' ? initialProjects : []);
    }

    function getLatLng(project) {
        const value = project?.latlong;
        if (!value) return null;

        let coords = value;
        if (typeof coords === 'string') {
            try { coords = JSON.parse(coords); } catch (_) { return null; }
        }

        const lat = Number(coords?.lat);
        const lng = Number(coords?.lng);
        if (!Number.isFinite(lat) || !Number.isFinite(lng)) return null;
        if (lat < -90 || lat > 90 || lng < -180 || lng > 180) return null;
        return [lat, lng];
    }

    function openProjectDetail(project) {
        const card = document.querySelector(`.project[data-project-id="${project.id}"]`);
        if (card) {
            card.click();
            return;
        }

        // Fallback: trigger the same project-detail event used by the monitor.
        window.dispatchEvent(new CustomEvent('monitor:open-project-detail', {
            detail: { project }
        }));
    }

    function initProjectMap() {
        const el = document.getElementById('projectMap');
        if (!el || typeof L === 'undefined') return;
        if (el._leaflet_id) return;

        const bandung = [-6.922222, 107.606944];
        const map = L.map(el, {
            zoomControl: true,
            attributionControl: true,
            scrollWheelZoom: false,
        }).setView(bandung, 9);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors',
        }).addTo(map);

        const markers = L.layerGroup().addTo(map);

        function renderMarkers(projects) {
            markers.clearLayers();
            const bounds = [];

            (projects || []).forEach(project => {
                const latlng = getLatLng(project);
                if (!latlng) return;

                const progress = Math.max(0, Math.min(100, Number(project.progress) || 0));
                const marker = L.marker(latlng).addTo(markers);
                marker.bindPopup(
                    '<strong>' + escapeHtml(project.name || '-') + '</strong>' +
                    '<br>' + escapeHtml(project.client || '-') +
                    '<br>Progress: ' + progress + '%'
                );

                marker.on('click', function () {
                    openProjectDetail(project);
                });

                bounds.push(latlng);
            });

            if (bounds.length === 1) {
                map.setView(bounds[0], 8);
            } else if (bounds.length > 1) {
                map.fitBounds(bounds, { padding: [30, 30], maxZoom: 8 });
            } else {
                map.setView(bandung, 9);
            }
        }

        renderMarkers(getProjects());

        window.addEventListener('monitor:projects-updated', function () {
            renderMarkers(getProjects());
            setTimeout(() => map.invalidateSize(), 50);
        });

        window.__projectMonitorMap = map;
        window.__renderProjectMap = renderMarkers;
        setTimeout(() => map.invalidateSize(), 100);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initProjectMap, { once: true });
    } else {
        initProjectMap();
    }
})();
</script>
