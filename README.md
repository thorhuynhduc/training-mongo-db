# Phần 1: Mở đầu - SQL vs NoSQL

## Link slide: https://www.canva.com/design/DAHDDiVMk9k/zuRuVL4ycTm0kapTR6FSQw/edit?utm_content=DAHDDiVMk9k&utm_campaign=designshare&utm_medium=link2&utm_source=sharebutton

# Phần 2: Làm quen với MongoDB qua lăng kính SQL

## Link slide: https://www.canva.com/design/DAHDazEGAd4/PCF_S7Wd_lSF_3gE8MF46A/edit?utm_content=DAHDazEGAd4&utm_campaign=designshare&utm_medium=link2&utm_source=sharebutton

### Bài tập Dịch cấu trúc: 
  - docker compose exec mongodb bash
    + use training
    + db.auth('traininguser', 'trainingpassword')
     ```
        db.nv.insertMany([
          {
            "_id": "NV001",
            "HoDem": "Trần",
            "Ten": "Minh",
            "NgaySinh": "1995-05-10",
            "PhongBan": "Kinh doanh",
            "Luong": 1500
          },
          {
            "_id": "NV002",
            "HoDem": "Lê",
            "Ten": "Hoa",
            "NgaySinh": "1998-08-15",
            "PhongBan": "Kế toán",
            "Luong": 1200
          }
        ])
    ```

### Bài tập Dịch các kiểu quan hệ: 
  - docker compose exec mongodb bash
    + use training
    + db.auth('traininguser', 'trainingpassword')
    + Liên kết thủ công:
     ```
        // Collection: tac_gia
        db.tac_gia.insertMany([
          {
              "_id": "T01",
              "TenTacGia": "Nguyễn Du",
              "QuocGia": "Việt Nam"
          },
          {
              "_id": "T02",
              "TenTacGia": "Nguyễn Hòa",
              "QuocGia": "Việt Nam"
          }
        ])
    
        // Collection: sach
        db.sach.insertMany([
          {
            "_id": "S01",
            "TuaSach": "Truyện Kiều",
            "MaTG": "T01",  // Đây chính là khóa ngoại
            "NamXB": 1820
          },
          {
            "_id": "S02",
            "TuaSach": "Văn tế nghĩa sĩ Cần Giuộc",
            "MaTG": "T01",
            "NamXB": 1861
          }
        ])
    ```
     + Nhúng dữ liệu
     ```
        // Collection: tac_gia
        db.tac_gia.insert(
            {
              "_id": "T01",
              "TenTacGia": "Nguyễn Du",
              "QuocGia": "Việt Nam",
              "sach": [
                {
                  "TuaSach": "Truyện Kiều",
                  "NamXB": 1820
                },
                {
                  "TuaSach": "Văn tế nghĩa sĩ Cần Giuộc",
                  "NamXB": 1861
                }
              ]
            }
        )
    
        // Collection: sach
        db.sach.insertMany([
          {
            "_id": "S01",
            "TuaSach": "Truyện Kiều",
            "MaTG": "T01",  // Đây chính là khóa ngoại
            "NamXB": 1820
          },
          {
            "_id": "S02",
            "TuaSach": "Văn tế nghĩa sĩ Cần Giuộc",
            "MaTG": "T01",
            "NamXB": 1861
          }
        ])
    ```

### Bài tập Dịch truy vấn: 
  - docker compose exec mongodb bash
    + use training
    + db.auth('traininguser', 'trainingpassword')
    ```
    db.nv.find(
      { Luong: { $gt: 1000 } },           // WHERE Luong > 1000
      { Ten: 1, NgaySinh: 1, _id: 0 }    // SELECT Ten, NgaySinh (và ẩn _id đi)
    )
    .sort({ Luong: -1 })                 // ORDER BY Luong DESC
    .limit(5)                             // LIMIT 5
    ```

