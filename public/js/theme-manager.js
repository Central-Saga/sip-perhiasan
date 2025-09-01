// Theme Manager for SIP Perhiasan
class ThemeManager {
    constructor() {
        this.theme = localStorage.getItem("theme") || "dark";
        this.init();
    }

    init() {
        this.applyTheme(this.theme);
        this.setupEventListeners();
    }

    applyTheme(theme) {
        const html = document.documentElement;

        // Remove existing theme classes
        html.classList.remove("light", "dark");

        // Add new theme class
        html.classList.add(theme);

        // Set data attribute
        html.setAttribute("data-theme", theme);

        // Update color scheme
        html.style.colorScheme = theme;

        // Store in localStorage
        localStorage.setItem("theme", theme);

        this.theme = theme;
    }

    toggleTheme() {
        const newTheme = this.theme === "dark" ? "light" : "dark";
        this.applyTheme(newTheme);
    }

    setTheme(theme) {
        if (["light", "dark", "system"].includes(theme)) {
            if (theme === "system") {
                const systemTheme = window.matchMedia(
                    "(prefers-color-scheme: dark)"
                ).matches
                    ? "dark"
                    : "light";
                this.applyTheme(systemTheme);
            } else {
                this.applyTheme(theme);
            }
        }
    }

    setupEventListeners() {
        // Listen for system theme changes
        window
            .matchMedia("(prefers-color-scheme: dark)")
            .addEventListener("change", (e) => {
                if (localStorage.getItem("theme") === "system") {
                    this.applyTheme(e.matches ? "dark" : "light");
                }
            });

        // Expose to global scope for use in other scripts
        window.themeManager = this;
    }

    getCurrentTheme() {
        return this.theme;
    }
}

// Initialize theme manager when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
    new ThemeManager();
});

// Export for module usage
if (typeof module !== "undefined" && module.exports) {
    module.exports = ThemeManager;
}
