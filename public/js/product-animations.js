// Product Page GSAP Animations
document.addEventListener("DOMContentLoaded", function () {
    // Check if GSAP is loaded
    if (typeof gsap === "undefined") {
        console.warn("GSAP is not loaded. Product animations will not work.");
        return;
    }

    // Register GSAP plugins
    gsap.registerPlugin(ScrollTrigger, ScrollToPlugin);

    // Product Hero Section Animations
    const productTl = gsap.timeline();

    // Initial state - hide elements
    gsap.set(
        [
            ".product-hero-badge",
            ".product-hero-title",
            ".product-hero-description",
            ".product-hero-stats",
        ],
        {
            opacity: 0,
            y: 50,
        }
    );

    // Animate elements in sequence
    productTl
        .to(".product-hero-badge", {
            opacity: 1,
            y: 0,
            duration: 0.8,
            ease: "power2.out",
        })
        .to(
            ".product-hero-title",
            {
                opacity: 1,
                y: 0,
                duration: 1,
                ease: "power2.out",
            },
            "-=0.4"
        )
        .to(
            ".product-hero-description",
            {
                opacity: 1,
                y: 0,
                duration: 0.8,
                ease: "power2.out",
            },
            "-=0.6"
        )
        .to(
            ".product-hero-stats",
            {
                opacity: 1,
                y: 0,
                duration: 0.8,
                ease: "power2.out",
            },
            "-=0.4"
        );

    // Product background elements animation
    gsap.to(".product-bg-1", {
        scale: 1.2,
        x: 30,
        y: -20,
        duration: 8,
        ease: "power1.inOut",
        yoyo: true,
        repeat: -1,
    });

    gsap.to(".product-bg-2", {
        scale: 0.9,
        x: -25,
        y: 25,
        duration: 10,
        ease: "power1.inOut",
        yoyo: true,
        repeat: -1,
    });

    gsap.to(".product-bg-3", {
        scale: 1.1,
        x: 20,
        y: -15,
        duration: 6,
        ease: "power1.inOut",
        yoyo: true,
        repeat: -1,
    });

    // Product particles animation
    gsap.to(".product-particle", {
        y: -30,
        opacity: 0.8,
        duration: 3,
        ease: "power1.inOut",
        yoyo: true,
        repeat: -1,
        stagger: 0.5,
    });

    // Product cards animation with stagger - only once on load
    gsap.utils.toArray(".product-card").forEach((card, index) => {
        gsap.fromTo(
            card,
            {
                opacity: 0,
                y: 50,
                scale: 0.9,
            },
            {
                opacity: 1,
                y: 0,
                scale: 1,
                duration: 0.8,
                ease: "power2.out",
                delay: index * 0.1,
                scrollTrigger: {
                    trigger: card,
                    start: "top 85%",
                    end: "bottom 15%",
                    toggleActions: "play none none none", // Changed to prevent reverse animation
                },
            }
        );
    });

    // Product cards hover effects
    document.querySelectorAll(".product-card").forEach((card) => {
        const floatingElements = card.querySelectorAll(
            ".absolute.-top-2, .absolute.-bottom-2"
        );

        card.addEventListener("mouseenter", () => {
            gsap.to(card, {
                y: -10,
                scale: 1.02,
                duration: 0.3,
                ease: "power2.out",
            });

            gsap.to(floatingElements, {
                scale: 1.2,
                duration: 0.3,
                ease: "power2.out",
                stagger: 0.1,
            });
        });

        card.addEventListener("mouseleave", () => {
            gsap.to(card, {
                y: 0,
                scale: 1,
                duration: 0.3,
                ease: "power2.out",
            });

            gsap.to(floatingElements, {
                scale: 1,
                duration: 0.3,
                ease: "power2.out",
                stagger: 0.1,
            });
        });
    });

    // Button hover effects
    document.querySelectorAll(".btn-add-cart").forEach((button) => {
        button.addEventListener("mouseenter", () => {
            gsap.to(button, {
                scale: 1.05,
                duration: 0.3,
                ease: "power2.out",
            });
        });

        button.addEventListener("mouseleave", () => {
            gsap.to(button, {
                scale: 1,
                duration: 0.3,
                ease: "power2.out",
            });
        });
    });

    // Add to cart animation
    document.querySelectorAll(".btn-add-cart").forEach((button) => {
        button.addEventListener("click", function () {
            // Create ripple effect
            const ripple = document.createElement("div");
            ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.6);
                transform: scale(0);
                animation: ripple 0.6s linear;
                pointer-events: none;
            `;

            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            ripple.style.width = ripple.style.height = size + "px";
            ripple.style.left = rect.width / 2 - size / 2 + "px";
            ripple.style.top = rect.height / 2 - size / 2 + "px";

            this.style.position = "relative";
            this.style.overflow = "hidden";
            this.appendChild(ripple);

            // Animate button
            gsap.to(this, {
                scale: 0.95,
                duration: 0.1,
                yoyo: true,
                repeat: 1,
                ease: "power2.inOut",
            });

            // Remove ripple after animation
            setTimeout(() => {
                if (ripple.parentNode) {
                    ripple.parentNode.removeChild(ripple);
                }
            }, 600);
        });
    });

    // Parallax effect for background elements
    gsap.to(".product-bg-1", {
        yPercent: -50,
        ease: "none",
        scrollTrigger: {
            trigger: "body",
            start: "top top",
            end: "bottom top",
            scrub: true,
        },
    });

    gsap.to(".product-bg-2", {
        yPercent: 50,
        ease: "none",
        scrollTrigger: {
            trigger: "body",
            start: "top top",
            end: "bottom top",
            scrub: true,
        },
    });

    // Section header animation - only once on load
    gsap.fromTo(
        ".text-center.mb-16",
        {
            opacity: 0,
            y: 50,
        },
        {
            opacity: 1,
            y: 0,
            duration: 1,
            ease: "power2.out",
            scrollTrigger: {
                trigger: ".text-center.mb-16",
                start: "top 80%",
                end: "bottom 20%",
                toggleActions: "play none none none", // Changed to prevent reverse animation
            },
        }
    );

    // Category tabs animation - only once on load
    gsap.fromTo(
        ".category-tab",
        {
            opacity: 0,
            y: 30,
            scale: 0.9,
        },
        {
            opacity: 1,
            y: 0,
            scale: 1,
            duration: 0.6,
            ease: "power2.out",
            stagger: 0.1,
            scrollTrigger: {
                trigger: ".category-tab",
                start: "top 85%",
                end: "bottom 15%",
                toggleActions: "play none none none", // Changed to prevent reverse animation
            },
        }
    );

    // Category tab hover effects
    document.querySelectorAll(".category-tab").forEach((tab) => {
        tab.addEventListener("mouseenter", () => {
            if (!tab.classList.contains("active")) {
                gsap.to(tab, {
                    scale: 1.05,
                    duration: 0.3,
                    ease: "power2.out",
                });
            }
        });

        tab.addEventListener("mouseleave", () => {
            if (!tab.classList.contains("active")) {
                gsap.to(tab, {
                    scale: 1,
                    duration: 0.3,
                    ease: "power2.out",
                });
            }
        });
    });

    // Back to home button animation
    const backButton = document.querySelector(
        "a[href=\"{{ route('home') }}\"]"
    );
    if (backButton) {
        backButton.addEventListener("mouseenter", () => {
            gsap.to(backButton, {
                scale: 1.05,
                duration: 0.3,
                ease: "power2.out",
            });
        });

        backButton.addEventListener("mouseleave", () => {
            gsap.to(backButton, {
                scale: 1,
                duration: 0.3,
                ease: "power2.out",
            });
        });
    }
});

// Add CSS for ripple effect
const style = document.createElement("style");
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
