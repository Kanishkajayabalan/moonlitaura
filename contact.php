<?php
/**
 * ========================================
 * CONTACT US PAGE
 * Customer support contact form
 * ========================================
 */

$successMsg = '';
$errorMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';

    // Validation
    if (empty($name) || empty($email) || empty($message)) {
        $errorMsg = 'Please fill in all required fields';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg = 'Please enter a valid email address';
    } else {
        // Store in database or send email
        // For now, just show success
        $successMsg = 'Thank you! We will get back to you shortly.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Moonlit Aura</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/src/styles/main.css">
    <style>
        .contact-container {
            max-width: 1200px;
            margin: 100px auto;
            padding: var(--spacing-2xl);
        }

        .contact-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--spacing-3xl);
            margin-bottom: var(--spacing-3xl);
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-2xl);
        }

        .info-box {
            background: rgba(30, 41, 59, 0.5);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: var(--spacing-lg);
            display: flex;
            gap: var(--spacing-lg);
        }

        .info-icon {
            width: 50px;
            height: 50px;
            background: rgba(139, 92, 246, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--primary-light);
            flex-shrink: 0;
        }

        .info-content h3 {
            margin-bottom: var(--spacing-sm);
        }

        .contact-form {
            background: rgba(30, 41, 59, 0.5);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: var(--spacing-2xl);
        }

        .form-group {
            margin-bottom: var(--spacing-lg);
        }

        label {
            display: block;
            margin-bottom: var(--spacing-sm);
            font-weight: 500;
        }

        input, textarea, select {
            width: 100%;
            padding: var(--spacing-md);
            background: rgba(100, 116, 139, 0.1);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            color: var(--text-primary);
            font-family: inherit;
            resize: vertical;
        }

        textarea {
            min-height: 120px;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--primary-light);
            background: rgba(100, 116, 139, 0.2);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--spacing-lg);
        }

        .alert {
            padding: var(--spacing-lg);
            border-radius: var(--radius-md);
            margin-bottom: var(--spacing-lg);
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #10b981;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
        }

        .faq-section {
            background: rgba(30, 41, 59, 0.5);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: var(--spacing-2xl);
            margin-top: var(--spacing-3xl);
        }

        @media (max-width: 1024px) {
            .contact-content {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include 'public/includes/navbar.php'; ?>

    <div class="contact-container">
        <div style="text-align: center; margin-bottom: var(--spacing-3xl);">
            <h1 style="margin-bottom: var(--spacing-md);">Contact Us</h1>
            <p style="color: var(--text-muted);">We're here to help and answer any question you might have</p>
        </div>

        <div class="contact-content">
            <!-- Contact Info -->
            <div class="contact-info">
                <div class="info-box">
                    <div class="info-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="info-content">
                        <h3>Address</h3>
                        <p style="color: var(--text-muted);">
                            123 Shopping Street<br>
                            New Delhi, India 110001
                        </p>
                    </div>
                </div>

                <div class="info-box">
                    <div class="info-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="info-content">
                        <h3>Phone</h3>
                        <p style="color: var(--text-muted);">
                            +91 9876 543 210<br>
                            Monday - Friday, 9AM - 6PM IST
                        </p>
                    </div>
                </div>

                <div class="info-box">
                    <div class="info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="info-content">
                        <h3>Email</h3>
                        <p style="color: var(--text-muted);">
                            support@moonlitaura.com<br>
                            We'll respond within 24 hours
                        </p>
                    </div>
                </div>

                <div class="info-box">
                    <div class="info-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="info-content">
                        <h3>Business Hours</h3>
                        <p style="color: var(--text-muted);">
                            Mon - Fri: 9:00 AM - 6:00 PM<br>
                            Sat - Sun: 10:00 AM - 4:00 PM
                        </p>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="contact-form">
                <h2 style="margin-bottom: var(--spacing-lg);">Send us a Message</h2>

                <?php if ($successMsg): ?>
                    <div class="alert alert-success">✓ <?php echo $successMsg; ?></div>
                <?php endif; ?>

                <?php if ($errorMsg): ?>
                    <div class="alert alert-error">✗ <?php echo $errorMsg; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Your Name *</label>
                            <input type="text" id="name" name="name" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Your Email *</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone">
                    </div>

                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <select id="subject" name="subject">
                            <option value="general">General Inquiry</option>
                            <option value="order">Order Related</option>
                            <option value="shipping">Shipping & Delivery</option>
                            <option value="product">Product Quality</option>
                            <option value="return">Return & Refund</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="faq-section">
            <h2 style="margin-bottom: var(--spacing-lg);">Frequently Asked Questions</h2>
            
            <div style="display: flex; flex-direction: column; gap: var(--spacing-lg);">
                <details style="padding: var(--spacing-lg); border-radius: var(--radius-md); background: rgba(100, 116, 139, 0.05);">
                    <summary style="cursor: pointer; font-weight: 600; color: var(--text-primary);">
                        How can I track my order?
                    </summary>
                    <p style="color: var(--text-secondary); margin-top: var(--spacing-md); line-height: 1.6;">
                        You can track your order in real-time from your dashboard. Go to "My Orders" and click on the order you wish to track. You'll see live updates on the shipping status.
                    </p>
                </details>

                <details style="padding: var(--spacing-lg); border-radius: var(--radius-md); background: rgba(100, 116, 139, 0.05);">
                    <summary style="cursor: pointer; font-weight: 600; color: var(--text-primary);">
                        What is your return policy?
                    </summary>
                    <p style="color: var(--text-secondary); margin-top: var(--spacing-md); line-height: 1.6;">
                        We offer a 30-day return policy for all products. If you're not satisfied, you can initiate a return from your order details page.
                    </p>
                </details>

                <details style="padding: var(--spacing-lg); border-radius: var(--radius-md); background: rgba(100, 116, 139, 0.05);">
                    <summary style="cursor: pointer; font-weight: 600; color: var(--text-primary);">
                        How long does shipping take?
                    </summary>
                    <p style="color: var(--text-secondary); margin-top: var(--spacing-md); line-height: 1.6;">
                        Standard delivery takes 5-7 business days. Express delivery is available for 2-3 business days. Overnight delivery is available in select areas.
                    </p>
                </details>

                <details style="padding: var(--spacing-lg); border-radius: var(--radius-md); background: rgba(100, 116, 139, 0.05);">
                    <summary style="cursor: pointer; font-weight: 600; color: var(--text-primary);">
                        Do you offer international shipping?
                    </summary>
                    <p style="color: var(--text-secondary); margin-top: var(--spacing-md); line-height: 1.6;">
                        Currently, we ship within India only. International shipping is coming soon!
                    </p>
                </details>
            </div>
        </div>
    </div>
</body>
</html>
