/**
 * Hakajobspro main.js
 * Mobile nav toggle, tab, scroll-to-top, filter form.
 */
(function () {
    'use strict';

    // Mobile nav
    const toggle  = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');
    toggle?.addEventListener('click', () => {
        const open = navMenu.classList.toggle('open');
        toggle.setAttribute('aria-expanded', open);
    });

    // Escape menutup nav
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') navMenu?.classList.remove('open');
    });

    // Scroll to top
    const scrollBtn = document.querySelector('.scroll-top');
    if (scrollBtn) {
        window.addEventListener('scroll', () => {
            scrollBtn.classList.toggle('visible', window.scrollY > 250);
        }, { passive: true });
        scrollBtn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    }

    // Filter form submit via GET ke halaman arsip loker
    const filterForm = document.querySelector('.filter-bar--form');
    filterForm?.addEventListener('submit', e => {
        // Hapus field kosong supaya URL bersih
        filterForm.querySelectorAll('select, input').forEach(el => {
            if (!el.value) el.disabled = true;
        });
    });

    // Tombol Salin Teks Siaran Saluran WhatsApp (Khusus Admin)
    document.querySelectorAll('.admin-wa-btn--copy').forEach(btn => {
        btn.addEventListener('click', () => {
            const text  = btn.getAttribute('data-wa-copy') || '';
            const label = btn.querySelector('.copy-label');
            if (!text) return;
            navigator.clipboard.writeText(text).then(() => {
                if (label) {
                    const orig = label.textContent;
                    label.textContent = 'Teks Tersalin!';
                    setTimeout(() => { label.textContent = orig; }, 2200);
                }
            });
        });
    });

    // Proteksi Anti-Copas Konten
    if (typeof hakajobspro !== 'undefined' && hakajobspro.anticopas) {
        let toastTimer = null;
        const showAntiCopasNotice = () => {
            let toast = document.getElementById('hakajobspro-anticopas-toast');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'hakajobspro-anticopas-toast';
                toast.className = 'anticopas-toast';
                toast.setAttribute('role', 'status');
                toast.textContent = hakajobspro.anticopasMsg || 'Konten dilindungi. Dilarang menyalin informasi lowongan kerja tanpa izin.';
                document.body.appendChild(toast);
            }
            toast.classList.add('show');
            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => toast.classList.remove('show'), 2400);
        };

        const isFormInput = el => el && ['INPUT', 'TEXTAREA', 'SELECT'].includes(el.tagName);

        document.addEventListener('contextmenu', e => {
            if (!isFormInput(e.target)) {
                e.preventDefault();
                showAntiCopasNotice();
            }
        });

        ['copy', 'cut', 'dragstart'].forEach(evt => {
            document.addEventListener(evt, e => {
                if (!isFormInput(e.target)) {
                    e.preventDefault();
                    showAntiCopasNotice();
                }
            });
        });

        document.addEventListener('keydown', e => {
            if (isFormInput(e.target)) return;
            const key = e.key ? e.key.toLowerCase() : '';
            if ((e.ctrlKey || e.metaKey) && ['c', 'x', 'u', 's', 'a', 'p'].includes(key)) {
                e.preventDefault();
                showAntiCopasNotice();
            } else if (e.key === 'F12' || ((e.ctrlKey || e.metaKey) && e.shiftKey && ['i', 'j', 'c'].includes(key))) {
                e.preventDefault();
                showAntiCopasNotice();
            }
        });
    }
})();

