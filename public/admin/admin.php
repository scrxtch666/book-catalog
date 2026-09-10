<?php

require_once __DIR__ . '/../admin/config/auth.php';
require_once '/var/www/src/Database.php';

$errors = [];
$values = ['title' => '', 'author' => '', 'year' => '', 'annotation' => '', 'rating' => ''];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrace</title>
  <link rel="stylesheet" href="/css/style.css" />
</head>

<body>
  <div class="container-layout">
    <header class="navbar no-print">
      <a href="../index.html" class="btn-print">← Zpět na přehled</a>
      <h2>Administrace</h2>
      <div class="nav-actions">
        <a href="../api/import.php" class="btn-login">Import knih</a>
        <a href="../api/logout.php" class="btn-login">Odhlásit se</a>
      </div>
    </header>

    <section class="card">
      <h2>Přidat knihu</h2>

      <form action="../api/add-book.php" method="POST" novalidate>
        <div class="form-group">
          <label for="title">Název</label>
          <input type="text" id="title" name="title" maxlength="255"
            value="<?= htmlspecialchars($values['title']) ?>" required>
          <?php if (isset($errors['title'])): ?>
            <p class="error-message"><?= htmlspecialchars($errors['title']) ?></p>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label for="author">Autor</label>
          <input type="text" id="author" name="author" maxlength="255"
            value="<?= htmlspecialchars($values['author']) ?>" required>
          <?php if (isset($errors['author'])): ?>
            <p class="error-message"><?= htmlspecialchars($errors['author']) ?></p>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label for="year">Rok vydání</label>
          <input type="number" id="year" name="year" min="1450" max="<?= date('Y') + 1 ?>"
            value="<?= htmlspecialchars($values['year']) ?>" required>
          <?php if (isset($errors['year'])): ?>
            <p class="error-message"><?= htmlspecialchars($errors['year']) ?></p>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label for="annotation">Anotace</label>
          <textarea id="annotation" name="annotation" rows="4"><?= htmlspecialchars($values['annotation']) ?></textarea>
        </div>

        <div class="form-group">
          <label for="rating">Hodnocení (0–5)</label>
          <input type="number" id="rating" name="rating" min="0" max="5" step="0.1"
            value="<?= htmlspecialchars($values['rating']) ?>">
          <?php if (isset($errors['rating'])): ?>
            <p class="error-message"><?= htmlspecialchars($errors['rating']) ?></p>
          <?php endif; ?>
        </div>

        <button type="submit" class="btn-login">Uložit knihu</button>
      </form>
    </section>
    <h2>Seznam knih</h2>
    <div id="books"></div>
    <script>
      const container = document.getElementById("books");

      fetch("/api/books.php")
        .then((response) => response.json())
        .then((books) => {
          books.forEach((book) => {
            const div = document.createElement("div");

            div.innerHTML = `
              <a href="../../detail.html?id=${book.id}">
                <div class="book">
                    <h3>${book.title}</h3>
                    <p>Autor: ${book.author}</p>
                    <p>Rok vydání: ${book.year}</p>
                </div>
              </a>
                `;

            container.appendChild(div);
          });
        });

      document.getElementById("print-btn").addEventListener("click", () => {
        window.print();
      });
    </script>
  </div>
</body>

</html>