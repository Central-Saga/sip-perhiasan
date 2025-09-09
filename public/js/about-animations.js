// About Page GSAP Animations
document.addEventListener("DOMContentLoaded", function () {
    // Check if GSAP is loaded
    if (typeof gsap === "undefined") {
        console.warn("GSAP is not loaded. About animations will not work.");
        return;
    }

    // Register GSAP plugins
    gsap.registerPlugin(ScrollTrigger, ScrollToPlugin);

    // About Hero Section Animations
    const aboutTl = gsap.timeline();

    // Initial state - hide elements
    gsap.set(
        [
            ".about-hero-badge",
            ".about-hero-title",
            ".about-hero-description",
            ".about-hero-stats",
            ".about-image-card",
            ".about-floating-1",
            ".about-floating-2",
            ".about-floating-3",
        ],
        {
            opacity: 0,
            y: 50,
        }
    );

    // Animate elements in sequence
    aboutTl
        .to(".about-hero-badge", {
            opacity: 1,
            y: 0,
            duration: 0.8,
            ease: "power2.out",
        })
        .to(
            ".about-hero-title",
            {
                opacity: 1,
                y: 0,
                duration: 1,
                ease: "power2.out",
            },
            "-=0.4"
        )
        .to(
            ".about-hero-description",
            {
                opacity: 1,
                y: 0,
                duration: 0.8,
                ease: "power2.out",
            },
            "-=0.6"
        )
        .to(
            ".about-hero-stats",
            {
                opacity: 1,
                y: 0,
                duration: 0.8,
                ease: "power2.out",
            },
            "-=0.4"
        )
        .to(
            ".about-image-card",
            {
                opacity: 1,
                y: 0,
                duration: 1,
                ease: "power2.out",
            },
            "-=1.2"
        )
        .to(
            [".about-floating-1", ".about-floating-2", ".about-floating-3"],
            {
                opacity: 1,
                y: 0,
                duration: 0.8,
                ease: "power2.out",
                stagger: 0.2,
            },
            "-=0.8"
        );

    // About background elements animation
    gsap.to(".about-bg-1", {
        scale: 1.2,
        x: 30,
        y: -20,
        duration: 8,
        ease: "power1.inOut",
        yoyo: true,
        repeat: -1,
    });

    gsap.to(".about-bg-2", {
        scale: 0.9,
        x: -25,
        y: 25,
        duration: 10,
        ease: "power1.inOut",
        yoyo: true,
        repeat: -1,
    });

    gsap.to(".about-bg-3", {
        scale: 1.1,
        x: 20,
        y: -15,
        duration: 6,
        ease: "power1.inOut",
        yoyo: true,
        repeat: -1,
    });

    // About particles animation
    gsap.to(".about-particle", {
        y: -30,
        opacity: 0.8,
        duration: 3,
        ease: "power1.inOut",
        yoyo: true,
        repeat: -1,
        stagger: 0.5,
    });

    // Continuous floating animation for floating elements
    gsap.to(".about-floating-1", {
        y: -15,
        rotation: 15,
        duration: 4,
        ease: "power1.inOut",
        yoyo: true,
        repeat: -1,
    });

    gsap.to(".about-floating-2", {
        y: 20,
        rotation: -20,
        duration: 5,
        ease: "power1.inOut",
        yoyo: true,
        repeat: -1,
    });

    gsap.to(".about-floating-3", {
        y: -10,
        rotation: 10,
        duration: 3.5,
        ease: "power1.inOut",
        yoyo: true,
        repeat: -1,
    });

    // Story cards animation - only once on load
    gsap.utils.toArray(".about-story-card").forEach((card, index) => {
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
                delay: index * 0.2,
                scrollTrigger: {
                    trigger: card,
                    start: "top 85%",
                    end: "bottom 15%",
                    toggleActions: "play none none none",
                },
            }
        );
    });

    // Mission cards animation - only once on load
    gsap.utils.toArray(".about-mission-card").forEach((card, index) => {
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
                delay: index * 0.2,
                scrollTrigger: {
                    trigger: card,
                    start: "top 85%",
                    end: "bottom 15%",
                    toggleActions: "play none none none",
                },
            }
        );
    });

    // Value cards animation - only once on load
    gsap.utils.toArray(".about-value-card").forEach((card, index) => {
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

    // Process cards animation - only once on load
    gsap.utils.toArray(".about-process-card").forEach((card, index) => {
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

    // Material and care cards animation - only once on load
    gsap.utils
        .toArray(".about-material-card, .about-care-card")
        .forEach((card, index) => {
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
                    delay: index * 0.2,
                    scrollTrigger: {
                        trigger: card,
                        start: "top 85%",
                        end: "bottom 15%",
                        toggleActions: "play none none none",
                    },
                }
            );
        });

    // Card hover effects
    document
        .querySelectorAll(
            ".about-story-card, .about-mission-card, .about-value-card, .about-process-card, .about-material-card, .about-care-card"
        )
        .forEach((card) => {
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

    // Parallax effect for background elements
    gsap.to(".about-bg-1", {
        yPercent: -50,
        ease: "none",
        scrollTrigger: {
            trigger: "body",
            start: "top top",
            end: "bottom top",
            scrub: true,
        },
    });

    gsap.to(".about-bg-2", {
        yPercent: 50,
        ease: "none",
        scrollTrigger: {
            trigger: "body",
            start: "top top",
            end: "bottom top",
            scrub: true,
        },
    });

    // Section headers animation - only once on load
    gsap.utils.toArray(".text-center.mb-16").forEach((header, index) => {
        gsap.fromTo(
            header,
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
                    trigger: header,
                    start: "top 80%",
                    end: "bottom 20%",
                    toggleActions: "play none none none",
                },
            }
        );
    });

    // CTA button hover effects
    const ctaButton = document.querySelector(
        "a[href=\"{{ route('custom') }}\"]"
    );
    if (ctaButton) {
        ctaButton.addEventListener("mouseenter", () => {
            gsap.to(ctaButton, {
                scale: 1.05,
                duration: 0.3,
                ease: "power2.out",
            });
        });

        ctaButton.addEventListener("mouseleave", () => {
            gsap.to(ctaButton, {
                scale: 1,
                duration: 0.3,
                ease: "power2.out",
            });
        });
    }
});
