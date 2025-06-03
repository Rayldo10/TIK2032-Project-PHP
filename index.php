<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "portofoliophp";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get articles from database
$articles = [];
$sql = "SELECT * FROM articles ORDER BY created_at DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $articles[] = $row;
    }
}

// Handle article operations (add, edit, delete)
if (isset($_POST['action'])) {
    // Add article
    if ($_POST['action'] == 'add') {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $sql = "INSERT INTO articles (title, content, created_at) VALUES (?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $title, $content);
        $stmt->execute();
        header("Location: index.php#blog");
        exit();
    }
    
    // Edit article
    if ($_POST['action'] == 'edit') {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $sql = "UPDATE articles SET title = ?, content = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $title, $content, $id);
        $stmt->execute();
        header("Location: index.php#blog");
        exit();
    }
    
    // Delete article
    if ($_POST['action'] == 'delete') {
        $id = $_POST['id'];
        $sql = "DELETE FROM articles WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        header("Location: index.php#blog");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Multi Halaman</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <a href="#home" onclick="showPage('home')">Home</a>
        <a href="#gallery" onclick="showPage('gallery')">Gallery</a>
        <a href="#blog" onclick="showPage('blog')">Blog</a>
        <a href="#contact" onclick="showPage('contact')">Contact</a>
    </nav>

    <!-- Fixed home container - removed any potential duplicate elements -->
    <div id="home" class="container home-container">
        <h1 class="fade-in">Hi, I'm Roynaldo Mailangkay 👋</h1>
        <p class="fade-in delay-1">Selamat datang di portofolio dan rekam jejak saya!</p>
        <button class="cta-button fade-in delay-2" onclick="showPage('gallery')">Lihat Gallery</button>
    </div>
    
    <div id="gallery" class="container" style="display: none;">
        <h1>Gallery</h1>
        <div class="gallery">
            <img src="Galery/pin_amba_jmk48.jpg" alt="Foto 1">
            <img src="Galery/meme _Udah_Yapping_nYa__.jpg" alt="Foto 2">
            <img src="Galery/Satwa_Liar.jpg" alt="Foto 3">
        </div>          
    </div>
    
    <div id="blog" class="container" style="display: none;">
        <button id="backToTop" onclick="scrollToTop()">⬆</button>
        <h1>Blog</h1>
        
        <button class="add-article-button" onclick="showPage('add-article')">Tambah Artikel Baru</button>
        
        <?php if(count($articles) > 0): ?>
            <?php foreach($articles as $article): ?>
                <div class="article">
                    <h2><?php echo htmlspecialchars($article['title']); ?></h2>
                    <p class="article-date">Dibuat pada: <?php echo date('d M Y', strtotime($article['created_at'])); ?></p>
                    <div class="article-content">
                        <?php echo nl2br(htmlspecialchars($article['content'])); ?>
                    </div>
                    <div class="article-controls">
                        <button type="button" onclick="editArticle(<?php echo $article['id']; ?>)">Edit</button>
                        <form method="post" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus artikel ini?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
                            <button type="submit">Hapus</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Belum ada artikel.</p>
        <?php endif; ?>
    </div>
    
    <div id="contact" class="container" style="display: none;">
        <h1>Contact</h1>
        <div class="contact-info">
            <p><strong>Nama:</strong> Roynaldo Mailangkay</p>
            <p><strong>Email:</strong> aldomailangkay8@gmail.com</p>
            <p><strong>Telepon:</strong> 08123456789</p>
        </div>
        <div class="form-wrapper">
            <form id="contactForm">
                <input type="text" placeholder="Nama" required>
                <input type="email" placeholder="Email" required>
                <textarea placeholder="Pesan" required></textarea>
                <button type="submit">Kirim</button>
            </form>
        </div>
    </div>
    
    <!-- Add article form -->
    <div id="add-article" class="container" style="display: none;">
        <h1>Tambah Artikel Baru</h1>
        <form method="post">
            <input type="hidden" name="action" value="add">
            <input type="text" name="title" placeholder="Judul Artikel" required>
            <textarea name="content" placeholder="Isi Artikel" rows="10" required></textarea>
            <button type="submit">Simpan Artikel</button>
            <button type="button" onclick="showPage('blog')">Batal</button>
        </form>
    </div>
    
    <!-- Edit article form -->
    <div id="edit-article" class="container" style="display: none;">
        <h1>Edit Artikel</h1>
        <form method="post" id="edit-article-form">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit-article-id">
            <input type="text" name="title" id="edit-article-title" placeholder="Judul Artikel" required>
            <textarea name="content" id="edit-article-content" placeholder="Isi Artikel" rows="10" required></textarea>
            <button type="submit">Simpan Perubahan</button>
            <button type="button" onclick="showPage('blog')">Batal</button>
        </form>
    </div>
    
    <!-- Added inline JavaScript -->
    <script>
        // Improved page display function to prevent double content
        function showPage(pageId) {
            // First hide all containers
            document.querySelectorAll('.container').forEach(page => {
                page.style.display = 'none';
            });
            
            // Then show the selected container
            const currentPage = document.getElementById(pageId);
            currentPage.style.display = 'block';
            
            // Reset scroll position to top
            window.scrollTo(0, 0);
            
            // Add gallery image click handlers if needed
            if(pageId === 'gallery') {
                document.querySelectorAll('.gallery img').forEach(img => {
                    img.addEventListener('click', () => {
                        document.getElementById('lightbox-img').src = img.src;
                        document.getElementById('lightbox').style.display = 'flex';
                    });
                });
            }
        }
        
        function editArticle(id) {
            // Make an AJAX request to get article data
            fetch('get_article.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit-article-id').value = data.id;
                    document.getElementById('edit-article-title').value = data.title;
                    document.getElementById('edit-article-content').value = data.content;
                    showPage('edit-article');
                });
        }
        
        function closeLightbox() {
            document.getElementById('lightbox').style.display = 'none';
        }
        
        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        
        // Show home page by default when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Hide all containers first to prevent double displays
            document.querySelectorAll('.container').forEach(page => {
                page.style.display = 'none';
            });
            
            // Then show home
            document.getElementById('home').style.display = 'block';
        });
        
        // Show back to top button when scrolling
        window.onscroll = function () {
            const btn = document.getElementById("backToTop");
            if (document.body.scrollTop > 400 || document.documentElement.scrollTop > 400) {
                btn.style.display = "block";
            } else {
                btn.style.display = "none";
            }
        };
    </script>
    
    <div id="lightbox" class="lightbox">
        <span class="close-btn" onclick="closeLightbox()">×</span>
        <img id="lightbox-img" src="" alt="Preview">
    </div>
</body>
</html>