<?php

declare(strict_types=1);

require __DIR__ . "/header.php";
// require __DIR__ . "/data-source.php";
require __DIR__ . "/data-generated.php";


// sort books based on form input:
if (isset($_GET["sort"], $_GET["ascOrDesc"])) {
  $sortBy = htmlspecialchars($_GET["sort"]); // ex. pages or author
  $sortAscOrDesc = htmlspecialchars($_GET["ascOrDesc"]);
}

$sortingKey = array_column($books, $sortBy);
if ($sortAscOrDesc === "asc") {
  array_multisort($sortingKey, SORT_ASC, $books);
} elseif ($sortAscOrDesc === "desc") {
  array_multisort($sortingKey, SORT_DESC, $books);
}

// filter books based on search(author and title):
$filteredBooks = $books;

if (isset($_GET["filterBy"])) {
  $filterBy = trim(htmlspecialchars(strtolower($_GET["filterBy"])));
  $filteredBooks = array_filter($books, function ($var) use ($filterBy) {
    if (
      str_contains(strtolower($var["title"]), $filterBy) ||
      str_contains(strtolower($var["author"]), $filterBy)
    ) {
      return 1;
    }
  });
}

?>

<main>

  <h1>Bookshelf</h1>

  <section class="info-section">
    <!-- Display the selected book: -->
    <div class="selection">
      <?php if (isset($_GET["id"])) :
        $selectedBook = htmlspecialchars($_GET["id"]); ?>
        <p><strong>Chosen book:</strong></p>
        <p><strong>Title:</strong> <?= $books[$selectedBook]["title"]; ?></p>
        <p><strong>Author:</strong> <?= $books[$selectedBook]["author"]; ?></p>
        <p><strong>Genre:</strong> <?= $books[$selectedBook]["genre"]; ?></p>
        <p><strong>Release year:</strong> <?= $books[$selectedBook]["year"]; ?></p>
        <p><strong>Number of pages:</strong> <?= $books[$selectedBook]["pages"]; ?></p>
      <?php else : ?>
        <p><strong>Do one of the following:</strong></p>
        <ul>
          <li>Pick a book</li>
          <li>Sort the books</li>
          <li>Search for a book</li>
        </ul>
      <?php endif; ?>
    </div>

    <div class="sort-and-filter">

      <!-- select which parameter to sort by alphabetically. ASC or DESC.
      Color currently sorts by hex value... not ideal, might fix later. -->
      <div class="sort">
        <form action="index.php" method="get">
          <label for="sort"><strong>Sort by:</strong></label><br>
          <!-- <div class="flex-container"> -->
          <select id="sort" name="sort">
            <option value="title" selected>Title</option>
            <option value="author">Author</option>
            <option value="pages">Pages</option>
            <option value="year">Release year</option>
            <option value="color">Color of book</option>
          </select>
          <div class="buttons-asc-desc">
            <button type="submit" value="asc" name="ascOrDesc">ASC</button>
            <button type="submit" value="desc" name="ascOrDesc">DESC</button>
          </div>
          <!-- </div> -->
        </form>
      </div>


      <!-- Search and filter by title or author: -->
      <div class="filter">
        <form action="index.php" method="get">
          <label for="filterBy"><strong>Search title or author:</strong></label><br>
          <input type="text" name="filterBy">
          <div class="button-filter"><button type="submit">OK</button></div>
        </form>
      </div>
    </div>
  </section>

  <section class="bookshelf">
    <form action="index.php" method="get" class="form-bookshelf">
      <?php foreach ($books as $book) : ?>
        <?php foreach ($filteredBooks as $filteredBook) :
          if ($filteredBook == $book) : ?>

            <!-- Each book is a button and can be selected: -->
            <button type="submit" value="<?= $book["id"] ?>" name="id" class="book" style="
            background-color: <?= $book["color"] ?>;
            font-family: <?= $book["fonts"] ?> , sans-serif;
            width: <?= $book["pages"] / 4 ?>px;">
              <p><?= $book["title"] ?></p>
              <p><?= $book["author"] ?></p>
            </button>

          <?php endif; ?>
        <?php endforeach; ?>
      <?php endforeach ?>
    </form>
  </section>
</main>

</body>

</html>