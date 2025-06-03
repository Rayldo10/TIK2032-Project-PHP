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

function filterGallery(category) {
    const items = document.querySelectorAll('.gallery-item');
    items.forEach(item => {
        if (category === 'all' || item.classList.contains(category)) {
            item.style.display = 'inline-block';
        } else {
            item.style.display = 'none';
        }
    });
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
}
