<?php 
require_once BASE_PATH . "/models/Wishlist.php";
require_once BASE_PATH . "/controllers/BaseController.php";

class WishlistController extends BaseController
{
    private Wishlist $wishlistModel;

    public function __construct()
    {
        $this->wishlistModel = new Wishlist();
    }

    public function toggle()
    {
        $this->requireAuth();

        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);

        $propertyId = $data['propertyId'] ?? null;

        if (!$propertyId) {
            echo json_encode([
                "status" => "error"
            ]);
            return;
        }

        $this->wishlistModel->setUser_id($_SESSION['user_id']);
        $this->wishlistModel->setProperty_id($propertyId);
        $isSaved = $this->wishlistModel->isSaved();

        if ($isSaved) {
            $success = $this->wishlistModel->delete();
            $remove = true;
        } else {
            $success = $this->wishlistModel->save();
            $remove = false;
        }

        echo json_encode([
            "status" => $success ? "success" : "error",
            "remove" => $remove
        ]);
    }
}
?>