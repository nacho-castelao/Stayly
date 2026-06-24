<?php
require_once BASE_PATH . '/models/ContactMessage.php';
require_once BASE_PATH . '/controllers/BaseController.php';

class ContactController extends BaseController
{
    private ContactMessage $messageModel;

    public function __construct()
    {
        $this->messageModel = new ContactMessage();
    }

    // Render the contact form (header + page + footer).
    public function index()
    {
        // Repopulate the form after a failed submit, then clear the stash.
        $old = $_SESSION['contact_old'] ?? [];
        unset($_SESSION['contact_old']);

        $this->view('layout/header');
        $this->view('contact/show', ['old' => $old]);
        $this->view('layout/footer');
    }

    // Handle the POST submission, then PRG-redirect back to the form.
    public function send()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('public/Contact/index');
        }

        $name    = trim($_POST['name'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        // Server-side validation: name, valid email, and a message are required.
        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $message === '') {
            $_SESSION['contact_old'] = compact('name', 'email', 'subject', 'message');
            $this->loadToast('error', 'Please enter your name, a valid email, and a message.');
            $this->redirect('public/Contact/index');
        }

        $saved = $this->messageModel->create(
            $name,
            $email,
            $subject !== '' ? $subject : null,
            $message
        );

        if ($saved) {
            $this->loadToast('success', 'Thanks! Your message has been sent.');
        } else {
            $_SESSION['contact_old'] = compact('name', 'email', 'subject', 'message');
            $this->loadToast('error', 'Something went wrong. Please try again.');
        }

        $this->redirect('public/Contact/index');
    }
}
