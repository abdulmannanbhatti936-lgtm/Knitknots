document.addEventListener('DOMContentLoaded', function() {
    // Mobile Menu Toggle
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const navLinks = document.querySelector('.nav-links');
    
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', () => {
            if (navLinks) {
                navLinks.classList.toggle('open');
                // Fallback inline styles if not managed purely by CSS
                if (navLinks.classList.contains('open')) {
                    navLinks.style.display = 'flex';
                } else {
                    navLinks.style.display = '';
                }
            }
        });
    }

    // Sticky Header Effect
    const header = document.querySelector('header');
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.style.padding = '10px 0';
                header.style.boxShadow = '0 2px 20px rgba(0,0,0,0.1)';
            } else {
                header.style.padding = '15px 0';
                header.style.boxShadow = 'none';
            }
        });
    }

    // Smooth Scroll for Navigation Links
});

/**
 * Initiates payment logic and handles redirect via JSON response.
 */
async function initiatePayment(paymentData) {
    try {
        const response = await fetch('/api/payment-verify.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(paymentData)
        });

        const data = await response.json();

        if (data.success && data.redirect_url) {
            window.location.href = data.redirect_url;
        } else if (!data.success) {
            console.error('Payment failed:', data.error || 'Unknown error');
            alert('Payment could not be verified: ' + (data.error || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error during payment initialization:', error);
        alert('A network error occurred. Please try again.');
    }
}