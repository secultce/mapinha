document.addEventListener('DOMContentLoaded', () => {
    function toggleDropdown() {
        const dropdownMenu = document.getElementById("customDropdown");
        dropdownMenu?.classList.toggle("show");

        setTimeout(() => {
            document.getElementById("dropdownMenuButton")?.blur();
        }, 100);
    }

    const dropdownButton = document.getElementById("dropdownMenuButton");
    dropdownButton?.addEventListener('click', (e) => {
        e.stopPropagation();
        toggleDropdown();
    });

    document.addEventListener('click', (event) => {
        const dropdownMenu = document.getElementById("customDropdown");

        if (dropdownMenu?.classList.contains('show')) {
            if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.remove('show');
            }
        }
    });

    const dropdownItems = document.querySelectorAll('.dropdown-item');
    dropdownItems.forEach(item => {
        item.addEventListener('click', () => {
            const dropdownMenu = document.getElementById("customDropdown");
            dropdownMenu.classList.remove('show');

            setTimeout(() => {
                dropdownButton.blur();
            }, 100);
        });
    });

    const notificationBtn = document.getElementById("notificationDropdown");
    const notificationMenu = document.querySelector(".dropdown-notification .dropdown-menu");

    if (notificationBtn && notificationMenu) {
        notificationBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            notificationMenu.classList.toggle("show");
        });

        document.addEventListener("click", function (event) {
            if (!notificationBtn.contains(event.target) && !notificationMenu.contains(event.target)) {
                notificationMenu.classList.remove("show");
            }
        });
    }
});
