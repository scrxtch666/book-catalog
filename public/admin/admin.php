<?php

require_once __DIR__ . '/../admin/config/auth.php';
require_once '/var/www/src/Database.php';

$errors = [];

$values = [
  'title' => '',
  'author' => '',
  'year' => '',
  'annotation' => '',
  'rating' => ''
];

?>

<!DOCTYPE html>
<html lang="cs">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Administrace</title>

  <link rel="stylesheet" href="/css/style.css" />
</head>

<body>

  <div class="container-layout">

    <header class="navbar no-print">

      <a href="../index.html" class="btn-print">
        ← Zpět na přehled
      </a>

      <h2>Administrace</h2>

      <div class="nav-actions">

        <a href="../api/import.php" class="btn-login">
          Import knih
        </a>

        <a href="../api/logout.php" class="btn-login">
          Odhlásit se
        </a>

      </div>

    </header>


    <section class="card">

      <h2>Přidat knihu</h2>

      <form action="../api/add-book.php" method="POST" novalidate>

        <div class="form-group">

          <label for="title">
            Název
          </label>

          <input
            type="text"
            id="title"
            name="title"
            maxlength="255"
            value="<?= htmlspecialchars($values['title'], ENT_QUOTES, 'UTF-8') ?>"
            required>

          <?php if (isset($errors['title'])): ?>

            <p class="error-message">
              <?= htmlspecialchars($errors['title'], ENT_QUOTES, 'UTF-8') ?>
            </p>

          <?php endif; ?>

        </div>


        <div class="form-group">

          <label for="author">
            Autor
          </label>

          <input
            type="text"
            id="author"
            name="author"
            maxlength="255"
            value="<?= htmlspecialchars($values['author'], ENT_QUOTES, 'UTF-8') ?>"
            required>

          <?php if (isset($errors['author'])): ?>

            <p class="error-message">
              <?= htmlspecialchars($errors['author'], ENT_QUOTES, 'UTF-8') ?>
            </p>

          <?php endif; ?>

        </div>


        <div class="form-group">

          <label for="year">
            Rok vydání
          </label>

          <input
            type="number"
            id="year"
            name="year"
            min="1450"
            max="<?= date('Y') + 1 ?>"
            value="<?= htmlspecialchars($values['year'], ENT_QUOTES, 'UTF-8') ?>"
            required>

          <?php if (isset($errors['year'])): ?>

            <p class="error-message">
              <?= htmlspecialchars($errors['year'], ENT_QUOTES, 'UTF-8') ?>
            </p>

          <?php endif; ?>

        </div>


        <div class="form-group">

          <label for="annotation">
            Anotace
          </label>

          <textarea
            id="annotation"
            name="annotation"
            rows="4"><?= htmlspecialchars($values['annotation'], ENT_QUOTES, 'UTF-8') ?></textarea>

        </div>


        <div class="form-group">

          <label for="rating">
            Hodnocení (0–5)
          </label>

          <input
            type="number"
            id="rating"
            name="rating"
            min="0"
            max="5"
            step="0.1"
            value="<?= htmlspecialchars($values['rating'], ENT_QUOTES, 'UTF-8') ?>">

          <?php if (isset($errors['rating'])): ?>

            <p class="error-message">
              <?= htmlspecialchars($errors['rating'], ENT_QUOTES, 'UTF-8') ?>
            </p>

          <?php endif; ?>

        </div>


        <button type="submit" class="btn-login">
          Uložit knihu
        </button>

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

            const link = document.createElement("a");
            link.href = `../../detail.html?id=${encodeURIComponent(book.id)}`;

            const div = document.createElement("div");
            div.className = "book";

            const title = document.createElement("h3");
            title.textContent = book.title;

            const author = document.createElement("p");
            author.textContent = `Autor: ${book.author}`;

            const year = document.createElement("p");
            year.textContent = `Rok vydání: ${book.year}`;

            div.appendChild(title);
            div.appendChild(author);
            div.appendChild(year);

            link.appendChild(div);

            container.appendChild(link);

          });

        })
        .catch((error) => {

          console.error("Chyba při načítání knih:", error);

        });
    </script>

  </div>

</body>

</html>