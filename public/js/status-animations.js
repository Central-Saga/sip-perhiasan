// Status Page GSAP Animations
document.addEventListener("DOMContentLoaded", function () {
    // Check if GSAP is loaded
    if (typeof gsap === "undefined") {
        console.warn("GSAP is not loaded. Status animations will not work.");
        return;
    }

    // Register GSAP plugins
    gsap.registerPlugin(ScrollTrigger, ScrollToPlugin);

    // Status Hero Section Animations
    const statusTl = gsap.timeline();

    // Initial state - hide elements
    gsap.set([".hero-badge", ".hero-title", ".hero-description"], {
        opacity: 0,
        y: 50,
    });

    // Animate elements in sequence
    statusTl
        .to(".hero-badge", {
            opacity: 1,
            y: 0,
            duration: 0.8,
            ease: "power2.out",
        })
        .to(
            ".hero-title",
            {
                opacity: 1,
                y: 0,
                duration: 1,
                ease: "power2.out",
            },
            "-=0.4"
        )
        .to(
            ".hero-description",
            {
                opacity: 1,
                y: 0,
                duration: 0.8,
                ease: "power2.out",
            },
            "-=0.6"
        );

    // Background elements animation
    gsap.to(".absolute.top-1\\/4.left-1\\/4", {
        scale: 1.2,
        x: 30,
        y: -20,
        duration: 8,
        ease: "power1.inOut",
        yoyo: true,
        repeat: -1,
    });

    gsap.to(".absolute.bottom-1\\/4.right-1\\/4", {
        scale: 0.9,
        x: -25,
        y: 25,
        duration: 10,
        ease: "power1.inOut",
        yoyo: true,
        repeat: -1,
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
                toggleActions: "play none none none",
            },
        }
    );

    // Custom request cards animation with stagger - only once on load
    gsap.utils.toArray(".bg-white\\/80").forEach((card, index) => {
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
                    toggleActions: "play none none none",
                },
            }
        );
    });

    // Custom request cards hover effects
    document.querySelectorAll(".bg-white\\/80").forEach((card) => {
        card.addEventListener("mouseenter", () => {
            gsap.to(card, {
                y: -5,
                scale: 1.02,
                duration: 0.3,
                ease: "power2.out",
            });
        });

        card.addEventListener("mouseleave", () => {
            gsap.to(card, {
                y: 0,
                scale: 1,
                duration: 0.3,
                ease: "power2.out",
            });
        });
    });

    // Status badge animation
    document
        .querySelectorAll(".inline-flex.items-center.gap-2.rounded-full")
        .forEach((badge) => {
            badge.addEventListener("mouseenter", () => {
                gsap.to(badge, {
                    scale: 1.05,
                    duration: 0.3,
                    ease: "power2.out",
                });
            });

            badge.addEventListener("mouseleave", () => {
                gsap.to(badge, {
                    scale: 1,
                    duration: 0.3,
                    ease: "power2.out",
                });
            });
        });

    // Action buttons animation
    document
        .querySelectorAll("button[wire\\:click], a[href]")
        .forEach((button) => {
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

    // Loading spinner animation
    const loadingSpinner = document.querySelector(".animate-spin");
    if (loadingSpinner) {
        gsap.to(loadingSpinner, {
            rotation: 360,
            duration: 1,
            ease: "none",
            repeat: -1,
        });
    }

    // Empty state animation
    const emptyState = document.querySelector(".text-center.py-16");
    if (emptyState) {
        gsap.fromTo(
            emptyState,
            {
                opacity: 0,
                y: 30,
            },
            {
                opacity: 1,
                y: 0,
                duration: 0.8,
                ease: "power2.out",
                scrollTrigger: {
                    trigger: emptyState,
                    start: "top 80%",
                    end: "bottom 20%",
                    toggleActions: "play none none none",
                },
            }
        );
    }

    // Parallax effect for background elements
    gsap.to(".absolute.top-1\\/4.left-1\\/4", {
        yPercent: -50,
        ease: "none",
        scrollTrigger: {
            trigger: "body",
            start: "top top",
            end: "bottom top",
            scrub: true,
        },
    });

    gsap.to(".absolute.bottom-1\\/4.right-1\\/4", {
        yPercent: 50,
        ease: "none",
        scrollTrigger: {
            trigger: "body",
            start: "top top",
            end: "bottom top",
            scrub: true,
        },
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

    // Notification animation
    const notifications = document.querySelectorAll(".fixed.top-4.right-4");
    notifications.forEach((notification, index) => {
        gsap.fromTo(
            notification,
            {
                opacity: 0,
                x: 100,
                scale: 0.8,
            },
            {
                opacity: 1,
                x: 0,
                scale: 1,
                duration: 0.5,
                ease: "back.out(1.7)",
                delay: index * 0.1,
            }
        );
    });

    // Image hover effects
    document.querySelectorAll("img").forEach((img) => {
        img.addEventListener("mouseenter", () => {
            gsap.to(img, {
                scale: 1.05,
                duration: 0.3,
                ease: "power2.out",
            });
        });

        img.addEventListener("mouseleave", () => {
            gsap.to(img, {
                scale: 1,
                duration: 0.3,
                ease: "power2.out",
            });
        });
    });

    // Stagger animation for request details
    gsap.utils.toArray(".flex.items-center.gap-3").forEach((detail, index) => {
        gsap.fromTo(
            detail,
            {
                opacity: 0,
                x: -20,
            },
            {
                opacity: 1,
                x: 0,
                duration: 0.5,
                ease: "power2.out",
                delay: index * 0.1,
                scrollTrigger: {
                    trigger: detail,
                    start: "top 90%",
                    end: "bottom 10%",
                    toggleActions: "play none none none",
                },
            }
        );
    });

    // Price information animation
    const priceInfo = document.querySelector(".bg-green-50");
    if (priceInfo) {
        gsap.fromTo(
            priceInfo,
            {
                opacity: 0,
                scale: 0.9,
            },
            {
                opacity: 1,
                scale: 1,
                duration: 0.6,
                ease: "back.out(1.7)",
                scrollTrigger: {
                    trigger: priceInfo,
                    start: "top 85%",
                    end: "bottom 15%",
                    toggleActions: "play none none none",
                },
            }
        );
    }

    // Description box animation
    const descriptionBox = document.querySelector(".bg-slate-50");
    if (descriptionBox) {
        gsap.fromTo(
            descriptionBox,
            {
                opacity: 0,
                y: 20,
            },
            {
                opacity: 1,
                y: 0,
                duration: 0.6,
                ease: "power2.out",
                scrollTrigger: {
                    trigger: descriptionBox,
                    start: "top 85%",
                    end: "bottom 15%",
                    toggleActions: "play none none none",
                },
            }
        );
    }
});
