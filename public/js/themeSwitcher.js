var themeSwitcher = document.getElementById('theme-switcher');

if (themeSwitcher) {
    if (document.body.classList.contains('dark-theme')) {
        themeSwitcher.innerText = 'Passer au thème lumineux';
    }

    themeSwitcher.addEventListener('click', function(e) {
        e.preventDefault();

        if (document.body.classList.contains('dark-theme')) {
            document.body.classList.remove('dark-theme');
            document.body.classList.add('light-theme');
            localStorage.setItem('themeName', 'light-theme');
            themeSwitcher.innerText = 'Passer au thème sombre';
        } else if (document.body.classList.contains('light-theme')) {
            document.body.classList.remove('light-theme');
            document.body.classList.add('dark-theme');
            localStorage.setItem('themeName', 'dark-theme');
            themeSwitcher.innerText = 'Passer au thème lumineux';
        }
    });
}

document.body.classList.add(
    localStorage.getItem('themeName') || (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark-theme' : 'light-theme')
);

document.body.classList.add(
    (navigator.appVersion.indexOf('Win') !== -1) ? 'windows' : (navigator.appVersion.indexOf('Mac') !== -1) ? 'macos' : 'linux'
);