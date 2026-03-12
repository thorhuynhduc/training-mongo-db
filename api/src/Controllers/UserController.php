<?php
namespace Src\Controllers;

use Exception;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Collection;
use Src\Config\Database;

class UserController
{
    private Collection $collection;
    
    public function __construct()
    {
        $db = Database::getInstance();
        $this->collection = $db->getCollection('users');
    }
    
    // GET /users - Lấy danh sách users
    public function index(): void
    {
        $users = $this->collection->find()->toArray();
        
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'data' => $users
        ]);
    }
    
    // GET /users/{id} - Lấy user theo ID
    public function show($id): void
    {
        try {
            $user = $this->collection->findOne(['_id' => new ObjectId($id)]);
            
            if ($user) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                    'data' => $user
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Không tìm thấy user'
                ]);
            }
        } catch (Exception) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'ID không hợp lệ'
            ]);
        }
    }
    
    // POST /users - Tạo user mới
    public function create(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['name']) || !isset($input['email'])) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'Thiếu thông tin name hoặc email'
            ]);
            return;
        }
        
        $user = [
            'name' => $input['name'],
            'email' => $input['email'],
            'created_at' => new UTCDateTime()
        ];
        
        $result = $this->collection->insertOne($user);
        
        header('Content-Type: application/json');
        http_response_code(201);
        echo json_encode([
            'status' => 'success',
            'message' => 'Tạo user thành công',
            'id' => (string)$result->getInsertedId()
        ]);
    }
    
    // PUT /users/{id} - Cập nhật user
    public function update($id): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'Không có dữ liệu cập nhật'
            ]);
            return;
        }
        
        try {
            $updateData = [];
            if (isset($input['name'])) $updateData['name'] = $input['name'];
            if (isset($input['email'])) $updateData['email'] = $input['email'];
            
            $result = $this->collection->updateOne(
                ['_id' => new ObjectId($id)],
                ['$set' => $updateData]
            );
            
            if ($result->getModifiedCount() > 0) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Cập nhật user thành công'
                ]);
            } else {
                echo json_encode([
                    'status' => 'info',
                    'message' => 'Không có thay đổi nào'
                ]);
            }
        } catch (Exception) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'ID không hợp lệ'
            ]);
        }
    }
    
    // DELETE /users/{id} - Xóa user
    public function delete($id): void
    {
        try {
            $result = $this->collection->deleteOne(['_id' => new ObjectId($id)]);
            
            if ($result->getDeletedCount() > 0) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Xóa user thành công'
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Không tìm thấy user'
                ]);
            }
        } catch (Exception) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'ID không hợp lệ'
            ]);
        }
    }
}