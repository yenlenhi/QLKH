<?php
if (!defined('ALLOW_ACCESS')) {
    http_response_code(403);
    die(json_encode(["status" => "error", "message" => "Access Denied"]));
}
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
// Nhúng middleware xác thực JWT (Sẽ tự động kiểm tra Token và exit nếu lỗi)
require_once __DIR__ . '/auth_middleware.php';
require_once __DIR__ . '/../../Model/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Phương thức không được hỗ trợ!"]);
    exit();
}

if (!isset($pdo)) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Không tìm thấy kết nối CSDL!"]);
    exit();
}

// 2. Lấy chuỗi JSON từ $_POST['data'] do JS gửi lên
$rawJson =$_POST['data'] ?? '';
$projectData = json_decode($rawJson, true);

if (!$projectData || empty($projectData['id'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Dữ liệu gửi lên không hợp lệ!"]);
    exit();
}

$projectId   =$projectData['id'];
$title       =$projectData['title'] ?? '';
$category    =$projectData['category'] ?? 'REGISTER_NEW';
$type        =$projectData['type'] ?? '';
$subType     =$projectData['subType'] ?? '';
$budget      =$projectData['budget'] ?? 0;
$days        =$projectData['days'] ?? 0;
$desc        =$projectData['desc'] ?? '';
$keepFiles   =$projectData['keepFiles'] ?? []; // Danh sách file cũ người dùng giữ lại
$members     =$projectData['members'] ?? [];   // Danh sách thành viên tham gia

// 3. Xử lý lưu các FILE MỚI tải lên vào thư mục asset/file/reports/
$uploadDir = '../../asset/file/reports/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$newUploadedFiles = [];
if (isset($_FILES['files']) && !empty($_FILES['files']['name'][0])) {
    $totalFiles = count($_FILES['files']['name']);

    for ($i = 0; $i < $totalFiles; $i++) {
        if ($_FILES['files']['error'][$i] === UPLOAD_ERR_OK) {
            $tmpName      =$_FILES['files']['tmp_name'][$i];$originalName = $_FILES['files']['name'][$i];
            $ext          = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            $allowed = ['pdf', 'doc', 'docx', 'zip', 'rar'];
            if (in_array($ext,$allowed)) {
                $newName    =$projectId . '_' . time() . '_' . $i . '.' .$ext;
                $targetPath = $uploadDir .$newName;

                if (move_uploaded_file($tmpName,$targetPath)) {
                    // Tên file hiển thị hoặc lưu vào mảng quản lý file
                    $newUploadedFiles[] =$newName;
                }
            }
        }
    }
}

// Tổng hợp lại tất cả file đính kèm (File cũ giữ lại + File mới upload)
$finalFilesList = array_merge($keepFiles,$newUploadedFiles);
$filesJson = json_encode($finalFilesList, JSON_UNESCAPED_UNICODE);

// 4. Bắt đầu Transaction cập nhật dữ liệu vào Database db_qlnckh
try {
    $pdo->beginTransaction();

    // A. Cập nhật thông tin chính của Đề tài vào bảng `projects`
    $sqlProject = "UPDATE projects 
                   SET title = :title, 
                       category = :category, 
                       type = :type, 
                       sub_type = :sub_type, 
                       budget = :budget, 
                       days = :days, 
                       description = :description, 
                       files = :files,
                       updated_at = NOW() 
                   WHERE id = :id";
                   
    $stmtProject =$pdo->prepare($sqlProject);$stmtProject->execute([
        ':title'       => $title,
        ':category'    => $category,
        ':type'        => $type,
        ':sub_type'    => $subType,
        ':budget'      => $budget,
        ':days'        => $days,
        ':description' => $desc,
        ':files'       => $filesJson,
        ':id'          => $projectId
    ]);

    // B. Cập nhật danh sách thành viên tham gia (Bảng `project_members`)
    // B1: Xóa thành viên cũ của đề tài này
    $sqlDeleteMembers = "DELETE FROM project_members WHERE project_id = :project_id";
    $stmtDelete = $pdo->prepare($sqlDeleteMembers);
    $stmtDelete->execute([':project_id' =>$projectId]);

    // B2: Thêm mới lại danh sách thành viên mới
    if (!empty($members)) {$sqlInsertMember = "INSERT INTO project_members (project_id, full_name, role, unit) 
                            VALUES (:project_id, :full_name, :role, :unit)";
        $stmtInsert = $pdo->prepare($sqlInsertMember);

        foreach ($members as $member) {$stmtInsert->execute([
                ':project_id' => $projectId,
                ':full_name'  => $member['fullName'] ?? '',
                ':role'       => $member['role'] ?? 'THÀNH VIÊN',
                ':unit'       => $member['unit'] ?? ''
            ]);
        }
    }

    // Hoàn tất Transaction
    $pdo->commit();

    http_response_code(200);
    echo json_encode([
        "status"  => "success",
        "message" => "Cập nhật thông tin đề tài thành công!"
    ]);

} catch (PDOException $e) {
    // Hoàn tác nếu xảy ra lỗi
    if ($pdo->inTransaction()) {$pdo->rollBack();
    }

    http_response_code(500);
    echo json_encode([
        "status"  => "error",
        "message" => "Lỗi CSDL: " . $e->getMessage()
    ]);
}
?>