-- Create the database
CREATE DATABASE IF NOT EXISTS portofoliophp;

-- Use the database
USE portofoliophp;

-- Create articles table
CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Optional: Insert some sample articles
INSERT INTO articles (title, content) VALUES 
('Sejarah Perbudakan', 'Artikel ini membahas bagaimana sistem perbudakan berkembang sejak zaman Mesir Kuno, Romawi, hingga perdagangan budak di Atlantik dan akhirnya penghapusannya di berbagai negara.'),
('Dampak Perbudakan terhadap Dunia Modern', 'Mengulas bagaimana perbudakan membentuk ekonomi, budaya, dan struktur sosial di banyak negara serta dampaknya yang masih terasa hingga kini.');
