// Hero Section GSAP Animations
document.addEventListener("DOMContentLoaded", function () {
    // Check if GSAP is loaded
    if (typeof gsap === "undefined") {
        console.warn("GSAP is not loaded. Hero animations will not work.");
        return;
    }

    // Register GSAP plugins
    gsap.registerPlugin(ScrollTrigger, ScrollToPlugin);

    // Hero Section Animations
    const tl = gsap.timeline();

    // Initial state - hide elements
    gsap.set(
        [
            ".hero-badge",
            ".hero-title",
            ".hero-description",
            ".hero-buttons",
            ".hero-stats",
            ".product-card",
            ".floating-card-1",
            ".floating-card-2",
            ".floating-card-3",
        ],
        {
            opacity: 0,
            y: 50,
        }
    );

    // Animate elements in sequence
    tl.to(".hero-badge", {
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
        )
        .to(
            ".hero-buttons",
            {
                opacity: 1,
                y: 0,
                duration: 0.8,
                ease: "power2.out",
            },
            "-=0.4"
        )
        .to(
            ".hero-stats",
            {
                opacity: 1,
                y: 0,
                duration: 0.8,
                ease: "power2.out",
            },
            "-=0.6"
        )
        .to(
            ".product-card",
            {
                opacity: 1,
                y: 0,
                duration: 1,
                ease: "power2.out",
            },
            "-=1.2"
        )
        .to(
            [".floating-card-1", ".floating-card-2", ".floating-card-3"],
            {
                opacity: 1,
                y: 0,
                duration: 0.8,
                ease: "power2.out",
                stagger: 0.2,
            },
            "-=0.8"
        );

    // Continuous floating animation for cards
    gsap.to(".floating-card-1", {
        y: -20,
        rotation: -5,
        duration: 3,
        ease: "power1.inOut",
        yoyo: true,
        repeat: -1,
    });

    gsap.to(".floating-card-2", {
        y: 15,
        rotation: 8,
        duration: 4,
        ease: "power1.inOut",
        yoyo: true,
        repeat: -1,
    });

    gsap.to(".floating-card-3", {
        y: -10,
        rotation: -3,
        duration: 2.5,
        ease: "power1.inOut",
        yoyo: true,
        repeat: -1,
    });

    // Particle animation
    gsap.to(".particle", {
        y: -30,
        opacity: 0.8,
        duration: 3,
        ease: "power1.inOut",
        yoyo: true,
        repeat: -1,
        stagger: 0.5,
    });

    // Background gradient animation
    gsap.to(".absolute.top-1\\/4.left-1\\/4", {
        scale: 1.2,
        duration: 8,
        ease: "power1.inOut",
        yoyo: true,
        repeat: -1,
    });

    gsap.to(".absolute.bottom-1\\/4.right-1\\/4", {
        scale: 0.8,
        duration: 6,
        ease: "power1.inOut",
        yoyo: true,
        repeat: -1,
    });

    // Scroll-triggered animations for other sections
    gsap.utils.toArray("section").forEach((section, index) => {
        if (index > 0) {
            // Skip hero section
            gsap.fromTo(
                section,
                { opacity: 0, y: 100 },
                {
                    opacity: 1,
                    y: 0,
                    duration: 1,
                    ease: "power2.out",
                    scrollTrigger: {
                        trigger: section,
                        start: "top 80%",
                        end: "bottom 20%",
                        toggleActions: "play none none reverse",
                    },
                }
            );
        }
    });

    // Product cards animation - only once on load
    gsap.utils.toArray(".product-item").forEach((item, index) => {
        gsap.fromTo(
            item,
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
                    trigger: item,
                    start: "top 85%",
                    end: "bottom 15%",
                    toggleActions: "play none none none", // Changed to prevent reverse animation
                },
            }
        );
    });

    // Feature cards animation - only once on load
    gsap.utils.toArray(".feature-card").forEach((item, index) => {
        gsap.fromTo(
            item,
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
                    trigger: item,
                    start: "top 85%",
                    end: "bottom 15%",
                    toggleActions: "play none none none", // Changed to prevent reverse animation
                },
            }
        );
    });

    // About section background elements animation
    gsap.to(".about-bg-1", {
        scale: 1.3,
        x: 50,
        y: -30,
        duration: 8,
        ease: "power1.inOut",
        yoyo: true,
        repeat: -1,
    });

    gsap.to(".about-bg-2", {
        scale: 0.8,
        x: -40,
        y: 40,
        duration: 10,
        ease: "power1.inOut",
        yoyo: true,
        repeat: -1,
    });

    gsap.to(".about-bg-3", {
        scale: 1.2,
        x: 30,
        y: -20,
        duration: 6,
        ease: "power1.inOut",
        yoyo: true,
        repeat: -1,
    });

    // About section floating elements animation
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

    // Carousel functionality
    const carouselTrack = document.querySelector(".carousel-track");
    const carouselSlides = document.querySelectorAll(".carousel-slide");
    const prevBtn = document.querySelector(".carousel-prev");
    const nextBtn = document.querySelector(".carousel-next");
    const dots = document.querySelectorAll(".carousel-dot");

    if (carouselTrack && carouselSlides.length > 0) {
        let currentSlide = 0;
        const slidesPerView = 3; // Number of slides visible at once
        const totalSlides = Math.min(10, carouselSlides.length); // Limit to 10 products
        const maxSlide = Math.max(0, totalSlides - slidesPerView);

        function updateCarousel() {
            const translateX = -currentSlide * (320 + 24); // 320px width + 24px gap
            gsap.to(carouselTrack, {
                x: translateX,
                duration: 0.5,
                ease: "power2.out",
            });

            // Update dots
            dots.forEach((dot, index) => {
                dot.classList.toggle(
                    "bg-indigo-500",
                    index === Math.floor(currentSlide / slidesPerView)
                );
                dot.classList.toggle(
                    "dark:bg-indigo-400",
                    index === Math.floor(currentSlide / slidesPerView)
                );
                dot.classList.toggle(
                    "bg-slate-300",
                    index !== Math.floor(currentSlide / slidesPerView)
                );
                dot.classList.toggle(
                    "dark:bg-slate-600",
                    index !== Math.floor(currentSlide / slidesPerView)
                );
            });

            // Update button states
            prevBtn.style.opacity = currentSlide === 0 ? "0.5" : "1";
            nextBtn.style.opacity = currentSlide >= maxSlide ? "0.5" : "1";
        }

        // Next button
        nextBtn.addEventListener("click", () => {
            if (currentSlide < maxSlide) {
                currentSlide = Math.min(currentSlide + 1, maxSlide);
                updateCarousel();
            }
        });

        // Previous button
        prevBtn.addEventListener("click", () => {
            if (currentSlide > 0) {
                currentSlide = Math.max(currentSlide - 1, 0);
                updateCarousel();
            }
        });

        // Dot navigation
        dots.forEach((dot, index) => {
            dot.addEventListener("click", () => {
                currentSlide = index * slidesPerView;
                updateCarousel();
            });
        });

        // Touch/swipe support
        let startX = 0;
        let isDragging = false;

        carouselTrack.addEventListener("mousedown", (e) => {
            startX = e.clientX;
            isDragging = true;
        });

        carouselTrack.addEventListener("mousemove", (e) => {
            if (!isDragging) return;
            e.preventDefault();
        });

        carouselTrack.addEventListener("mouseup", (e) => {
            if (!isDragging) return;
            isDragging = false;

            const endX = e.clientX;
            const diff = startX - endX;

            if (Math.abs(diff) > 50) {
                if (diff > 0 && currentSlide < maxSlide) {
                    currentSlide++;
                } else if (diff < 0 && currentSlide > 0) {
                    currentSlide--;
                }
                updateCarousel();
            }
        });

        // Auto-play (optional)
        let autoPlayInterval;

        function startAutoPlay() {
            autoPlayInterval = setInterval(() => {
                if (currentSlide < maxSlide) {
                    currentSlide++;
                } else {
                    currentSlide = 0;
                }
                updateCarousel();
            }, 5000);
        }

        function stopAutoPlay() {
            clearInterval(autoPlayInterval);
        }

        // Start auto-play
        startAutoPlay();

        // Pause on hover
        carouselTrack.addEventListener("mouseenter", stopAutoPlay);
        carouselTrack.addEventListener("mouseleave", startAutoPlay);

        // Initialize
        updateCarousel();
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

    // Button hover effects
    document
        .querySelectorAll('a[href="#produk"], a[href="#about"]')
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

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener("click", function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute("href"));
            if (target) {
                gsap.to(window, {
                    duration: 1,
                    scrollTo: {
                        y: target,
                        offsetY: 80,
                    },
                    ease: "power2.inOut",
                });
            }
        });
    });

    // Scroll down arrow functionality
    const scrollDownArrow = document.querySelector(".scroll-down-arrow");
    if (scrollDownArrow) {
        // Click functionality
        scrollDownArrow.addEventListener("click", function (e) {
            e.preventDefault();
            const nextSection = document.querySelector("#produk");
            if (nextSection) {
                gsap.to(window, {
                    duration: 1.5,
                    scrollTo: {
                        y: nextSection,
                        offsetY: 80,
                    },
                    ease: "power2.inOut",
                });
            }
        });

        // Hover animation
        scrollDownArrow.addEventListener("mouseenter", function () {
            gsap.to(this, {
                scale: 1.2,
                duration: 0.3,
                ease: "power2.out",
            });
        });

        scrollDownArrow.addEventListener("mouseleave", function () {
            gsap.to(this, {
                scale: 1,
                duration: 0.3,
                ease: "power2.out",
            });
        });

        // Continuous floating animation
        gsap.to(scrollDownArrow, {
            y: 10,
            duration: 2,
            ease: "power1.inOut",
            yoyo: true,
            repeat: -1,
        });
    }
});
