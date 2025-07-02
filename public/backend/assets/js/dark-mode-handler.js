        // Dark mode logic with localStorage persistence
        const htmlElement = document.documentElement;
        const darkModeIcon = $(".dark-mode i");

        // Function to apply the theme
        const applyTheme = (theme) => {
            htmlElement.classList.remove("dark-theme", "light-theme");
            htmlElement.classList.add(theme);
            localStorage.setItem("theme", theme);
            if (theme === "dark-theme") {
                darkModeIcon.attr("class", "bx bx-moon");
            } else {
                darkModeIcon.attr("class", "bx bx-sun");
            }
        };

        // Apply theme on page load
        const savedTheme = localStorage.getItem("theme");
        if (savedTheme) {
            applyTheme(savedTheme);
        } else if (window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches) {
            // Default to dark theme if system prefers it and no saved theme
            applyTheme("dark-theme");
        } else {
            applyTheme("light-theme");
        }

        $(".dark-mode").on("click", function() {
            if (htmlElement.classList.contains("dark-theme")) {
                applyTheme("light-theme");
            } else {
                applyTheme("dark-theme");
            }
        });