db = db.getSiblingDB('training'); // Tạo database "training"
db.createUser({
    user: "traininguser",
    pwd: "trainingpassword",
    roles: [{ role: "readWrite", db: "training" }]
});
db.mycollection.insert({ message: "Hello MongoDB!" }); // Thêm dữ liệu mẫu