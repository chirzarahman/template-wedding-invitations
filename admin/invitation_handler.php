<?php
require_once '../config.php';
session_start();

// Auth check removed
// if (!isset($_SESSION['user_id'])) {
//     header("Location: ../login.php");
//     exit;
// }

$action = $_POST['action'] ?? '';
$id = $_POST['id'] ?? '';

try {
    if ($action === 'create') {
        // Generate Slug
        $slug = strtolower(str_replace(' ', '-', $_POST['groom_nickname'] . '-and-' . $_POST['bride_nickname'] . '-' . rand(100,999)));
        
        $sql = "INSERT INTO invitations (slug, groom_name, groom_nickname, bride_name, bride_nickname, event_date) VALUES (?, ?, ?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([
            $slug, 
            $_POST['groom_name'], 
            $_POST['groom_nickname'], 
            $_POST['bride_name'], 
            $_POST['bride_nickname'], 
            $_POST['event_date']
        ]);
        
        $newId = $pdo->lastInsertId();
        $_SESSION['success'] = "Invitation created!";
        header("Location: invitation_builder.php?id=" . $newId);
    }

    elseif ($action === 'update_couple') {
        // Handle Photos
        $groom_photo = $_POST['old_groom_photo'];
        if (!empty($_FILES['groom_photo']['name'])) {
            $path = 'uploads/' . time() . '_groom_' . $_FILES['groom_photo']['name'];
            move_uploaded_file($_FILES['groom_photo']['tmp_name'], '../' . $path);
            $groom_photo = $path;
        }

        $bride_photo = $_POST['old_bride_photo'];
        if (!empty($_FILES['bride_photo']['name'])) {
            $path = 'uploads/' . time() . '_bride_' . $_FILES['bride_photo']['name'];
            move_uploaded_file($_FILES['bride_photo']['tmp_name'], '../' . $path);
            $bride_photo = $path;
        }

        $sql = "UPDATE invitations SET 
            groom_name=?, groom_nickname=?, groom_father=?, groom_mother=?, groom_photo=?,
            bride_name=?, bride_nickname=?, bride_father=?, bride_mother=?, bride_photo=?
            WHERE id=?";
        
        $pdo->prepare($sql)->execute([
            $_POST['groom_name'], $_POST['groom_nickname'], $_POST['groom_father'], $_POST['groom_mother'], $groom_photo,
            $_POST['bride_name'], $_POST['bride_nickname'], $_POST['bride_father'], $_POST['bride_mother'], $bride_photo,
            $id
        ]);

        $_SESSION['success'] = "Couple details updated!";
        header("Location: invitation_builder.php?id=" . $id);
    }

    elseif ($action === 'update_event') {
        // Handle Map (Convert iframe to link if needed, but schema says map_link is TEXT)
        // User likely pastes a link.
        
        $sql = "UPDATE invitations SET event_date=?, event_address=?, map_link=? WHERE id=?";
        $pdo->prepare($sql)->execute([
            $_POST['event_date'],
            $_POST['event_address'],
            $_POST['map_link'],
            $id
        ]);

        $_SESSION['success'] = "Event details updated!";
        header("Location: invitation_builder.php?id=" . $id);
    }

    elseif ($action === 'update_cover') {
        $cover_image = $_POST['old_cover_image'];
        if (!empty($_FILES['cover_image']['name'])) {
            $path = 'uploads/' . time() . '_cover_' . $_FILES['cover_image']['name'];
            move_uploaded_file($_FILES['cover_image']['tmp_name'], '../' . $path);
            $cover_image = $path;
        }

        $music_file = $_POST['old_music_file'];
        if (!empty($_FILES['music_file']['name'])) {
            $path = 'uploads/' . time() . '_music_' . $_FILES['music_file']['name'];
            move_uploaded_file($_FILES['music_file']['tmp_name'], '../' . $path);
            $music_file = $path;
        }

        $sql = "UPDATE invitations SET cover_title=?, cover_image=?, music_file=? WHERE id=?";
        $pdo->prepare($sql)->execute([
            $_POST['cover_title'],
            $cover_image,
            $music_file,
            $id
        ]);
        
        $_SESSION['success'] = "Cover & Music updated!";
        header("Location: invitation_builder.php?id=" . $id);
    }
    
    // ... Add Gallery/Story handlers if needed (simple for now)

    elseif ($action === 'create_full' || $action === 'update_full') {
        // 0. Fetch Current Data (for updates)
        $current = [];
        if ($action === 'update_full' && $id) {
             $stmt = $pdo->prepare("SELECT * FROM invitations WHERE id = ?");
             $stmt->execute([$id]);
             $current = $stmt->fetch();
        }

        // 1. Process Dates
        $event_date = $_POST['event_date_only'] . ' ' . $_POST['akad_time'] . ':00';
        $reception_date = null;
        if (!empty($_POST['reception_time'])) {
            $reception_date = $_POST['event_date_only'] . ' ' . $_POST['reception_time'] . ':00';
        }

        // 2. Process Features (JSON)
        $features = isset($_POST['features']) ? json_encode($_POST['features']) : json_encode([]);

        // 3. File Upload Helper
        function uploadFile($fileInput, $prefix, $oldValue = '') {
            if (isset($_FILES[$fileInput]) && $_FILES[$fileInput]['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES[$fileInput]['name'], PATHINFO_EXTENSION);
                $filename = 'uploads/' . uniqid($prefix . '_') . '.' . $ext;
                if (move_uploaded_file($_FILES[$fileInput]['tmp_name'], '../' . $filename)) {
                    return $filename;
                }
            }
            return $oldValue;
        }

        // 4. Handle Single Files
        $hero_image_link = $current['hero_image_link'] ?? '';
        if (isset($_POST['delete_hero']) && $_POST['delete_hero'] == '1') {
            $hero_image_link = '';
        }
        $hero_image_link = uploadFile('hero_image', 'hero', $hero_image_link);

        $music_file = $current['music_file'] ?? '';
        if (isset($_POST['delete_music']) && $_POST['delete_music'] == '1') {
            $music_file = '';
        }
        $music_file = uploadFile('music_file', 'music', $music_file);

        // Couple Photos
        $groom_photo_link = $current['groom_photo'] ?? '';
        if(isset($_POST['delete_groom_photo']) && $_POST['delete_groom_photo'] == '1') $groom_photo_link = '';
        $groom_photo_link = uploadFile('groom_photo', 'groom', $groom_photo_link);

        $bride_photo_link = $current['bride_photo'] ?? '';
        if(isset($_POST['delete_bride_photo']) && $_POST['delete_bride_photo'] == '1') $bride_photo_link = '';
        $bride_photo_link = uploadFile('bride_photo', 'bride', $bride_photo_link);

        // 5. Handle Gallery (Multiple) - JSON Based
        // Receive existing images that user KEPT (hidden inputs)
        $existing_gallery = isset($_POST['existing_gallery']) ? $_POST['existing_gallery'] : []; 
        if(!is_array($existing_gallery)) $existing_gallery = [];

        // Add New Uploads
        if (isset($_FILES['gallery_images'])) {
            $total_files = count($_FILES['gallery_images']['name']);
            for ($i = 0; $i < $total_files; $i++) {
                if ($_FILES['gallery_images']['error'][$i] === UPLOAD_ERR_OK) {
                    $ext = pathinfo($_FILES['gallery_images']['name'][$i], PATHINFO_EXTENSION);
                    $filename = 'uploads/' . uniqid('gallery_') . '.' . $ext;
                    if (move_uploaded_file($_FILES['gallery_images']['tmp_name'][$i], '../' . $filename)) {
                        $existing_gallery[] = $filename;
                    }
                }
            }
        }
        $gallery_links = json_encode(array_values($existing_gallery)); // Re-encode as JSON


        // 6. Slug Generation (If Create)
        $slug = isset($_POST['slug']) ? $_POST['slug'] : strtolower(str_replace(' ', '-', $_POST['groom_nickname'] . '-and-' . $_POST['bride_nickname'] . '-' . rand(100,999)));

        if ($action === 'create_full') {
            $gifts = $_POST['gifts'] ?? '[]';
            
             $sql = "INSERT INTO invitations (
                slug, groom_name, groom_nickname, groom_father, groom_mother, groom_photo,
                bride_name, bride_nickname, bride_father, bride_mother, bride_photo,
                event_date, reception_date, event_address, reception_address, map_link, reception_map_link,
                theme_color, visual_style, hero_image_link, gallery_links, music_file,
                love_story, wishes_opening, enabled_features, gifts
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $slug, $_POST['groom_name'], $_POST['groom_nickname'], $_POST['groom_father'], $_POST['groom_mother'], $groom_photo_link,
                $_POST['bride_name'], $_POST['bride_nickname'], $_POST['bride_father'], $_POST['bride_mother'], $bride_photo_link,
                $event_date, $reception_date, $_POST['event_address'], $_POST['reception_address'], $_POST['map_link'], $_POST['reception_map_link'],
                $_POST['theme_color'], $_POST['visual_style'], $hero_image_link, $gallery_links, $music_file,
                $_POST['love_story'], $_POST['wishes_opening'], $features, $gifts
            ]);
            $newId = $pdo->lastInsertId();
            $_SESSION['success'] = "Invitation Generated Successfully!";
            header("Location: invitation_form.php?id=" . $newId);
        } else {
            $gifts = $_POST['gifts'] ?? '[]';

             $sql = "UPDATE invitations SET 
                groom_name=?, groom_nickname=?, groom_father=?, groom_mother=?, groom_photo=?,
                bride_name=?, bride_nickname=?, bride_father=?, bride_mother=?, bride_photo=?,
                event_date=?, reception_date=?, event_address=?, reception_address=?, map_link=?, reception_map_link=?,
                theme_color=?, visual_style=?, hero_image_link=?, gallery_links=?, music_file=?,
                love_story=?, wishes_opening=?, enabled_features=?, gifts=?
                WHERE id=?";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $_POST['groom_name'], $_POST['groom_nickname'], $_POST['groom_father'], $_POST['groom_mother'], $groom_photo_link,
                $_POST['bride_name'], $_POST['bride_nickname'], $_POST['bride_father'], $_POST['bride_mother'], $bride_photo_link,
                $event_date, $reception_date, $_POST['event_address'], $_POST['reception_address'], $_POST['map_link'], $_POST['reception_map_link'],
                $_POST['theme_color'], $_POST['visual_style'], $hero_image_link, $gallery_links, $music_file,
                $_POST['love_story'], $_POST['wishes_opening'], $features, $gifts,
                $id
            ]);
            $_SESSION['success'] = "Invitation Updated Successfully!";
            header("Location: invitation_form.php?id=" . $id);
        }
    }

    elseif ($action === 'delete') {
        $pdo->prepare("DELETE FROM invitations WHERE id=?")->execute([$id]);
        $_SESSION['success'] = "Invitation deleted!";
        header("Location: invitations.php");
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
