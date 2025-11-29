document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();

            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                const mobileMenu = document.getElementById('mobile-menu');
                if (mobileMenu && mobileMenu.hasAttribute('open')) {
                    mobileMenu.removeAttribute('open');
                }
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                history.pushState(null, null, targetId);
            }
        });
    });

    const sections = document.querySelectorAll('[id]');
    const navLinks = document.querySelectorAll('a[href^="#"]');

    function updateActiveNavLink() {
        let current = '';
        const scrollPosition = window.scrollY + 100;

        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('bg-[#00C3B3]', 'text-white');
            link.classList.add('text-black', 'hover:bg-black/5');

            if (link.getAttribute('href') === `#${current}`) {
                link.classList.add('bg-[#00C3B3]', 'text-white');
                link.classList.remove('text-black', 'hover:bg-black/5');
            }
        });
    }

    window.addEventListener('scroll', updateActiveNavLink);

    updateActiveNavLink();

    window.addEventListener('popstate', function() {
        const hash = window.location.hash;
        if (hash) {
            const target = document.querySelector(hash);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        }
    });
});
