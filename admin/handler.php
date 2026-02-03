<?php
session_start();
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'update_settings') {
            $sql = "UPDATE settings SET 
                    site_title = ?, 
                    hero_title = ?, 
                    hero_description = ?, 
                    logo_text = ?, 
                    address = ?, 
                    map_embed = ?, 
                    whatsapp_number = ?, 
                    instagram_link = ?, 
                    tiktok_link = ?,
                    latitude = ?,
                    longitude = ?";
            
            $params = [
                $_POST['site_title'],
                $_POST['hero_title'],
                $_POST['hero_description'],
                $_POST['logo_text'],
                $_POST['address'],
                $_POST['map_embed'],
                $_POST['whatsapp_number'],
                $_POST['instagram_link'],
                $_POST['tiktok_link'],
                $_POST['latitude'],
                $_POST['longitude']
            ];

            // Handle Logo Upload
            if (isset($_FILES['logo_image']) && $_FILES['logo_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $fileExt = pathinfo($_FILES['logo_image']['name'], PATHINFO_EXTENSION);
                $fileName = 'logo_' . time() . '.' . $fileExt;
                $targetFile = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['logo_image']['tmp_name'], $targetFile)) {
                    $sql .= ", logo_image = ?";
                    $params[] = 'uploads/' . $fileName;
                }
            }

            $sql .= " WHERE id = 1";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $_SESSION['success'] = "Settings updated successfully!";
            
        } elseif ($action === 'add_category') {
            $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->execute([$_POST['name']]);
            $_SESSION['success'] = "Category added successfully!";
            
        } elseif ($action === 'update_category') {
            $stmt = $pdo->prepare("UPDATE categories SET name = ? WHERE id = ?");
            $stmt->execute([$_POST['name'], $_POST['id']]);
            $_SESSION['success'] = "Category updated successfully!";

        } elseif ($action === 'delete_category') {
            $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $_SESSION['success'] = "Category deleted successfully!";
            
        } elseif ($action === 'add_product') {
            $image = '#';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                $fileExt = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $fileName = 'prod_' . time() . '.' . $fileExt;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                    $image = 'uploads/' . $fileName;
                }
            }

            $stmt = $pdo->prepare("INSERT INTO products (name, category_id, price, discounted_price, image, link) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['name'],
                $_POST['category_id'],
                $_POST['price'],
                !empty($_POST['discounted_price']) ? $_POST['discounted_price'] : NULL,
                $image,
                $_POST['link']
            ]);
            $_SESSION['success'] = "Product added successfully!";

        } elseif ($action === 'update_product') {
            $sql = "UPDATE products SET name=?, category_id=?, price=?, discounted_price=?, link=?";
            $params = [
                $_POST['name'],
                $_POST['category_id'],
                $_POST['price'],
                !empty($_POST['discounted_price']) ? $_POST['discounted_price'] : NULL,
                $_POST['link']
            ];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                $fileExt = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $fileName = 'prod_' . time() . '.' . $fileExt;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                    $sql .= ", image=?";
                    $params[] = 'uploads/' . $fileName;
                }
            } elseif (!empty($_POST['image'])) {
                 $sql .= ", image=?";
                 $params[] = $_POST['image'];
            }

            $sql .= " WHERE id=?";
            $params[] = $_POST['id'];

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $_SESSION['success'] = "Product updated successfully!";

        } elseif ($action === 'delete_product') {
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $_SESSION['success'] = "Product deleted successfully!";

        } elseif ($action === 'add_testimonial') {
            $image = '#';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                $fileExt = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $fileName = 'testi_' . time() . '.' . $fileExt;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                    $image = 'uploads/' . $fileName;
                }
            }
            
            $stmt = $pdo->prepare("INSERT INTO testimonials (couple_name, rating, quote, image) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $_POST['couple_name'],
                $_POST['rating'],
                $_POST['quote'],
                $image
            ]);
            $_SESSION['success'] = "Testimonial added successfully!";

        } elseif ($action === 'update_testimonial') {
            $sql = "UPDATE testimonials SET couple_name=?, rating=?, quote=?";
            $params = [
                $_POST['couple_name'],
                $_POST['rating'],
                $_POST['quote']
            ];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../uploads/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                $fileExt = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $fileName = 'testi_' . time() . '.' . $fileExt;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                    $sql .= ", image=?";
                    $params[] = 'uploads/' . $fileName;
                }
            } elseif (!empty($_POST['image'])) {
                $sql .= ", image=?";
                $params[] = $_POST['image'];
            }

            $sql .= " WHERE id=?";
            $params[] = $_POST['id'];

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $_SESSION['success'] = "Testimonial updated successfully!";

        } elseif ($action === 'delete_testimonial') {
            $stmt = $pdo->prepare("DELETE FROM testimonials WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $_SESSION['success'] = "Testimonial deleted successfully!";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }

    // Redirect back to admin panel
    header("Location: index.php");
    exit;
}
?>
