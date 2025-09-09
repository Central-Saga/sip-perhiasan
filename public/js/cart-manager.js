// Cart Manager for SIP Perhiasan
class CartManager {
    constructor() {
        this.cart = this.getCart();
        this.init();
    }

    init() {
        this.updateCartCount();
        this.setupEventListeners();
    }

    getCart() {
        try {
            return JSON.parse(localStorage.getItem("cart") || "{}");
        } catch (e) {
            console.error("Error parsing cart from localStorage:", e);
            return {};
        }
    }

    setCart(cart) {
        try {
            localStorage.setItem("cart", JSON.stringify(cart));
            this.cart = cart;
            this.updateCartCount();
        } catch (e) {
            console.error("Error saving cart to localStorage:", e);
        }
    }

    updateCartCount() {
        let count = 0;
        for (const id in this.cart) {
            count += this.cart[id].qty || 0;
        }

        // Update all cart count elements (only if they exist - user is logged in)
        const cartCountElements = document.querySelectorAll("#cartCount");
        cartCountElements.forEach((el) => {
            if (el) {
                el.innerText = count;
            }
        });

        // Dispatch custom event for other components
        window.dispatchEvent(
            new CustomEvent("cartCountUpdated", {
                detail: { count: count, cart: this.cart },
            })
        );
    }

    addToCart(product) {
        // This function is now deprecated in favor of Livewire actions
        // Redirect to login if not authenticated
        const isLoggedIn = document.querySelector(
            'meta[name="user-authenticated"]'
        );
        if (!isLoggedIn || isLoggedIn.getAttribute("content") !== "true") {
            window.location.href = "/login";
            return false;
        }

        // Cart operations are now handled by Livewire components
        // This function is kept for backward compatibility only
        return false;
    }

    removeFromCart(productId) {
        const id = String(productId);
        if (this.cart[id]) {
            delete this.cart[id];
            this.setCart(this.cart);
            return true;
        }
        return false;
    }

    updateQuantity(productId, quantity) {
        const id = String(productId);
        if (this.cart[id]) {
            const newQty = Math.max(
                0,
                Math.min(quantity, this.cart[id].stok || 0)
            );
            if (newQty === 0) {
                delete this.cart[id];
            } else {
                this.cart[id].qty = newQty;
            }
            this.setCart(this.cart);
            return true;
        }
        return false;
    }

    getCartCount() {
        let count = 0;
        for (const id in this.cart) {
            count += this.cart[id].qty || 0;
        }
        return count;
    }

    getCartTotal() {
        let total = 0;
        for (const id in this.cart) {
            total += (this.cart[id].harga || 0) * (this.cart[id].qty || 0);
        }
        return total;
    }

    clearCart() {
        this.cart = {};
        this.setCart(this.cart);
    }

    setupEventListeners() {
        // Listen for storage changes from other tabs
        window.addEventListener("storage", (e) => {
            if (e.key === "cart") {
                this.cart = this.getCart();
                this.updateCartCount();
            }
        });

        // Listen for custom cart events
        window.addEventListener("cartUpdated", () => {
            this.cart = this.getCart();
            this.updateCartCount();
        });
    }
}

// Initialize cart manager when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
    window.cartManager = new CartManager();
});

// Global functions for backward compatibility
window.updateCartCount = function () {
    if (window.cartManager) {
        window.cartManager.updateCartCount();
    }
};

window.addToCart = function (product) {
    if (window.cartManager) {
        return window.cartManager.addToCart(product);
    }
    return false;
};

window.getCart = function () {
    if (window.cartManager) {
        return window.cartManager.getCart();
    }
    return {};
};

// Export for module usage
if (typeof module !== "undefined" && module.exports) {
    module.exports = CartManager;
}
