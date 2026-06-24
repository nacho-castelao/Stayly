<link rel="stylesheet" href="<?= DEFAULT_URL ?>assets/css/contact.css?v=<?= filemtime(BASE_PATH . '/assets/css/contact.css') ?>">

<main class="contact-page">
    <section class="contact-card">
        <header class="contact-card__head">
            <h1 class="contact-card__title">Contact Us</h1>
            <p class="contact-card__subtitle">
                Have a question or some feedback? Send us a message and we'll get back to you.
            </p>
        </header>

        <form class="contact-form" action="<?= DEFAULT_URL ?>public/Contact/send" method="POST" novalidate>
            <div class="contact-form__row">
                <div class="contact-field">
                    <label class="contact-field__label" for="name">Name</label>
                    <input class="contact-field__input" type="text" id="name" name="name"
                        placeholder="Your name" required
                        value="<?= htmlspecialchars($old['name'] ?? '') ?>">
                </div>

                <div class="contact-field">
                    <label class="contact-field__label" for="email">Email</label>
                    <input class="contact-field__input" type="email" id="email" name="email"
                        placeholder="you@example.com" required
                        value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                </div>
            </div>

            <div class="contact-field">
                <label class="contact-field__label" for="subject">Subject <span class="contact-field__optional">(optional)</span></label>
                <input class="contact-field__input" type="text" id="subject" name="subject"
                    placeholder="What's this about?"
                    value="<?= htmlspecialchars($old['subject'] ?? '') ?>">
            </div>

            <div class="contact-field">
                <label class="contact-field__label" for="message">Message</label>
                <textarea class="contact-field__input contact-field__textarea" id="message" name="message"
                    rows="6" placeholder="Write your message here..." required><?= htmlspecialchars($old['message'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="contact-form__submit">Send message</button>
        </form>
    </section>
</main>

<div id="toast-container"></div>

<!-- Flash toast bridge: surfaces $_SESSION['toast'] set by ContactController.
     toast.js is loaded as a classic script so showToast is global for the
     inline call below (matching property/show and payment/show). -->
<script src="<?= DEFAULT_URL ?>assets/js/toast.js"></script>
<?php if (isset($_SESSION['toast'])): ?>
    <script>
        showToast('<?= $_SESSION['toast']['type'] ?>', '<?= addslashes($_SESSION['toast']['message']) ?>');
    </script>
    <?php unset($_SESSION['toast']); ?>
<?php endif; ?>
