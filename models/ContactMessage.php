<?php

class ContactMessage
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    // Persist a contact submission. `subject` is optional (nullable column).
    public function create(string $name, string $email, ?string $subject, string $message): bool
    {
        $sql = "INSERT INTO contact_messages (name, email, subject, message)
                VALUES (:name, :email, :subject, :message)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':name'    => $name,
            ':email'   => $email,
            ':subject' => $subject,
            ':message' => $message,
        ]);
    }
}
